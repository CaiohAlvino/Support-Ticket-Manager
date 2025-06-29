<?php
require("../../config/Database.php");
require("../../config/EmpresaServico.php");
require("../../config/JWT.php");
require("../../config/Logger.php");

$db = new Database();
$logger = new Logger();
$logger->setLogLevel("INFO"); // Só registra INFO, WARNING e ERROR (opcional)

$dados = JWT::verificar($db);
if (!$dados) {

    $logger->log(
        "Tentativa de acesso não autorizado",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        ["ip" => $_SERVER["REMOTE_ADDR"] ?? null]
    );

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

$empresa_id = isset($_GET["empresa_id"]) ? (int)$_GET["empresa_id"] : 0;

if (!$empresa_id || $empresa_id <= 0) {

    $logger->log(
        "ID da empresa inválido",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        ["empresa_id" => $empresa_id]
    );

    echo json_encode(["debug" => "empresa_id inválido", "servicos" => []]);
    exit;
}

$classEmpresaServico = new EmpresaServico($db->getConnection());
$servicos = $classEmpresaServico->pegarServicos($empresa_id);

// Loga o resultado para o console do navegador
if (count($servicos) == 0) {

    $logger->log(
        "Nenhum serviço encontrado para o empresa_id " . $empresa_id,
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        ["empresa_id" => $empresa_id]
    );

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
