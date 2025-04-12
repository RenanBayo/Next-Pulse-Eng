<?php 
require "../layouts/session.php";
require_once '../controllers/db_connection.php';
$connect = (new DbConnection())->getConnection();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require '../layouts/head.php'; ?>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <?php require '../layouts/menu.php'; ?>
    <main class="container">
        <form method="post" id="formCadService" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="action" value="add_service">
            
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="ordem-servico">Ordem de Serviço</label>
                    <input type="text" class="form-control" id="ordem-servico" name="ordem-servico" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="atividade">Atividade</label>
                    <input type="text" class="form-control" id="atividade" name="atividade" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="escritorio">Escritório</label>
                    <input type="text" class="form-control" id="escritorio" name="escritorio" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="cliente">Cliente</label>
                    <input type="text" class="form-control" id="cliente" name="cliente" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="cidade">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="bairro">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="numero">Número</label>
                    <input type="number" class="form-control" id="numero" name="numero" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="complemento">Complemento</label>
                    <input type="text" class="form-control" id="complemento" name="complemento" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="cep">CEP</label>
                    <input type="text" class="form-control" id="cep" name="cep" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="contato">Contato</label>
                    <input type="text" class="form-control" id="contato" name="contato" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="resp-tecnico">Responsável Técnico</label>
                    <input type="text" class="form-control" id="resp-tecnico" name="resp-tecnico" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="resp-legal">Responsável Legal</label>
                    <input type="text" class="form-control" id="resp-legal" name="resp-legal" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="valor">Valor</label>
                    <input type="number" class="form-control" id="valor" name="valor" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="deslocamento">Deslocamento</label>
                    <input type="number" class="form-control" id="deslocamento" name="deslocamento" required>
                </div>
            </div>

            <div class="mt-3 text-center">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </form>
    </main>
</body>
<script src="../js/_component/validation.js"></script>
<script src="../js/_component/mask.js"></script>
<script src="../js/register.js"></script>
</html>
