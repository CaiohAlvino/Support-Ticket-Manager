<?php
require("../../config/Database.php");
require("../../config/EmpresaCliente.php");
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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
