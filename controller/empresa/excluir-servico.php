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

$id = isset($_POST["id"]) ? (int)$_POST["id"] : null;

if (!$id) {

    $logger->log(
        "Parâmetro importante faltando para exclusão de serviço",
        "WARNING",
        $_SESSION["usuario_id"] ?? null,
        ["id" => $id]
    );

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

        $logger->log(
            "Serviço excluído com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            ["id" => $id]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Serviço excluído com sucesso"
        ]);
    } else {

        $logger->log(
            "Nenhum registro foi excluído",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["id" => $id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Nenhum registro foi excluído. Verifique os parâmetros enviados.",
            "debug" => "id: $id"
        ]);
    }
    exit;
} catch (Exception $e) {
    $logger->log(
        "Erro ao excluir serviço da empresa",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        [
            "id" => $id,
            "error" => $e->getMessage()
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao excluir serviço da empresa",
        "debug" => $e->getMessage()
    ]);
    exit;
}
