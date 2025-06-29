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
    $existeGrupo = $db->prepare(
        "SELECT * FROM
            `grupo`
        WHERE
            `nome` = :nome AND
            `id` != :id
        LIMIT 1"
    );
    $existeGrupo->bindParam(":nome", $dadosServico["nome"], PDO::PARAM_STR);
    $existeGrupo->bindParam(":id", $dadosServico["id"], PDO::PARAM_INT);
    $existeGrupo->execute();

    if ($existeGrupo->rowCount() > 0) {

        $logger->log(
            "Tentativa de atualização de grupo com nome já existente",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            [
                "grupo_id" => $dadosServico["id"],
                "nome" => $dadosServico["nome"]
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Já existe um grupo cadastrado com este nome."
        ]);
        exit;
    } else {
        $grupo = $db->prepare(
            "UPDATE
                `grupo`
            SET
                `situacao` = :situacao,
                `nome` = :nome
            WHERE
                `id` = :id"
        );
        $grupo->bindParam(":situacao", $dadosServico["situacao"], PDO::PARAM_INT);
        $grupo->bindParam(":nome", $dadosServico["nome"], PDO::PARAM_STR);
        $grupo->bindParam(":id", $dadosServico["id"], PDO::PARAM_INT);
        $grupo->execute();

        $logger->log(
            "Grupo atualizado com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            [
                "grupo_id" => $dadosServico["id"],
                "nome" => $dadosServico["nome"]
            ]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Grupo atualizado com sucesso."
        ]);
        exit;
    }
} catch (Exception $e) {
    $logger->log(
        "Erro ao processar dados de serviço",
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
