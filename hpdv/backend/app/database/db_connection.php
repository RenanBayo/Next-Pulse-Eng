<?php

class DbConnection
{
    private $host = "localhost";
    private $dbname = "eng";
    private $user = "root";
    private $pass = "Krypton";

    public function getConnection()
    {
        try {
            $connect = new PDO(
                "mysql:host=localhost;dbname=eng",
                "sa",
                "Krypton"
            );
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connect;
        } catch (PDOException $e) {
            $e = $e->getMessage();
            echo "Erro: $e";
        } catch (Exception $e) {
            $e = $e->getMessage();
            echo "Erro: $e";
        }
    }
}
