<?php
require("../../config/Database.php");
require("../../config/EmpresaServico.php");

header("Content-Type: application/json");

$db = new Database();

$empresa_id = isset($_GET["empresa_id"]) ? (int)$_GET["empresa_id"] : 0;

if ($empresa_id <= 0) {
    echo json_encode(["debug" => "empresa_id inválido", "servicos" => []]);
    exit;
}

$classEmpresaServico = new EmpresaServico($db->getConnection());
$servicos = $classEmpresaServico->pegarServicos($empresa_id);

// Loga o resultado para o console do navegador
if (count($servicos) == 0) {
    echo json_encode(["debug" => "Nenhum serviço encontrado para o empresa_id " . $empresa_id, "servicos" => []]);
    exit;
}

$result = [];
foreach ($servicos as $servico) {
    $result[] = [
        "id" => $servico->servico_id,
        "nome" => $servico->servico_nome
    ];
}
echo json_encode(["servicos" => $result]);
