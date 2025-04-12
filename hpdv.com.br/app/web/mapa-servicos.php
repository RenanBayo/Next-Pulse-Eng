<?php require "../layouts/session.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require '../layouts/head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #map {
            height: 500px;
            margin-top: 20px;
        }
        .filter-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Main Navigation -->
    <header>
        <?php require '../layouts/menu-monitor.php'; ?>
    </header>

    <!-- Main Layout -->
    <main class="container">
        <h2 class="text-center mb-4">Mapa de Serviços</h2>

        <!-- Filtros -->
        <div class="filter-container">
            <form id="filterForm" class="form-inline justify-content-center">
                <div class="form-group mx-2">
                    <label for="status" class="mr-2">Status:</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluido">Concluído</option>
                    </select>
                </div>
                <div class="form-group mx-2">
                    <label for="cliente" class="mr-2">Cliente:</label>
                    <input type="text" id="cliente" name="cliente" class="form-control" placeholder="Nome do Cliente">
                </div>
                <div class="form-group mx-2">
                    <label for="cidade" class="mr-2">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" class="form-control" placeholder="Nome da Cidade">
                </div>
                <button type="submit" class="btn btn-primary mx-2">Filtrar</button>
            </form>
        </div>

        <!-- Mapa -->
        <div id="map"></div>
    </main>

    <script>
        $(document).ready(function () {
            // Inicializar o mapa
            const map = L.map('map').setView([-15.7942, -47.8822], 5); // Coordenadas iniciais (Brasil)

            // Adicionar camada de mapa (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Função para carregar os serviços no mapa
            function loadServices(filters = {}) {
                $.ajax({
                    url: '../controllers/get-services-map.php',
                    method: 'GET',
                    data: filters,
                    success: function (response) {
                        const services = JSON.parse(response);

                        // Limpar marcadores existentes
                        map.eachLayer(function (layer) {
                            if (layer instanceof L.Marker) {
                                map.removeLayer(layer);
                            }
                        });

                        // Adicionar marcadores no mapa
                        services.forEach(service => {
                            if (service.coordenadas_decimal) {
                                const coords = service.coordenadas_decimal.split(',');
                                const marker = L.marker([parseFloat(coords[0]), parseFloat(coords[1])]).addTo(map);
                                marker.bindPopup(`
                                    <strong>Cliente:</strong> ${service.cliente}<br>
                                    <strong>Cidade:</strong> ${service.cidade}<br>
                                    <strong>Status:</strong> ${service.status}<br>
                                    <strong>Atividade:</strong> ${service.atividade}
                                `);
                            }
                        });
                    },
                    error: function () {
                        alert('Erro ao carregar os serviços no mapa.');
                    }
                });
            }

            // Carregar serviços ao carregar a página
            loadServices();

            // Aplicar filtros
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                const filters = {
                    status: $('#status').val(),
                    cliente: $('#cliente').val(),
                    cidade: $('#cidade').val()
                };
                loadServices(filters);
            });
        });
    </script>
</body>
</html>