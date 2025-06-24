<?php

require("../../config/Database.php");
// require("../../config/JWT.php");

$db = new Database();

// $tokenEValido = JWT::verificar($db);

// if (!$tokenEValido) {
//     echo json_encode(["status" => "error", "message" => "Token inválido."]);
//     exit;
// }


$cliente_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$cliente_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Registro não encontrado!"
    ]);
    exit;
}

$query = "SELECT
            COUNT(*)
        FROM
            `empresa_cliente`
        WHERE
            `cliente_id` = :cliente_id";

$existeEmpresaCliente = $db->prepare($query);
$existeEmpresaCliente->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
$existeEmpresaCliente->execute();

if ($existeEmpresaCliente->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a uma empresa."
    ]);
    exit;
}

$query = "SELECT COUNT(*) FROM `suporte` WHERE `cliente_id` = :cliente_id";

$existeSuporteCliente = $db->prepare($query);
$existeSuporteCliente->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
$existeSuporteCliente->execute();

if ($existeSuporteCliente->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a um suporte."
    ]);
    exit;
}

$query = "DELETE FROM
        `cliente`
    WHERE
        `id` = :id";

$cliente = $db->prepare($query);
$cliente->bindParam(":id", $cliente_id, PDO::PARAM_INT);
$cliente->execute();

echo json_encode([
    "status" => "success",
    "message" => "Cliente excluído!"
]);
