<?php
session_start();
// Limpa todas as variáveis de sessão
$_SESSION = array();
// Destroi a sessão
session_destroy();

setcookie("token", "", time() - 3600, "/");

// Redireciona para a tela de login
header("Location: ../../index.php");
exit;
