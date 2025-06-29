<?php
require('../../config/Database.php');
require('../../config/Login.php');
require('../../config/Logger.php');

// Validação básica de entrada
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($email) || empty($senha)) {
    echo json_encode([
        'erro' => 'E-mail e senha são obrigatórios.'
    ]);
    exit;
}

// Conexão com o banco
// Ajuste conforme a implementação real da classe Database
$db = new Database();
$logger = new Logger();
$logger->setLogLevel("INFO"); // Só registra INFO, WARNING e ERROR (opcional)

// Autentica usuário
$resposta = $login->autenticar($email, $senha);

if ($resposta['status'] === 'success') {
    $logger->log(
        "Usuário autenticado com sucesso",
        "INFO",
        $resposta['usuario_id'] ?? null,
        ["ip" => $_SERVER["REMOTE_ADDR"] ?? null]
    );
} else {
    $logger->log(
        "Falha na autenticação do usuário",
        "WARNING",
        null,
        ["ip" => $_SERVER["REMOTE_ADDR"] ?? null]
    );
}

echo json_encode($resposta);
exit;
