<?php
session_start();
require __DIR__ . "/../database/db_connection.php";
require __DIR__ . "/../models/register_obra_model.php";
require __DIR__ . "/../services/register_obra_service.php";

$connect = new DbConnection();
$model_obra = new RegisterObraModel();
$service_obra = new RegisterObraService($connect, $model_obra);

function redirect($msg)
{
    header('Location: ../web/cadastro-obra?' . http_build_query($msg));
    exit();
}

//se o usuário não por imagem no obra, a imagem padrão será essa
function defaultImage()
{
    return 'obra-sem-imagem.webp';
}

//diretório onde as imagens dos obras serão salvas
function savedIamgeDirectory()
{
    return "../assets/img/obras/";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
    $new_obra_values = $_POST;
    $img_obra = $_FILES['img-obra'];

    $csrf_token = $new_obra_values['csrf_token'];
    $action = $new_obra_values['action'];
    $obra_name = $new_obra_values['obra-name'];
    $obra_code = $new_obra_values['obra-code'];
    $obra_supplier = $new_obra_values['obra-supplier'];
    $obra_description = $new_obra_values['obra-description'];
    $obra_qnt = $new_obra_values['obra-qnt'];
    $obra_unit_price = $new_obra_values['obra-unit-price'];
    $obra_sale_price = $new_obra_values['obra-sale-price'];
    $total_price_on_obra = $new_obra_values['total-price-on-obra'];
    $categoria = $new_obra_values['categoria'];

    if (!isset($csrf_token) || $csrf_token !== $_SESSION['csrf_token']) {
        redirect(['error' => 'erro2', "message" => 'Erro de autenticação']);
    } else {

        //adicionar um novo obra
        if (isset($action) && $action === "add_obra") {

            //consultar se o codigo do obra já existe no banco de dados
            $model_obra->__set('codigo_obra', $obra_code);
            $result = $service_obra->searchobraByCode();

            if ($result) {
                redirect(['error' => 'erro3', "message" => 'Código da obra já existe']);
            } else {

                //verificar se o usuário enviou uma imagem, caso contrário, a imagem padrão será usada
                if (empty($img_obra['name'])) {
                    $img_obra = defaultImage();

                    $model_obra->__set('nome_obra', $obra_name);
                    $model_obra->__set('codigo_obra', $obra_code);
                    $model_obra->__set('fornecedor', $obra_supplier);
                    $model_obra->__set('descricao_produto', $obra_description);
                    $model_obra->__set('quantidade_produto', $obra_qnt);
                    $model_obra->__set('preco_unitario_produto', $obra_unit_price);
                    $model_obra->__set('preco_venda_produto', $obra_sale_price);
                    $model_obra->__set('preco_total_em_produto', $total_price_on_obra);
                    $model_obra->__set('imagem_obra', $img_obra);
                    $model_obra->__set('categoria', $categoria);
                    $result =  $service_obra->RegisterObra();

                    if ($result) {
                        redirect(['success' => 'sucesso', "message" => 'obra cadastrada com sucesso']);
                    } else {
                        redirect(['error' => 'erro5', "message" => 'Erro ao cadastrar obra']);
                    }
                } else {

                    $error = array(); //array para armazenar erros

                    // verifica se o arquivo é uma imagem
                    if (!preg_match("/^image\/(jpeg|jpg|png|webp)$/", $img_obra['type'])) {
                        $error[1] = "Formato de imagem inválido. Permitido apenas jpeg, jpg, png e webp";
                        redirect(['error' => 'erro4', "message" => 'Formato de imagem inválido']);
                    }

                    if (count($error) === 0) {

                        $altered_obra_img = $img_obra['name'];
                        $extension = strtolower(substr($altered_obra_img, -4));
                        $new_name_img_obra = md5($altered_obra_img) . "." . $extension;

                        //salva a imagem no diretório
                        move_uploaded_file($img_obra['tmp_name'], savedIamgeDirectory() . $new_name_img_obra);

                        $model_obra->__set('nome_obra', $obra_name);
                        $model_obra->__set('codigo_obra', $obra_code);
                        $model_obra->__set('fornecedor', $obra_supplier);
                        $model_obra->__set('descricao_produto', $obra_description);
                        $model_obra->__set('quantidade_produto', $obra_qnt);
                        $model_obra->__set('preco_unitario_produto', $obra_unit_price);
                        $model_obra->__set('preco_venda_produto', $obra_sale_price);
                        $model_obra->__set('preco_total_em_produto', $total_price_on_obra);
                        $model_obra->__set('imagem_obra', $new_name_img_obra);
                        $result =  $service_obra->RegisterObra();

                        if ($result) {
                            redirect(['success' => 'sucesso', "message" => 'obra cadastrada com sucesso']);
                        } else {
                            redirect(['error' => 'erro5', "message" => 'Erro ao cadastrar obra']);
                        }
                    }
                }
            }
        }
    }
    //atualizar os dados do obra
    if (isset($action) && $action === "update") {

        //consultar se o codigo do obra já existe no banco de dados
        $model_obra->__set('codigo_obra', $obra_code);
        $model_obra->__set('id', $new_obra_values['id']);
        $result = $service_obra->searchobraByCodeUpdate();

        if ($result) {
            redirect(['error' => 'erro3', "message" => 'Código da obra já existe']);
        } else {

            //verificar se o usuário enviou uma imagem, caso contrário, a imagem padrão será usada
            if (empty($img_obra['name'])) {

                $model_obra->__set('nome_obra', $obra_name);
                $model_obra->__set('codigo_obra', $obra_code);
                $model_obra->__set('fornecedor', $obra_supplier);
                $model_obra->__set('descricao_produto', $obra_description);
                $model_obra->__set('quantidade_produto', $obra_qnt);
                $model_obra->__set('preco_unitario_produto', $obra_unit_price);
                $model_obra->__set('preco_venda_produto', $obra_sale_price);
                $model_obra->__set('preco_total_em_produto', $total_price_on_obra);
                $result =  $service_obra->updateobraWithouImage();

                if ($result) {
                    redirect(['success' => 'sucesso', "message" => 'obra atualizada com sucesso']);
                } else {
                    redirect(['error' => 'erro5', "message" => 'Erro ao atualizar obra']);
                }
            } else {

                $error = array(); //array para armazenar erros

                // verifica se o arquivo é uma imagem
                if (!preg_match("/^image\/(jpeg|jpg|png|webp)$/", $img_obra['type'])) {
                    $error[1] = "Formato de imagem inválido. Permitido apenas jpeg, jpg, png e webp";
                    redirect(['error' => 'erro4', "message" => 'Formato de imagem inválido']);
                }

                if (count($error) === 0) {

                    $altered_obra_img = $img_obra['name'];
                    $extension = strtolower(substr($altered_obra_img, -4));
                    $new_name_img_obra = md5($altered_obra_img) . "." . $extension;

                    //salva a imagem no diretório
                    move_uploaded_file($img_obra['tmp_name'], savedIamgeDirectory() . $new_name_img_obra);

                    $model_obra->__set('nome_obra', $obra_name);
                    $model_obra->__set('codigo_obra', $obra_code);
                    $model_obra->__set('fornecedor', $obra_supplier);
                    $model_obra->__set('descricao_produto', $obra_description);
                    $model_obra->__set('quantidade_produto', $obra_qnt);
                    $model_obra->__set('preco_unitario_produto', $obra_unit_price);
                    $model_obra->__set('preco_venda_produto', $obra_sale_price);
                    $model_obra->__set('preco_total_em_produto', $total_price_on_obra);
                    $model_obra->__set('imagem_obra', $new_name_img_obra);

                    $result =  $service_obra->updateobra();

                    if ($result) {
                        redirect(['success' => 'sucesso', "message" => 'obra atualizado com sucesso']);
                    } else {
                        redirect(['error' => 'erro5', "message" => 'Erro ao atualizar obra']);
                    }
                }
            }
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET)) {

    $new_obra_values = json_encode($_GET);
    $new_obra_values_decode = json_decode($new_obra_values);

    $csrf_token = $new_obra_values_decode->csrfToken;
    $action = $new_obra_values_decode->action;

    if (!isset($csrf_token) || $csrf_token !== $_SESSION['csrf_token']) {
        echo json_encode(array("error" => "erro", "message" => "Erro de autenticação "));
    } else {
        //pesquisando obra
        if (isset($action) && $action === "search_obra") {
            $valueSearch = $new_obra_values_decode->valueSearch;

            $model_obra->__set('valueSearch', $valueSearch);
            $result = $service_obra->searchobra();

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(array("error" => "erro2", "message" => "Nenhuma obra encontrado"));
            }
        }
        // deletar obra
        if (isset($action) && $action === "delete") {
            $id = $new_obra_values_decode->id;
            $model_obra->__set('id', $id);
            $result = $service_obra->deleteobra();

            if ($result) {
                echo json_encode(array("success" => "sucesso", "message" => "obra deletada com sucesso"));
            } else {
                echo json_encode(array("error" => "erro3", "message" => "Erro ao deletar obra"));
            }
        }
    }
} else {
    redirect(['error' => 'erro1', "message" => 'Nenhum dado enviado']);
}
