<?php

require("../../config/Database.php");
require("../../config/JWT.php");

$db = new Database();

$tokenEValido = JWT::verificar($db);

if (!$tokenEValido) {
    echo json_encode(["status" => "error", "message" => "Token inválido."]);
    exit;
}

$conexao = $db->getConnection();

$fornecedor_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$fornecedor_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Registro não encontrado!"
    ]);
    exit;
}

$produto = $conexao->prepare("SELECT * FROM `agenda` WHERE `fornecedor_id` = :fornecedor_id LIMIT 1");
$produto->bindParam(":fornecedor_id", $fornecedor_id, PDO::PARAM_INT);
$produto->execute();

if ($produto->rowCount() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Existe um fornecedor cadastrado em Contas a Pagar!"
    ]);
    exit;
}

$categoria = $conexao->prepare("DELETE FROM `fornecedor` WHERE `id` = :id");
$categoria->bindParam(":id", $fornecedor_id, PDO::PARAM_INT);
$categoria->execute();

echo json_encode([
    "status" => "success",
    "message" => "Fornecedor excluído!"
]);
