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
    public function index($parametros = array(), $cliente = NULL, $usuario_id = NULL)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $suporte_id = isset($parametros["suporte_id"]) ? (int)$parametros["suporte_id"] : null;
            $status     = isset($parametros["status"]) ? $parametros["status"] : null;
            $assunto    = isset($parametros["assunto"]) ? $parametros["assunto"] : null;
            $pagina     = isset($parametros["pagina"]) ? (int)$parametros["pagina"] : 1;
            $limite     = isset($parametros["limite"]) ? (int)$parametros["limite"] : 10;
            $dias       = isset($parametros["dias"]) ? (int)$parametros["dias"] : null;
            $quantidade = isset($parametros["quantidade"]) ? (int)$parametros["quantidade"] : null;

            $where = array();
            $params = array();

            // Verifica se o usuário não é admin (grupo != 1) para aplicar filtro por empresas
            $usuario_grupo = isset($_SESSION["usuario_grupo"]) ? $_SESSION["usuario_grupo"] : null;
            $usuario_sessao_id = isset($_SESSION["usuario_id"]) ? $_SESSION["usuario_id"] : null;

            if ($usuario_id) {
                $where[] = "`suporte`.`usuario_id` = :usuario_id";
                $params[":usuario_id"] = (int)$usuario_id;
            }
            if ($cliente && isset($cliente->id)) {
                $where[] = "`suporte`.`cliente_id` = :cliente_id";
                $params[":cliente_id"] = (int)$cliente->id;
            }
            if ($suporte_id) {
                $where[] = "`suporte`.`id` = :suporte_id";
                $params[":suporte_id"] = (int)$suporte_id;
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

            $joins = " LEFT JOIN `empresa` ON `empresa`.`id` = `suporte`.`empresa_id`" .
                " LEFT JOIN `usuario` ON `usuario`.`id` = `suporte`.`usuario_id`" .
                " LEFT JOIN `cliente` ON `cliente`.`id` = `suporte`.`cliente_id`" .
                " LEFT JOIN `servico` ON `servico`.`id` = `suporte`.`servico_id`";

            // Se não for admin, adiciona joins para filtrar por empresas do usuário + tickets sem empresa
            if ($usuario_grupo != 1 && $usuario_sessao_id && !$cliente) {
                $joins .= " LEFT JOIN `empresa_cliente` ON `empresa_cliente`.`cliente_id` = `suporte`.`cliente_id` AND `empresa_cliente`.`situacao` = 1";
                $joins .= " LEFT JOIN `empresa_usuario` ON `empresa_usuario`.`empresa_id` = `empresa_cliente`.`empresa_id` AND `empresa_usuario`.`situacao` = 1";
                $where[] = "(`empresa_usuario`.`usuario_id` = :usuario_sessao_id OR `suporte`.`empresa_id` IS NULL OR `empresa_cliente`.`cliente_id` IS NULL)";
                $params[":usuario_sessao_id"] = $usuario_sessao_id;
            }

            $query = "SELECT DISTINCT `suporte`.*,
                    `empresa`.`nome` AS empresa_nome,
                    `usuario`.`nome` AS usuario_nome,
                    `cliente`.`nome_fantasia` AS cliente_nome,
                    `servico`.`nome` AS servico_nome
                    FROM `suporte`";

            $queryCount = "SELECT COUNT(DISTINCT `suporte`.`id`) FROM `suporte`";
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
     * Corrigido: uso de prepared statement com joins completos.
     */
    public function pegarPorId($id = null)
    {
        try {
            if (!$id) {
                return null;
            }
            $query = "SELECT `suporte`.*,
                `cliente`.`nome_fantasia` AS cliente_nome_fantasia,
                `cliente`.`razao_social` AS cliente_razao_social,
                `cliente`.`responsavel_nome` AS cliente_responsavel_nome,
                `cliente`.`responsavel_email` AS cliente_responsavel_email,
                `servico`.`nome` AS servico_nome,
                `usuario`.`nome` AS usuario_nome,
                `empresa`.`nome` AS empresa_nome
                FROM `suporte`
                LEFT JOIN `cliente` ON `cliente`.`id` = `suporte`.`cliente_id`
                LEFT JOIN `usuario` ON `usuario`.`id` = `suporte`.`usuario_id`
                LEFT JOIN `empresa` ON `empresa`.`id` = `suporte`.`empresa_id`
                LEFT JOIN `servico` ON `servico`.`id` = `suporte`.`servico_id`
                WHERE `suporte`.`id` = :id LIMIT 1";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
            $stmt->execute();
            $registro = $stmt->fetch(PDO::FETCH_OBJ);
            return $registro;
        } catch (\Throwable $th) {
            error_log("Erro em pegarPorId: " . $th->getMessage());
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
            $ativo = isset($filtros["ativo"]) ? $filtros["ativo"] : null;

            $query = "SELECT `suporte`.*,
                `cliente`.`nome` AS cliente_nome,
                `cliente`.`email` AS cliente_email,
                `usuario`.`nome` AS usuario_nome,
                `empresa`.`razao_social` AS empresa_nome,
                `servico`.`nome` AS servico_nome
                FROM `suporte`
                LEFT JOIN `cliente` ON `cliente`.`id` = `suporte`.`cliente_id`
                LEFT JOIN `usuario` ON `usuario`.`id` = `suporte`.`usuario_id`
                LEFT JOIN `empresa` ON `empresa`.`id` = `suporte`.`empresa_id`
                LEFT JOIN `servico` ON `servico`.`id` = `suporte`.`servico_id`";

            if ($ativo === "ATIVO") {
                $query .= " WHERE `suporte`.`ativo` = 1";
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $registros;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function listarSuportes()
    {
        try {
            $query = "SELECT * FROM
                            `suporte`
                        WHERE
                            `suporte`.`ativo` = 1
                        ORDER BY
                            `suporte`.`cadastrado` DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\Throwable $th) {
            return [];
        }
    }

    /**
     * Método para debug - retorna informações sobre o filtro aplicado para suporte
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

            // Se não for admin, busca informações sobre tickets e empresas
            if ($info['aplica_filtro']) {
                // Tickets que o usuário pode ver
                $query = "SELECT COUNT(DISTINCT s.id) as total_tickets
                         FROM suporte s
                         LEFT JOIN empresa e ON e.id = s.empresa_id
                         LEFT JOIN cliente c ON c.id = s.cliente_id
                         LEFT JOIN empresa_cliente ec ON ec.cliente_id = s.cliente_id AND ec.situacao = 1
                         LEFT JOIN empresa_usuario eu ON eu.empresa_id = ec.empresa_id AND eu.situacao = 1
                         WHERE (eu.usuario_id = :usuario_id OR s.empresa_id IS NULL OR ec.cliente_id IS NULL)";

                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
                $stmt->execute();

                $info['total_tickets_acesso'] = $stmt->fetchColumn();

                // Tickets sem empresa/cliente
                $query = "SELECT COUNT(*) as tickets_sem_empresa
                         FROM suporte s
                         LEFT JOIN empresa_cliente ec ON ec.cliente_id = s.cliente_id AND ec.situacao = 1
                         WHERE (s.empresa_id IS NULL OR ec.cliente_id IS NULL)";

                $stmt = $this->db->prepare($query);
                $stmt->execute();

                $info['tickets_sem_empresa'] = $stmt->fetchColumn();
            }

            return $info;
        } catch (\Throwable $th) {
            return ['erro' => $th->getMessage()];
        }
    }
}
