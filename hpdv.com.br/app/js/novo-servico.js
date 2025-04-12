$('#createManualServiceForm').on('submit', function (e) {
    e.preventDefault();

    const formData = {
        action: 'create_manual',
        office: $('#office').val(),
        process: $('#process').val(),
        service_name: $('#service_name').val()
    };

    $.post('../controllers/novo-servico-backend.php', formData, function (response) {
        if (response.status === 'success') {
            alert(response.message);
            location.reload();
        } else {
            alert(response.message);
        }
    }, 'json');
});

$('#fileUploadForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'upload_file');

    $.ajax({
        url: '../controllers/novo-servico-backend.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 'success') {
                alert(response.message);
                location.reload();
            } else {
                alert(response.message);
            }
        },
        dataType: 'json'
    });
});