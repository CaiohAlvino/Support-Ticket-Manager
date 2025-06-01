<?php
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
            // Busca usuário pelo e-mail
            $query = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
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

            // Gera token simples (pode ser JWT futuramente)
            $token = bin2hex(random_bytes(32));

            // Salva token no banco (opcional, para controle de sessão)
            $stmt = $this->db->prepare("UPDATE usuario SET token = :token WHERE id = :id");
            $stmt->bindValue(":token", $token, PDO::PARAM_STR);
            $stmt->bindValue(":id", $usuario['id'], PDO::PARAM_INT);
            $stmt->execute();

            // Inicia sessão e salva dados do usuário
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_token'] = $token;
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
                    "token" => $token
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
