<?php
require("../../config/Database.php");
require("../../config/JWT.php");

$db = new Database();

$dados = JWT::verificar($db);
if (!$dados) {
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

    echo json_encode([
        "status" => "success",
        "message" => "Serviço atualizado com sucesso."
    ]);
    exit;
}
