<?php
require("../../config/Database.php");
require("../../config/Suporte.php");
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

$conexao = $db->getConnection();
$classeSuporte = new Suporte($conexao);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : NULL;
$usuario_id = isset($usuario->id) ? $usuario->id : NULL;

$dadosSuporte = [
    "empresa_id" => isset($_POST["empresa_id_suporte"]) ? $_POST["empresa_id_suporte"] : NULL,
    "servico_id" => isset($_POST["servico_id"]) ? $_POST["servico_id"] : NULL,
    "cliente_id" => isset($_POST["cliente_id_suporte"]) ? $_POST["cliente_id_suporte"] : NULL,
    "assunto" => isset($_POST["assunto"]) ? $_POST["assunto"] : NULL,
    "mensagem" => isset($_POST["mensagem"]) ? $_POST["mensagem"] : NULL,
];

try {
    if (strlen($dadosSuporte["mensagem"]) < 20) {

        $logger->log(
            "Mensagem de suporte inválida",
            "WARNING",
            $usuario_id,
            [
                "mensagem" => $dadosSuporte["mensagem"],
                "empresa_id" => $dadosSuporte["empresa_id"],
                "servico_id" => $dadosSuporte["servico_id"]
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "A mensagem deve ter no mínimo 20 caracteres!"
        ]);
        exit;
    }

    if (!$dadosSuporte["empresa_id"]) {

        $logger->log(
            "Tentativa de cadastro de suporte sem empresa",
            "WARNING",
            $usuario_id,
            [
                "empresa_id" => $dadosSuporte["empresa_id"],
                "servico_id" => $dadosSuporte["servico_id"]
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Empresa obrigatória!"
        ]);
        exit;
    }
    if (!$dadosSuporte["servico_id"]) {

        $logger->log(
            "Tentativa de cadastro de suporte sem serviço",
            "WARNING",
            $usuario_id,
            [
                "empresa_id" => $dadosSuporte["empresa_id"],
                "servico_id" => $dadosSuporte["servico_id"]
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Serviço obrigatório!"
        ]);
        exit;
    }

    $suporte = $conexao->prepare(
        "INSERT INTO
                `suporte` (
                `empresa_id`,
                `servico_id`,
                `cliente_id`,
                `usuario_id`,
                `assunto`
            ) VALUES (
                :empresa_id,
                :servico_id,
                :cliente_id,
                :usuario_id,
                :assunto
            )"
    );

    $suporte->bindParam(":empresa_id", $dadosSuporte["empresa_id"], PDO::PARAM_INT);
    $suporte->bindParam(":servico_id", $dadosSuporte["servico_id"], PDO::PARAM_INT);
    $suporte->bindParam(":cliente_id", $dadosSuporte["cliente_id"], PDO::PARAM_INT);
    $suporte->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $suporte->bindParam(":assunto", $dadosSuporte["assunto"], PDO::PARAM_STR);

    $suporte->execute();

    $suporte_id = $conexao->lastInsertId();

    $suporte_mensagem = $conexao->prepare(
        "INSERT INTO
            `suporte_mensagem` (
                `suporte_id`,
                `mensagem`
            ) VALUES (
                :suporte_id,
                :mensagem
            )"
    );

    $suporte_mensagem->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
    $suporte_mensagem->bindParam(":mensagem", $dadosSuporte["mensagem"], PDO::PARAM_STR);

    $suporte_mensagem->execute();

    $logger->log(
        "Mensagem de suporte cadastrada com sucesso",
        "INFO",
        $usuario_id,
        [
            "suporte_id" => $suporte_id,
            "mensagem" => $dadosSuporte["mensagem"]
        ]
    );

    echo json_encode([
        "status" => "success",
        "message" => "Suporte solicitado!",
        "id" => $conexao->lastInsertId()
    ]);
} catch (Exception $e) {

    $logger->log(
        "Erro ao processar dados de suporte",
        "ERROR",
        $usuario_id,
        [
            "mensagem" => $e->getMessage(),
            "empresa_id" => $dadosSuporte["empresa_id"],
            "servico_id" => $dadosSuporte["servico_id"]
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao processar dados de suporte: " . $e->getMessage()
    ]);
    exit;
}
