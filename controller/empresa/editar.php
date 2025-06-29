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

$dadosEmpresa = [
    "id" => isset($_POST["id"]) ? (int)$_POST["id"] : NULL,
    "situacao" => isset($_POST["ativo"]) ? (int)$_POST["ativo"] : NULL,
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL,
];

$existeEmpresa = $db->prepare(
    "SELECT * FROM
        `empresa`
    WHERE
        `nome` = :nome AND
        `id` != :id
    LIMIT 1"
);

$existeEmpresa->bindParam(":nome", $dadosEmpresa["nome"], PDO::PARAM_STR);
$existeEmpresa->bindParam(":id", $dadosEmpresa["id"], PDO::PARAM_INT);
$existeEmpresa->execute();

if ($existeEmpresa->rowCount() > 0) {

    $logger->log(
        "Tentativa de atualização de empresa com nome já existente",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        [
            "empresa_id" => $dadosEmpresa["id"],
            "nome" => $dadosEmpresa["nome"]
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Já existe uma empresa cadastrada com este nome."
    ]);
    exit;
} else {
    try {
        $empresa = $db->prepare(
            "UPDATE
                `empresa`
            SET
                `situacao` = :situacao,
                `nome` = :nome
            WHERE
                `id` = :id"
        );
        $empresa->bindParam(":situacao", $dadosEmpresa["situacao"], PDO::PARAM_INT);
        $empresa->bindParam(":nome", $dadosEmpresa["nome"], PDO::PARAM_STR);
        $empresa->bindParam(":id", $dadosEmpresa["id"], PDO::PARAM_INT);
        $empresa->execute();

        $logger->log(
            "Empresa atualizada com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $dadosEmpresa["id"]]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Empresa atualizada com sucesso."
        ]);
        exit;
    } catch (Exception $e) {

        $logger->log(
            "Erro ao atualizar empresa",
            "ERROR",
            $_SESSION["usuario_id"] ?? null,
            [
                "empresa_id" => $dadosEmpresa["id"],
                "error" => $e->getMessage()
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Erro ao atualizar empresa: " . $e->getMessage()
        ]);
        exit;
    }
}
