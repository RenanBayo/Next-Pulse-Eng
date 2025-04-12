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
        `                   ;
                        }
                    }
                ]
            });
    // Inicializar o mapa
    let map = L.map('map').setView([-15.7942, -47.8822], 5); // Coordenadas iniciais (Brasil)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker; // Variável para o marcador

            // Abrir modal para visualizar/editar serviço
$('#servicesTable').on('click', '.view-service', function () {
    const id = $(this).data('id');
    $.get(`../controllers/get-service.php?id=${id}`, function (data) {
        const service = JSON.parse(data);
        $('#serviceId').val(service.id);
        $('#data_emissao').val(service.data_emissao);
        $('#prazo').val(service.prazo);
        $('#os').val(service.os);
        $('#atividade').val(service.atividade);
        $('#escritorio').val(service.escritorio);
        $('#cliente').val(service.cliente);
        $('#cpf_cnpj_cliente').val(service.cpf_cnpj_cliente);
        $('#cidade').val(service.cidade);
        $('#bairro').val(service.bairro);
        $('#endereco').val(service.endereco);
        $('#numero').val(service.numero);
        $('#complemento').val(service.complemento);
        $('#cep').val(service.cep);
        $('#contato').val(service.contato);
        $('#agencia').val(service.agencia);
        $('#dados_correspondente').val(service.dados_correspondente);
        $('#coordenadas_decimal').val(service.coordenadas_decimal);
        $('#coordenadas_gms').val(service.coordenadas_gms);

        // Atualizar o mapa com as coordenadas
        if (service.coordenadas_decimal) {
            const coords = service.coordenadas_decimal.split(',');
            const lat = parseFloat(coords[0]);
            const lng = parseFloat(coords[1]);

            // Atualizar a posição do mapa
            map.setView([lat, lng], 15);

            // Adicionar ou mover o marcador
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }

            // Adicionar popup ao marcador
            marker.bindPopup(`
                <strong>Cliente:</strong> ${service.cliente}<br>
                <strong>Cidade:</strong> ${service.cidade}<br>
                <strong>Atividade:</strong> ${service.atividade}
            `).openPopup();
        }

        // Carregar o histórico de alterações
        $.get(`../controllers/get-service-history.php?id=${id}`, function (historyData) {
            const history = JSON.parse(historyData);
            const historyTableBody = $('#historyTable tbody');
            historyTableBody.empty(); // Limpar o histórico anterior

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

        $('#serviceModal').modal('show');
    });
});
});
    </script>