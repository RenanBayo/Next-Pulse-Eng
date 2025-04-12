<?php
require_once '../config/db_connection.php';

if ($db instanceof PDO) {
    echo "Conexão com o banco de dados bem-sucedida!";
} else {
    echo "Erro: A conexão com o banco de dados falhou.";
}