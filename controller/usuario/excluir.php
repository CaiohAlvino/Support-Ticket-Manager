<?php
require("../../config/Database.php");
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

$usuario_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

try {
    if (!$usuario_id) {

        $logger->log(
            "Tentativa de exclusão de usuário sem ID",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["ip" => $_SERVER["REMOTE_ADDR"] ?? null]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Registro não encontrado!"
        ]);
        exit;
    }

    $query = "SELECT COUNT(*) FROM `empresa_usuario` WHERE `usuario_id` = :usuario_id";
    $existeUsuarioEmpresa = $db->prepare($query);
    $existeUsuarioEmpresa->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $existeUsuarioEmpresa->execute();
    if ($existeUsuarioEmpresa->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de usuário vinculado a uma empresa",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["usuario_id" => $usuario_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o usuário, pois ele está vinculado a uma empresa."
        ]);
        exit;
    }

    $query = "SELECT COUNT(*) FROM `cliente` WHERE `usuario_id` = :usuario_id";
    $existeClienteUsuario = $db->prepare($query);
    $existeClienteUsuario->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $existeClienteUsuario->execute();
    if ($existeClienteUsuario->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de usuário vinculado a um cliente",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["usuario_id" => $usuario_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o usuário, pois ele está vinculado a um cliente."
        ]);
        exit;
    }

    $query = "SELECT COUNT(*) FROM `suporte` WHERE `usuario_id` = :usuario_id";
    $existeSuporteUsuario = $db->prepare($query);
    $existeSuporteUsuario->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $existeSuporteUsuario->execute();
    if ($existeSuporteUsuario->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de usuário vinculado a um suporte",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["usuario_id" => $usuario_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o usuário, pois ele está vinculado a um suporte."
        ]);
        exit;
    }

    $usuario = $db->prepare("DELETE FROM `usuario` WHERE `id` = :id");
    $usuario->bindParam(":id", $usuario_id, PDO::PARAM_INT);
    $usuario->execute();

    $logger->log(
        "Usuário excluído com sucesso",
        "INFO",
        $_SESSION["usuario_id"] ?? null,
        ["usuario_id" => $usuario_id]
    );

    echo json_encode([
        "status" => "success",
        "message" => "Usuário excluído!"
    ]);
} catch (Exception $e) {

    $logger->log(
        "Erro ao excluir usuário",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        [
            "usuario_id" => $usuario_id,
            "error" => $e->getMessage()
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao excluir usuário: " . $e->getMessage()
    ]);
    exit;
}
