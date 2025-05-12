<?php

require_once("phpmailer/src/Exception.php");
require_once("phpmailer/src/PHPMailer.php");
require_once("phpmailer/src/SMTP.php");

class Suporte
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
            $empresa_id = isset($_SESSION["empresa_id"]) ? $_SESSION["empresa_id"] : NULL;

            if (!$empresa_id) {
                return array(
                    "resultados" => [],
                    "paginacao" => array(
                        "pagina" => 1,
                        "limite" => 10,
                        "total" => 0,
                        "total_paginas" => 1
                    )
                );
            }

            $suporte_id = isset($parametros["suporte_id"]) ? $parametros["suporte_id"] : NULL;
            $status = isset($parametros["status"]) ? $parametros["status"] : NULL;
            $assunto = isset($parametros["assunto"]) ? $parametros["assunto"] : NULL;
            $pagina = isset($parametros["pagina"]) ? $parametros["pagina"] : 1;
            $limite = isset($parametros["limite"]) ? $parametros["limite"] : 10;

            $where = array();

            $where[] = "`suporte`.`empresa_id` = {$empresa_id}";

            $query = "SELECT 
                        `suporte`.*,
                        `empresa`.`nome_fantasia` AS empresa_nome,
                        `empresa`.`responsavel` AS empresa_responsavel,
                        `usuario`.`nome` AS usuario_nome";

            $queryCount = "SELECT COUNT(`suporte`.`id`)";

            $query .= " FROM `suporte`";
            $queryCount .= " FROM `suporte`";

            $query .= " LEFT JOIN `empresa` ON `empresa`.id = `suporte`.`empresa_id`";
            $query .= " LEFT JOIN `usuario` ON `usuario`.id = `suporte`.`usuario_id`";

            $queryCount .= " LEFT JOIN `empresa` ON `empresa`.id = `suporte`.`empresa_id`";
            $queryCount .= " LEFT JOIN `usuario` ON `usuario`.id = `suporte`.`usuario_id`";

            if ($suporte_id) {
                $where[] = "`suporte`.`id` = :suporte_id";
            }

            if ($status) {
                $where[] = "`suporte`.`status` = :status";
            }

            if ($assunto) {
                $where[] = "`suporte`.`assunto` = :assunto";
            }

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `suporte`.`cadastrado` DESC";

            $query .= " LIMIT :limite OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmtCount = $this->db->prepare($queryCount);

            if ($suporte_id) {
                $stmt->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
                $stmtCount->bindParam(":suporte_id", $suporte_id, PDO::PARAM_INT);
            }

            if ($status) {
                $stmt->bindParam(":status", $status, PDO::PARAM_STR);
                $stmtCount->bindParam(":status", $status, PDO::PARAM_STR);
            }

            if ($assunto) {
                $stmt->bindParam(":assunto", $assunto, PDO::PARAM_STR);
                $stmtCount->bindParam(":assunto", $assunto, PDO::PARAM_STR);
            }

            $offset = ($pagina - 1) * $limite;

            $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();
            $stmtCount->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
            $total = $stmtCount->fetchColumn();

            $paginacao = array(
                "pagina" => $pagina,
                "limite" => $limite,
                "total" => $total,
                "total_paginas" => ceil($total / $limite)
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

    public function pegarPorId($id = NULL)
    {
        try {
            if (!$id) {
                return NULL;
            }

            $query = "SELECT
                        `suporte`.*,
                        `usuario`.`nome` AS nome_usuario,
                        `usuario`.`email` AS email_usuario,
                        `admin`.`nome` AS nome_admin,
                        `empresa`.`razao_social` AS nome_empresa";

            $query .= " FROM `suporte`";

            $query .= " LEFT JOIN `usuario` on `usuario`.id = `suporte`.`usuario_id`";
            $query .= " LEFT JOIN `admin` on `admin`.id = `suporte`.`admin_id`";
            $query .= " LEFT JOIN `empresa` on `empresa`.id = `suporte`.`empresa_id`";

            $query .= " WHERE `suporte`.`id` = :id LIMIT 1";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $registro = $stmt->fetch(PDO::FETCH_OBJ);

            return $registro;
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function pegarTodos($filtros = array())
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $empresa_id = isset($_SESSION["empresa_id"]) ? $_SESSION["empresa_id"] : NULL;

            if (!$empresa_id) {
                return [];
            }

            $ativo = isset($filtros["ativo"]) ? $filtros["ativo"] : NULL;

            $query = "SELECT `suporte`.*";

            $query .= " FROM `suporte`";

            $query .= " WHERE `suporte`.`empresa_id` = {$empresa_id}";

            if ($ativo == "ATIVO") {
                $query .= " AND `suporte`.`ativo` = 1";
            }

            $stmt = $this->db->prepare($query);

            $stmt->execute();

            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $registros;
        } catch (\Throwable $th) {
            return [];
        }
    }
}
