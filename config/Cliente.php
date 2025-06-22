<?php
class Cliente
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index($parametros = array())
    {
        try {
            // if (session_status() === PHP_SESSION_NONE) {
            //     session_start();
            // }

            $empresa_id = isset($parametros["empresa_id"]) ? $parametros["empresa_id"] : NULL;
            $cliente_id = isset($parametros["cliente_id"]) ? $parametros["cliente_id"] : NULL;
            $nome_fantasia = isset($parametros["nome_fantasia"]) ? $parametros["nome_fantasia"] : NULL;
            $documento = isset($parametros["documento"]) ? $parametros["documento"] : NULL;
            $pagina = isset($parametros["pagina"]) ? $parametros["pagina"] : 1;
            $limite = isset($parametros["limite"]) ? $parametros["limite"] : 10;

            $where = array();

            $query = "SELECT `cliente`.*";
            $queryCount = "SELECT COUNT(`cliente`.`id`)";

            $query .= " FROM `cliente`";
            $queryCount .= " FROM `cliente`";

            if ($cliente_id) {
                $where[] = "`cliente`.`id` = :cliente_id";
            }

            if ($nome_fantasia) {
                $where[] = "`cliente`.`nome_fantasia` LIKE :nome_fantasia";
            }

            if ($documento) {
                $where[] = "`cliente`.`documento` LIKE :documento";
            }

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `cliente`.`nome_fantasia` ASC";

            // Só adiciona LIMIT/OFFSET se limite for definido e maior que zero
            if ($limite !== null && $limite > 0) {
                $query .= " LIMIT :limite OFFSET :offset";
            }

            $stmt = $this->db->prepare($query);
            $stmtCount = $this->db->prepare($queryCount);

            if ($cliente_id) {
                $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
                $stmtCount->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
            }

            if ($nome_fantasia) {
                $nome_fantasia = "%$nome_fantasia%";
                $stmt->bindParam(":nome_fantasia", $nome_fantasia, PDO::PARAM_STR);
                $stmtCount->bindParam(":nome_fantasia", $nome_fantasia, PDO::PARAM_STR);
            }

            if ($documento) {
                $documento = "%$documento%";
                $stmt->bindParam(":documento", $documento, PDO::PARAM_STR);
                $stmtCount->bindParam(":documento", $documento, PDO::PARAM_STR);
            }

            if ($limite !== null && $limite > 0) {
                $offset = ($pagina - 1) * $limite;
                $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
                $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            }

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

    public function pegarTodos($filtros = array())
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $ativo = isset($filtros["ativo"]) ? $filtros["ativo"] : NULL;

            $query = "SELECT";

            $query .= " `cliente`.`id`,";
            $query .= " `cliente`.`nome_fantasia`,";
            $query .= " `cliente`.`documento`";

            $query .= " FROM `cliente`";

            if ($ativo == "ATIVO") {
                $query .= " AND `cliente`.`ativo` = 1";
            }

            $stmt = $this->db->prepare($query);

            $stmt->execute();

            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $registros;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function pegarPorId($id = NULL)
    {
        try {
            if (!$id) {
                return NULL;
            }

            $query = "SELECT `cliente`.*";

            $query .= " FROM `cliente`";

            $query .= " WHERE `cliente`.`id` = :id LIMIT 1";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $registro = $stmt->fetch(PDO::FETCH_OBJ);

            return $registro;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function pegarPorUsuarioId($usuario_id = NULL)
    {
        try {
            if (!$usuario_id) {
                return NULL;
            }

            $query = "SELECT `cliente`.* FROM `cliente` WHERE `cliente`.`usuario_id` = :usuario_id LIMIT 1";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            $stmt->execute();

            $registro = $stmt->fetch(PDO::FETCH_OBJ);
            return $registro;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function listarClientes()
    {
        try {
            $query = "SELECT * FROM
                            `cliente`
                        WHERE
                            `situacao` = 1
                        ORDER BY
                            `nome_fantasia`
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
