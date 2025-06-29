<?php
require("../../config/Database.php");
require("../../config/JWT.php");
require("../../config/Logger.php");

$db = new Database();
$logger = new Logger();
$logger->setLogLevel("INFO"); // Só registra INFO, WARNING e ERROR (opcional)

$dados = JWT::verificar($db);
if (!$dados) {

    $logger->log(
        "Tentativa de acesso não autorizado",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        ["ip" => $_SERVER["REMOTE_ADDR"] ?? null]
    );

    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Não autorizado"
    ]);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cliente_id = isset($_POST["cliente_id"]) ? (int)$_POST["cliente_id"] : NULL;
$empresa_id = isset($_POST["empresa_id"]) ? (int)$_POST["empresa_id"] : NULL;

if (!$cliente_id || !$empresa_id) {

    $logger->log(
        "Parâmetros inválidos para cadastro de empresa-cliente",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        [
            "cliente_id" => $cliente_id,
            "empresa_id" => $empresa_id
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "cliente ou empresa inválido",
        "debug" => "cliente_id ou empresa_id inválido: " . $cliente_id . " - " . $empresa_id
    ]);
    exit;
}

$query = "SELECT * FROM
            `empresa_cliente`
        WHERE
            `cliente_id` = :cliente_id
            AND `empresa_id` = :empresa_id
        LIMIT 1";

$existeEmpresa = $db->prepare($query);

$existeEmpresa->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
$existeEmpresa->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeEmpresa->execute();

if ($existeEmpresa->rowCount() > 0) {

    $logger->log(
        "Empresa já cadastrada para este cliente $cliente_id",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        [
            "cliente_id" => $cliente_id,
            "empresa_id" => $empresa_id
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Cliente já cadastrado para esta empresa",
        "debug" => "Cliente já cadastrado"
    ]);
    exit;
} else {
    try {
        $query = "INSERT INTO
                `empresa_cliente` (`empresa_id`, `cliente_id`)
            VALUES
                (:empresa_id, :cliente_id)";

        $empresa = $db->prepare($query);

        $empresa->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
        $empresa->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $empresa->execute();

        $logger->log(
            "Empresa cadastrada com sucesso para o cliente: $cliente_id",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            [
                "cliente_id" => $cliente_id,
                "empresa_id" => $empresa_id
            ]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Cliente cadastrado com sucesso",
            "cliente_id" => $cliente_id,
            "empresa_id" => $empresa_id
        ]);
        exit;
    } catch (Exception $e) {

        $logger->logException(
            $e,
            $_SESSION["usuario_id"] ?? null,
            [
                "cliente_id" => $cliente_id,
                "empresa_id" => $empresa_id
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Erro ao cadastrar empresa para o cliente",
            "debug" => $e->getMessage()
        ]);
        exit;
    }
}
