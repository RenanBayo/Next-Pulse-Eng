<?php
session_start();
require __DIR__ . "/../database/db_connection.php";
require __DIR__ . "/../utils/mpdf/vendor/autoload.php";

$connect = new DbConnection();
$connect = $connect->getConnection();

function redirect($msg)
{
    echo json_encode($msg);
    exit();
}

if (!isset($_GET['action'])) {
    redirect(["error" => "erro1", "message" => "Erro de autenticação."]);
    exit();
} else {
    ob_start();
    
    $action = $_GET['action'];
    if ($action === "report_total_by_client") {
        $query = "SELECT c.id AS cliente_id, c.nome, c.cpf, SUM(v.quantidade) AS total_quantidade
                  FROM tb_vendas v
                  JOIN tb_clientes c ON v.cliente = c.id
                  GROUP BY c.id, c.nome, c.cpf
                  ORDER BY c.nome";
        
        $stmt = $connect->prepare($query);
        $stmt->execute();
        $results_sales = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Adicionar os dados ao dashboard
        echo '<div class="container">';
        echo '<h3>Relatório de Quantidade Total por Cliente</h3>';
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Nome</th><th>CPF</th><th>Quantidade Total Comprada</th></tr></thead>';
        echo '<tbody>';
        foreach ($results_sales as $sale) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($sale->nome) . '</td>';
            echo '<td>' . htmlspecialchars($sale->cpf) . '</td>';
            echo '<td>' . htmlspecialchars($sale->total_quantidade) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';

        // Geração do PDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'default_font_size' => 10,
            'default_font' => 'sans-serif',
        ]);

        $html = '<style>
        table { font-size:10px; }
        .table, .table th, .table td { border: 1px solid black; border-collapse: collapse; }
        </style>';

        $html .= '<div class="container">
            <h3>Relatório de Quantidade Total por Cliente</h3>
            <table class="table" width="100%" style="text-align:center; border-collapse: collapse;">
                <tr><th>Nome</th><th>CPF</th><th>Quantidade Total Comprada</th></tr>';

        foreach ($results_sales as $sale) {
            $html .= '<tr>
                <td>' . htmlspecialchars($sale->nome) . '</td>
                <td>' . htmlspecialchars($sale->cpf) . '</td>
                <td>' . htmlspecialchars($sale->total_quantidade) . '</td>
            </tr>';
        }

        $html .= '</table></div>';
        $mpdf->WriteHTML($html);
        $mpdf->Output("relatorio_clientes.pdf", "I");
        exit();
    }
}
