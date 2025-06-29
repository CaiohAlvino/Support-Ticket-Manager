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

$servico_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$servico_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Registro não encontrado!"
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

$query = "SELECT COUNT(*) FROM `suporte` WHERE `servico_id` = :servico_id";
$existeSuporteServico = $db->prepare($query);
$existeSuporteServico->bindParam(":servico_id", $servico_id, PDO::PARAM_INT);
$existeSuporteServico->execute();
if ($existeSuporteServico->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o serviço, pois ele está vinculado a um suporte."
    ]);
    exit;
}

$servico = $db->prepare("DELETE FROM `servico` WHERE `id` = :id");
$servico->bindParam(":id", $servico_id, PDO::PARAM_INT);
$servico->execute();

echo json_encode([
    "status" => "success",
    "message" => "Serviço excluído!"
]);
