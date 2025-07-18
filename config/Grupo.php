<?php

class Grupo
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index(array $parametros = array())
    {
        try {
            $nome = $parametros["nome"] ?? null;
            $situacao = $parametros["situacao"] ?? null;
            $pagina     = isset($parametros["pagina"]) ? (int)$parametros["pagina"] : 1;
            $limite     = isset($parametros["limite"]) ? (int)$parametros["limite"] : 10;

            $where = array();
            $params = array();

            // Recupera o grupo do usuário da sessão
            $grupoUsuario = isset($_SESSION['usuario_grupo']) ? (int)$_SESSION['usuario_grupo'] : null;

            if ($nome) {
                $where[] = "nome LIKE :nome";
                $params[":nome"] = "%$nome%";
            }

            if ($situacao) {
                $where[] = "situacao = :situacao";
                $params[":situacao"] = $situacao;
            }

            if ($grupoUsuario !== 1) {
                $where[] = " AND `id` NOT IN (1,2)";
            }

            $query = "SELECT * FROM `grupo`";
            $queryCount = "SELECT COUNT(*) as total FROM `grupo`";

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `id` ASC";

            // Só adiciona LIMIT/OFFSET se limite for definido e maior que zero
            if ($limite !== null && $limite > 0) {
                $offset = ($pagina - 1) * $limite;
                $query .= " LIMIT $limite OFFSET $offset";
            }

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

    public function pegarPorId($id = null)
    {
        try {
            if (!$id) {
                return null;
            }
            $query = "SELECT `grupo`.* FROM `grupo` WHERE `grupo`.`id` = :id LIMIT 1";
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

    public function listarGrupos()
    {
        try {
            // Recupera o grupo do usuário da sessão
            $grupoUsuario = isset($_SESSION['usuario_grupo']) ? (int)$_SESSION['usuario_grupo'] : null;

            $query = "SELECT * FROM `grupo` WHERE `situacao` = 1";
            $params = [];

            if ($grupoUsuario !== 1) {
                $query .= " AND `id` NOT IN (1,2)";
            }

            $query .= " ORDER BY `id` ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            return $stmt->fetchAll();
        } catch (\Throwable $th) {
            return [];
        }
    }
}
