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

$empresa_id = isset($_POST["id"]) ? $_POST["id"] : NULL;

try {
    if (!$empresa_id) {

        $logger->log(
            "Parâmetro importante faltando para exclusão de empresa",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["id" => $empresa_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Registro não encontrado!"
        ]);
        exit;
    }

    $query = "SELECT
                COUNT(*)
            FROM
                `empresa_cliente`
            WHERE
                `empresa_id` = :empresa_id";

    $existeEmpresaCliente = $db->prepare($query);
    $existeEmpresaCliente->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
    $existeEmpresaCliente->execute();

    if ($existeEmpresaCliente->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de empresa vinculada a cliente",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $empresa_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o cliente, pois ele está vinculado a um cliente."
        ]);
        exit;
    }

    $query = "SELECT COUNT(*) FROM `empresa_servico` WHERE `empresa_id` = :empresa_id";
    $existeServicoEmpresa = $db->prepare($query);
    $existeServicoEmpresa->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
    $existeServicoEmpresa->execute();
    if ($existeServicoEmpresa->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de empresa vinculada a serviço",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $empresa_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o cliente, pois ele está vinculado a um serviço."
        ]);
        exit;
    }

    $query = "SELECT COUNT(*) FROM `empresa_usuario` WHERE `empresa_id` = :empresa_id";
    $existeUsuarioEmpresa = $db->prepare($query);
    $existeUsuarioEmpresa->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
    $existeUsuarioEmpresa->execute();

    if ($existeUsuarioEmpresa->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de empresa vinculada a usuário",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $empresa_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o cliente, pois ele está vinculado a um usuário."
        ]);
        exit;
    }

    $query = "SELECT COUNT(*) FROM `suporte` WHERE `empresa_id` = :empresa_id";

    $existeSuporteCliente = $db->prepare($query);
    $existeSuporteCliente->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
    $existeSuporteCliente->execute();

    if ($existeSuporteCliente->fetchColumn() > 0) {

        $logger->log(
            "Tentativa de exclusão de empresa vinculada a suporte",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $empresa_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Não é possível excluir o cliente, pois ele está vinculado a um suporte."
        ]);
        exit;
    }
} catch (Exception $e) {
    $logger->log(
        "Erro ao obter o ID da empresa",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        ["error" => $e->getMessage()]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao obter o ID da empresa",
        "debug" => $e->getMessage()
    ]);
    exit;
}


try {
    $empresa = $db->prepare("DELETE FROM `empresa` WHERE `id` = :id");
    $empresa->bindParam(":id", $empresa_id, PDO::PARAM_INT);
    $empresa->execute();

    if ($empresa->rowCount() === 0) {

        $logger->log(
            "Nenhum registro foi excluído",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $empresa_id]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Nenhum registro foi excluído. Verifique os parâmetros enviados.",
            "debug" => "id: $empresa_id"
        ]);
        exit;
    } else {
        $logger->log(
            "Empresa excluída com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            ["empresa_id" => $empresa_id]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Empresa excluída!"
        ]);
    }
} catch (Exception $e) {

    $logger->log(
        "Erro ao verificar vinculações da empresa",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        [
            "empresa_id" => $empresa_id,
            "error" => $e->getMessage()
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao verificar vinculações da empresa",
        "debug" => $e->getMessage()
    ]);
    exit;
}
