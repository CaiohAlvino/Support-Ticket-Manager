<?php
require_once 'init.php';

// Verificar se está logado
if (isset($_SESSION['token'])) {
    echo "Token: " . $_SESSION['token'] . "<br>";
    echo "Usuário: " . print_r($_SESSION['usuario'], true) . "<br>";
    echo "Verificação: " . (JWT::verificar($db) ? "OK" : "FALHA") . "<br>";
} else {
    echo "Não está logado";
}
