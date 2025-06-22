<?php

class Database
{
    private $host = "tcc-db-phpmyadmin.a5b4kx.easypanel.host";
    private $dbName = "support-ticket-manager";
    private $username = "alvino";
    private $password = "WQDThX9tbD";
    private $charset = "utf8mb4";
    private $pdo;
    private $error;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $dsn = "mysql:host={$this->host};port=3306;dbname={$this->dbName};charset={$this->charset}";

        try {
            $this->pdo = new PDO(
                $dsn,
                $this->username,
                $this->password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Erro ao conectar ao banco de dados: {$this->error}");
        }
    }

    public function getConnection()
    {
        try {
            return $this->pdo;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Erro ao conectar ao banco de dados: {$this->error}");
        }
    }

    // Adicionado para expor o método prepare do PDO
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}
