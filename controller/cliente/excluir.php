<?php

require("../../config/Database.php");
require("../../config/JWT.php");
// require("../../config/Validador.php");
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

$cliente_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$cliente_id) {

    $logger->log(
        "Parâmetro importante faltando para exclusão do cliente",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        ["cliente_id" => $cliente_id]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Registro não encontrado!"
    ]);
    exit;
}

$query = "SELECT
            COUNT(*)
        FROM
            `empresa_cliente`
        WHERE
            `cliente_id` = :cliente_id";

$existeEmpresaCliente = $db->prepare($query);
$existeEmpresaCliente->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
$existeEmpresaCliente->execute();

if ($existeEmpresaCliente->fetchColumn() > 0) {

    $logger->log(
        "Não é possível excluir o cliente, pois ele está vinculado a uma empresa.",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        [
            "cliente_id" => $cliente_id
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a uma empresa."
    ]);
    exit;
}

$query = "SELECT COUNT(*) FROM `suporte` WHERE `cliente_id` = :cliente_id";

$existeSuporteCliente = $db->prepare($query);
$existeSuporteCliente->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
$existeSuporteCliente->execute();

if ($existeSuporteCliente->fetchColumn() > 0) {

    $logger->log(
        "Não é possível excluir o cliente, pois ele está vinculado a um suporte.",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        ["cliente_id" => $cliente_id]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o cliente, pois ele está vinculado a um suporte."
    ]);
    exit;
}

try {
    $query = "DELETE FROM
        `cliente`
    WHERE
        `id` = :id";

    $cliente = $db->prepare($query);
    $cliente->bindParam(":id", $cliente_id, PDO::PARAM_INT);
    $cliente->execute();

    $logger->log(
        "Cliente excluído com sucesso",
        "INFO",
        $_SESSION["usuario_id"] ?? null,
        ["cliente_id" => $cliente_id]
    );

    echo json_encode([
        "status" => "success",
        "message" => "Cliente excluído!"
    ]);
} catch (Exception $e) {

    $logger->logException(
        $e,
        $_SESSION["usuario_id"] ?? null,
        ["cliente_id" => $cliente_id]
    );

    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao excluir o cliente. Tente novamente mais tarde.",
        "debug" => $e->getMessage()
    ]);
}
