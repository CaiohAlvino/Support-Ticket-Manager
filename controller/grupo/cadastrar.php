<?php

require("../../config/Database.php");

$db = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dadosGrupo = [
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL
];

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
    echo json_encode([
        "status" => "success",
        "message" => "Grupo cadastrado com sucesso.",
        "grupo_id" => $grupo_id
    ]);
    exit;
}
