<?php
session_start();
require __DIR__ . "/../database/db_connection.php";
require __DIR__ . "/../models/register_service_model.php";
require __DIR__ . "/../services/register_service_service.php";

$connect = new DbConnection();
$model_service = new ServiceModel();
$service_service = new ServiceService($connect, $model_service);

function redirect($msg)
{
    header('Location: ../web/novo-servico?' . http_build_query($msg));
    exit();
}

//diretório onde as imagens dos serviços serão salvas
function savedImageDirectory()
{
    return "../assets/img/services/";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
    $new_service_values = $_POST;
    $img_service = $_FILES['img-service'];

    $csrf_token = $new_service_values['csrf_token'];
    $action = $new_service_values['action'];
    $service_name = $new_service_values['service-name'];
    $service_code = $new_service_values['service-code'];
    $service_description = $new_service_values['service-description'];
    $service_price = $new_service_values['service-price'];

    if (!isset($csrf_token) || $csrf_token !== $_SESSION['csrf_token']) {
        redirect(['error' => 'erro2', "message" => 'Erro de autenticação']);
    } else {

        // Adicionar um novo serviço
        if (isset($action) && $action === "add_service") {

            // Consultar se o código do serviço já existe no banco de dados
            $model_service->__set('codigo_servico', $service_code);
            $result = $service_service->searchServiceByCode();

            if ($result) {
                redirect(['error' => 'erro3', "message" => 'Código do serviço já existe']);
            } else {

                // Verificar se o usuário enviou uma imagem, caso contrário, a imagem padrão será usada
                if (empty($img_service['name'])) {
                    $img_service = 'servico-sem-imagem.webp'; // imagem padrão

                    $model_service->__set('nome_servico', $service_name);
                    $model_service->__set('codigo_servico', $service_code);
                    $model_service->__set('descricao_servico', $service_description);
                    $model_service->__set('preco_servico', $service_price);
                    $model_service->__set('imagem_servico', $img_service);
                    $result =  $service_service->registerService();

                    if ($result) {
                        redirect(['success' => 'sucesso', "message" => 'Serviço cadastrado com sucesso']);
                    } else {
                        redirect(['error' => 'erro5', "message" => 'Erro ao cadastrar serviço']);
                    }
                } else {

                    $error = array(); // array para armazenar erros

                    // Verifica se o arquivo é uma imagem
                    if (!preg_match("/^image\/(jpeg|jpg|png|webp)$/", $img_service['type'])) {
                        $error[1] = "Formato de imagem inválido. Permitido apenas jpeg, jpg, png e webp";
                        redirect(['error' => 'erro4', "message" => 'Formato de imagem inválido']);
                    }

                    if (count($error) === 0) {

                        $altered_service_img = $img_service['name'];
                        $extension = strtolower(substr($altered_service_img, -4));
                        $new_name_img_service = md5($altered_service_img) . "." . $extension;

                        // Salva a imagem no diretório
                        move_uploaded_file($img_service['tmp_name'], savedImageDirectory() . $new_name_img_service);

                        $model_service->__set('nome_servico', $service_name);
                        $model_service->__set('codigo_servico', $service_code);
                        $model_service->__set('descricao_servico', $service_description);
                        $model_service->__set('preco_servico', $service_price);
                        $model_service->__set('imagem_servico', $new_name_img_service);
                        $result =  $service_service->registerService();

                        if ($result) {
                            redirect(['success' => 'sucesso', "message" => 'Serviço cadastrado com sucesso']);
                        } else {
                            redirect(['error' => 'erro5', "message" => 'Erro ao cadastrar serviço']);
                        }
                    }
                }
            }
        }
    }

    // Atualizar os dados do serviço
    if (isset($action) && $action === "update") {

        // Consultar se o código do serviço já existe no banco de dados
        $model_service->__set('ordem_servico', $service_code);
        $model_service->__set('id', $new_service_values['id']);
        $result = $service_service->searchServiceByCodeUpdate();

        if ($result) {
            redirect(['error' => 'erro3', "message" => 'Código do serviço já existe']);
        } else {

            // Verificar se o usuário enviou uma imagem, caso contrário, a imagem padrão será usada
            if (empty($img_service['name'])) {

                $model_service->__set('nome_servico', $service_name);
                $model_service->__set('codigo_servico', $service_code);
                $model_service->__set('descricao_servico', $service_description);
                $model_service->__set('preco_servico', $service_price);
                $result =  $service_service->updateServiceWithoutImage();

                if ($result) {
                    redirect(['success' => 'sucesso', "message" => 'Serviço atualizado com sucesso']);
                } else {
                    redirect(['error' => 'erro5', "message" => 'Erro ao atualizar serviço']);
                }
            } else {

                $error = array(); // array para armazenar erros

                // Verifica se o arquivo é uma imagem
                if (!preg_match("/^image\/(jpeg|jpg|png|webp)$/", $img_service['type'])) {
                    $error[1] = "Formato de imagem inválido. Permitido apenas jpeg, jpg, png e webp";
                    redirect(['error' => 'erro4', "message" => 'Formato de imagem inválido']);
                }

                if (count($error) === 0) {

                    $altered_service_img = $img_service['name'];
                    $extension = strtolower(substr($altered_service_img, -4));
                    $new_name_img_service = md5($altered_service_img) . "." . $extension;

                    // Salva a imagem no diretório
                    move_uploaded_file($img_service['tmp_name'], savedImageDirectory() . $new_name_img_service);

                    $model_service->__set('nome_servico', $service_name);
                    $model_service->__set('codigo_servico', $service_code);
                    $model_service->__set('descricao_servico', $service_description);
                    $model_service->__set('preco_servico', $service_price);
                    $model_service->__set('imagem_servico', $new_name_img_service);

                    $result =  $service_service->updateService();

                    if ($result) {
                        redirect(['success' => 'sucesso', "message" => 'Serviço atualizado com sucesso']);
                    } else {
                        redirect(['error' => 'erro5', "message" => 'Erro ao atualizar serviço']);
                    }
                }
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET)) {

    $new_service_values = json_encode($_GET);
    $new_service_values_decode = json_decode($new_service_values);

    $csrf_token = $new_service_values_decode->csrfToken;
    $action = $new_service_values_decode->action;

    if (!isset($csrf_token) || $csrf_token !== $_SESSION['csrf_token']) {
        echo json_encode(array("error" => "erro", "message" => "Erro de autenticação"));
    } else {
        // Pesquisando serviço
        if (isset($action) && $action === "search_service") {
            $valueSearch = $new_service_values_decode->valueSearch;

            $model_service->__set('valueSearch', $valueSearch);
            $result = $service_service->searchService();

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(array("error" => "erro2", "message" => "Nenhum serviço encontrado"));
            }
        }
        // Deletar serviço
        if (isset($action) && $action === "delete") {
            $id = $new_service_values_decode->id;
            $model_service->__set('id', $id);
            $result = $service_service->deleteService();

            if ($result) {
                echo json_encode(array("success" => "sucesso", "message" => "Serviço deletado com sucesso"));
            } else {
                echo json_encode(array("error" => "erro3", "message" => "Erro ao deletar serviço"));
            }
        }
    }
} else {
    redirect(['error' => 'erro1', "message" => 'Nenhum dado enviado']);
}
