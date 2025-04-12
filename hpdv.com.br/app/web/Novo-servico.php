<?php require "../layouts/session.php" ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require '../layouts/head.php' ?>
    <link rel="stylesheet" href="../css/pericias.css">
    <link rel="stylesheet" href="../css/global.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/mdb.min.css">
    <link rel="stylesheet" href="../css/admin_mdb.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="../css/buttons.bootstrap4.css">
    <link rel="stylesheet" href="../css/colReorder.bootstrap4.css">
    <link rel="stylesheet" href="../css/fullcalendar-main.css">
    <link rel="stylesheet" href="../css/fullcalendar-daygrid-main.css">
    <link rel="stylesheet" href="../css/fullcalendar-bootstrap-main.css">
    <link rel="stylesheet" href="../css/jquery-ui.min.css">
    <link rel="stylesheet" href="../css/siaupro_app.css">
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for (let registration of registrations) {  
                    registration.unregister();
                }
            });
        }
    </script>
</head>
<body id="monitor" class="servico pericias fixed-sn white-skin">
    <!-- Cookie Modal -->
    <div class="modal fade bottom" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-bottom" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row d-flex justify-content-center align-items-center">
                        <p class="p-3">
                            O NP Monitor utiliza cookies para identificar os logins, analisar estatísticas de acesso e informações de publicidade e marketing. <br>
                            Saiba mais detalhes em nossa 
                            <a class="cookies_inside_modal" target="_blank" href="politicas/cookies.php">Política de Cookies</a>, 
                            <a class="privacidade_inside_modal" target="_blank" href="politicas/privacidade.php">Política de Privacidade</a> e 
                            <a class="termos_de_uso_inside_modal" target="_blank" href="politicas/termos_de_uso.php">Termos de Uso</a>.
                        </p>
                        <button type="button" class="btn btn-primary p-2 waves-effect waves-light" id="btn_modal_aceitar_cookies">Aceitar e Continuar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <header>
        <?php require '../layouts/menu-monitor.php' ?>
    </header>

    <!-- Main Layout -->
    <main class="mx-0">
        <div class="container-fluid">
            <div class="card card-cascade narrower">
                <div class="card-body">
                    <!-- Formulário para criação manual -->
                    <div class="col-md-12 text-center">
                        <h4>Criar um novo serviço manualmente</h4>
                        <form id="createManualServiceForm">
                            <div class="form-group">
                                <label for="office">Escritório</label>
                                <input type="text" id="office" name="office" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="process">Processo</label>
                                <input type="text" id="process" name="process" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="service_name">Nome do Serviço</label>
                                <input type="text" id="service_name" name="service_name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Criar Serviço</button>
                        </form>
                    </div>

                    <!-- Upload de arquivo -->
                    <div class="col-md-12 text-center mt-5">
                        <h4>Criar um novo serviço a partir de um arquivo</h4>
                        <form id="fileUploadForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="file">Selecione o arquivo</label>
                                <input type="file" id="file" name="file" class="form-control" accept=".txt" required>
                            </div>
                            <button type="submit" class="btn btn-success">Enviar Arquivo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="page-footer pt-0 rgba-stylish-light">
        <div class="container-fluid">
            &copy;2025 NextPulse Desenvolvimento de Softwares Ltda
        </div>
    </footer>

    <!-- Scripts -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/jquery.mask.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/mdb.min.js"></script>
    <script src="../js/dataTables.bootstrap4.js"></script>
    <script src="../js/moment.min.js"></script>
    <script>
        // Envio de formulário para criação manual
        document.getElementById('createManualServiceForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = {
                action: 'create_manual',
                office: document.getElementById('office').value,
                process: document.getElementById('process').value,
                service_name: document.getElementById('service_name').value
            };

            fetch('../controllers/novo-servico-backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao criar o serviço.');
                });
        });

        // Envio de formulário para upload de arquivo
        document.getElementById('fileUploadForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'upload_file');

            fetch('../controllers/novo-servico-backend.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar o arquivo.');
                });
        });
    </script>
</body>
</html>