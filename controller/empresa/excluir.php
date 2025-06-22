<?php

require("../../config/Database.php");

$db = new Database();

$empresa_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$empresa_id) {
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
            `empresa_id` = :empresa_id";

$existeEmpresaCliente = $db->prepare($query);
$existeEmpresaCliente->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeEmpresaCliente->execute();

if ($existeEmpresaCliente->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a um cliente."
    ]);
    exit;
}

$query = "SELECT COUNT(*) FROM `empresa_servico` WHERE `empresa_id` = :empresa_id";
$existeServicoEmpresa = $db->prepare($query);
$existeServicoEmpresa->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeServicoEmpresa->execute();
if ($existeServicoEmpresa->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a um serviço."
    ]);
    exit;
}

$query = "SELECT COUNT(*) FROM `empresa_usuario` WHERE `empresa_id` = :empresa_id";
$existeUsuarioEmpresa = $db->prepare($query);
$existeUsuarioEmpresa->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeUsuarioEmpresa->execute();
if ($existeUsuarioEmpresa->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a um usuário."
    ]);
    exit;
}

$query = "SELECT COUNT(*) FROM `suporte` WHERE `empresa_id` = :empresa_id";

$existeSuporteCliente = $db->prepare($query);
$existeSuporteCliente->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeSuporteCliente->execute();

if ($existeSuporteCliente->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a um suporte."
    ]);
    exit;
}

$empresa = $db->prepare("DELETE FROM `empresa` WHERE `id` = :id");
$empresa->bindParam(":id", $empresa_id, PDO::PARAM_INT);
$empresa->execute();

echo json_encode([
    "status" => "success",
    "message" => "Empresa excluída!"
]);
