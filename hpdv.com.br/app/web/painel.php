<?php require "../layouts/session.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php require '../layouts/head.php' ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard da Obra</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/home.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 900px; margin: auto; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);max-width: 400px; margin: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; }
    </style>
</head>

<body>
    <?php require '../layouts/menu.php' ?>
    <div class="container">
        <h2>Dashboard da Obra</h2>
        
        <div class="card">
            <h3>Progresso da Obra</h3>
            <canvas id="progressChart"></canvas>
        </div>
        
        <div class="card">
            <h3>Total Investido</h3>
            <p id="totalInvestido">R$ 0,00</p>
        </div>
        
        <div class="card">
            <h3>Relatório Diário</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Atividade</th>
                        <th>Custo</th>
                    </tr>
                </thead>
                <tbody id="relatorioDiario"></tbody>
            </table>
        </div>
    </div>
    <script>
        const progresso = 60; // Percentual concluído da obra
        const totalInvestido = 150000; // Valor total já investido
        const relatorio = [
            { data: "2025-02-20", atividade: "Fundação concluída", custo: "R$ 50.000" },
            { data: "2025-02-21", atividade: "Estrutura iniciada", custo: "R$ 20.000" }
        ];

        document.getElementById("totalInvestido").textContent = `R$ ${totalInvestido.toLocaleString('pt-BR')}`;
        const tabela = document.getElementById("relatorioDiario");
        relatorio.forEach(item => {
            let row = `<tr><td>${item.data}</td><td>${item.atividade}</td><td>${item.custo}</td></tr>`;
            tabela.innerHTML += row;
        });

        new Chart(document.getElementById("progressChart"), {
            type: 'doughnut',
            data: {
                labels: ["Concluído", "Restante"],
                datasets: [{
                    data: [progresso, 100 - progresso],
                    backgroundColor: ["#4CAF50", "#ddd"]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>

</html>