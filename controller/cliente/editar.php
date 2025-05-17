<?php
require("../../config/Database.php");
require("../../config/Validacao.php");
require("../../config/Empresa.php");
require("../../config/Fornecedor.php");
require("../../config/JWT.php");

$db = new Database();

$tokenEValido = JWT::verificar($db);

if (!$tokenEValido) {
    echo json_encode(["status" => "error", "message" => "Token inválido."]);
    exit;
}

$conexao = $db->getConnection();

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

$dadosFornecedor = [
    "id" => isset($_POST["id"]) ? $_POST["id"] : NULL,
    "tipo" => isset($_POST["tipo"]) ? trim($_POST["tipo"]) : NULL,
    "nome_fantasia" => isset($_POST["nome_fantasia"]) ? trim($_POST["nome_fantasia"]) : NULL,
    "razao_social" => isset($_POST["tipo"]) && $_POST["tipo"] == "CNPJ" ? trim($_POST["razao_social"]) : NULL,
    "documento" => isset($_POST["documento"]) ? trim($_POST["documento"]) : NULL,
    "responsavel" => isset($_POST["responsavel"]) ? trim($_POST["responsavel"]) : NULL,
    "responsavel_documento" => isset($_POST["responsavel_documento"]) ? trim($_POST["responsavel_documento"]) : NULL,
    "responsavel_whatsapp" => isset($_POST["responsavel_whatsapp"]) ? trim($_POST["responsavel_whatsapp"]) : NULL,
    "responsavel_email" => isset($_POST["responsavel_email"]) ? trim($_POST["responsavel_email"]) : NULL,
    "telefone" => isset($_POST["telefone"]) ? trim($_POST["telefone"]) : NULL,
    "cep" => isset($_POST["cep"]) ? trim($_POST["cep"]) : NULL,
    "logradouro" => isset($_POST["logradouro"]) ? trim($_POST["logradouro"]) : NULL,
    "numero" => isset($_POST["numero"]) ? trim($_POST["numero"]) : NULL,
    "bairro" => isset($_POST["bairro"]) ? trim($_POST["bairro"]) : NULL,
    "cidade" => isset($_POST["cidade"]) ? trim($_POST["cidade"]) : NULL,
    "estado" => isset($_POST["estado"]) ? trim($_POST["estado"]) : NULL,
];

if (empty($dadosFornecedor["id"])) {
    echo json_encode([
        "status" => "error",
        "message" => "Fornecedor não Encontrado."
    ]);
    exit;
}

$validacao = Validacao::validarFornecedor($dadosFornecedor, $conexao, $empresa_id);

if (!$validacao["valido"]) {
    echo json_encode([
        "status" => "error",
        "message" => $validacao["mensagem"]
    ]);
    exit;
}

try {
    $conexao->beginTransaction();

    if ($dadosFornecedor["tipo"] == "CNPJ") {
        $sql = "UPDATE `fornecedor` SET
            `tipo` = :tipo,
            `nome_fantasia` = :nome_fantasia,
            `razao_social` = :razao_social,
            `documento` = :documento,
            `responsavel` = :responsavel,
            `responsavel_documento` = :responsavel_documento,
            `responsavel_whatsapp` = :responsavel_whatsapp,
            `responsavel_email` = :responsavel_email,
            `telefone` = :telefone,
            `cep` = :cep,
            `logradouro` = :logradouro,
            `numero` = :numero,
            `bairro` = :bairro,
            `cidade` = :cidade,
            `estado` = :estado
            WHERE `id` = :id";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":id", $dadosFornecedor["id"], PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $dadosFornecedor["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":nome_fantasia", $dadosFornecedor["nome_fantasia"], PDO::PARAM_STR);
        $stmt->bindParam(":razao_social", $dadosFornecedor["razao_social"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $dadosFornecedor["documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel", $dadosFornecedor["responsavel"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_documento", $dadosFornecedor["responsavel_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_whatsapp", $dadosFornecedor["responsavel_whatsapp"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_email", $dadosFornecedor["responsavel_email"], PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $dadosFornecedor["telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":cep", $dadosFornecedor["cep"], PDO::PARAM_STR);
        $stmt->bindParam(":logradouro", $dadosFornecedor["logradouro"], PDO::PARAM_STR);
        $stmt->bindParam(":numero", $dadosFornecedor["numero"], PDO::PARAM_STR);
        $stmt->bindParam(":bairro", $dadosFornecedor["bairro"], PDO::PARAM_STR);
        $stmt->bindParam(":cidade", $dadosFornecedor["cidade"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $dadosFornecedor["estado"], PDO::PARAM_STR);
    } else {
        $sql = "UPDATE `fornecedor` SET
            `tipo` = :tipo,
            `nome_fantasia` = :nome_fantasia,
            `razao_social` = NULL,
            `documento` = NULL,
            `responsavel` = :responsavel,
            `responsavel_documento` = :responsavel_documento,
            `responsavel_whatsapp` = :responsavel_whatsapp,
            `responsavel_email` = :responsavel_email,
            `telefone` = :telefone,
            `cep` = :cep,
            `logradouro` = :logradouro,
            `numero` = :numero,
            `bairro` = :bairro,
            `cidade` = :cidade,
            `estado` = :estado
            WHERE `id` = :id";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":id", $dadosFornecedor["id"], PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $dadosFornecedor["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":nome_fantasia", $dadosFornecedor["nome_fantasia"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel", $dadosFornecedor["responsavel"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_documento", $dadosFornecedor["responsavel_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_whatsapp", $dadosFornecedor["responsavel_whatsapp"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_email", $dadosFornecedor["responsavel_email"], PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $dadosFornecedor["telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":cep", $dadosFornecedor["cep"], PDO::PARAM_STR);
        $stmt->bindParam(":logradouro", $dadosFornecedor["logradouro"], PDO::PARAM_STR);
        $stmt->bindParam(":numero", $dadosFornecedor["numero"], PDO::PARAM_STR);
        $stmt->bindParam(":bairro", $dadosFornecedor["bairro"], PDO::PARAM_STR);
        $stmt->bindParam(":cidade", $dadosFornecedor["cidade"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $dadosFornecedor["estado"], PDO::PARAM_STR);
    }
    $stmt->execute();

    $conexao->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Dados do fornecedor atualizados!"
    ]);
} catch (PDOException $e) {
    $conexao->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao atualizar dados do fornecedor: " . $e->getMessage()
    ]);
}
