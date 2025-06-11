<?php

class Empresa
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index($parametros = array())
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $nome = isset($parametros["nome"]) ? $parametros["nome"] : NULL;
            $pagina = isset($parametros["pagina"]) ? $parametros["pagina"] : 1;
            $limite = isset($parametros["limite"]) ? $parametros["limite"] : 10;

            $where = array();

            $query = "SELECT `empresa`.*";
            $queryCount = "SELECT COUNT(`empresa`.`id`)";

            $query .= " FROM `empresa`";
            $queryCount .= " FROM `empresa`";

            if ($nome) {
                $where[] = "`empresa`.`nome` = :nome";
            }

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `empresa`.`id` ASC";

            $query .= " LIMIT :limite OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmtCount = $this->db->prepare($queryCount);

            if ($nome) {
                $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
                $stmtCount->bindParam(":nome", $nome, PDO::PARAM_STR);
            }

            $offset = ($pagina - 1) * $limite;

            $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();
            $stmtCount->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
            $total = $stmtCount->fetchColumn();

            $paginação = array(
                "pagina" => $pagina,
                "limite" => $limite,
                "total" => $total,
                "total_paginas" => ceil($total / $limite)
            );

            return array(
                "resultados" => $resultados,
                "paginacao" => $paginação
            );
        } catch (\Throwable $th) {
            return array(
                "erro" => $th->getMessage() . " -> " . $th->getLine(),
                "resultados" => [],
                "paginacao" => array(
                    "pagina" => 1,
                    "limite" => 100,
                    "total" => 0,
                    "total_paginas" => 1
                )
            );
        }
    }

    public function pegarPorId($id = null)
    {
        try {
            if (!$id) {
                return null;
            }
            $query = "SELECT `empresa`.* FROM `empresa` WHERE `empresa`.`id` = :id LIMIT 1";
            // $query = "SELECT `suporte`.*,
            // `empresa`.`nome` AS empresa_nome,
            // `empresa`.`email` AS empresa_email,
            // `usuario`.`nome` AS usuario_nome,
            // `empresa`.`razao_social` AS empresa_nome
            // FROM `suporte`
            // LEFT JOIN `empresa` on `empresa`.id = `suporte`.`empresa_id`
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

    public function listarEmpresas()
    {
        try {
            $query = "SELECT * FROM
                        `empresa`
                    WHERE
                        `situacao` = 1
                    ORDER BY
                        `id`
                    ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            return $stmt->fetchAll();
        } catch (\Throwable $th) {
            return [];
        }
    }
}
