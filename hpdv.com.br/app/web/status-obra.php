<?php require "../layouts/session.php" ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require '../layouts/head.php' ?>
    <link rel="stylesheet" href="../css/register.css">
    <style>
        .status-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .status-title {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .search-section {
            margin-bottom: 2rem;
        }

        .search-section input {
            padding: 0.5rem;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 0.5rem;
        }

        .search-section button {
            padding: 0.5rem 1rem;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .progress-section, .photos-section {
            margin-bottom: 2rem;
        }

        .progress-bar {
            background-color: #e0e0e0;
            border-radius: 20px;
            overflow: hidden;
            height: 25px;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            text-align: center;
            color: white;
            line-height: 25px;
            font-weight: bold;
        }

        .photo-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .photo-gallery img {
            max-width: 180px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>
<?php require '../layouts/menu.php' ?>
    <div class="status-container">
        <h1 class="status-title">Status da Obra</h1>

        <div class="search-section">
            <form id="formBusca">
                <input type="text" name="codigo_obra" id="obraSearch" placeholder="Digite o nome ou código da obra">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <div class="progress-section">
            <h3>Evolução da Obra</h3>
            <div class="progress-bar">
                <div id="progressObra" class="progress-fill" style="width: 0%; background-color: #4caf50;">0%</div>
            </div>

            <h3>Gastos da Obra</h3>
            <div class="progress-bar">
                <div id="progressGastos" class="progress-fill" style="width: 0%; background-color: #f44336;">0%</div>
            </div>
        </div>

        <div class="photos-section">
            <h3>Fotos da Obra</h3>
            <div class="photo-gallery" id="photoGallery">
                <!-- Fotos serão inseridas aqui via JS -->
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formBusca').addEventListener('submit', async function(e) {
            e.preventDefault();
            const codigo = document.getElementById('obraSearch').value.trim();

            const response = await fetch('../services/get_obra.php?codigo=' + encodeURIComponent(codigo));
            const data = await response.json();

            if (data && data.status === 'ok') {
                atualizarProgresso(data.evolucao, data.gastos);
                atualizarFotos(data.fotos);
            } else {
                alert('Obra não encontrada.');
            }
        });

        function atualizarProgresso(evolucao, gastos) {
            const progresso = document.getElementById('progressObra');
            progresso.style.width = evolucao + '%';
            progresso.innerText = evolucao + '%';

            const gastosElem = document.getElementById('progressGastos');
            gastosElem.style.width = gastos + '%';
            gastosElem.innerText = gastos + '%';
        }

        function atualizarFotos(fotos) {
            const gallery = document.getElementById('photoGallery');
            gallery.innerHTML = '';
            fotos.forEach(src => {
                const img = document.createElement('img');
                img.src = src;
                img.alt = "Foto da obra";
                gallery.appendChild(img);
            });
        }
    </script>

    <script src="../js/_component/validation.js"></script>
    <script src="../js/_component/mask.js"></script>
    <script src="../js/status.js"></script>
</body>

</html>
