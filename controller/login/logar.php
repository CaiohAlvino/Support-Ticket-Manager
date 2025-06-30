<?php
require('../../config/Database.php');
require('../../config/Login.php');
require('../../config/Logger.php');

// Conexão com o banco
// Ajuste conforme a implementação real da classe Database
$db = new Database();
$logger = new Logger();
$login = new Login($db);
$logger->setLogLevel("INFO"); // Só registra INFO, WARNING e ERROR (opcional)

// Validação básica de entrada
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($email) || empty($senha)) {

    $logger->log(
        "Tentativa de login com campos vazios",
        "ERROR",
        $email ?? NULL,
        ["ip" => $_SERVER["REMOTE_ADDR"] ?? NULL]
    );

    echo json_encode([
        'erro' => 'E-mail e senha são obrigatórios.'
    ]);
    exit;
}

// Autentica usuário
$resposta = $login->autenticar($email, $senha);

if ($resposta['status'] === 'success') {
    $logger->log(
        "Usuário autenticado com sucesso",
        "INFO",
        $resposta['usuario_id'] ?? NULL,
        ["ip" => $_SERVER["REMOTE_ADDR"] ?? NULL]
    );
} else {
    $logger->log(
        "Falha na autenticação do usuário",
        "WARNING",
        NULL,
        ["ip" => $_SERVER["REMOTE_ADDR"] ?? NULL]
    );
}

echo json_encode($resposta);
exit;
