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

$dadosServico = [
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL
];

try {
    $existeServico = $db->prepare(
        "SELECT * FROM
            `servico`
        WHERE
            `nome` = :nome
        LIMIT 1"
    );
    $existeServico->bindParam(":nome", $dadosServico["nome"], PDO::PARAM_STR);
    $existeServico->execute();

    if ($existeServico->rowCount() > 0) {
        $servicoExistente = $existeServico->fetch(PDO::FETCH_ASSOC);
        $servico_id = $servicoExistente['id'];

        $logger->log(
            "Serviço já cadastrado",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            [
                "servico_id" => $servico_id,
                "nome" => $dadosServico["nome"]
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Já existe um serviço cadastrado com este nome."
        ]);
        exit;
    } else {
        $servico = $db->prepare(
            "INSERT INTO
                `servico`
            (
                `nome`
            )
            VALUES
            (
                :nome
            )"
        );
        $servico->bindParam(":nome", $dadosServico["nome"], PDO::PARAM_STR);
        $servico->execute();
        $servico_id = $db->getConnection()->lastInsertId();

        $logger->log(
            "Serviço cadastrado com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            [
                "servico_id" => $servico_id,
                "nome" => $dadosServico["nome"]
            ]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Serviço cadastrado com sucesso.",
            "servico_id" => $servico_id
        ]);
        exit;
    }
} catch (Exception $e) {
    $logger->log(
        "Erro ao processar dados do serviço",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        [
            "error" => $e->getMessage(),
            "trace" => $e->getTraceAsString()
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao processar os dados do serviço."
    ]);
    exit;
}
