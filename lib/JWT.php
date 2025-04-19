<?php

class JWT
{
    private static $chave = 'sua_chave_secreta_aqui'; // Altere para uma chave segura em produção
    private static $algoritmo = 'HS256';

    /**
     * Cria um token JWT
     * @param array $payload Dados a serem codificados no token
     * @return string Token JWT
     */
    public static function criar($payload)
    {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => self::$algoritmo
        ]);

        $header = self::base64UrlEncode($header);
        $payload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$header.$payload", self::$chave, true);
        $signature = self::base64UrlEncode($signature);

        return "$header.$payload.$signature";
    }

    /**
     * Verifica se um token JWT é válido
     * @param PDO $db Conexão com o banco de dados
     * @return bool
     */
    public static function verificar($db)
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        try {
            $token = $_SESSION['token'];
            $partes = explode('.', $token);

            if (count($partes) != 3) {
                return false;
            }

            list($header, $payload, $signature) = $partes;

            $novaAssinatura = self::base64UrlEncode(
                hash_hmac('sha256', "$header.$payload", self::$chave, true)
            );

            if ($signature !== $novaAssinatura) {
                return false;
            }

            $payload = json_decode(self::base64UrlDecode($payload), true);

            // Verificar se o usuário ainda existe no banco de dados
            $stmt = $db->prepare("SELECT id FROM usuario WHERE id = :id AND email = :email");
            $stmt->execute([
                ':id' => $payload['id'],
                ':email' => $payload['email']
            ]);

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Decodifica um token JWT
     * @param string $token Token JWT
     * @return array|false Payload decodificado ou false em caso de erro
     */
    public static function decodificar($token)
    {
        try {
            $partes = explode('.', $token);

            if (count($partes) != 3) {
                return false;
            }

            list($header, $payload, $signature) = $partes;

            $novaAssinatura = self::base64UrlEncode(
                hash_hmac('sha256', "$header.$payload", self::$chave, true)
            );

            if ($signature !== $novaAssinatura) {
                return false;
            }

            return json_decode(self::base64UrlDecode($payload), true);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Codifica uma string em base64url
     * @param string $data String a ser codificada
     * @return string String codificada
     */
    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decodifica uma string em base64url
     * @param string $data String a ser decodificada
     * @return string String decodificada
     */
    private static function base64UrlDecode($data)
    {
        $pad = strlen($data) % 4;
        if ($pad) {
            $data .= str_repeat('=', 4 - $pad);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
