<?php

require("../../config/Database.php");

$db = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dadosUsuario = [
    "id" => isset($_POST["id"]) ? (int)$_POST["id"] : NULL,
    "situacao" => isset($_POST["ativo"]) ? (int)$_POST["ativo"] : NULL,
    "grupo_id" => isset($_POST["grupo_id"]) ? (int)$_POST["grupo_id"] : NULL,
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL,
    "email" => isset($_POST["email"]) ? trim($_POST["email"]) : NULL,
    "senha" => isset($_POST["senha"]) ? trim($_POST["senha"]) : NULL,
];

$existeUsuario = $db->prepare(
    "SELECT * FROM
        `usuario`
    WHERE
        `email` = :email AND
        `id` != :id
    LIMIT 1"
);
$existeUsuario->bindParam(":email", $dadosUsuario["email"], PDO::PARAM_STR);
$existeUsuario->bindParam(":id", $dadosUsuario["id"], PDO::PARAM_INT);
$existeUsuario->execute();

if ($existeUsuario->rowCount() > 0) {
    $usuarioExistente = $existeUsuario->fetch(PDO::FETCH_ASSOC);
    if ($usuarioExistente) {
        echo json_encode([
            "status" => "error",
            "message" => "Já existe um usuário cadastrado com o e-mail informado."
        ]);
        exit;
    }
} else {
    $usuario = $db->prepare(
        "UPDATE
            `usuario`
        SET
            `situacao` = :situacao,
            `grupo_id` = :grupo_id,
            `nome` = :nome,
            `email` = :email,
            `senha` = :senha
        WHERE
            `id` = :id"
    );
    $usuario->bindParam(":situacao", $dadosUsuario["situacao"], PDO::PARAM_INT);
    $usuario->bindParam(":grupo_id", $dadosUsuario["grupo_id"], PDO::PARAM_INT);
    $usuario->bindParam(":nome", $dadosUsuario["nome"], PDO::PARAM_STR);
    $usuario->bindParam(":email", $dadosUsuario["email"], PDO::PARAM_STR);

    // Se a senha for informada, criptografá-la antes de salvar
    if (!empty($dadosUsuario["senha"])) {
        $senhaCriptografada = password_hash($dadosUsuario["senha"], PASSWORD_BCRYPT);
        $usuario->bindParam(":senha", $senhaCriptografada, PDO::PARAM_STR);
    } else {
        // Se a senha não for informada, manter a senha atual
        $usuario->bindValue(":senha", NULL, PDO::PARAM_NULL);
    }

    $usuario->bindParam(":id", $dadosUsuario["id"], PDO::PARAM_INT);
    $usuario->execute();

    echo json_encode([
        "status" => "success",
        "message" => "Usuário atualizado com sucesso."
    ]);
    exit;
}
