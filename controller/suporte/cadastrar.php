<?php
require("../../config/Database.php");
require("../../config/Empresa.php");
require("../../config/Suporte.php");
// require("../../config/JWT.php");

$db = new Database();

// $tokenEValido = JWT::verificar($db);

// if (!$tokenEValido) {
//     echo json_encode(["status" => "error", "message" => "Token inválido."]);
//     exit;
// }

$conexao = $db->getConnection();
$classeSuporte = new Suporte($conexao);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$empresa_id = isset($_SESSION["empresa_id"]) ? $_SESSION["empresa_id"] : NULL;

if (!$empresa_id) {
    echo json_encode(["status" => "error", "message" => "Empresa não encontrada!"]);
    exit;
}

$usuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : NULL;
$usuario_id = isset($usuario->id) ? $usuario->id : NULL;

$dadosSuporte = [
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

$suporte = $conexao->prepare("INSERT INTO `suporte` (
                        `empresa_id`,
                        `usuario_id`,
                        `assunto`
                        ) VALUES (
                        :empresa_id,
                        :usuario_id,
                        :assunto
                        )");

$suporte->bindParam(":empresa_id", $empresa_id, PDO::PARAM_INT);
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
