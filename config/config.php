<?php // Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_suporte');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações de Email
define('MAIL_HOST', 'mail.seudominio.com.br');
define('MAIL_USERNAME', 'notificacao@seudominio.com.br');
define('MAIL_PASSWORD', 'sua_senha');
define('MAIL_FROM', 'notificacao@seudominio.com.br');
define('MAIL_FROM_NAME', 'Sistema de Suporte');

// Status do Suporte
define('STATUS_ABERTO', 'ABERTO');
define('STATUS_AGUARDANDO_SUPORTE', 'AGUARDANDO_SUPORTE');
define('STATUS_RESPONDIDO', 'RESPONDIDO');
define('STATUS_FECHADO', 'FECHADO');

// Tipos de Usuário
define('TIPO_USUARIO', 'USUARIO');
define('TIPO_ADMIN', 'ADMIN');

// URLs do Sistema
define('BASE_URL', 'http://localhost/wdevel/Support-Ticket-Manager');
define('ASSETS_URL', BASE_URL . '/assets');

// Configurações Gerais
define('TIMEZONE', 'America/Sao_Paulo');
date_default_timezone_set(TIMEZONE);
