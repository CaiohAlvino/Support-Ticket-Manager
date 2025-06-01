<?php

class SuporteMensagem
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function pegarPorSuporteId($suporte_id = NULL)
    {
        try {
            if (!$suporte_id) {
                return [];
            }

            $query = "SELECT
                        suporte_mensagem.*,
                        usuario.nome AS usuario_nome,
                        admin.nome AS admin_nome
                    FROM suporte_mensagem
                    LEFT JOIN usuario ON usuario.id = suporte_mensagem.usuario_id
                    LEFT JOIN admin ON admin.id = suporte_mensagem.admin_id
                    WHERE suporte_mensagem.suporte_id = :suporte_id
                    ORDER BY suporte_mensagem.cadastrado ASC";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
            $stmt->execute();

            $registro = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $registro;
        } catch (\Throwable $th) {
            return [];
        }
    }
}
