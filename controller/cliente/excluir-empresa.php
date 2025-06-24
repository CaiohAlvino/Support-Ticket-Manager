<?php
require("../../config/Database.php");

header('Content-Type: application/json');

$db = new Database();

$id = isset($_POST["id"]) ? (int)$_POST["id"] : null;

if (!$id) {
    echo json_encode([
        "status" => "error",
        "message" => "Id inválido",
        "debug" => "cliente ou empresa não informado"
    ]);
    exit;
}

try {
    $query = "DELETE FROM
            `empresa_cliente`
        WHERE
            `id` = :id
        LIMIT 1";

    $excluirRegistro = $db->prepare($query);

    $excluirRegistro->bindParam(":id", $id, PDO::PARAM_INT);
    $excluirRegistro->execute();

    if ($excluirRegistro->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "empresa excluída com sucesso"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Nenhum registro foi excluído. Verifique os parâmetros enviados.",
            "debug" => "id: $id"
        ]);
    }
    exit;
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao excluir empresa do cliente",
        "debug" => $e->getMessage()
    ]);
    exit;
}
