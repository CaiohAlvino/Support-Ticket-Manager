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

$dadosGrupo = [
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL
];

try {
    $existeGrupo = $db->prepare(
        "SELECT * FROM
            `grupo`
        WHERE
            `nome` = :nome
        LIMIT 1"
    );

    $existeGrupo->bindParam(":nome", $dadosGrupo["nome"], PDO::PARAM_STR);
    $existeGrupo->execute();

    if ($existeGrupo->rowCount() > 0) {
        $grupoExistente = $existeGrupo->fetch(PDO::FETCH_ASSOC);
        $grupo_id = $grupoExistente['id'];

        $logger->log(
            "Grupo já cadastrado",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            [
                "grupo_id" => $grupo_id,
                "nome" => $dadosGrupo["nome"]
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Já existe um grupo cadastrado com este nome."
        ]);
        exit;
    } else {
        $grupo = $db->prepare(
            "INSERT INTO
                `grupo`
            (
                `nome`
            )
            VALUES
            (
                :nome
            )"
        );

        $grupo->bindParam(":nome", $dadosGrupo["nome"], PDO::PARAM_STR);
        $grupo->execute();
        $grupo_id = $db->getConnection()->lastInsertId();

        $logger->log(
            "Grupo cadastrado com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            ["grupo_id" => $grupo_id, "nome" => $dadosGrupo["nome"]]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Grupo cadastrado com sucesso.",
            "grupo_id" => $grupo_id
        ]);
        exit;
    }
} catch (Exception $e) {
    $logger->log(
        "Erro ao processar dados do grupo",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        ["error" => $e->getMessage()]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao processar os dados do grupo."
    ]);
    exit;
}
