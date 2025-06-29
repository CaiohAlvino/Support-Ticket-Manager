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

$servico_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

try {

    if (!$servico_id) {

        $logger->log(
            "Tentativa de exclusão de serviço sem ID",
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

    $query = "SELECT COUNT(*) FROM `empresa_servico` WHERE `empresa_id` = :empresa_id";
    $existeServicoEmpresa = $db->prepare($query);
    $existeServicoEmpresa->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
    $existeServicoEmpresa->execute();

    if ($existeServicoEmpresa->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de serviço vinculado a uma empresa",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["servico_id" => $servico_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o cliente, pois ele está vinculado a um serviço."
        ]);
        exit;
    }

    $query = "SELECT COUNT(*) FROM `suporte` WHERE `servico_id` = :servico_id";
    $existeSuporteServico = $db->prepare($query);
    $existeSuporteServico->bindParam(":servico_id", $servico_id, PDO::PARAM_INT);
    $existeSuporteServico->execute();
    if ($existeSuporteServico->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de serviço vinculado a um suporte",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["servico_id" => $servico_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o serviço, pois ele está vinculado a um suporte."
        ]);
        exit;
    }

    $servico = $db->prepare("DELETE FROM `servico` WHERE `id` = :id");
    $servico->bindParam(":id", $servico_id, PDO::PARAM_INT);
    $servico->execute();

    $logger->log(
        "Serviço excluído com sucesso",
        "INFO",
        $_SESSION["usuario_id"] ?? null,
        ["servico_id" => $servico_id]
    );

    echo json_encode([
        "status" => "success",
        "message" => "Serviço excluído!"
    ]);
} catch (Exception $e) {
    $logger->log(
        "Erro ao processar a solicitação de exclusão de serviço",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        ["error" => $e->getMessage()]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao processar a solicitação."
    ]);
    exit;
}
