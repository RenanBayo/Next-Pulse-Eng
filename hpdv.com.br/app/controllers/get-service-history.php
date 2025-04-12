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

// Verificar se o ID foi enviado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "ID do serviço não fornecido."
    ]);
    exit;
}

$id = intval($_GET['id']);

try {
    // Consultar o histórico de alterações do serviço
    $query = "SELECT data_hora, usuario, descricao FROM service_history WHERE service_id = :id ORDER BY data_hora DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($history);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao buscar o histórico de alterações: " . $e->getMessage()
    ]);
}