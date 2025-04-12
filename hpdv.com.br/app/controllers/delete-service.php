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

// Verificar se o ID foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "ID do serviço não fornecido."
        ]);
        exit;
    }

    $id = intval($_POST['id']);

    try {
        // Excluir o serviço pelo ID
        $query = "DELETE FROM services WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Serviço excluído com sucesso!"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Serviço não encontrado ou já excluído."
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Erro ao excluir o serviço: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método de requisição inválido."
    ]);
}