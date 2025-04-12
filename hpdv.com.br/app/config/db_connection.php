<?php

class DbConnection
{
    private $host = "localhost";
    private $dbname = "eng";
    private $user = "root";
    // Alterar o usuário para "sa" se necessário
    private $pass = "Krypton";

    public function getConnection()
    {
        try {
            // Log para depuração
            error_log("Tentando conectar ao banco de dados: {$this->dbname} no host {$this->host} com o usuário {$this->user}");

            $connect = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->user,
                $this->pass
            );
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Log para sucesso
            error_log("Conexão com o banco de dados estabelecida com sucesso!");

            return $connect;
        } catch (PDOException $e) {
            // Log para erro de PDO
            error_log("Erro ao conectar ao banco de dados (PDOException): " . $e->getMessage());
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        } catch (Exception $e) {
            // Log para erro genérico
            error_log("Erro ao conectar ao banco de dados (Exception): " . $e->getMessage());
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}