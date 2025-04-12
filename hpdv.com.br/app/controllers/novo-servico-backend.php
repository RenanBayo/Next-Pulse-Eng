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
    die("Erro: A conexão com o banco de dados não foi estabelecida.");
}

$response = [
    "status" => "error",
    "message" => "Ação inválida."
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'upload_file') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $fileType = $_FILES['file']['type'];

            $allowedFileTypes = ['text/plain'];
            if (in_array($fileType, $allowedFileTypes)) {
                try {
                    $fileContent = file_get_contents($fileTmpPath);
                    $lines = explode(PHP_EOL, $fileContent);

                    $data = [
                        'data_emissao' => '',
                        'prazo' => '',
                        'os' => '',
                        'atividade' => '',
                        'escritorio' => '',
                        'cliente' => '',
                        'cpf_cnpj_cliente' => '',
                        'cidade' => '',
                        'bairro' => '',
                        'endereco' => '',
                        'numero' => '',
                        'complemento' => '',
                        'cep' => '',
                        'contato' => '',
                        'agencia' => '',
                        'dados_correspondente' => '',
                        'coordenadas_decimal' => '',
                        'coordenadas_gms' => '',
                    ];

                    foreach ($lines as $line) {
                        $line = trim($line);

                        if (strpos($line, 'Data da Ordem de Serviço......:') === 0) {
                            $rawDate = trim(substr($line, 30));
                            $dateParts = date_parse_from_format('d/m/Y', $rawDate);
                            if ($dateParts['error_count'] === 0) {
                                $data['data_emissao'] = sprintf('%04d-%02d-%02d', $dateParts['year'], $dateParts['month'], $dateParts['day']);
                            } else {
                                $data['data_emissao'] = null;
                            }
                        } elseif (strpos($line, 'Prazo de execução.............:') === 0) {
                            $data['prazo'] = substr($line, 30);
                        } elseif (strpos($line, 'Referência:') === 0) {
                            $data['os'] = substr($line, 12);
                        } elseif (strpos($line, 'Atividade:') === 0) {
                            $data['atividade'] = substr($line, 10);
                        } elseif (strpos($line, 'Empresa .:') === 0) {
                            $data['escritorio'] = substr($line, 10);
                        } elseif (strpos($line, 'Cliente .:') === 0) {
                            $data['cliente'] = substr($line, 10);
                        } elseif (strpos($line, 'CNPJ ....:') === 0 || strpos($line, 'CPF ....:') === 0) {
                            $data['cpf_cnpj_cliente'] = substr($line, 10);
                        } elseif (strpos($line, 'Cidade/UF:') === 0) {
                            $data['cidade'] = substr($line, 10);
                        } elseif (strpos($line, 'Bairro...:') === 0) {
                            $data['bairro'] = substr($line, 10);
                        } elseif (strpos($line, 'Endereço.:') === 0) {
                            $data['endereco'] = substr($line, 10);
                        } elseif (strpos($line, 'Número...:') === 0) {
                            $data['numero'] = substr($line, 10);
                        } elseif (strpos($line, 'Complemento:') === 0) {
                            $data['complemento'] = substr($line, 12);
                        } elseif (strpos($line, 'CEP......:') === 0) {
                            $data['cep'] = substr($line, 10);
                        } elseif (strpos($line, 'Nome do contato.:') === 0) {
                            $data['contato'] = substr($line, 18);
                        } elseif (strpos($line, 'Agência...:') === 0) {
                            $data['agencia'] = substr($line, 10);
                        } elseif (strpos($line, 'Dados do Correspondente:') === 0) {
                            $data['dados_correspondente'] = substr($line, 25);
                        } elseif (strpos($line, 'Coordenadas Decimal:') === 0) {
                            $data['coordenadas_decimal'] = substr($line, 21);
                        } elseif (strpos($line, 'Coordenadas GMS:') === 0) {
                            $data['coordenadas_gms'] = substr($line, 18);
                        }
                    }

                    foreach ($data as $key => $value) {
                        if (empty($value)) {
                            $data[$key] = null;
                        }
                    }

                    $query = "INSERT INTO services (
                        data_emissao, prazo, os, atividade, escritorio, cliente, cpf_cnpj_cliente, cidade, bairro, endereco, numero, complemento, cep, contato, agencia, dados_correspondente, coordenadas_decimal, coordenadas_gms, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        $data['data_emissao'],
                        $data['prazo'],
                        $data['os'],
                        $data['atividade'],
                        $data['escritorio'],
                        $data['cliente'],
                        $data['cpf_cnpj_cliente'],
                        $data['cidade'],
                        $data['bairro'],
                        $data['endereco'],
                        $data['numero'],
                        $data['complemento'],
                        $data['cep'],
                        $data['contato'],
                        $data['agencia'],
                        $data['dados_correspondente'],
                        $data['coordenadas_decimal'],
                        $data['coordenadas_gms']
                    ]);

                    $response = [
                        "status" => "success",
                        "message" => "Serviço criado com sucesso!"
                    ];
                } catch (PDOException $e) {
                    error_log("Erro ao criar o serviço: " . $e->getMessage());
                    $response = [
                        "status" => "error",
                        "message" => "Erro ao criar o serviço: " . $e->getMessage()
                    ];
                }
            } else {
                $response = [
                    "status" => "error",
                    "message" => "Tipo de arquivo inválido. Apenas arquivos TXT são permitidos."
                ];
            }
        } else {
            $response = [
                "status" => "error",
                "message" => "Erro ao fazer upload do arquivo."
            ];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);