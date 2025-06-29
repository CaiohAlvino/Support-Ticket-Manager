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

$servico_id = isset($_POST["servico_id"]) ? (int)$_POST["servico_id"] : NULL;
$empresa_id = isset($_POST["empresa_id"]) ? (int)$_POST["empresa_id"] : NULL;

if (!$servico_id || !$empresa_id) {
    echo json_encode([
        "status" => "error",
        "message" => "servico ou empresa inválido",
        "debug" => "servico_id ou empresa_id inválido"
    ]);
    exit;
}

$existeServico = $db->prepare(
    "SELECT * FROM
        `empresa_servico`
    WHERE
        `servico_id` = :servico_id
        AND `empresa_id` = :empresa_id
    LIMIT 1"
);

$existeServico->bindParam(":servico_id", $servico_id, PDO::PARAM_INT);
$existeServico->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeServico->execute();

if ($existeServico->rowCount() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Serviço já cadastrado para esta empresa",
        "debug" => "Serviço já cadastrado"
    ]);
    exit;
} else {
    try {
        $query = "INSERT INTO
                `empresa_servico` (`empresa_id`, `servico_id`)
            VALUES
                (:empresa_id, :servico_id)";

        $servico = $db->prepare($query);

        $servico->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
        $servico->bindParam(":servico_id", $servico_id, PDO::PARAM_INT);
        $servico->execute();

        echo json_encode([
            "status" => "success",
            "message" => "Serviço cadastrado com sucesso",
            "servico_id" => $servico_id,
            "empresa_id" => $empresa_id
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Erro ao cadastrar serviço",
            "debug" => $e->getMessage()
        ]);
        exit;
    }
}
