<?php require "../layouts/session.php" ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require '../layouts/head.php' ?>
    <link rel="stylesheet" href="../css/register.css">

</head>

<body>
    <?php require '../layouts/menu.php' ?>
    <main>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 center">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="container-box">
                                        <div class="col-md-6">
                                            <div class="col-md-4 card-box ">
                                                <a href="#" onclick="display('modal-cad-obra')">
                                                    <i class="fa-solid fa-person-digging"></i>
                                                    Nova Obra</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-4 card-box">
                                                <a href="#" onclick="display('modal-search-obra')">
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                    Pesquisar Obra</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-4 card-box">
                                            <a href="status-obra">
                                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                                    Status da Obra</a>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade bd-example-modal-lg show" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-cad-obra" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="container">
                        <div class="modal-title">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="row">
                            <form action="#" method="post" id="formAddNewProvider">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" tabindex="-1">
                                <input type="hidden" name="action" value="add_obra" tabindex="-1">
                                <div class="row">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="obra-name" id="obra-name" placeholder="Nome para identificação da Obra" required>
                                        <label for="obra-name" class="required-field-label">Nome da Obra</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-floating col-md-6">
                                        <input type="text" id="client-name" name="client-name" title="Cliente" class="form-control" placeholder="Cliente" required>
                                        <label for="client-name" class="required-field-label">Cliente</label>
                                    </div>
                                    <div class="form-floating col-md-6">
                                        <input type="text" id="codigo-obra" name="codigo-obra" title="Código da Obra" class="form-control" placeholder="Código da Obra" required>
                                        <label for="codigo-obra" class="required-field-label">Código da Obra</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-floating col-md-4">
                                        <input type="text" id="cep" name="cep" class="form-control" title="CEP" onchange="getAddressByCep(this.value)" placeholder="CEP" maxlength="9" required>
                                        <label for="cep" class="required-field-label" title="CEP">CEP</label>
                                    </div>

                                    <div class="form-floating col-md-4">
                                        <input type="text" id="city" name="city" title="Cidade" class="form-control texto-input" placeholder="Cidade" required>
                                        <label for="city" class="required-field-label" title="Cidade">Cidade</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select id="state" name="state" class="form-select form-control" title="UF" required>
                                            <option selected="">UF</option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PR">Paraná</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="TO">Tocantins</option>
                                            <option value="EX">Estrangeiro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-floating col-md-6">
                                        <input type="text" id="address" name="address" class="form-control" title="Endereço" placeholder="endereco" required>
                                        <label for="address" class="required-field-label" title="Endereço">Endereço</label>
                                    </div>
                                    <div class="form-floating col-md-6">
                                        <input type="text" id="neighborhood" name="neighborhood" class="form-control" title="Bairro" placeholder="Bairro" required>
                                        <label for="neighborhood" class="required-field-label" title="Bairro">Bairro</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-floating col-md-4">
                                        <input type="text" id="street-complement" name="street-complement" title="Complemento" class="form-control" placeholder="Complemento">
                                        <label for="street-complement" title="Complemento">Complemento</label>
                                    </div>

                                    <div class="form-floating col-md-4">
                                        <input type="text" id="number" name="number" title="Número" class="form-control texto-input-num" placeholder="Número" required>
                                        <label for="number" class="required-field-label" title="Número">Número</label>
                                    </div>

                                    <div class="form-floating col-md-4">
                                        <input type="text" id="reference-point" name="reference-point" title="Ponto de Referência" class="form-control" placeholder="Ponto de Referência">
                                        <label for="reference-point" title="Ponto de Referência">Ponto de Referência</label>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary" id="btnSend">Salvar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg show" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-search-obra" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="container">
                        <div class="modal-title">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="row">
                            <form action="#" method="post" id="formSearchProvider">
                                <input type="hidden" name="csrf_token_search" value="<?= $_SESSION['csrf_token'] ?>" tabindex="-1">
                                <input type="hidden" name="action_search" value="search_provider" tabindex="-1">
                                <div id="container-search">
                                    <label for="pesquisar-produto" class="label-search">
                                        <input type="search" name="search-provider" id="search-provider" class="form-control" placeholder="Pesquise pelo nome ou reference do fornecedor" required>
                                        <div class="icon-search">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <button type="reset" class="btn-close" id="btn-close-search-provider"></button>
                                    </label>
                                </div>
                            </form>
                            <div class="table-responsive d-none">
                                <table class="table table-hover mt-4" id="result-search">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="../js/_component/validation.js"></script>
<script src="../js/_component/mask.js"></script>
<script src="../js/register.js"></script>

</html>