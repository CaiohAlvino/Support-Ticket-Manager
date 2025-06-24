<?php
require("../../config/Database.php");

header('Content-Type: application/json');

$db = new Database();

$id = isset($_POST["id"]) ? (int)$_POST["id"] : null;

if (!$id) {
    echo json_encode([
        "status" => "error",
        "message" => "Serviço ou empresa inválido",
        "debug" => "servico ou empresa não informado"
    ]);
    exit;
}

try {
    $excluirRegistro = $db->prepare(
        "DELETE FROM
            `empresa_servico`
        WHERE
            `id` = :id
        LIMIT 1"
    );

    $excluirRegistro->bindParam(":id", $id, PDO::PARAM_INT);
    $excluirRegistro->execute();

    if ($excluirRegistro->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Serviço excluído com sucesso"
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
        "message" => "Erro ao excluir serviço da empresa",
        "debug" => $e->getMessage()
    ]);
    exit;
}
