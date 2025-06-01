<?php
require("../../config/Database.php");
require("../../config/Empresa.php");
// require("../../config/JWT.php");

$db = new Database();

// $tokenEValido = JWT::verificar($db);

// if (!$tokenEValido) {
//     echo json_encode(["status" => "error", "message" => "Token inválido."]);
//     exit;
// }

$conexao = $db->getConnection();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$empresa_id = isset($_SESSION["empresa_id"]) ? $_SESSION["empresa_id"] : NULL;

if (!$empresa_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Empresa não encontrada!"
    ]);
    exit;
}

$suporte_id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$admin_id = isset($_POST["admin_id"]) && !empty($_POST["admin_id"]) ? $_POST["admin_id"] : NULL;
$mensagem = isset($_POST["mensagem"]) ? $_POST["mensagem"] : NULL;
$status = isset($_POST["status"]) ? $_POST["status"] : "AGUARDANDO_SUPORTE";

if (!$suporte_id) {
    echo json_encode([
        "status" => "error",
        "message" => "ID do suporte não informado!"
    ]);
    exit;
}

try {
    $conexao->beginTransaction();

    if (!empty($mensagem)) {
        $suporte_mensagem = $conexao->prepare("INSERT INTO `suporte_mensagem` (
            `suporte_id`,
            `mensagem`,
            `proprietario`
            ) VALUES (
            :suporte_id,
            :mensagem,
            'USUARIO'
            )");

        $suporte_mensagem->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
        $suporte_mensagem->bindParam(":mensagem", $mensagem, PDO::PARAM_STR);
        $suporte_mensagem->execute();

        $atualizar_respondido = $conexao->prepare("UPDATE `suporte_mensagem` SET
        `respondido` = 1 WHERE `suporte_id` = :suporte_id AND `proprietario` = 'ADMIN'");

        $atualizar_respondido->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
        $atualizar_respondido->execute();
    }

    $suporte = $conexao->prepare("UPDATE `suporte` SET
                                    `admin_id` = :admin_id,
                                    `status` = CASE WHEN :status = 'AGUARDANDO_SUPORTE' AND :admin_id IS NULL THEN `status` ELSE :status
                                    END WHERE
                                        `id` = :id
                                    AND
                                        `empresa_id` = :empresa_id");

    $suporte->bindParam(":id", $suporte_id, PDO::PARAM_INT);
    $suporte->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
    if ($admin_id === NULL) {
        $suporte->bindValue(":admin_id", NULL, PDO::PARAM_NULL);
    } else {
        $suporte->bindParam(":admin_id", $admin_id, PDO::PARAM_INT);
    }
    $suporte->bindParam(":status", $status, PDO::PARAM_STR);

    $suporte->execute();

    $conexao->commit();

    switch ($status) {
        case "FECHADO":
            echo json_encode([
                "status" => "success",
                "message" => "Ticket fechado!",
            ]);
            break;
        case "ABERTO":
            echo json_encode([
                "status" => "success",
                "message" => isset($mensagem) ? "Mensagem enviada!" : "Ticket aberto!",
            ]);
            break;
        default:
            echo json_encode([
                "status" => "success",
                "message" => "Mensagem enviada!",
            ]);
    }
} catch (\Throwable $th) {
    $conexao->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao processar solicitação: " . $th->getMessage()
    ]);
}
