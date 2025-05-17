<?php

require("../../config/Database.php");
// require("../../config/JWT.php");

$db = new Database();

// $tokenEValido = JWT::verificar($db);

// if (!$tokenEValido) {
//     echo json_encode(["status" => "error", "message" => "Token inválido."]);
//     exit;
// }

$conexao = $db->getConnection();

$cliente_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$cliente_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Registro não encontrado!"
    ]);
    exit;
}

$cliente = $conexao->prepare("DELETE FROM `cliente` WHERE `id` = :id");
$cliente->bindParam(":id", $cliente_id, PDO::PARAM_INT);
$cliente->execute();

echo json_encode([
    "status" => "success",
    "message" => "Cliente excluído!"
]);
