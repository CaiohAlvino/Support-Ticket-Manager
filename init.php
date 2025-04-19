<?php
// Configurações de Sessão
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);

// Iniciar sessão
session_start();

// Carregar configurações
require_once 'config/config.php';
require_once 'config/functions.php';
require_once 'config/JWT.php';

// Carregar classes principais
require_once 'config/Database.php';
require_once 'config/Suporte.php';
require_once 'config/SuporteMensagem.php';

// Definir timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurar relatório de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'logs/error.log');

// Inicializar conexão com o banco de dados
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Verificar se é uma página que requer autenticação
$current_file = basename($_SERVER['SCRIPT_NAME']);
$public_pages = [
    'index.php',
    'registro.php',
    'recuperar-senha.php',
    'teste_auth.php' // Adicionado para testes
];

// Log para debug
error_log("Arquivo atual: " . $current_file);
error_log("Token na sessão: " . (isset($_SESSION['token']) ? "Sim" : "Não"));

if (!in_array($current_file, $public_pages)) {
    // Verificar autenticação em páginas restritas
    if (!isset($_SESSION['token'])) {
        error_log("Token não encontrado na sessão");
        session_destroy();
        redirecionarComMensagem(BASE_URL . '/index.php', 'Por favor, faça login para continuar.', 'warning');
        exit;
    }

    if (!JWT::verificar($db)) {
        error_log("Token inválido");
        session_destroy();
        redirecionarComMensagem(BASE_URL . '/index.php', 'Sua sessão expirou. Por favor, faça login novamente.', 'warning');
        exit;
    }
}
