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
    "id" => isset($_POST["id"]) ? (int)$_POST["id"] : NULL,
    "situacao" => isset($_POST["ativo"]) ? (int)$_POST["ativo"] : NULL,
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL,
];
try {
    $existeServico = $db->prepare(
        "SELECT * FROM
            `servico`
        WHERE
            `nome` = :nome AND
            `id` != :id
        LIMIT 1"
    );

    $existeServico->bindParam(":nome", $dadosServico["nome"], PDO::PARAM_STR);
    $existeServico->bindParam(":id", $dadosServico["id"], PDO::PARAM_INT);
    $existeServico->execute();

    if ($existeServico->rowCount() > 0) {

        $logger->log(
            "Serviço já cadastrado com este nome",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            [
                "servico_id" => $dadosServico["id"],
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
            "UPDATE
                `servico`
            SET
                `situacao` = :situacao,
                `nome` = :nome
            WHERE
                `id` = :id"
        );

        $servico->bindParam(":situacao", $dadosServico["situacao"], PDO::PARAM_INT);
        $servico->bindParam(":nome", $dadosServico["nome"], PDO::PARAM_STR);
        $servico->bindParam(":id", $dadosServico["id"], PDO::PARAM_INT);
        $servico->execute();

        $logger->log(
            "Serviço atualizado com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            [
                "servico_id" => $dadosServico["id"],
                "nome" => $dadosServico["nome"]
            ]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Serviço atualizado com sucesso."
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
            "dados" => $dadosServico
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao processar os dados do serviço."
    ]);
    exit;
}
