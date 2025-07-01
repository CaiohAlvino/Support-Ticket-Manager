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

$grupo_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

if (!$grupo_id) {

    $logger->log(
        "Tentativa de exclusão de grupo sem ID",
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
try {
    $query = "SELECT COUNT(*) FROM `usuario` WHERE `grupo_id` = :grupo_id";

    $existeUsuarioGrupo = $db->prepare($query);
    $existeUsuarioGrupo->bindParam(":grupo_id", $grupo_id, PDO::PARAM_INT);
    $existeUsuarioGrupo->execute();

    if ($existeUsuarioGrupo->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de grupo vinculado a usuário",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["grupo_id" => $grupo_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o grupo, pois ele está vinculado a um usuário."
        ]);
        exit;
    } else {
        $grupo = $db->prepare("DELETE FROM `grupo` WHERE `id` = :id");
        $grupo->bindParam(":id", $grupo_id, PDO::PARAM_INT);
        $grupo->execute();

        $logger->log(
            "Grupo excluído com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            ["grupo_id" => $grupo_id]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Grupo excluído!"
        ]);
    }
} catch (PDOException $e) {

    $logger->log(
        "Erro ao preparar consulta para verificar grupo",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        ["error" => $e->getMessage()]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao verificar grupo: " . $e->getMessage()
    ]);
    exit;
}
