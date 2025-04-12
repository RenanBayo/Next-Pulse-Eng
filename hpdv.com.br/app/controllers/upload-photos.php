<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados
require_once '../config/db_connection.php';
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['service_id']) || empty($_POST['service_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "ID do serviço não fornecido."
        ]);
        exit;
    }

    $serviceId = intval($_POST['service_id']);
    $uploadDir = '../uploads/';
    $uploadedFiles = [];

    foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
        $filename = basename($_FILES['photos']['name'][$key]);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $targetFile)) {
            // Salvar no banco de dados
            $query = "INSERT INTO service_photos (service_id, filename) VALUES (:service_id, :filename)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
            $stmt->bindParam(':filename', $filename);
            $stmt->execute();

            $uploadedFiles[] = $filename;
        }
    }

    echo json_encode([
        "status" => "success",
        "message" => "Fotos enviadas com sucesso!",
        "files" => $uploadedFiles
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método de requisição inválido."
    ]);
}