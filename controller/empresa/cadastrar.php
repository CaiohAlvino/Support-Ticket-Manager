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
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL,
];

$existeEmpresa = $db->prepare(
    "SELECT * FROM
        `empresa`
    WHERE
        `nome` = :nome
    LIMIT 1"
);
$existeEmpresa->bindParam(":nome", $dadosEmpresa["nome"], PDO::PARAM_STR);
$existeEmpresa->execute();

if ($existeEmpresa->rowCount() > 0) {
    $empresaExistente = $existeEmpresa->fetch(PDO::FETCH_ASSOC);
    $empresa_id = $empresaExistente['id'];

    $logger->log(
        "Tentativa de cadastro de empresa com nome já existente",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        [
            "empresa_id" => $empresa_id,
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
            "INSERT INTO
                `empresa`
            (
                `nome`
            )
            VALUES
            (
                :nome
            )"
        );

        $empresa->bindParam(":nome", $dadosEmpresa["nome"], PDO::PARAM_STR);
        $empresa->execute();

        $empresa_id = $db->getConnection()->lastInsertId();

        $logger->log(
            "Empresa cadastrada com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $empresa_id]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Empresa cadastrada com sucesso.",
            "empresa_id" => $empresa_id
        ]);
        exit;
    } catch (Exception $e) {
        $logger->log(
            "Erro ao cadastrar empresa",
            "ERROR",
            $_SESSION["usuario_id"] ?? null,
            [
                "error" => $e->getMessage(),
                "nome" => $dadosEmpresa["nome"]
            ]
        );

        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Erro ao cadastrar empresa: " . $e->getMessage()
        ]);
        exit;
    }
}
