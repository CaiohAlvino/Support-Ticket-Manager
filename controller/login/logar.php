<?php
// controller/login/logar.php
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/Database.php';
require_once '../../config/Login.php';

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
$login = new Login($db);

// Autentica usuário
$resposta = $login->autenticar($email, $senha);

echo json_encode($resposta);
exit;
