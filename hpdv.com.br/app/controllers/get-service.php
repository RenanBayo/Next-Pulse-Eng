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
    // Consultar o serviço pelo ID
    $query = "SELECT * FROM services WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        echo json_encode($service);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Serviço não encontrado."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao buscar o serviço: " . $e->getMessage()
    ]);
}