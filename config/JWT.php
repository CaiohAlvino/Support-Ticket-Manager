<?php

class JWT
{
    private static $key = "sua_chave_secreta_aqui";
    private static $algorithm = 'HS256';

    public static function criar($data)
    {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => self::$algorithm
        ]);

        // Adicionar timestamp de expiração (24 horas)
        $data['exp'] = time() + (24 * 60 * 60);

        $payload = json_encode($data);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function verificar($db)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['token'])) {
            return false;
        }

        try {
            $token = $_SESSION['token'];
            $parts = explode('.', $token);

            if (count($parts) != 3) {
                return false;
            }

            list($header, $payload, $signature) = $parts;

            // Verificar assinatura
            $expectedSignature = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
            $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

            if ($signature !== $expectedSignature) {
                return false;
            }

            // Decodificar payload
            $payloadData = json_decode(
                base64_decode(str_replace(['-', '_'], ['+', '/'], $payload))
            );

            if (!$payloadData) {
                return false;
            }

            // Verificar expiração
            if (isset($payloadData->exp) && $payloadData->exp < time()) {
                return false;
            }

            // Verificar se o usuário existe e está ativo
            $stmt = $db->prepare("SELECT id FROM usuario WHERE id = :id AND email = :email AND situacao = 1");
            $stmt->execute([
                ':id' => $payloadData->id,
                ':email' => $payloadData->email
            ]);

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Erro na verificação do token: " . $e->getMessage());
            return false;
        }
    }

    public static function decode($token)
    {
        try {
            $parts = explode('.', $token);

            if (count($parts) != 3) {
                return null;
            }

            $payload = base64_decode(
                str_replace(['-', '_'], ['+', '/'], $parts[1])
            );

            return json_decode($payload);
        } catch (Exception $e) {
            error_log("Erro ao decodificar token: " . $e->getMessage());
            return null;
        }
    }
}
