<?php
require_once '../database/db_connection.php';

header('Content-Type: application/json');

if (!isset($_GET['codigo']) || empty($_GET['codigo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Código da obra não informado.']);
    exit;
}

$codigo = trim($_GET['codigo']);

try {
    $db = new DbConnection();
    $conn = $db->getConnection();

    // Buscar dados da obra
    $stmt = $conn->prepare("SELECT id, codigo_obra, nome_obra, valor_total_previsto FROM tb_obras WHERE codigo_obra = :codigo OR nome_obra LIKE :nome LIMIT 1");
    $stmt->execute([
        ':codigo' => $codigo,
        ':nome' => "%$codigo%"
    ]);

    $obra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$obra) {
        echo json_encode(['status' => 'error', 'message' => 'Obra não encontrada.']);
        exit;
    }

    $id_obra = $obra['id'];
    $codigo_obra = $obra['codigo_obra'];
    $valor_total_previsto = isset($obra['valor_total_previsto']) ? floatval($obra['valor_total_previsto']) : 0;

    // Buscar total de gastos da obra
    $stmtGastos = $conn->prepare("SELECT SUM(valor) AS total_gasto FROM tb_despesas WHERE codigo_obra = :codigo");
    $stmtGastos->execute([':codigo' => $codigo_obra]);
    $resultGastos = $stmtGastos->fetch(PDO::FETCH_ASSOC);
    $totalGasto = isset($resultGastos['total_gasto']) ? floatval($resultGastos['total_gasto']) : 0;

    // Calcular porcentagem de gastos (caso tenha valor previsto)
    $porcentagemGasto = $valor_total_previsto > 0 ? round(($totalGasto / $valor_total_previsto) * 100, 1) : 0;

    // Simular evolução por enquanto (você pode mudar para dados reais depois)
    $evolucao = rand(10, 90);

    // Buscar fotos da obra
    $stmtFotos = $conn->prepare("SELECT caminho_arquivo FROM tb_fotos_obra WHERE id_obra = :id_obra");
    $stmtFotos->execute([':id_obra' => $id_obra]);
    $fotos = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'status' => 'ok',
        'obra' => $obra['nome_obra'],
        'evolucao' => $evolucao,
        'gastos' => $porcentagemGasto,
        'fotos' => $fotos
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
