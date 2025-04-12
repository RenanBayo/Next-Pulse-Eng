<?php
// filepath: c:\xampp1\htdocs\horus_Engenharia-main\horuseng-app\hpdv.com.br\app\web\cadastro-escritorio.php

require_once "../layouts/session.php";


// Conexão com o banco de dados
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if (!$db instanceof PDO) {
    die("Erro: A conexão com o banco de dados não foi estabelecida.");
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_fantasia = $_POST['nome_fantasia'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $ultima_atualizacao = date('Y-m-d H:i:s');

    try {
        $query = "INSERT INTO escritorios (nome_fantasia, cnpj, ultima_atualizacao) VALUES (:nome_fantasia, :cnpj, :ultima_atualizacao)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nome_fantasia', $nome_fantasia);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':ultima_atualizacao', $ultima_atualizacao);
        $stmt->execute();

        $success_message = "Escritório cadastrado com sucesso!";
    } catch (PDOException $e) {
        $error_message = "Erro ao cadastrar o escritório: " . $e->getMessage();
    }
}

// Consultar escritórios cadastrados
try {
    $query = "SELECT * FROM escritorios ORDER BY ultima_atualizacao DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $escritorios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar os escritórios: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require '../layouts/head.php'; ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">
    <title>Cadastro de Escritórios</title>
</head>
<body>
    <header>
        <?php require '../layouts/menu-monitor.php'; ?>
    </header>

    <main class="container mt-5">
        <h2 class="text-center">Cadastro de Escritórios</h2>

        <!-- Mensagens de sucesso ou erro -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulário de Cadastro -->
        <form method="POST" class="mt-4">
            <div class="form-group">
                <label for="nome_fantasia">Nome Fantasia</label>
                <input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cnpj">CNPJ</label>
                <input type="text" id="cnpj" name="cnpj" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Escritório</button>
        </form>

        <!-- Lista de Escritórios -->
        <h3 class="mt-5">Escritórios Cadastrados</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome Fantasia</th>
                    <th>CNPJ</th>
                    <th>Última Atualização</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($escritorios as $escritorio): ?>
                    <tr>
                        <td><?php echo $escritorio['id']; ?></td>
                        <td><?php echo $escritorio['nome_fantasia']; ?></td>
                        <td><?php echo $escritorio['cnpj']; ?></td>
                        <td><?php echo $escritorio['ultima_atualizacao']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>