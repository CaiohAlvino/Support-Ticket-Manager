<?php
require("../../config/Database.php");
require("../../config/EmpresaCliente.php");

header("Content-Type: application/json");

$db = new Database();

$cliente_id = isset($_GET["cliente_id"]) ? (int)$_GET["cliente_id"] : 0;

if ($cliente_id <= 0) {
    echo json_encode(["debug" => "cliente_id inválido", "empresas" => []]);
    exit;
}

$classEmpresaCliente = new EmpresaCliente($db->getConnection());
$empresas = $classEmpresaCliente->pegarEmpresas($cliente_id);

// Loga o resultado para o console do navegador
if (count($empresas) == 0) {
    echo json_encode(["debug" => "Nenhuma empresa encontrada para o cliente_id " . $cliente_id, "empresas" => []]);
    exit;
}

$result = [];
foreach ($empresas as $empresa) {
    $result[] = [
        "id" => $empresa->empresa_id,
        "nome" => $empresa->empresa_nome
    ];
}
echo json_encode(["empresas" => $result]);
