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

$cliente_id = isset($_POST["cliente_id"]) ? (int)$_POST["cliente_id"] : NULL;
$empresa_id = isset($_POST["empresa_id"]) ? (int)$_POST["empresa_id"] : NULL;

if (!$cliente_id || !$empresa_id) {
    echo json_encode([
        "status" => "error",
        "message" => "cliente ou empresa inválido",
        "debug" => "cliente_id ou empresa_id inválido: " . $cliente_id . " - " . $empresa_id
    ]);
    exit;
}

$query = "SELECT * FROM
            `empresa_cliente`
        WHERE
            `cliente_id` = :cliente_id
            AND `empresa_id` = :empresa_id
        LIMIT 1";

$existeServico = $db->prepare($query);

$existeServico->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
$existeServico->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
$existeServico->execute();

if ($existeServico->rowCount() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Cliente já cadastrado para esta empresa",
        "debug" => "Cliente já cadastrado"
    ]);
    exit;
} else {
    try {
        $query = "INSERT INTO
                `empresa_cliente` (`empresa_id`, `cliente_id`)
            VALUES
                (:empresa_id, :cliente_id)";

        $servico = $db->prepare($query);

        $servico->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
        $servico->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $servico->execute();

        echo json_encode([
            "status" => "success",
            "message" => "Cliente cadastrado com sucesso",
            "cliente_id" => $cliente_id,
            "empresa_id" => $empresa_id
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Erro ao cadastrar cliente",
            "debug" => $e->getMessage()
        ]);
        exit;
    }
}
