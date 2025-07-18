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

$usuario_id = isset($_POST["usuario_id"]) ? (int)$_POST["usuario_id"] : NULL;
$empresa_id = isset($_POST["empresa_id"]) ? (int)$_POST["empresa_id"] : NULL;

if (!$usuario_id || !$empresa_id) {

    $logger->log(
        "Parâmetros inválidos para cadastro de empresa-usuario",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        [
            "usuario_id" => $usuario_id,
            "empresa_id" => $empresa_id
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "usuario ou empresa inválido",
        "debug" => "usuario_id ou empresa_id inválido: " . $usuario_id . " - " . $empresa_id
    ]);
    exit;
}

$query = "SELECT * FROM
            `empresa_usuario`
        WHERE
            `usuario_id` = :usuario_id
            AND `empresa_id` = :empresa_id
        LIMIT 1";

$existeServico = $db->prepare($query);

$existeServico->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
$existeServico->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeServico->execute();

if ($existeServico->rowCount() > 0) {

    $logger->log(
        "Usuário já cadastrado para esta empresa",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        [
            "usuario_id" => $usuario_id,
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
                `empresa_usuario` (`empresa_id`, `usuario_id`)
            VALUES
                (:empresa_id, :usuario_id)";

        $servico = $db->prepare($query);

        $servico->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
        $servico->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $servico->execute();

        $logger->log(
            "Usuário cadastrado com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            [
                "usuario_id" => $usuario_id,
                "empresa_id" => $empresa_id
            ]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Cliente cadastrado com sucesso",
            "usuario_id" => $usuario_id,
            "empresa_id" => $empresa_id
        ]);
        exit;
    } catch (Exception $e) {

        $logger->log(
            "Erro ao cadastrar usuário",
            "ERROR",
            $_SESSION["usuario_id"] ?? null,
            [
                "usuario_id" => $usuario_id,
                "empresa_id" => $empresa_id,
                "error" => $e->getMessage()
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Erro ao cadastrar usuario",
            "debug" => $e->getMessage()
        ]);
        exit;
    }
}
