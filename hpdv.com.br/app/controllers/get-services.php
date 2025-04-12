<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados
require_once '../config/db_connection.php';
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if (!$db instanceof PDO) {
    die(json_encode([
        "status" => "error",
        "message" => "Erro: A conexão com o banco de dados não foi estabelecida."
    ]));
}

try {
    // Consultar todos os serviços
    $query = "SELECT id, data_emissao, prazo, os, atividade, escritorio, cliente FROM services";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $services // DataTables espera os dados dentro da chave "data"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao buscar os serviços: " . $e->getMessage()
    ]);
}