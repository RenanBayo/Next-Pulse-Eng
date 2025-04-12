<?php require "../layouts/session.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require '../layouts/head.php'; ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <?php require '../layouts/menu-monitor.php'; ?>
    </header>

    <main class="container">
        <h2 class="text-center mb-4">Gerenciamento de Serviços</h2>
        <div class="card">
            <div class="card-body">
                <table id="servicesTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data de Emissão</th>
                            <th>Prazo</th>
                            <th>O.S</th>
                            <th>Atividade</th>
                            <th>Escritório</th>
                            <th>Cliente</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Os dados serão carregados via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function () {
            // Inicializar DataTable
            const table = $('#servicesTable').DataTable({
                ajax: '../controllers/get-services.php',
                columns: [
                    { data: 'id' },
                    { data: 'data_emissao' },
                    { data: 'prazo' },
                    { data: 'os' },
                    { data: 'atividade' },
                    { data: 'escritorio' },
                    { data: 'cliente' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <a href="detalhes-servico.php?id=${row.id}" class="btn btn-info btn-sm">Visualizar</a>
                                <button class="btn btn-danger btn-sm delete-service" data-id="${row.id}">Excluir</button>
                            `;
                        }
                    }
                ]
            });

            // Excluir serviço
            $('#servicesTable').on('click', '.delete-service', function () {
                const id = $(this).data('id');
                if (confirm('Tem certeza de que deseja excluir este serviço?')) {
                    $.post('../controllers/delete-service.php', { id }, function (response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            table.ajax.reload();
                        } else {
                            alert(result.message);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>