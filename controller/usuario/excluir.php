<?php
require("../../config/Database.php");
require("../../config/JWT.php");

$db = new Database();

$dados = JWT::verificar($db);
if (!$dados) {
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

if (!$usuario_id) {
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
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o usuário, pois ele está vinculado a um suporte."
    ]);
    exit;
}

$usuario = $db->prepare("DELETE FROM `usuario` WHERE `id` = :id");
$usuario->bindParam(":id", $usuario_id, PDO::PARAM_INT);
$usuario->execute();

echo json_encode([
    "status" => "success",
    "message" => "Usuário excluído!"
]);
