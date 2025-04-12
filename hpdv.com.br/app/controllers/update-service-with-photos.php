<?php
// filepath: c:\xampp1\htdocs\horus_Engenharia-main\horuseng-app\hpdv.com.br\app\controllers\update-service-with-photos.php

require_once '../config/db_connection.php';
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $data_emissao = $_POST['data_emissao'] ?? null;
    $prazo = $_POST['prazo'] ?? null;
    $status = $_POST['status'] ?? null;
    $os = $_POST['os'] ?? null;
    $atividade = $_POST['atividade'] ?? null;
    $escritorio = $_POST['escritorio'] ?? null;
    $cliente = $_POST['cliente'] ?? null;
    $cpf_cnpj_cliente = $_POST['cpf_cnpj_cliente'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $bairro = $_POST['bairro'] ?? null;
    $endereco = $_POST['endereco'] ?? null;
    $numero = $_POST['numero'] ?? null;
    $complemento = $_POST['complemento'] ?? null;
    $cep = $_POST['cep'] ?? null;
    $contato = $_POST['contato'] ?? null;
    $agencia = $_POST['agencia'] ?? null;
    $dados_correspondente = $_POST['dados_correspondente'] ?? null;
    $coordenadas_decimal = $_POST['coordenadas_decimal'] ?? null;
    $coordenadas_gms = $_POST['coordenadas_gms'] ?? null;

    try {
        // Atualizar os dados do serviço
        $query = "UPDATE services SET 
            data_emissao = :data_emissao,
            prazo = :prazo,
            status = :status,
            os = :os,
            atividade = :atividade,
            escritorio = :escritorio,
            cliente = :cliente,
            cpf_cnpj_cliente = :cpf_cnpj_cliente,
            cidade = :cidade,
            bairro = :bairro,
            endereco = :endereco,
            numero = :numero,
            complemento = :complemento,
            cep = :cep,
            contato = :contato,
            agencia = :agencia,
            dados_correspondente = :dados_correspondente,
            coordenadas_decimal = :coordenadas_decimal,
            coordenadas_gms = :coordenadas_gms
        WHERE id = :id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':data_emissao', $data_emissao);
        $stmt->bindParam(':prazo', $prazo);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':os', $os);
        $stmt->bindParam(':atividade', $atividade);
        $stmt->bindParam(':escritorio', $escritorio);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':cpf_cnpj_cliente', $cpf_cnpj_cliente);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':contato', $contato);
        $stmt->bindParam(':agencia', $agencia);
        $stmt->bindParam(':dados_correspondente', $dados_correspondente);
        $stmt->bindParam(':coordenadas_decimal', $coordenadas_decimal);
        $stmt->bindParam(':coordenadas_gms', $coordenadas_gms);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Upload de fotos
        if (!empty($_FILES['photos']['name'][0])) {
            $uploadDir = '../uploads/';
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
                $filename = basename($_FILES['photos']['name'][$key]);
                $targetFile = $uploadDir . $filename;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    $query = "INSERT INTO service_photos (service_id, filename) VALUES (:service_id, :filename)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':service_id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':filename', $filename);
                    $stmt->execute();
                }
            }
        }

        echo json_encode([
            "status" => "success",
            "message" => "Serviço e fotos atualizados com sucesso!"
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Erro ao atualizar o serviço: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método de requisição inválido."
    ]);
}