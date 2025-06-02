<?php

class Empresa
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function pegarPorId($id = null)
    {
        try {
            if (!$id) {
                return null;
            }
            $query = "SELECT `empresa`.* FROM `empresa` WHERE `empresa`.`id` = :id LIMIT 1";
            // $query = "SELECT `suporte`.*,
            // `cliente`.`nome` AS cliente_nome,
            // `cliente`.`email` AS cliente_email,
            // `usuario`.`nome` AS usuario_nome,
            // `empresa`.`razao_social` AS empresa_nome
            // FROM `suporte`
            // LEFT JOIN `cliente` on `cliente`.id = `suporte`.`cliente_id`
            // LEFT JOIN `usuario` on `usuario`.id = `suporte`.`usuario_id`
            // LEFT JOIN `empresa` on `empresa`.id = `suporte`.`empresa_id`
            // WHERE `suporte`.`id` = :id LIMIT 1";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $stmt->execute();
            $registro = $stmt->fetch(PDO::FETCH_OBJ);
            return $registro;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function listarEmpresas($filtros = array())
    {
        try {
            $ativo = isset($filtros["ativo"]) ? (int)$filtros["ativo"] : null;
            $query = "SELECT * FROM `empresa`";
            if ($ativo !== null) {
                $query .= " WHERE `ativo` = :ativo";
            }
            $query .= " ORDER BY `nome` ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            return $stmt->fetchAll();
        } catch (\Throwable $th) {
            return [];
        }
    }
}
