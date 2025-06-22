<?php

require("../../config/Database.php");

$db = new Database();

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
    echo json_encode([
        "status" => "error",
        "message" => "Já existe uma empresa cadastrada com este nome."
    ]);
    exit;
} else {
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
    echo json_encode([
        "status" => "success",
        "message" => "Empresa cadastrada com sucesso.",
        "empresa_id" => $empresa_id
    ]);
    exit;
}
