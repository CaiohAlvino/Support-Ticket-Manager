<?php

/**
 *  Classe Validacao
 *  Fornece métodos para validação de diferentes tipos de dados
 */
class Validador
{

    // -------------------------- → CPF ← -------------------------- //
    /**
     *  Valida um CPF
     *  @param string|null $cpf - O CPF a ser validado
     *  @return bool - Verdadeiro se o CPF for válido
     */
    public static function cpfEValido($cpf = NULL)
    {
        if (empty($cpf)) {
            return FALSE;
        }

        $cpf = preg_replace("/[^0-9]/", "", $cpf);

        if (strlen($cpf) != 11) {
            return FALSE;
        }

        if (preg_match("/^(\d)\1+$/", $cpf)) {
            return FALSE;
        }

        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $cpf[$i] * (10 - $i);
        }

        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;

        if ($cpf[9] != $dv1) {
            return FALSE;
        }

        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $cpf[$i] * (11 - $i);
        }

        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;

        if ($cpf[10] != $dv2) {
            return FALSE;
        }

        return TRUE;
    }

    // -------------------------- → DATA NASCIMENTO ← -------------------------- //

    /**
     *  Valida uma data de nascimento
     *  @param string|null $data - A data a ser validada (formato YYYY-MM-DD)
     *  @return bool - Verdadeiro se a data for válida
     */
    public static function dataNascimentoEValida($data = NULL)
    {
        if (empty($data)) {
            return FALSE;
        }

        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $data)) {
            return FALSE;
        }

        $dataObj = DateTime::createFromFormat("Y-m-d", $data);
        if (!$dataObj || $dataObj->format("Y-m-d") !== $data) {
            return FALSE;
        }

        $hoje = new DateTime();
        if ($dataObj > $hoje) {
            return FALSE;
        }

        $idadeMaxima = clone $hoje;
        $idadeMaxima->modify("-120 years");
        if ($dataObj < $idadeMaxima) {
            return FALSE;
        }

        return TRUE;
    }

    // -------------------------- → NOME ← -------------------------- //

    /**
     *  Valida um nome
     *  @param string|null $nome - O nome a ser validado
     *  @return bool - Verdadeiro se o nome for válido
     */
    public static function nomeEValido($nome = NULL)
    {
        if (empty($nome)) {
            return FALSE;
        }

        $nome = trim($nome);

        if (mb_strlen($nome, 'UTF-8') > 255) {
            return FALSE;
        }

        if (mb_strlen($nome, 'UTF-8') < 3) {
            return FALSE;
        }

        return TRUE;
    }

    // -------------------------- → CEP ← -------------------------- //

    /**
     *  Valida um CEP
     *  @param string|null $cep - O CEP a ser validado
     *  @return bool - Verdadeiro se o CEP for válido
     */
    public static function cepEValido($cep = NULL)
    {
        if (empty($cep)) {
            return TRUE; // CEP não é obrigatório
        }

        $cep = preg_replace("/[^0-9]/", "", $cep);

        return strlen($cep) === 8;
    }

    // -------------------------- → WHATSAPP ← -------------------------- //

    /**
     *  Valida um número de WhatsApp
     *  @param string|null $whatsapp - O número a ser validado
     *  @return bool - Verdadeiro se o número for válido
     */
    // public static function whatsappEValido($whatsapp = NULL)
    // {
    //     // Quando for necessário realizar a validação mais pesada para os números
    //     if (empty($whatsapp)) {
    //         return TRUE; // WhatsApp não é obrigatório
    //     }

    //     $whatsapp = preg_replace("/[^0-9]/", "", $whatsapp);

    //     // Para consistência com JS, que exige exatamente 11 dígitos para BR
    //     //return strlen($whatsapp) === 11;
    // }

    // -------------------------- → EMAIL ← -------------------------- //

    /**
     *  Valida um email
     *  @param string|null $email - O email a ser validado
     *  @return bool - Verdadeiro se o email for válido
     */
    public static function emailEValido($email = NULL)
    {
        if (empty($email)) {
            return FALSE;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE;
    }

    // -------------------------- → NÚMERO ENDEREÇO ← -------------------------- //

    /**
     *  Valida um número de endereço
     *  @param string|null $numero - O número a ser validado
     *  @return bool - Verdadeiro se o número for válido
     */
    public static function numeroEnderecoEValido($numero = NULL)
    {
        if (empty($numero)) {
            return TRUE;
        }

        return preg_match('/^\d+.*$/', $numero) === 1;
    }

    // -------------------------- → EXISTE CLIENTE COM CPF ← -------------------------- //

    /**
     *  Verifica se existe um cliente com o mesmo CPF
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @param int $empresa_id - ID da empresa
     *  @param string $cpf - CPF a ser verificado
     *  @param int|null $cliente_id - ID do cliente (para exclusão na verificação de edição)
     *  @return bool - Verdadeiro se já existe o CPF
     */
    // public static function existeClienteComCPF($conexao, $empresa_id, $cpf, $cliente_id = NULL)
    // {
    // }

    // -------------------------- → EXISTE USUÁRIO COM CPF ← -------------------------- //

    /**
     *  Verifica se existe um usuário com o mesmo CPF
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @param int $empresa_id - ID da empresa
     *  @param string $cpf - CPF a ser verificado
     *  @param int|null $usuario_id - ID do usuario (para exclusão na verificação de edição)
     *  @return bool - Verdadeiro se já existe o CPF
     */
    // public static function existeUsuarioComCPF($conexao, $empresa_id, $cpf, $usuario_id = NULL)
    // {
    // }

    // -------------------------- → EXISTE USUÁRIO COM EMAIL ← -------------------------- //

    /**
     *  Verifica se existe um usuário com o mesmo email
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @param int $empresa_id - ID da empresa
     *  @param string $email - Email a ser verificado
     *  @param int|null $usuario_id - ID do usuario (para exclusão na verificação de edição)
     *  @return bool - Verdadeiro se já existe o email
     */
    // public static function existeUsuarioComEMAIL($conexao, $empresa_id, $email, $usuario_id = NULL)
    // {
    // }

    // -------------------------- → EXISTE CLIENTE COM WHATSAPP ← -------------------------- //

    /**
     *  Verifica se existe um cliente com o mesmo WhatsApp
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @param int $empresa_id - ID da empresa
     *  @param string $whatsapp - WhatsApp a ser verificado
     *  @param int|null $cliente_id - ID do cliente (para exclusão na verificação de edição)
     *  @return bool - Verdadeiro se já existe o WhatsApp
     */
    // public static function existeClienteComWhatsApp($conexao, $empresa_id, $whatsapp, $cliente_id = NULL)
    // {
    // }

    // -------------------------- → CNPJ ← -------------------------- //

    /**
     *  Valida um CNPJ
     *  @param string|null $cnpj - O CNPJ a ser validado
     *  @return bool - Verdadeiro se o CNPJ for válido
     */
    public static function cnpjEValido($cnpj = NULL)
    {
        if (empty($cnpj)) {
            return FALSE;
        }

        $cnpj = preg_replace("/[^0-9]/", "", $cnpj);

        if (strlen($cnpj) != 14) {
            return FALSE;
        }

        if (preg_match("/^(\d)\1+$/", $cnpj)) {
            return FALSE;
        }

        // Valida primeiro dígito verificador
        $soma = 0;
        for ($i = 0, $j = 5; $i < 12; $i++) {
            $soma += (int)$cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;

        if ($cnpj[12] != $dv1) {
            return FALSE;
        }

        // Valida segundo dígito verificador
        $soma = 0;
        for ($i = 0, $j = 6; $i < 13; $i++) {
            $soma += (int)$cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;

        return ($cnpj[13] == $dv2);
    }

    // -------------------------- → EXISTE EMPRESA ← -------------------------- //

    /**
     *  Verifica se existe uma empresa com os mesmos dados
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @param int|null $id - ID da empresa (para exclusão na verificação de edição)
     *  @param string $razao_social - Razão social a ser verificada
     *  @param string $nome_fantasia - Nome fantasia a ser verificado
     *  @param string $documento - Documento a ser verificado
     *  @return bool - Verdadeiro se já existe uma empresa com esses dados
     */
    public static function existeEmpresa($conexao, $id, $razao_social, $nome_fantasia, $documento)
    {
        // Verifica se já existe uma empresa com a razão social
        if (!empty($razao_social)) {
            $sql = "SELECT * FROM `empresa` WHERE `razao_social` = :razao_social";
            if ($id) {
                $sql .= " AND `id` != :id";
            }
            $sql .= " LIMIT 1";

            $query = $conexao->prepare($sql);
            $query->bindParam(":razao_social", $razao_social, PDO::PARAM_STR);
            if ($id) {
                $query->bindParam(":id", $id, PDO::PARAM_INT);
            }
            $query->execute();

            if ($query->rowCount() > 0) {
                return [
                    "existe" => TRUE,
                    "erro" => "RAZAO_SOCIAL"
                ];
            }
        }

        // Verifica se já existe uma empresa com o nome fantasia
        if (!empty($nome_fantasia)) {
            $sql = "SELECT * FROM `empresa` WHERE `nome_fantasia` = :nome_fantasia";
            if ($id) {
                $sql .= " AND `id` != :id";
            }
            $sql .= " LIMIT 1";

            $query = $conexao->prepare($sql);
            $query->bindParam(":nome_fantasia", $nome_fantasia, PDO::PARAM_STR);
            if ($id) {
                $query->bindParam(":id", $id, PDO::PARAM_INT);
            }
            $query->execute();

            if ($query->rowCount() > 0) {
                return [
                    "existe" => TRUE,
                    "erro" => "NOME_FANTASIA"
                ];
            }
        }

        // Verifica se já existe uma empresa com o documento
        if (!empty($documento)) {
            $sql = "SELECT * FROM `empresa` WHERE `documento` = :documento";
            if ($id) {
                $sql .= " AND `id` != :id";
            }
            $sql .= " LIMIT 1";

            $query = $conexao->prepare($sql);
            $query->bindParam(":documento", $documento, PDO::PARAM_STR);
            if ($id) {
                $query->bindParam(":id", $id, PDO::PARAM_INT);
            }
            $query->execute();

            if ($query->rowCount() > 0) {
                return [
                    "existe" => TRUE,
                    "erro" => "DOCUMENTO"
                ];
            }
        }

        return [
            "existe" => FALSE,
            "erro" => NULL
        ];
    }

    // -------------------------- → VALIDAR USUÁRIO ← -------------------------- //

    /**
     *  Valida os dados de um usuário
     *  @param array $dados - Dados do usuário
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @param int $empresa_id - ID da empresa
     *  @param int|null $usuario_id - ID do usuário (no caso de edição)
     *  @return array - Array com status da validação e mensagem de erro, se houver
     */
    public static function validarUsuario($dados, $conexao, $empresa_id, $usuario_id = NULL)
    {
        if (isset($dados["nome"]) && isset($dados["cpf"]) && isset($dados["email"])) {
            if (empty($dados["nome"]) || empty($dados["cpf"]) || empty($dados["email"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Informe todos os campos obrigatórios*!",
                    "codigo_erro" => "CAMPOS_VAZIOS"
                ];
            }
        }

        if (isset($dados["nome"])) {
            if (!self::nomeEValido($dados["nome"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Nome inválido. O nome deve ter entre 3 e 255 caracteres.",
                    "codigo_erro" => "NOME_INVALIDO"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => "O campo Nome é obrigatório",
                "codigo_erro" => "NOME_VAZIO"
            ];
        }

        if (isset($dados["cpf"]) && !empty($dados["cpf"])) {
            if (!self::cpfEValido($dados["cpf"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "CPF inválido! Por favor, verifique o número informado.",
                    "codigo_erro" => "CPF_INVALIDO"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => "O campo CPF é obrigatório",
                "codigo_erro" => "CPF_VAZIO"
            ];
        }

        if (isset($dados["email"]) && !empty($dados["email"])) {
            if (!self::emailEValido($dados["email"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "E-mail inválido! Por favor, informe um e-mail válido.",
                    "codigo_erro" => "EMAIL_INVALIDO"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => "O campo E-mail é obrigatório",
                "codigo_erro" => "EMAIL_VAZIO"
            ];
        }

        return [
            "valido" => TRUE,
            "mensagem" => "",
            "codigo_erro" => NULL
        ];
    }

    // -------------------------- → VALIDAR EMPRESA ← -------------------------- //

    /**
     *  Valida os dados de uma empresa
     *  @param array $dados - Dados da empresa
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @return array - Array com status da validação e mensagem de erro, se houver
     */
    public static function validarEmpresa($dados, $conexao)
    {
        $tipo = isset($dados["tipo"]) ? $dados["tipo"] : "CNPJ";
        $id = isset($dados["id"]) ? $dados["id"] : NULL;

        if (isset($dados["nome_fantasia"]) && isset($dados["responsavel"]) && isset($dados["responsavel_documento"])) {
            if (empty($dados["nome_fantasia"]) || empty($dados["responsavel"]) || empty($dados["responsavel_documento"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Informe todos os campos obrigatórios",
                    "codigo_erro" => "CAMPOS_VAZIOS"
                ];
            }
        }

        if ($tipo == "CNPJ") {
            if (isset($dados["razao_social"]) && !empty($dados["razao_social"])) {
                if (!self::nomeEValido($dados["razao_social"])) {
                    return [
                        "valido" => FALSE,
                        "mensagem" => "Razão Social inválida. O nome deve conter de 3 à 255 caracteres",
                        "codigo_erro" => "RAZAO_SOCIAL_INVALIDA"
                    ];
                }
            } else {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Razão Social é obrigatoria.",
                    "codigo_erro" => "RAZAO_SOCIAL_VAZIA"
                ];
            }

            if (isset($dados["documento"]) && !empty($dados["documento"])) {
                if (!self::cnpjEValido($dados["documento"])) {
                    return [
                        "valido" => FALSE,
                        "mensagem" => "CNPJ inválido. Por favor, verifique.",
                        "codigo_erro" => "CNPJ_INVALIDO"
                    ];
                }
            } else {
                return [
                    "valido" => FALSE,
                    "mensagem" => "CNPJ é obrigatório.",
                    "codigo_erro" => "CNPJ_VAZIO"
                ];
            }
        }

        if (isset($dados["nome_fantasia"]) && !empty($dados["nome_fantasia"])) {
            if (!self::nomeEValido($dados["nome_fantasia"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => $tipo == "CNPJ" ? "Nome Fantasia inválido. O nome deve conter de 3 à 255 caracteres." : "Nome da empresa inválido. O nome deve conter de 3 à 255 caracteres.",
                    "codigo_erro" => "NOME_FANTASIA_INVALIDO"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => $tipo == "CNPJ" ? "Nome Fantasia é obrigatorio." : "Nome da empresa é obrigatório.",
                "codigo_erro" => "NOME_FANTASIA_VAZIO"
            ];
        }

        if (isset($dados["responsavel"]) && !empty($dados["responsavel"])) {
            if (!self::nomeEValido($dados["responsavel"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => $tipo == "CNPJ" ? "Nome do responsável inválido. O nome deve conter de 3 à 255 caracteres;" : "Nome inválido. O nome deve conter de 3 à 255 caracteres.",
                    "codigo_erro" => "RESPONSAVEL_INVALIDO"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => $tipo == "CNPJ" ? "Nome do responsável é obrigatório." : "Nome é obrigatório.",
                "codigo_erro" => "RESPONSAVEL_VAZIO"
            ];
        }

        if (isset($dados["responsavel_documento"]) && !empty($dados["responsavel_documento"])) {
            if (!self::cpfEValido($dados["responsavel_documento"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => $tipo == "CNPJ" ? "CPF do responsável inválido. Por favor, verifique." : "CPF inválido. Por favor, verifique.",
                    "codigo_erro" => "RESPONSAVEL_DOCUMENTO_INVALIDO"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => $tipo == "CNPJ" ? "CPF do responsável é o obrigatório." : "CPF do responsável é obrigatório.",
                "codigo_erro" => "RESPONSAVEL_DOCUMENTO_VAZIO"
            ];
        }

        if (isset($dados["cep"]) && !empty($dados["cep"])) {
            if (!self::cepEValido($dados["cep"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "CEP inválido. Por favor, verifique.",
                    "codigo_erro" => "CEP_INVALIDO"
                ];
            }
        }

        if (isset($dados["numero"]) && !empty($dados["numero"])) {
            if (!self::numeroEnderecoEValido($dados["numero"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Número de endereço inválido. Deve começar com dígitos.",
                    "codigo_erro" => "NUMERO_INVALIDO"
                ];
            }
        }

        $verificacao = self::existeEmpresa($conexao, $id, $dados["razao_social"], $dados["nome_fantasia"], $dados["documento"]);
        if (isset($verificacao["erro"]) && !empty($verificacao["erro"])) {
            $mensagem = "Já existe uma empresa com ";
            $codigo = "";

            switch ($verificacao["erro"]) {
                case "RAZAO_SOCIAL":
                    $mensagem .= "esta Razão Social.";
                    $codigo = "RAZAO_SOCIAL_DUPLICADA";
                    break;
                case "NOME_FANTASIA":
                    $mensagem .= "este Nome Fantasia.";
                    $codigo = "NOME_FANTASIA_DUPLICADO";
                    break;
                case "DOCUMENTO":
                    $mensagem .= "este Documento.";
                    $codigo = "DOCUMENTO_DUPLICADA";
                    break;
                default:
                    $mensagem .= "esses dados.";
                    $codigo = "DADOS_DUPLICADOS";
            }

            return [
                "valido" => FALSE,
                "mensagem" => $mensagem,
                "codigo_erro" => $codigo
            ];
        }

        return [
            "valido" => TRUE,
            "mensagem" => "",
            "codigo_erro" => NULL
        ];
    }

    // -------------------------- → VALIDAR CLIENTE ← -------------------------- //

    /**
     *  Valida os dados de um cliente
     *  @param array $dados - Dados do cliente
     *  @param PDO $conexao - Conexão com o banco de dados
     *  @param int $empresa_id - ID da empresa
     *  @param int|null $cliente_id - ID do cliente (no caso de edição)
     *  @return array - Array com status da validação e mensagem de erro, se houver
     */
    public static function validarCliente($dados, $conexao, $empresa_id, $cliente_id = NULL)
    {
        if (isset($dados["nome"]) && isset($dados["data_nascimento"])) {
            if (empty($dados["nome"]) || empty($dados["data_nascimento"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Informe todos os campos obrigatórios!",
                    "codigo_erro" => "CAMPOS_VAZIOS"
                ];
            }
        }

        if (isset($dados["nome"]) && !empty($dados["nome"])) {
            if (!self::nomeEValido($dados["nome"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Nome inválido. O nome deve ter entre 3 e 255 caracteres.",
                    "codigo_erro" => "NOME_INVALIDO"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => "O campo Nome é obrigatório",
                "codigo_erro" => "NOME_VAZIO"
            ];
        }

        if (isset($dados["data_nascimento"]) && !empty($dados["data_nascimento"])) {
            if (!self::dataNascimentoEValida($dados["data_nascimento"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Data de nascimento inválida.",
                    "codigo_erro" => "DATA_NASCIMENTO_INVALIDA"
                ];
            }
        } else {
            return [
                "valido" => FALSE,
                "mensagem" => "O campo Data de nascimento é obrigatório",
                "codigo_erro" => "DATA_NASCIMENTO_VAZIA"
            ];
        }

        if (isset($dados["cpf"]) && !empty($dados["cpf"])) {
            if (!self::cpfEValido($dados["cpf"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "CPF inválido! Por favor, verifique o número informado.",
                    "codigo_erro" => "CPF_INVALIDO"
                ];
            }
        }

        if (isset($dados["cep"]) && !empty($dados["cep"])) {
            if (!self::cepEValido($dados["cep"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "CEP inválido. Por favor, verifique.",
                    "codigo_erro" => "CEP_INVALIDO"
                ];
            }
        }

        // VERIFICAR A EFICIENCIA DESSA VALIDAÇÃO

        // if (isset($dados["whatsapp"]) && !empty($dados["whatsapp"])) {
        //     if (!self::whatsappEValido($dados["whatsapp"])) {
        //         return [
        //             "valido" => FALSE,
        //             "mensagem" => "Número de WhatsApp inválido.",
        //             "codigo_erro" => "WHATS_INVALIDO"
        //         ];
        //     }

        //     if (self::existeClienteComWhatsApp($conexao, $empresa_id, $dados["whatsapp"], $cliente_id)) {
        //         return [
        //             "valido" => FALSE,
        //             "mensagem" => "Já existe um cliente com este número de celular cadastrado!",
        //             "codigo_erro" => "WHATS_DUPLICADO"
        //         ];
        //     }
        // }

        if (isset($dados["numero"]) && !empty($dados["numero"])) {
            if (!self::numeroEnderecoEValido($dados["numero"])) {
                return [
                    "valido" => FALSE,
                    "mensagem" => "Número de endereço inválido. Deve começar com dígitos.",
                    "codigo_erro" => "NUMERO_INVALIDO"
                ];
            }
        }

        return [
            "valido" => TRUE,
            "mensagem" => "",
            "codigo_erro" => NULL
        ];
    }
}
