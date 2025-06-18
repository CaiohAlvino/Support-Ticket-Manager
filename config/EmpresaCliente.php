<?php

class EmpresaCliente
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index($parametros = array())
    {
        try {
            $empresa_id = isset($parametros["empresa_id"]) ? (int)$parametros["empresa_id"] : null;
            $cliente_id = isset($parametros["cliente_id"]) ? (int)$parametros["cliente_id"] : null;
            $ativo      = isset($parametros["ativo"]) ? $parametros["ativo"] : null;
            $pagina     = isset($parametros["pagina"]) ? (int)$parametros["pagina"] : 1;
            $limite     = isset($parametros["limite"]) ? (int)$parametros["limite"] : 10;

            $where = array();
            $params = array();

            if ($empresa_id) {
                $where[] = "empresa_cliente.empresa_id = :empresa_id";
                $params[":empresa_id"] = $empresa_id;
            }
            if ($cliente_id) {
                $where[] = "empresa_cliente.cliente_id = :cliente_id";
                $params[":cliente_id"] = $cliente_id;
            }
            if ($ativo !== null) {
                $where[] = "empresa_cliente.ativo = :ativo";
                $params[":ativo"] = $ativo;
            }

            $query = "SELECT
                        empresa_cliente.*,
                        `empresa`.`nome` AS empresa_nome,
                        `cliente`.`nome` AS cliente_nome
                    FROM
                        `empresa_cliente`
                    LEFT JOIN
                        `empresa` ON `empresa`.`id` = `empresa_cliente`.`empresa_id`
                    LEFT JOIN
                        `cliente` ON `cliente`.`id` = `empresa_cliente`.`cliente_id`";

            $queryCount = "SELECT
                                COUNT(`empresa_cliente`.`id`) as total
                            FROM
                                `empresa_cliente`
                            LEFT JOIN
                                `empresa` ON `empresa`.`id` = `empresa_cliente`.`empresa_id`
                            LEFT JOIN
                                `cliente` ON `cliente`.`id` = `empresa_cliente`.`cliente_id`";

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `empresa_cliente`.`empresa_id` ASC";

            $offset = ($pagina - 1) * $limite;
            $query .= " LIMIT $limite OFFSET $offset";

            $stmt = $this->db->prepare($query);
            $stmtCount = $this->db->prepare($queryCount);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
                $stmtCount->bindValue($key, $value);
            }

            $stmt->execute();
            $stmtCount->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
            $total = (int)$stmtCount->fetchColumn();

            $paginacao = array(
                "pagina" => $pagina,
                "limite" => $limite,
                "total" => $total,
                "total_paginas" => max(1, (int)ceil($total / $limite))
            );

            return array(
                "resultados" => $resultados,
                "paginacao" => $paginacao
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

    public function pegarEmpresas($cliente_id)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($cliente_id === NULL) {
                return [];
            }

            $query = "SELECT
                        `empresa_cliente`.*,
                        `empresa`.`nome` AS empresa_nome,
                        `cliente`.`nome_fantasia` AS cliente_nome_fantasia,
                        `cliente`.`responsavel_nome` AS cliente_responsavel_nome
                    FROM
                        `empresa_cliente`
                    LEFT JOIN
                        `empresa` ON `empresa`.`id` = `empresa_cliente`.`empresa_id`
                    LEFT JOIN
                        `cliente` ON `cliente`.`id` = `empresa_cliente`.`cliente_id`
                    WHERE
                        `empresa_cliente`.`cliente_id` = :cliente_id
                    AND
                        `empresa_cliente`.`situacao` = 1
                    ORDER BY
                        `empresa_cliente`.`id` ASC";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":cliente_id", (int)$cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            $result = $stmt->fetchAll();
            return $result;
        } catch (\Throwable $th) {
            error_log('ERRO pegarEmpresas: ' . $th->getMessage());
            return [];
        }
    }
}
