<?php

class EmpresaServico
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
            $servico_id = isset($parametros["servico_id"]) ? (int)$parametros["servico_id"] : null;
            $ativo      = isset($parametros["ativo"]) ? $parametros["ativo"] : null;
            $pagina     = isset($parametros["pagina"]) ? (int)$parametros["pagina"] : 1;
            $limite     = isset($parametros["limite"]) ? (int)$parametros["limite"] : 10;

            $where = array();
            $params = array();

            if ($empresa_id) {
                $where[] = "`empresa_servico`.`empresa_id` = :empresa_id";
                $params[":empresa_id"] = $empresa_id;
            }
            if ($servico_id) {
                $where[] = "`empresa_servico`.`servico_id` = :servico_id";
                $params[":servico_id"] = $servico_id;
            }
            if ($ativo !== null) {
                $where[] = "`empresa_servico`.`situacao` = :ativo";
                $params[":ativo"] = $ativo;
            }

            $query = "SELECT
                        `empresa_servico`.*,
                        `empresa`.`nome` AS empresa_nome,
                        `servico`.`nome` AS servico_nome
                    FROM
                        `empresa_servico`
                        LEFT JOIN
                            `empresa` ON `empresa`.`id` = `empresa_servico`.`empresa_id`
                        LEFT JOIN
                            `servico` ON `servico`.`id` = `empresa_servico`.`servico_id`";

            $queryCount = "SELECT
                                COUNT(`empresa_servico`.`id`) as total
                            FROM
                                `empresa_servico`
                            LEFT JOIN
                                `empresa` ON `empresa`.`id` = `empresa_servico`.`empresa_id`
                            LEFT JOIN
                                `servico` ON `servico`.`id` = `empresa_servico`.`servico_id`";

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `empresa_servico`.`empresa_id` ASC";

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

    public function pegarServicos($empresa_id)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($empresa_id === NULL) {
                return [];
            }

            $query = "SELECT
                        `empresa_servico`.*,
                        `empresa`.`nome` AS empresa_nome,
                        `servico`.`nome` AS servico_nome
                    FROM
                        `empresa_servico`
                    LEFT JOIN
                        `empresa` ON `empresa`.`id` = `empresa_servico`.`empresa_id`
                    LEFT JOIN
                        `servico` ON `servico`.`id` = `empresa_servico`.`servico_id`
                    WHERE
                        `empresa_servico`.`empresa_id` = :empresa_id
                    AND
                        `empresa_servico`.`situacao` = 1
                    ORDER BY
                        `empresa_servico`.`id` ASC";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":empresa_id", (int)$empresa_id, PDO::PARAM_INT);
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
