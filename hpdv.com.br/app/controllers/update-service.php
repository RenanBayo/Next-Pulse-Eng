<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados
require_once '../config/db_connection.php';
$dbConnection = new DbConnection();
$db = $dbConnection->getConnection();

if (!$db instanceof PDO) {
    die(json_encode([
        "status" => "error",
        "message" => "Erro: A conexão com o banco de dados não foi estabelecida."
    ]));
}

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar se o ID foi enviado
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "ID do serviço não fornecido."
        ]);
        exit;
    }

    $id = intval($_POST['id']);
    $data_emissao = $_POST['data_emissao'] ?? null;
    $prazo = $_POST['prazo'] ?? null;
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
        // Obter os valores antigos do serviço
        $queryOld = "SELECT * FROM services WHERE id = :id";
        $stmtOld = $db->prepare($queryOld);
        $stmtOld->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtOld->execute();
        $oldService = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$oldService) {
            echo json_encode([
                "status" => "error",
                "message" => "Serviço não encontrado."
            ]);
            exit;
        }

        // Atualizar o serviço no banco de dados
        $query = "UPDATE services SET 
            data_emissao = :data_emissao,
            prazo = :prazo,
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

        // Comparar os valores antigos e novos para registrar no histórico
        $alteracoes = [];
        foreach ($_POST as $campo => $novoValor) {
            if (isset($oldService[$campo]) && $oldService[$campo] != $novoValor) {
                $alteracoes[] = ucfirst($campo) . " alterado de '" . $oldService[$campo] . "' para '" . $novoValor . "'";
            }
        }

        if (!empty($alteracoes)) {
            $usuario = "Usuário Atual"; // Substitua pelo nome do usuário logado
            $descricao = implode("; ", $alteracoes);

            $historyQuery = "INSERT INTO service_history (service_id, data_hora, usuario, descricao) 
                             VALUES (:service_id, NOW(), :usuario, :descricao)";
            $historyStmt = $db->prepare($historyQuery);
            $historyStmt->bindParam(':service_id', $id, PDO::PARAM_INT);
            $historyStmt->bindParam(':usuario', $usuario);
            $historyStmt->bindParam(':descricao', $descricao);
            $historyStmt->execute();
        }

        echo json_encode([
            "status" => "success",
            "message" => "Serviço atualizado com sucesso!"
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