<?php
require("../../config/Database.php");
require("../../config/Validador.php");
require("../../config/Cliente.php");
// require("../../config/JWT.php");

$db = new Database();

// $tokenEValido = JWT::verificar($db);

// if (!$tokenEValido) {
//     echo json_encode(["status" => "error", "message" => "Token inválido."]);
//     exit;
// }

$conexao = $db->getConnection();

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }


$clientedadosCliente = [
    "id" => isset($_POST["id"]) ? $_POST["id"] : NULL,
    "tipo" => isset($_POST["tipo"]) ? trim($_POST["tipo"]) : NULL,
    "nome_fantasia" => isset($_POST["nome_fantasia"]) ? trim($_POST["nome_fantasia"]) : NULL,
    "razao_social" => isset($_POST["tipo"]) && $_POST["tipo"] == "CNPJ" ? trim($_POST["razao_social"]) : NULL,
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

if (empty($clientedadosCliente["id"])) {
    echo json_encode([
        "status" => "error",
        "message" => "cliente não Encontrado."
    ]);
    exit;
}

// $validacao = Validador::validarCliente($clientedadosCliente, $conexao, $empresa_id);

// if (!$validacao["valido"]) {
//     echo json_encode([
//         "status" => "error",
//         "message" => $validacao["mensagem"]
//     ]);
//     exit;
// }

try {
    $conexao->beginTransaction();

    if ($clientedadosCliente["tipo"] == "CNPJ") {
        $sql = "UPDATE `cliente` SET
            `tipo` = :tipo,
            `nome_fantasia` = :nome_fantasia,
            `razao_social` = :razao_social,
            `documento` = :documento,
            `responsavel_nome` = :responsavel_nome,
            `responsavel_documento` = :responsavel_documento,
            `responsavel_telefone` = :responsavel_telefone,
            `responsavel_email` = :responsavel_email,
            `telefone` = :telefone,
            `cep` = :cep,
            `endereco` = :endereco,
            `numero` = :numero,
            `bairro` = :bairro,
            `cidade` = :cidade,
            `estado` = :estado
            WHERE `id` = :id";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":id", $clientedadosCliente["id"], PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $clientedadosCliente["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":nome_fantasia", $clientedadosCliente["nome_fantasia"], PDO::PARAM_STR);
        $stmt->bindParam(":razao_social", $clientedadosCliente["razao_social"], PDO::PARAM_STR);
        $stmt->bindParam(":documento", $clientedadosCliente["documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_nome", $clientedadosCliente["responsavel_nome"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_documento", $clientedadosCliente["responsavel_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_telefone", $clientedadosCliente["responsavel_telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_email", $clientedadosCliente["responsavel_email"], PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $clientedadosCliente["telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":cep", $clientedadosCliente["cep"], PDO::PARAM_STR);
        $stmt->bindParam(":endereco", $clientedadosCliente["endereco"], PDO::PARAM_STR);
        $stmt->bindParam(":numero", $clientedadosCliente["numero"], PDO::PARAM_STR);
        $stmt->bindParam(":bairro", $clientedadosCliente["bairro"], PDO::PARAM_STR);
        $stmt->bindParam(":cidade", $clientedadosCliente["cidade"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $clientedadosCliente["estado"], PDO::PARAM_STR);
    } else {
        $sql = "UPDATE `cliente` SET
            `tipo` = :tipo,
            `nome_fantasia` = :nome_fantasia,
            `razao_social` = NULL,
            `documento` = NULL,
            `responsavel_nome` = :responsavel_nome,
            `responsavel_documento` = :responsavel_documento,
            `responsavel_telefone` = :responsavel_telefone,
            `responsavel_email` = :responsavel_email,
            `telefone` = :telefone,
            `cep` = :cep,
            `endereco` = :endereco,
            `numero` = :numero,
            `bairro` = :bairro,
            `cidade` = :cidade,
            `estado` = :estado
            WHERE `id` = :id";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":id", $clientedadosCliente["id"], PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $clientedadosCliente["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":nome_fantasia", $clientedadosCliente["nome_fantasia"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_nome", $clientedadosCliente["responsavel_nome"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_documento", $clientedadosCliente["responsavel_documento"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_telefone", $clientedadosCliente["responsavel_telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":responsavel_email", $clientedadosCliente["responsavel_email"], PDO::PARAM_STR);
        $stmt->bindParam(":telefone", $clientedadosCliente["telefone"], PDO::PARAM_STR);
        $stmt->bindParam(":cep", $clientedadosCliente["cep"], PDO::PARAM_STR);
        $stmt->bindParam(":endereco", $clientedadosCliente["endereco"], PDO::PARAM_STR);
        $stmt->bindParam(":numero", $clientedadosCliente["numero"], PDO::PARAM_STR);
        $stmt->bindParam(":bairro", $clientedadosCliente["bairro"], PDO::PARAM_STR);
        $stmt->bindParam(":cidade", $clientedadosCliente["cidade"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $clientedadosCliente["estado"], PDO::PARAM_STR);
    }
    $stmt->execute();

    $conexao->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Dados do cliente atualizados!"
    ]);
} catch (PDOException $e) {
    $conexao->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao atualizar dados do cliente: " . $e->getMessage()
    ]);
}
