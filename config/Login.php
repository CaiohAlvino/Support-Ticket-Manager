<?php
require_once("JWT.php");
class Login
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Realiza o login do usuário, gera token e salva sessão.
     * @param string $email
     * @param string $senha
     * @return array
     */
    public function autenticar($email, $senha)
    {
        try {
            // Busca usuário pelo e-mail + nome do grupo
            $query = "SELECT usuario.*, grupo.nome AS grupo_nome FROM usuario LEFT JOIN grupo ON grupo.id = usuario.grupo_id WHERE usuario.email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return [
                    "status" => "error",
                    "message" => "Usuário ou senha inválidos."
                ];
            }

            // Verifica senha (ajuste conforme seu hash, ex: password_verify)
            if (!password_verify($senha, $usuario['senha'])) {
                return [
                    "status" => "error",
                    "message" => "Usuário ou senha inválidos."
                ];
            }

            $jwt = JWT::gerar([
                "id" => $usuario['id'],
                "nome" => $usuario['nome'],
                "email" => $usuario['email'],
                "empresa_id" => $usuario['empresa_id'] ?? null,
                "grupo_id" => $usuario['grupo_id'] ?? null
            ], 60);

            // Salva o JWT no banco (opcional, mas recomendado para logout/controlar sessões)
            $stmt = $this->db->prepare("UPDATE usuario SET token = :token WHERE id = :id");
            $stmt->bindValue(":token", $jwt, PDO::PARAM_STR);
            $stmt->bindValue(":id", $usuario['id'], PDO::PARAM_INT);
            $stmt->execute();

            // Inicia sessão e salva dados do usuário
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_grupo'] = $usuario['grupo_id'];
            $_SESSION['usuario_grupo_nome'] = $usuario['grupo_nome'];
            $_SESSION['usuario_token'] = $jwt;
            $_SESSION['empresa_id'] = $usuario['empresa_id'] ?? null;

            // Retorna dados para AJAX/front-end
            return [
                "status" => "success",
                "message" => "Login realizado com sucesso!",
                "usuario" => [
                    "id" => $usuario['id'],
                    "nome" => $usuario['nome'],
                    "email" => $usuario['email'],
                    "empresa_id" => $usuario['empresa_id'] ?? null,
                    "token" => $jwt
                ]
            ];
        } catch (\Throwable $th) {
            return [
                "status" => "error",
                "message" => "Erro ao autenticar: " . $th->getMessage()
            ];
        }
    }
}
