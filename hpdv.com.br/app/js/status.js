function fetchProjectData(codigo) {
    return fetch('../services/get_obra.php?codigo=' + encodeURIComponent(codigo))
        .then(response => response.json())
        .then(data => {
            if (data && data.status === 'ok') {
                console.log('Dados da obra carregados com sucesso.');
                return data;
            } else {
                throw new Error('Obra não encontrada.');
            }
        })
        .catch(error => {
            console.error('Erro ao buscar dados da obra:', error.message);
            alert('Erro: ' + error.message);
        });
}

function updateProgress(evolucao, gastos) {
    try {
        const progresso = document.getElementById('progressObra');
        progresso.style.width = evolucao + '%';
        progresso.innerText = evolucao + '%';

        const gastosElem = document.getElementById('progressGastos');
        gastosElem.style.width = gastos + '%';
        gastosElem.innerText = gastos + '%';

        console.log('Progresso atualizado com sucesso.');
    } catch (error) {
        console.error('Erro ao atualizar progresso:', error.message);
        alert('Erro ao atualizar progresso.');
    }
}

function updatePhotos(fotos) {
    try {
        const gallery = document.getElementById('photoGallery');
        gallery.innerHTML = '';
        fotos.forEach(src => {
            const img = document.createElement('img');
            img.src = src;
            img.alt = "Foto da obra";
            gallery.appendChild(img);
        });

        console.log('Fotos atualizadas com sucesso.');
    } catch (error) {
        console.error('Erro ao atualizar fotos:', error.message);
        alert('Erro ao atualizar fotos.');
    }
}