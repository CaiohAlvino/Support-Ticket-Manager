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

$dadosUsuario = [
    "grupo_id" => isset($_POST["grupo_id"]) ? trim($_POST["grupo_id"]) : NULL,
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL,
    "email" => isset($_POST["email"]) ? trim($_POST["email"]) : NULL,
    "senha" => isset($_POST["senha"]) ? trim($_POST["senha"]) : NULL
];

try {
    $existeUsuario = $db->prepare(
        "SELECT * FROM
            `usuario`
        WHERE
            `nome` = :nome
        LIMIT 1"
    );
    $existeUsuario->bindParam(":nome", $dadosUsuario["nome"], PDO::PARAM_STR);
    $existeUsuario->execute();

    if ($existeUsuario->rowCount() > 0) {
        $usuarioExistente = $existeUsuario->fetch(PDO::FETCH_ASSOC);
        $usuario_id = $usuarioExistente['id'];

        $logger->log(
            "Tentativa de cadastro de usuário com nome existente",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            [
                "usuario_id" => $usuario_id,
                "nome" => $dadosUsuario["nome"]
            ]
        );

        echo json_encode([
            "status" => "error",
            "message" => "Já existe um usuário cadastrado com este nome."
        ]);
        exit;
    } else {
        $usuario = $db->prepare(
            "INSERT INTO
                `usuario`
            (
                `grupo_id`,
                `nome`,
                `email`,
                `senha`
            )
            VALUES
            (
                :grupo_id,
                :nome,
                :email,
                :senha
            )"
        );

        $usuario->bindParam(":grupo_id", $dadosUsuario["grupo_id"], PDO::PARAM_INT);
        $usuario->bindParam(":nome", $dadosUsuario["nome"], PDO::PARAM_STR);
        $usuario->bindParam(":email", $dadosUsuario["email"], PDO::PARAM_STR);
        $usuario->bindParam(":senha", $dadosUsuario["senha"], PDO::PARAM_STR);
        $usuario->execute();
        $usuario_id = $db->getConnection()->lastInsertId();

        $logger->log(
            "Usuário cadastrado com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            [
                "usuario_id" => $usuario_id,
                "nome" => $dadosUsuario["nome"]
            ]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Usuário cadastrado com sucesso.",
            "usuario_id" => $usuario_id
        ]);
        exit;
    }
} catch (Exception $e) {
    $logger->log(
        "Erro ao processar dados do usuário",
        "ERROR",
        $_SESSION["usuario_id"] ?? null,
        [
            "error" => $e->getMessage(),
            "dados" => $dadosUsuario
        ]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao processar os dados do usuário."
    ]);
    exit;
}
