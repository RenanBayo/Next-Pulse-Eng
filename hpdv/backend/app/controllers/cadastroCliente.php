<?php 

require_once __DIR__ . "/../database/db_connection.php";

$mes = $_POST['mes'];
$quantidade = $_POST['quantidade'];


$sql = "INSERT INTO `clientes`( `mes_cliente`, `quantidade_cliente`) VALUES ('$mes',$quantidade)";

$query = mysqli_query($conexao,$sql);

header('Location: ../dashboard.php?pagina=cadastroclientes');