<?php
require("../../config/Database.php");
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

$suporte_id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$usuario_id = isset($_POST["usuario_id"]) && !empty($_POST["usuario_id"]) ? $_POST["usuario_id"] : NULL;
$cliente_id = isset($_POST["cliente_id"]) && !empty($_POST["cliente_id"]) ? $_POST["cliente_id"] : NULL;
$empresa_id = isset($_POST["empresa_id"]) && !empty($_POST["empresa_id"]) ? $_POST["empresa_id"] : NULL;
$mensagem = isset($_POST["mensagem"]) ? $_POST["mensagem"] : NULL;
$status = isset($_POST["status"]) ? $_POST["status"] : null;

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
        $proprietario = ($_SESSION["usuario_grupo"] == 2) ? 'CLIENTE' : 'USUARIO';

        if ($status === null) {
            $status = ($_SESSION["usuario_grupo"] == 2) ? 'AGUARDANDO_SUPORTE' : 'RESPONDIDO';
        }

        $suporte_mensagem = $conexao->prepare("INSERT INTO `suporte_mensagem` (
            `suporte_id`,
            `mensagem`,
            `proprietario`
            ) VALUES (
            :suporte_id,
            :mensagem,
            :proprietario
            )");

        $suporte_mensagem->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
        $suporte_mensagem->bindParam(":mensagem", $mensagem, PDO::PARAM_STR);
        $suporte_mensagem->bindParam(":proprietario", $proprietario, PDO::PARAM_STR);
        $suporte_mensagem->execute();

        $atualizar_respondido = $conexao->prepare("UPDATE `suporte_mensagem` SET
        `respondido` = 1 WHERE `suporte_id` = :suporte_id AND `proprietario` = 'USUARIO'");

        $atualizar_respondido->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
        $atualizar_respondido->execute();
    } else {
        if ($status === null) {
            $status = "AGUARDANDO_SUPORTE";
        }
    }

    // Atualiza o suporte apenas se não for cliente (grupo diferente de 2)
    if ($_SESSION["usuario_grupo"] != 2) {
        $suporte = $conexao->prepare("UPDATE `suporte` SET
                                        `usuario_id` = :usuario_id,
                                        `status` = :status
                                    WHERE
                                        `id` = :id");

        $suporte->bindParam(":id", $suporte_id, PDO::PARAM_INT);
        if ($usuario_id === NULL) {
            $suporte->bindValue(":usuario_id", NULL, PDO::PARAM_NULL);
        } else {
            $suporte->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        }
        $suporte->bindParam(":status", $status, PDO::PARAM_STR);

        $suporte->execute();
    } else {
        // Cliente só pode atualizar o status
        $suporte = $conexao->prepare("UPDATE `suporte` SET
                                        `status` = :status
                                    WHERE
                                        `id` = :id");
        $suporte->bindParam(":id", $suporte_id, PDO::PARAM_INT);
        $suporte->bindParam(":status", $status, PDO::PARAM_STR);
        $suporte->execute();
    }

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
