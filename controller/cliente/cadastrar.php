<?php
require("../../config/Database.php");
require("../../config/Validador.php");
require("../../config/Cliente.php");
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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Usado para testes
$grupo_id = 2;

$dadosCliente = [
    "id" => isset($_POST["id"]) ? $_POST["id"] : NULL,
    "tipo" => isset($_POST["tipo"]) ? trim($_POST["tipo"]) : NULL,
    "nome_fantasia" => isset($_POST["nome_fantasia"]) ? trim($_POST["nome_fantasia"]) : NULL,
    "razao_social" => isset($_POST["razao_social"]) ? trim($_POST["razao_social"]) : NULL,
    "documento" => isset($_POST["documento"]) ? trim($_POST["documento"]) : NULL,
    "responsavel_nome" => isset($_POST["responsavel_nome"]) ? trim($_POST["responsavel_nome"]) : NULL,
    "responsavel_documento" => isset($_POST["responsavel_documento"]) ? trim($_POST["responsavel_documento"]) : NULL,
    "responsavel_telefone" => isset($_POST["responsavel_telefone"]) ? trim($_POST["responsavel_telefone"]) : NULL,
    "responsavel_email" => isset($_POST["responsavel_email"]) ? trim($_POST["responsavel_email"]) : NULL,
    "telefone" => isset($_POST["telefone"]) ? trim($_POST["telefone"]) : NULL,
    "cep" => isset($_POST["cep"]) ? trim($_POST["cep"]) : NULL,
    "endereco" => isset($_POST["endereco"]) ? trim($_POST["endereco"]) : NULL,
    "numero" => isset($_POST["numero"]) ? trim($_POST["numero"]) : NULL,
    "bairro" => isset($_POST["bairro"]) ? trim($_POST["bairro"]) : NULL,
    "cidade" => isset($_POST["cidade"]) ? trim($_POST["cidade"]) : NULL,
    "estado" => isset($_POST["estado"]) ? trim($_POST["estado"]) : NULL,
];

// $validacao = Validador::validarCliente($dadosCliente, $conexao);

// if (!$validacao["valido"]) {
//     echo json_encode([
//         "status" => "error",
//         "message" => $validacao["mensagem"]
//     ]);
//     exit;
// }

try {
    // Criptografa a senha antes de salvar
    $senhaHash = password_hash("123", PASSWORD_DEFAULT);

    $sql = "INSERT INTO `usuario` (
        `grupo_id`,
        `nome`,
        `email`,
        `senha`
    ) VALUES (
        :grupo_id,
        :nome,
        :email,
        :senha
    )";

    $stmt = $conexao->prepare($sql);

    $stmt->bindParam(":grupo_id", $grupo_id, PDO::PARAM_INT);
    $stmt->bindParam(":nome", $dadosCliente["responsavel_nome"], PDO::PARAM_STR);
    $stmt->bindParam(":email", $dadosCliente["responsavel_email"], PDO::PARAM_STR);
    $stmt->bindParam(":senha", $senhaHash, PDO::PARAM_STR);

    $stmt->execute();

    $usuario_id = $conexao->lastInsertId();

    $logger->log(
        "Usuario ($usuario_id) cadastrado com sucesso para o cliente: {$dadosCliente['responsavel_nome']}",
        "INFO",
        $_SESSION["usuario_id"] ?? null,
        ["usuario_id" => $usuario_id]
    );
} catch (PDOException $e) {

    $logger->logException(
        $e,
        $_SESSION["usuario_id"] ?? null,
        ["dadosCliente" => $dadosCliente]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao cadastrar usuário: " . $e->getMessage()
    ]);
    exit;
}

