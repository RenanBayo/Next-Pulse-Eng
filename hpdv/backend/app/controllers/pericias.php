<?php
session_start();
require __DIR__ . "/../database/db_connection.php";
require __DIR__ . "/../models/register_client_model.php";
require __DIR__ . "/../services/register_client_service.php";

$connect = new DbConnection();
$model_client = new RegisterClientModel();
$service_client = new RegisterClientService($connect, $model_client);