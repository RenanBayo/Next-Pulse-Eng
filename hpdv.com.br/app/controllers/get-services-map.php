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

// Capturar filtros
$status = isset($_GET['status']) ? $_GET['status'] : '';
$cliente = isset($_GET['cliente']) ? $_GET['cliente'] : '';
$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';

try {
    // Construir consulta SQL com filtros
    $query = "SELECT cliente, cidade, status, atividade, coordenadas_decimal FROM services WHERE 1=1";

    if (!empty($status)) {
        $query .= " AND status = :status";
    }
    if (!empty($cliente)) {
        $query .= " AND cliente LIKE :cliente";
    }
    if (!empty($cidade)) {
        $query .= " AND cidade LIKE :cidade";
    }

    $stmt = $db->prepare($query);

    if (!empty($status)) {
        $stmt->bindParam(':status', $status);
    }
    if (!empty($cliente)) {
        $cliente = "%$cliente%";
        $stmt->bindParam(':cliente', $cliente);
    }
    if (!empty($cidade)) {
        $cidade = "%$cidade%";
        $stmt->bindParam(':cidade', $cidade);
    }

    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($services);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao buscar os serviços: " . $e->getMessage()
    ]);
}