// ------------- Cadastro Cliente ------------- //
try {
    $conexao->beginTransaction();

    if ($dadosCliente["tipo"] == "CNPJ") {
        $sql = "INSERT INTO `cliente` (
            `usuario_id`,
            `tipo`,
            `nome_fantasia`,
            `razao_social`,
            `documento`,
            `responsavel_nome`,
            `responsavel_documento`,
            `responsavel_telefone`,
            `responsavel_email`,
            `telefone`,
            `cep`,
            `endereco`,
            `numero`,
            `bairro`,
            `cidade`,
            `estado`
            ) VALUES (
            :usuario_id,
            :tipo,
            :nome_fantasia,
            :razao_social,
            :documento,
            :responsavel_nome,
            :responsavel_documento,
            :responsavel_telefone,
            :responsavel_email,
            :telefone,
            :cep,
            :endereco,
            :numero,
            :bairro,
            :cidade,
            :estado
            )";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $dadosCliente["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":nome_fantasia", $dadosCliente["nome_fantasia"], PDO::PARAM_STR);
        $stmt->bindParam(":razao_social", $dadosCliente["razao_social"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $dadosCliente["documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_nome", $dadosCliente["responsavel_nome"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_documento", $dadosCliente["responsavel_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_telefone", $dadosCliente["responsavel_telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_email", $dadosCliente["responsavel_email"], PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $dadosCliente["telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":cep", $dadosCliente["cep"], PDO::PARAM_STR);
        $stmt->bindParam(":endereco", $dadosCliente["endereco"], PDO::PARAM_STR);
        $stmt->bindParam(":numero", $dadosCliente["numero"], PDO::PARAM_STR);
        $stmt->bindParam(":bairro", $dadosCliente["bairro"], PDO::PARAM_STR);
        $stmt->bindParam(":cidade", $dadosCliente["cidade"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $dadosCliente["estado"], PDO::PARAM_STR);
    } else {
        $sql = "INSERT INTO `cliente` (
            `usuario_id`,
            `tipo`,
            `nome_fantasia`,
            `responsavel_nome`,
            `responsavel_documento`,
            `responsavel_telefone`,
            `responsavel_email`,
            `telefone`,
            `cep`,
            `endereco`,
            `numero`,
            `bairro`,
            `cidade`,
            `estado`
        ) VALUES (
            :usuario_id,
            :tipo,
            :nome_fantasia,
            :responsavel_nome,
            :responsavel_documento,
            :responsavel_telefone,
            :responsavel_email,
            :telefone,
            :cep,
            :endereco,
            :numero,
            :bairro,
            :cidade,
            :estado
        )";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $dadosCliente["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":nome_fantasia", $dadosCliente["nome_fantasia"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_nome", $dadosCliente["responsavel_nome"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_documento", $dadosCliente["responsavel_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_telefone", $dadosCliente["responsavel_telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_email", $dadosCliente["responsavel_email"], PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $dadosCliente["telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":cep", $dadosCliente["cep"], PDO::PARAM_STR);
        $stmt->bindParam(":endereco", $dadosCliente["endereco"], PDO::PARAM_STR);
        $stmt->bindParam(":numero", $dadosCliente["numero"], PDO::PARAM_STR);
        $stmt->bindParam(":bairro", $dadosCliente["bairro"], PDO::PARAM_STR);
        $stmt->bindParam(":cidade", $dadosCliente["cidade"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $dadosCliente["estado"], PDO::PARAM_STR);
    }
    $stmt->execute();

    $conexao->commit();

    $logger->log(
        "Cliente cadastrado com sucesso",
        "INFO",
        $_SESSION["usuario_id"] ?? null,
        ["dadosCliente" => $dadosCliente]
    );

    echo json_encode([
        "status" => "success",
        "message" => "Dados do cliente cadastrados!"
    ]);
} catch (PDOException $e) {
    $conexao->rollBack();

    $logger->logException(
        $e,
        $_SESSION["usuario_id"] ?? null,
        ["dadosCliente" => $dadosCliente]
    );

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao cadastrar dados do cliente: " . $e->getMessage()
    ]);
}
