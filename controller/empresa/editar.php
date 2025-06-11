<?php

require("../../config/Database.php");

$db = new Database();

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
    echo json_encode([
        "status" => "error",
        "message" => "Já existe uma empresa cadastrada com este nome."
    ]);
    exit;
} else {
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

    echo json_encode([
        "status" => "success",
        "message" => "Empresa atualizada com sucesso."
    ]);
    exit;
}
