<?php
require_once '../config/db_connection.php';
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if (!isset($_GET['service_id']) || empty($_GET['service_id'])) {
    echo json_encode([]);
    exit;
}

$serviceId = intval($_GET['service_id']);

$query = "SELECT id, filename FROM service_photos WHERE service_id = :service_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
$stmt->execute();

$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($photos);