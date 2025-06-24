<?php

class EmpresaUsuario
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
            $usuario_id = isset($parametros["usuario_id"]) ? (int)$parametros["usuario_id"] : null;
            $ativo      = isset($parametros["ativo"]) ? $parametros["ativo"] : null;
            $pagina     = isset($parametros["pagina"]) ? (int)$parametros["pagina"] : 1;
            $limite     = isset($parametros["limite"]) ? (int)$parametros["limite"] : 10;

            $where = array();
            $params = array();

            if ($empresa_id) {
                $where[] = "`empresa_usuario`.`empresa_id` = :empresa_id";
                $params[":empresa_id"] = $empresa_id;
            }
            if ($usuario_id) {
                $where[] = "`empresa_usuario`.`usuario_id` = :usuario_id";
                $params[":usuario_id"] = $usuario_id;
            }
            if ($ativo !== null) {
                $where[] = "`empresa_usuario`.`situacao` = :ativo";
                $params[":ativo"] = $ativo;
            }

            $query = "SELECT
                        `empresa_usuario`.*,
                        `empresa`.`nome` AS empresa_nome,
                        `usuario`.`nome` AS usuario_nome
                    FROM
                        `empresa_usuario`
                    LEFT JOIN
                        `empresa` ON `empresa`.`id` = `empresa_usuario`.`empresa_id`
                    LEFT JOIN
                        `usuario` ON `usuario`.`id` = `empresa_usuario`.`usuario_id`";

            $queryCount = "SELECT
                                COUNT(`empresa_usuario`.`id`) as total
                            FROM
                                `empresa_usuario`
                            LEFT JOIN
                                `empresa` ON `empresa`.`id` = `empresa_usuario`.`empresa_id`
                            LEFT JOIN
                                `usuario` ON `usuario`.`id` = `empresa_usuario`.`usuario_id`";

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `empresa_usuario`.`empresa_id` ASC";

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

    public function pegarEmpresas($usuario_id)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($usuario_id === NULL) {
                return [];
            }

            $query = "SELECT
                        `empresa_usuario`.*,
                        `empresa`.`nome` AS empresa_nome,
                        `usuario`.`nome_fantasia` AS usuario_nome_fantasia,
                        `usuario`.`responsavel_nome` AS usuario_responsavel_nome
                    FROM
                        `empresa_usuario`
                    LEFT JOIN
                        `empresa` ON `empresa`.`id` = `empresa_usuario`.`empresa_id`
                    LEFT JOIN
                        `usuario` ON `usuario`.`id` = `empresa_usuario`.`usuario_id`
                    WHERE
                        `empresa_usuario`.`usuario_id` = :usuario_id
                    AND
                        `empresa_usuario`.`situacao` = 1
                    ORDER BY
                        `empresa_usuario`.`id` ASC";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":usuario_id", (int)$usuario_id, PDO::PARAM_INT);
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
