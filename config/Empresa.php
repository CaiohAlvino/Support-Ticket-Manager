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
            $limite = isset($parametros["limite"]) ? $parametros["limite"] : null;

            $where = array();

            // Verifica se o usuário não é admin (grupo != 1) para aplicar filtro por empresas
            $usuario_grupo = isset($_SESSION["usuario_grupo"]) ? $_SESSION["usuario_grupo"] : null;
            $usuario_id = isset($_SESSION["usuario_id"]) ? $_SESSION["usuario_id"] : null;

            $query = "SELECT DISTINCT `empresa`.*";
            $queryCount = "SELECT COUNT(DISTINCT `empresa`.`id`)";

            $query .= " FROM `empresa`";
            $queryCount .= " FROM `empresa`";

            // Se não for admin, filtra pelas empresas que o usuário tem acesso
            if ($usuario_grupo != 1 && $usuario_id) {
                $query .= " INNER JOIN `empresa_usuario` ON `empresa_usuario`.`empresa_id` = `empresa`.`id`";
                $queryCount .= " INNER JOIN `empresa_usuario` ON `empresa_usuario`.`empresa_id` = `empresa`.`id`";
                $where[] = "`empresa_usuario`.`usuario_id` = :usuario_id";
                $where[] = "`empresa_usuario`.`situacao` = 1";
            }

            if ($nome) {
                $where[] = "`empresa`.`nome` LIKE :nome";
            }

            if ($where) {
                $query .= " WHERE " . implode(" AND ", $where);
                $queryCount .= " WHERE " . implode(" AND ", $where);
            }

            $query .= " ORDER BY `empresa`.`id` ASC";

            if ($limite) {
                $query .= " LIMIT :limite OFFSET :offset";
            }
            $stmt = $this->db->prepare($query);
            $stmtCount = $this->db->prepare($queryCount);

            // Bind do usuario_id se não for admin
            if ($usuario_grupo != 1 && $usuario_id) {
                $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
                $stmtCount->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            }

            if ($nome) {
                $nome = "%$nome%";
                $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
                $stmtCount->bindParam(":nome", $nome, PDO::PARAM_STR);
            }

            if ($limite) {
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
                "limite" => $limite ? $limite : $total,
                "total" => $total,
                "total_paginas" => $limite ? ceil($total / $limite) : 1
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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $usuario_grupo = isset($_SESSION["usuario_grupo"]) ? $_SESSION["usuario_grupo"] : null;
            $usuario_id = isset($_SESSION["usuario_id"]) ? $_SESSION["usuario_id"] : null;

            $query = "SELECT DISTINCT `empresa`.*";
            $query .= " FROM `empresa`";

            $where = array();
            $where[] = "`empresa`.`situacao` = 1";

            // Se não for admin, filtra pelas empresas que o usuário tem acesso
            if ($usuario_grupo != 1 && $usuario_id) {
                $query .= " INNER JOIN `empresa_usuario` ON `empresa_usuario`.`empresa_id` = `empresa`.`id`";
                $where[] = "`empresa_usuario`.`usuario_id` = :usuario_id";
                $where[] = "`empresa_usuario`.`situacao` = 1";
            }

            $query .= " WHERE " . implode(" AND ", $where);
            $query .= " ORDER BY `empresa`.`nome` ASC";

            $stmt = $this->db->prepare($query);

            // Bind do usuario_id se não for admin
            if ($usuario_grupo != 1 && $usuario_id) {
                $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            return $stmt->fetchAll();
        } catch (\Throwable $th) {
            return [];
        }
    }

    /**
     * Método para debug - retorna informações sobre o filtro aplicado para empresas
     */
    public function getInfoFiltro()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $usuario_grupo = isset($_SESSION["usuario_grupo"]) ? $_SESSION["usuario_grupo"] : null;
            $usuario_id = isset($_SESSION["usuario_id"]) ? $_SESSION["usuario_id"] : null;

            $info = [
                'usuario_id' => $usuario_id,
                'usuario_grupo' => $usuario_grupo,
                'is_admin' => ($usuario_grupo == 1),
                'aplica_filtro' => ($usuario_grupo != 1 && $usuario_id),
            ];

            // Se não for admin, busca as empresas associadas
            if ($info['aplica_filtro']) {
                $query = "SELECT e.id, e.nome, eu.situacao as vinculo_ativo
                         FROM empresa e
                         INNER JOIN empresa_usuario eu ON eu.empresa_id = e.id
                         WHERE eu.usuario_id = :usuario_id
                         ORDER BY e.nome";

                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
                $stmt->execute();

                $empresas = $stmt->fetchAll(PDO::FETCH_OBJ);
                $info['empresas_todas'] = $empresas;
                $info['empresas_ativas'] = array_filter($empresas, function ($emp) {
                    return $emp->vinculo_ativo == 1;
                });
                $info['total_empresas'] = count($empresas);
                $info['total_empresas_ativas'] = count($info['empresas_ativas']);
            }

            return $info;
        } catch (\Throwable $th) {
            return ['erro' => $th->getMessage()];
        }
    }
}
