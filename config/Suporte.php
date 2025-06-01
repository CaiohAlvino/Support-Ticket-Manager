<?php
class Suporte
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Lista tickets de suporte com filtros e paginação.
     * Corrigido: uso de prepared statements e SQL seguro.
     */
    public function index($parametros = array(), $usuario_id = NULL)
    {
        try {
            $suporte_id = isset($parametros["suporte_id"]) ? (int)$parametros["suporte_id"] : null;
            $status     = isset($parametros["status"]) ? $parametros["status"] : null;
            $assunto    = isset($parametros["assunto"]) ? $parametros["assunto"] : null;
            $pagina     = isset($parametros["pagina"]) ? (int)$parametros["pagina"] : 1;
            $limite     = isset($parametros["limite"]) ? (int)$parametros["limite"] : 10;
            $dias       = isset($parametros["dias"]) ? (int)$parametros["dias"] : null;
            $quantidade = isset($parametros["quantidade"]) ? (int)$parametros["quantidade"] : null;

            $where = array();
            $params = array();

            if ($usuario_id) {
                $where[] = "`suporte`.`usuario_id` = :usuario_id";
                $params[":usuario_id"] = $usuario_id;
            }
            if ($suporte_id) {
                $where[] = "`suporte`.`id` = :suporte_id";
                $params[":suporte_id"] = $suporte_id;
            }
            if ($status) {
                $where[] = "`suporte`.`status` = :status";
                $params[":status"] = $status;
            }
            if ($assunto) {
                $where[] = "`suporte`.`assunto` = :assunto";
                $params[":assunto"] = $assunto;
            }
            if (isset($parametros["empresa_id"]) && $parametros["empresa_id"]) {
                $where[] = "`suporte`.`empresa_id` = :empresa_id";
                $params[":empresa_id"] = (int)$parametros["empresa_id"];
            }
            if ($dias) {
                $where[] = "`suporte`.`cadastrado` >= DATE_SUB(NOW(), INTERVAL :dias DAY)";
                $params[":dias"] = $dias;
            }

            $joins = " LEFT JOIN `empresa` ON `empresa`.id = `suporte`.`empresa_id`" .
                " LEFT JOIN `usuario` ON `usuario`.id = `suporte`.`usuario_id`" .
                " LEFT JOIN `cliente` ON `cliente`.id = `suporte`.`cliente_id`";

            $query = "SELECT `suporte`.*,
                    `empresa`.`nome` AS empresa_nome,
                    `usuario`.`nome` AS usuario_nome,
                    `cliente`.`nome_fantasia` AS cliente_nome
                    FROM `suporte`";

            $queryCount = "SELECT COUNT(`suporte`.`id`) FROM `suporte`";
            $query .= $joins;
            $queryCount .= $joins;

            if ($where) {
                $whereSql = " WHERE " . implode(" AND ", $where);
                $query .= $whereSql;
                $queryCount .= $whereSql;
            }

            $query .= " ORDER BY `suporte`.`cadastrado` DESC";
            if ($quantidade) {
                $query .= " LIMIT :quantidade";
            } else {
                $query .= " LIMIT :limite OFFSET :offset";
            }

            $stmt = $this->db->prepare($query);
            $stmtCount = $this->db->prepare($queryCount);

            foreach ($params as $key => $value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $type);
                $stmtCount->bindValue($key, $value, $type);
            }
            if ($quantidade) {
                $stmt->bindValue(":quantidade", $quantidade, PDO::PARAM_INT);
            } else {
                $offset = ($pagina - 1) * $limite;
                $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
                $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
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
                "erro" => "Erro ao buscar tickets: " . $th->getMessage(),
                "resultados" => [],
                "paginacao" => array(
                    "pagina" => 1,
                    "limite" => 10,
                    "total" => 0,
                    "total_paginas" => 1
                )
            );
        }
    }

    /**
     * Busca um ticket de suporte por ID.
     * Corrigido: uso de prepared statement.
     */
    public function pegarPorId($id = null)
    {
        try {
            if (!$id) {
                return null;
            }
            $query = "SELECT `suporte`.* FROM `suporte` WHERE `suporte`.`id` = :id LIMIT 1";
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

    /**
     * Lista todos os tickets de uma empresa, com filtro de ativo.
     * Corrigido: uso de prepared statement e SQL seguro.
     */
    public function pegarTodos($filtros = array())
    {
        try {
            $empresa_id = isset($_SESSION["empresa_id"]) ? (int)$_SESSION["empresa_id"] : null;
            if (!$empresa_id) {
                return [];
            }
            $ativo = isset($filtros["ativo"]) ? $filtros["ativo"] : null;
            $query = "SELECT `suporte`.* FROM `suporte` WHERE `suporte`.`empresa_id` = :empresa_id";
            if ($ativo === "ATIVO") {
                $query .= " AND `suporte`.`ativo` = 1";
            }
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":empresa_id", $empresa_id, PDO::PARAM_INT);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $registros;
        } catch (\Throwable $th) {
            return [];
        }
    }
}
