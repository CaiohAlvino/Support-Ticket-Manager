<?php
require("../../config/Database.php");
require("../../config/Suporte.php");
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

if (strlen($dadosSuporte["mensagem"]) < 20) {
    echo json_encode([
        "status" => "error",
        "message" => "A mensagem deve ter no mínimo 20 caracteres!"
    ]);
    exit;
}

if (!$dadosSuporte["empresa_id"]) {
    echo json_encode([
        "status" => "error",
        "message" => "Empresa obrigatória!"
    ]);
    exit;
}
if (!$dadosSuporte["servico_id"]) {
    echo json_encode([
        "status" => "error",
        "message" => "Serviço obrigatório!"
    ]);
    exit;
}

$suporte = $conexao->prepare("INSERT INTO `suporte` (
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
                        )");

$suporte->bindParam(":empresa_id", $dadosSuporte["empresa_id"], PDO::PARAM_INT);
$suporte->bindParam(":servico_id", $dadosSuporte["servico_id"], PDO::PARAM_INT);
$suporte->bindParam(":cliente_id", $dadosSuporte["cliente_id"], PDO::PARAM_INT);
$suporte->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
$suporte->bindParam(":assunto", $dadosSuporte["assunto"], PDO::PARAM_STR);

$suporte->execute();

$suporte_id = $conexao->lastInsertId();

$suporte_mensagem = $conexao->prepare("INSERT INTO `suporte_mensagem` (
                                `suporte_id`,
                                `mensagem`
                                ) VALUES (
                                :suporte_id,
                                :mensagem
                                )");
$suporte_mensagem->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
$suporte_mensagem->bindParam(":mensagem", $dadosSuporte["mensagem"], PDO::PARAM_STR);

$suporte_mensagem->execute();

echo json_encode([
    "status" => "success",
    "message" => "Suporte solicitado!",
    "id" => $conexao->lastInsertId()
]);
