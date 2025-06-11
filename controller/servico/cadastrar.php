<?php

require("../../config/Database.php");

$db = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dadosServico = [
    "empresa_id" => isset($_POST["empresa_id"]) ? (int)$_POST["empresa_id"] : NULL,
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL
];

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
    echo json_encode([
        "status" => "success",
        "message" => "Serviço cadastrado com sucesso.",
        "servico_id" => $servico_id
    ]);
    exit;
}
