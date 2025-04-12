<?php
include "/../database/db_connection.php";

$sqlClientes = "SELECT * FROM `tb_clientes`";
$buscarClientes = mysqli_query($conexao,$sqlClientes);

$sqlVendas = "SELECT * FROM `tb_vendas`";
$buscarVendas = mysqli_query($conexao,$sqlVendas);