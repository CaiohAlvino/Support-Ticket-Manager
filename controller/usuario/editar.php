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
    "id" => isset($_POST["id"]) ? (int)$_POST["id"] : NULL,
    "situacao" => isset($_POST["ativo"]) ? (int)$_POST["ativo"] : NULL,
    "grupo_id" => isset($_POST["grupo_id"]) ? (int)$_POST["grupo_id"] : NULL,
    "nome" => isset($_POST["nome"]) ? trim($_POST["nome"]) : NULL,
    "email" => isset($_POST["email"]) ? trim($_POST["email"]) : NULL,
    "senha" => isset($_POST["senha"]) ? trim($_POST["senha"]) : NULL,
];

try {
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

        $logger->log(
            "Tentativa de cadastro de usuário com e-mail existente",
            "WARNING",
            $_SESSION["usuario_id"] ?? null,
            [
                "email" => $dadosUsuario["email"]
            ]
        );

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

        $logger->log(
            "Usuário atualizado com sucesso",
            "INFO",
            $_SESSION["usuario_id"] ?? null,
            [
                "usuario_id" => $dadosUsuario["id"],
                "nome" => $dadosUsuario["nome"]
            ]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Usuário atualizado com sucesso."
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
