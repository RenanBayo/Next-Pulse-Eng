<?php
require_once '../config/db_connection.php';
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "ID da foto não fornecido."
        ]);
        exit;
    }

    $photoId = intval($_POST['id']);

    // Obter o nome do arquivo
    $query = "SELECT filename FROM service_photos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $photoId, PDO::PARAM_INT);
    $stmt->execute();
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($photo) {
        $filePath = '../uploads/' . $photo['filename'];
        if (file_exists($filePath)) {
            unlink($filePath); // Excluir o arquivo
        }

        // Excluir do banco de dados
        $deleteQuery = "DELETE FROM service_photos WHERE id = :id";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindParam(':id', $photoId, PDO::PARAM_INT);
        $deleteStmt->execute();

        echo json_encode([
            "status" => "success",
            "message" => "Foto excluída com sucesso!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Foto não encontrada."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método de requisição inválido."
    ]);
}