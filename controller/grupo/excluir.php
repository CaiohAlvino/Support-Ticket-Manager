<?php

require("../../config/Database.php");

$db = new Database();

$grupo_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$grupo_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Registro não encontrado!"
    ]);
    exit;
}

$query = "SELECT COUNT(*) FROM `usuario` WHERE `grupo_id` = :grupo_id";

$existeUsuarioGrupo = $db->prepare($query);
$existeUsuarioGrupo->bindParam(":grupo_id", $grupo_id, PDO::PARAM_INT);
$existeUsuarioGrupo->execute();

if ($existeUsuarioGrupo->fetchColumn() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Não é possível excluir o grupo, pois ele está vinculado a um usuário."
    ]);
    exit;
}

$grupo = $db->prepare("DELETE FROM `grupo` WHERE `id` = :id");
$grupo->bindParam(":id", $grupo_id, PDO::PARAM_INT);
$grupo->execute();

echo json_encode([
    "status" => "success",
    "message" => "Grupo excluído!"
]);
