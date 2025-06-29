<?php
class JWT
{
    // COLOCAR UMA CHAVE NO VARIAVEL DE AMBIENTE
    private const SECRET_KEY = "CHAVE_TCC_SUPPORT_TICKET_MANAGER"; // Chave secreta para assinatura do JWT
    private const ALGORITHM = "HS256";

    public static function gerar($dados = [], $minutos = 60)
    {
        try {
            $exp = time() + ($minutos * 60); // expira em X minutos
            $payloadData = [
                "usuario" => $dados,
                "exp" => $exp
            ];

            $header = rtrim(strtr(base64_encode(json_encode(["alg" => self::ALGORITHM, "typ" => "JWT"])), '+/', '-_'), '=');

            $payload = rtrim(strtr(base64_encode(json_encode($payloadData)), '+/', '-_'), '=');

            $assinatura = rtrim(strtr(base64_encode(hash_hmac("sha256", "$header.$payload", self::SECRET_KEY, true)), '+/', '-_'), '=');

            return "$header.$payload.$assinatura";
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public static function verificar($db = NULL)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!$db) {
                return NULL;
            }

            $id = $_SESSION["usuario_id"] ?? null;

            if (!$id) {
                return NULL;
            }

            $tokenFront = $_COOKIE["token"] ?? NULL;

            if (!$tokenFront) {
                return NULL;
            }

            $query = "SELECT `token` FROM `usuario` WHERE `id` = :id LIMIT 1";
            $stmt = $db->getConnection()->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $registro = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$registro) {
                return NULL;
            }

            if ($registro->token !== $tokenFront) {
                return NULL;
            }

            $partes = explode(".", $tokenFront);

            if (count($partes) !== 3) {
                return NULL;
            }

            [$header, $payload, $assinatura] = $partes;

            // Decodificação base64url segura
            $decodeBase64Url = function ($data) {
                $remainder = strlen($data) % 4;
                if ($remainder) {
                    $data .= str_repeat('=', 4 - $remainder);
                }
                return base64_decode(strtr($data, '-_', '+/'));
            };

            // Assinatura no formato base64url
            $assinaturaVerificada = rtrim(strtr(base64_encode(hash_hmac("sha256", "$header.$payload", self::SECRET_KEY, TRUE)), '+/', '-_'), '=');

            if (!hash_equals($assinaturaVerificada, $assinatura)) {
                return NULL;
            }

            $dados = json_decode($decodeBase64Url($payload), TRUE);

            if (!is_array($dados)) {
                return NULL;
            }

            if (!isset($dados["usuario"])) {
                return NULL;
            }

            if (!isset($dados["usuario"]["id"])) {
                return NULL;
            }

            // Checa expiração
            if (!isset($dados["exp"]) || time() > $dados["exp"]) {
                return NULL;
            }

            if (($dados["usuario"]["id"] ?? NULL) !== $id) {
                return NULL;
            }

            return $dados;
        } catch (\Throwable $th) {
            return NULL;
        }
    }
}
