<?php require "../layouts/session.php" ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require '../layouts/head.php' ?>
    <link rel="stylesheet" href="../css/pericias.css">
    <link rel="stylesheet" href="../css/global.css">
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for (let registration of registrations) {
                    registration.unregister();
                }
            });
        }
    </script>
</head>
<body id="monitor" class="servico pericias fixed-sn white-skin">
    <!-- Loading Modal -->
    <div class="modal fade show" id="carregando" tabindex="-1" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog cascading-modal text-center" role="document">
            <div class="preloader-wrapper medium active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <div class="modal-body white-text"></div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="ModalSiaupro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-warning text-center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                </div>
                <div id="modal-body" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="modal_btn_cancel btn btn-danger" style="display: none;">Fechar</button>
                    <button type="button" data-dismiss="modal" class="modal_btn_ok btn btn-warning">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cookie Consent Modal -->
    <div class="modal fade bottom" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-bottom" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row d-flex justify-content-center align-items-center">
                        <p class="p-3">
                            O monitor utiliza cookies para identificar os logins, analisar estatísticas de acesso e informações de publicidade e marketing. <br/>
                            Saiba mais detalhes em nossa 
                            <a class="cookies_inside_modal" target="_blank" href="https://app.siaupro.com.br/politicas/cookies">Política de Cookies</a>, 
                            <a class="privacidade_inside_modal" target="_blank" href="https://app.siaupro.com.br/politicas/privacidade">Política de Privacidade</a> e 
                            <a class="termos_de_uso_inside_modal" target="_blank" href="https://app.siaupro.com.br/politicas/termos_de_uso">Termos de Uso</a>.
                        </p>
                        <button type="button" class="btn btn-primary p-2" id="btn_modal_aceitar_cookies">Aceitar e Continuar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <header>
        <?php require '../layouts/menu-monitor.php' ?>
    </header>

    <!-- Main Layout -->
    <main class="mx-0">
        <div class="container-fluid">
            <div class="card card-cascade narrower mt-3">
                <div class="text-center pt-2 col-lg-12 p-3">
                    <div class="row gradiente-metal pt-4">
                        <input type="hidden" id="hdn_usuario" value="" />
                        <input type="hidden" id="processo" value="QVhrQzR3Q2w0WkZLcFQ0MC9hbkxUZz09" />

                        <!-- Search -->
                        <div class="col-md-4 col-sm-4">
                            <div class="md-form form-sm">
                                <input class="form-control" placeholder=" " type="text" name="pesquisa" id="pesquisa" value=""/>
                                <label for="pesquisa"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</label>
                            </div>
                        </div>

                        <!-- Status Geral -->
                        <div class="col-md-4 col-sm-4">
                            <div class="md-form form-sm" id="div_status_pericia">
                                <label for='status_pericia'>Status Geral</label>
                                <select class="mdb-select colorful-select dropdown-info" name="status_pericia" id="status_pericia" multiple="multiple"
                                searchable="Pesquisar..." data-label-no-search-results="sem resultados" data-label-select-all="Todos" data-label-options-selected="selecionados">
                                    <option value="nomeado">Nomeado</option>
                                    <option value="intimado">Intimado</option>
                                    <option value="concluido">Concluido</option>
                                    <option value="contestado">Contestado</option>
                                    <option value="cancelado">Cancelado</option>
                                    <option value="vistoriados">Vistoriados</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="executando" selected="selected">Em Execução</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Filters -->
                        <div class="col-md-2 col-sm-4">
                            <div class="md-form form-sm">
                                <select class="mdb-select colorful-select dropdown-info" name="tipo_data" id="tipo_data">
                                    <option value="todas">Todas</option>
                                    <option value="emissao">Emissão</option>
                                </select>
                                <label for="tipo_data"><i class="fa fa-calendar" aria-hidden="true"></i> Datas</label>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-1 col-sm-4 px-2">
                            <div class="md-form form-sm filtro_periodo_data">
                                <input placeholder=" " value="01/02/2025" type="text" class="form-control datepicker" name="data_inicial" id="data_inicial" data-tipo="data" data-mask="00/00/0000" data-mask-clearifnotmatch="true"/>
                                <label for="data_inicial"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Inicial <a style='cursor: pointer;' class='a_link_datepicker'><span class='caret' title='Abrir Calendário'><i class="fa fa-calendar blue-text"></i></span></a></label>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-4 px-2">
                            <div class="md-form form-sm filtro_periodo_data">
                                <input placeholder=" " value="21/02/2025" type="text" class="form-control datepicker" name="data_final" id="data_final" data-tipo="data" data-mask="00/00/0000" data-mask-clearifnotmatch="true"/>
                                <label for="data_final"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Final <a style='cursor: pointer;' class='a_link_datepicker'><span class='caret' title='Abrir Calendário'><i class="fa fa-calendar blue-text"></i></span></a></label>
                            </div>
                        </div>

                        <!-- Filter Button -->
                        <div class="row col-12">
                            <div class="col-sm-12 col-md-4 text-center p-0"></div>
                            <div class="col-sm-12 col-md-4 text-center p-0">
                                <button id="btn_servico_todos_filtrar" class="btn btn-sm btn-primary">
                                    <i class="fa fa-search"></i> Filtrar Serviços
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-4 text-center p-0">
                                <a title="Nova Perícia" href="https://app.siaupro.com.br/servico/novo/QVhrQzR3Q2w0WkZLcFQ0MC9hbkxUZz09" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Nova Perícia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Div -->
                <div id="div_msg" class="text-center py-4" style="display:none;"></div>

                <!-- Table Div -->
                <div id="div_tabela_servicos" class="pb-3 text-center" style="display:none;"></div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="page-footer pt-0 rgba-stylish-light">
        <div class="footer-copyright">
            <div class="container-fluid">
                &copy;2025 Next Pulse Desenvolvimento de Softwares Ltda
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="../js/jquery.min.js?v=3.5.1"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/jquery.mask.min.js"></script>
    <script src="../js/popper.min.js?v=2"></script>
    <script src="../js/bootstrap.min.js?v=4.1.3"></script>
    <script src="../js/mdb.min.js?v=4.5"></script>
    <script src="../js/dataTables.bootstrap4.js"></script>
    <script src="../js/moment.min.js"></script>
    <script src="../js/SIAUPRO_app.js?t=1612371510"></script>
    <script src="../js/views/servico/pericias.js?t=1726603518"></script>
    <script>
        $(function() {
            // Initialize Siaupro
            $siaupro = new Siaupro("https://app.siaupro.com.br/", "production");
            $siaupro.usuario = {
                "id": "UUVwbzVDMlkrLzNKcGZEM25YUlhxdz09",
                "nome": "Renan Bayo",
                "email": "renanbayo@gmail.com"
            };

            // Filter Button Click
            $('#btn_servico_todos_filtrar').on('click', function() {
                $('#carregando').modal('show');
                setTimeout(() => {
                    $('#carregando').modal('hide');
                    alert('Serviços filtrados com sucesso!');
                }, 2000);
            });
        });
    </script>
</body>
</html>