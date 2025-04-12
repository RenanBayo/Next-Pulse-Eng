<?php
require_once "../layouts/session.php";


// Conexão com o banco de dados
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if (!$db instanceof PDO) {
    die("Erro: A conexão com o banco de dados não foi estabelecida.");
}

// Verificar se o ID foi enviado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Erro: ID do serviço não fornecido.'); window.location.href = 'servicos.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// Consultar os detalhes do serviço
try {
    $query = "SELECT * FROM services WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Erro: Serviço não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar os detalhes do serviço: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require '../layouts/head.php'; ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <?php require '../layouts/menu-monitor.php'; ?>
    </header>

    <main class="container mt-5">
        <h2 class="text-center">Detalhes do Serviço</h2>

        <!-- Formulário de Edição -->
        <form id="serviceForm" enctype="multipart/form-data" class="mt-4">
            <input type="hidden" id="serviceId" name="id" value="<?php echo $service['id']; ?>">
            <div class="row">
    <div class="form-group col-md-6">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="Em Execução" <?php echo ($service['status'] === 'Em Execução') ? 'selected' : ''; ?>>Em Execução</option>
            <option value="Pendente" <?php echo ($service['status'] === 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
            <option value="Vistoriado" <?php echo ($service['status'] === 'Vistoriado') ? 'selected' : ''; ?>>Vistoriado</option>
            <option value="Concluído" <?php echo ($service['status'] === 'Concluído') ? 'selected' : ''; ?>>Concluído</option>
            <option value="Pago" <?php echo ($service['status'] === 'Pago') ? 'selected' : ''; ?>>Pago</option>
            <option value="Cancelado" <?php echo ($service['status'] === 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
            <option value="PEPT" <?php echo ($service['status'] === 'PEPT') ? 'selected' : ''; ?>>PEPT</option>
            <option value="Aguardando" <?php echo ($service['status'] === 'Aguardando') ? 'selected' : ''; ?>>Aguardando</option>
            <option value="Baixado" <?php echo ($service['status'] === 'Baixado') ? 'selected' : ''; ?>>Baixado</option>
        </select>
    </div>
</div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="data_emissao">Data de Emissão</label>
                    <input type="date" id="data_emissao" name="data_emissao" class="form-control" value="<?php echo $service['data_emissao']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="prazo">Prazo</label>
                    <input type="text" id="prazo" name="prazo" class="form-control" value="<?php echo $service['prazo']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="os">O.S</label>
                    <input type="text" id="os" name="os" class="form-control" value="<?php echo $service['os']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="atividade">Atividade</label>
                    <input type="text" id="atividade" name="atividade" class="form-control" value="<?php echo $service['atividade']; ?>">
                </div>
            </div>

            <div class="row">
    <div class="form-group col-md-6">
        <label for="escritorio">Escritório</label>
        <select id="escritorio" name="escritorio" class="form-control">
            <?php
            // Consultar os escritórios no banco de dados
            try {
                $queryEscritorios = "SELECT id, nome_fantasia FROM escritorios ORDER BY nome_fantasia ASC";
                $stmtEscritorios = $db->prepare($queryEscritorios);
                $stmtEscritorios->execute();
                $escritorios = $stmtEscritorios->fetchAll(PDO::FETCH_ASSOC);

                foreach ($escritorios as $escritorio) {
                    $selected = ($service['escritorio'] == $escritorio['id']) ? 'selected' : '';
                    echo "<option value='{$escritorio['id']}' $selected>{$escritorio['nome_fantasia']}</option>";
                }
            } catch (PDOException $e) {
                echo "<option value=''>Erro ao carregar escritórios</option>";
            }
            ?>
        </select>
    </div>
</div>
                <div class="form-group col-md-6">
                    <label for="cliente">Cliente</label>
                    <input type="text" id="cliente" name="cliente" class="form-control" value="<?php echo $service['cliente']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="cpf_cnpj_cliente">CPF/CNPJ do Cliente</label>
                    <input type="text" id="cpf_cnpj_cliente" name="cpf_cnpj_cliente" class="form-control" value="<?php echo $service['cpf_cnpj_cliente']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="form-control" value="<?php echo $service['cidade']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro" class="form-control" value="<?php echo $service['bairro']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" class="form-control" value="<?php echo $service['endereco']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="numero">Número</label>
                    <input type="text" id="numero" name="numero" class="form-control" value="<?php echo $service['numero']; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="complemento">Complemento</label>
                    <input type="text" id="complemento" name="complemento" class="form-control" value="<?php echo $service['complemento']; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" class="form-control" value="<?php echo $service['cep']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="contato">Contato</label>
                    <input type="text" id="contato" name="contato" class="form-control" value="<?php echo $service['contato']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="agencia">Agência</label>
                    <input type="text" id="agencia" name="agencia" class="form-control" value="<?php echo $service['agencia']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="dados_correspondente">Dados do Correspondente</label>
                <textarea id="dados_correspondente" name="dados_correspondente" class="form-control"><?php echo $service['dados_correspondente']; ?></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="coordenadas_decimal">Coordenadas Decimal</label>
                    <input type="text" id="coordenadas_decimal" name="coordenadas_decimal" class="form-control" value="<?php echo $service['coordenadas_decimal']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="coordenadas_gms">Coordenadas GMS</label>
                    <input type="text" id="coordenadas_gms" name="coordenadas_gms" class="form-control" value="<?php echo $service['coordenadas_gms']; ?>">
                </div>
            </div>
            <div class="row mt-4">
            <!-- Relatório Fotográfico -->
            <div class="col-md-12">
                <p1 class="text-center">Relatório Fotográfico</p1>
                <div id="photoGallery" class="mt-4 row">
                    <!-- Miniaturas das imagens serão exibidas aqui -->
                </div>
            </div>
        </div>
            <div class="form-group">
                <label for="photoUpload">Adicionar Fotos</label>
                <input type="file" id="photoUpload" name="photos[]" class="form-control" multiple accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>

        <div class="row mt-4">
            <!-- Coluna do Mapa -->
            <div class="col-md-6">
                <h5>Localização</h5>
                <div id="map" style="height: 300px;"></div>
            </div>

            <!-- Coluna do Histórico -->
            <div class="col-md-6">
                <h5>Histórico de Alterações</h5>
                <table id="historyTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Data e Hora</th>
                            <th>Usuário</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- O histórico será carregado via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

       
    </main>

    <script>
        $(document).ready(function () {
            // Inicializar o mapa
            const map = L.map('map').setView([-15.7942, -47.8822], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const coords = "<?php echo $service['coordenadas_decimal']; ?>".split(',');
            if (coords.length === 2) {
                const lat = parseFloat(coords[0]);
                const lng = parseFloat(coords[1]);
                L.marker([lat, lng]).addTo(map).bindPopup("Localização do Serviço").openPopup();
                map.setView([lat, lng], 15);
            }

            const serviceId = $('#serviceId').val();

            // Carregar histórico
            $.get(`../controllers/get-service-history.php?id=${serviceId}`, function (historyData) {
                const history = JSON.parse(historyData);
                const historyTableBody = $('#historyTable tbody');
                historyTableBody.empty();

                history.forEach(item => {
                    historyTableBody.append(`
                        <tr>
                            <td>${item.data_hora}</td>
                            <td>${item.usuario}</td>
                            <td>${item.descricao}</td>
                        </tr>
                    `);
                });
            });

            // Carregar fotos
            function loadPhotos() {
                $.get(`../controllers/get-photos.php?service_id=${serviceId}`, function (response) {
                    const photos = JSON.parse(response);
                    const photoGallery = $('#photoGallery');
                    photoGallery.empty();

                    photos.forEach(photo => {
                        photoGallery.append(`
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="../uploads/${photo.filename}" class="card-img-top" alt="Foto">
                                    <div class="card-body text-center">
                                        <button class="btn btn-danger btn-sm delete-photo" data-id="${photo.id}">Excluir</button>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                });
            }

            $('#photoGallery').on('click', '.delete-photo', function () {
                const photoId = $(this).data('id');
                if (confirm('Tem certeza de que deseja excluir esta foto?')) {
                    $.post('../controllers/delete-photo.php', { id: photoId }, function (response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            loadPhotos();
                        } else {
                            alert(result.message);
                        }
                    });
                }
            });

            loadPhotos();

            // Salvar alterações
            $('#serviceForm').on('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: '../controllers/update-service-with-photos.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            loadPhotos();
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function () {
                        alert('Erro ao salvar as alterações.');
                    }
                });
            });
        });
    </script>
</body>
</html>