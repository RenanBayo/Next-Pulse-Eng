<?php require "../layouts/session.php" ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php require '../layouts/head.php' ?>
<link rel="stylesheet" href="../css/pericias.css">
<link rel="stylesheet" href="../css/global.css">
    <script>
        if ("serviceWorker" in navigator) {
            // if (!navigator.serviceWorker.controller) {
            //     navigator.serviceWorker
            //         .register("/siaupro_sw.js", {
            //             scope: "/"
            //         })
            //         .catch(function(err) {
            //             console.log("[SW] Erro: ", err);
            //         });
            // }
            navigator.serviceWorker.getRegistrations().then(
            function(registrations) {
                for(let registration of registrations) {  
                    registration.unregister();
                }
            });
        }
    </script>
</head>
<body id="monitor" class="servico pericias fixed-sn white-skin">
    <div class="modal fade show" id="carregando" tabindex="-1" role="dialog" data-toggle="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog cascading-modal text-center" role="document">
            <div class="preloader-wrapper medium active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <div class="modal-body white-text"></div>
        </div>
    </div>

    <!-- Modal de notificações de todas as views-->
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

    <!-- Frame Modal Bottom -->
    <div class="modal fade bottom" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <!-- Add class .modal-frame and then add class .modal-bottom (or other classes from list above) to set a position to the modal -->
        <div class="modal-dialog modal-frame modal-bottom" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row d-flex justify-content-center align-items-center">
                        <p class="p-3">O monitor utiliza cookies para identificar os logins, analisar estatísticas de acesso e informações de publicidade e marketing. <br/> Saiba mais detalhes em nossa <a class="cookies_inside_modal" target="_blank" href="https://app.siaupro.com.br/politicas/cookies">Política de Cookies</a>, <a class="privacidade_inside_modal" target="_blank" href="https://app.siaupro.com.br/politicas/privacidade">Política de Privacidade</a> e <a class="termos_de_uso_inside_modal" target="_blank" href="https://app.siaupro.com.br/politicas/termos_de_uso">Termos de Uso</a>.</p>              
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Personalizar Cookies</button> -->
                        <button type="button" class="btn btn-primary p-2" id="btn_modal_aceitar_cookies">Aceitar e Continuar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--Main Navigation-->
    <header>
        <?php require '../layouts/menu-monitor.php' ?>

    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    <main class="mx-0">
        <div class="container-fluid">
            <script type="text/javascript">
    window.$obj_acompanhamento = [];
</script>

<!-- modal Tabela com as novas perícias -->
<div class="modal" id="modal_tabela_novas_pericias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-notify modal-info modal-lg" role="document">
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <p class="heading lead">Selecione os Processos para Incluir no SIAUPRO</p>
                <button id="btn_add_modal_tabela_novas_pericias" type="button" data-dismiss="modal" class="btn btn btn-success">Adcionar Selecionados</button>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>
            <div id="modal-body" class="modal-body text-center" style="overflow-x: auto; max-height: 80vh;">
                
                <!--Table-->
                <table id='tabela_modal_tabela_novas_pericias' class='table table-hover table-responsive-md text-center' cellspacing='0' width='100%'>
                    <!--Table head-->
                    <thead>
                        <tr>
                            <th class='text-center'><a id="btn_modal_selecionar_todas_pericias"><i class="fa fa-square-o white-text"></i> Todos</a></th>
                            <th class='text-center'>Processo</th>
                            <th class='text-center'>Juiz</th>
                            <th class='text-center'>Vara / Comarca</th>
                            <th class='text-center'>Nomeado</th>
                        </tr>
                    </thead>
                    <!--Table head-->
                </table>
                <!--Table-->
            </div>
        </div>
    </div>
</div>
<!-- modal Tabela com as novas perícias -->


<!--Card-->
<div class="card card-cascade narrower mt-3">

    <div class="text-center pt-2 col-lg-12 p-3">
        <div class="row gradiente-metal pt-4">
            <!-- <div class="col-md-1 col-sm-8 small">
                <i class="fa fa-search"></i> Filtros
            </div> -->
            <input type="hidden" id="hdn_usuario" value="" />
            <input type="hidden" id="processo" value="QVhrQzR3Q2w0WkZLcFQ0MC9hbkxUZz09" />

            <!-- PESQUISA -->
            <div class="col-md-4 col-sm-4">
                <div class="md-form form-sm">
                    <input class="form-control" placeholder=" " type="text" name="pesquisa" id="pesquisa" value=""/>
                    <label for="pesquisa"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</label>
                </div>
            </div>
            <!-- / PESQUISA -->

            <!-- STATUS GERAL -->
            <div class="col-md-4 col-sm-4">
                
                <div class="md-form form-sm" id="div_status_pericia">
                    <label for='status_pericia'>Status Geral</label>
                    <select class="mdb-select colorful-select dropdown-info" name="status_pericia" id="status_pericia" multiple="multiple"
                    searchable="Pesquisar..." data-label-no-search-results="sem resultados" data-label-select-all="Todos" data-label-options-selected="selecionados">
                        <option value="nomeado">Nomeado</option><option value="intimado">Intimado</option><option value="concluido">Concluido</option><option value="contestado">Contestado</option><option value="cancelado">Cancelado</option><option value="vistoriados">Vistoriados</option><option value="pendente">Pendente</option><option value="executando" selected="selected">Em Execução</option>                    </select>
                </div>
                
            </div>
            <!-- / STATUS GERAL -->

            <!-- DATA -->
            <div class="col-md-2 col-sm-4">
                <div class="md-form form-sm">
                    <select class="mdb-select colorful-select dropdown-info" name="tipo_data" id="tipo_data">
                        <option value="todas">Todas</option>
                        <option value="emissao">Emissão</option>   
                    </select>
                    <label for="tipo_data"><i class="fa fa-calendar" aria-hidden="true"></i> Datas</label>
                </div>
            </div>
            <!-- / DATA -->

            <!-- DATA INICIAL -->
            <div class="col-md-1 col-sm-4 px-2">
                <div class="md-form form-sm filtro_periodo_data">
                    <input placeholder=" " value="01/02/2025" type="text" class="form-control datepicker"  name="data_inicial" id="data_inicial" data-tipo="data" data-mask="00/00/0000" data-mask-clearifnotmatch="true" data-mask="00/00/0000 00:00:00" data-mask-clearifnotmatch="true"/>
                    <label for="data_inicial"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Inicial <a style='cursor: pointer;' class='a_link_datepicker'><span class='caret' title='Abrir Calendário'><i class="fa fa-calendar blue-text"></i></span></a></label>
                </div>
            </div>

            <!-- DATA FINAL -->
            <div class="col-md-1 col-sm-4 px-2">
                <div class="md-form form-sm filtro_periodo_data">
                    <input placeholder=" " value="21/02/2025" type="text" class="form-control datepicker"  name="data_final" id="data_final" data-tipo="data" data-mask="00/00/0000" data-mask-clearifnotmatch="true" data-mask="00/00/0000 00:00:00" data-mask-clearifnotmatch="true"/>
                    <label for="data_final"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Final <a style='cursor: pointer;' class='a_link_datepicker'><span class='caret' title='Abrir Calendário'><i class="fa fa-calendar blue-text"></i></span></a></label>
                </div>
            </div>
            <!-- FILTRAR -->
            <div class="row col-12">
                <!-- BAIXAR DADOS -->
                <div class="col-sm-12 col-md-4 text-center p-0">
                                    </div>
                <div class="col-sm-12 col-md-4 text-center p-0">
                    <button id="btn_servico_todos_filtrar" class="btn btn-sm btn-primary">
                        <i class="fa fa-search"></i> Filtrar Serviços
                    </button>
                </div>
                <!-- NOVA -->
                <div class="col-sm-12 col-md-4 text-center p-0">
                                            <!-- Oculta caso for usuário externo -->
                        <a title="Nova Perícia" href="https://app.siaupro.com.br/servico/novo/QVhrQzR3Q2w0WkZLcFQ0MC9hbkxUZz09" class="btn btn-sm btn-success">
                            <i class="fa fa-plus" aria-hidden="true"></i> Nova Perícia
                        </a>
                                    </div>
            </div>
        </div>
    </div>

    <!-- DIV PARA MSG -->
    <div id="div_msg" class="text-center py-4" style="display:none;"></div>

    <!-- DIV TABELA -->
    <div id="div_tabela_servicos" class="pb-3 text-center" style="display:none;"></div>

</div>            <div id="mdb-lightbox-ui"></div>
        </div>

    </main>
    <!--Main layout-->

    
    <!--Footer-->
    <footer class="page-footer pt-0 rgba-stylish-light">

        <!--Copyright-->
        <div class="footer-copyright">
            <div class="container-fluid">
                &copy;2025 Next Pulse Desenvolvimento de Softwares Ltda
            </div>
        </div>
        <!--/.Copyright-->

    </footer>
    <!--/.Footer-->
    
    <script src="../js/jquery.min.js?v=3.5.1"></script><script src="../js/jquery-ui.min.js"></script><script src="../js/jquery.rest.min.js"></script><script src="../js/jquery.mask.min.js"></script><script src="../js/popper.min.js?v=2"></script><script src="../js/bootstrap.min.js?v=4.1.3"></script><script src="../js/mdb.min.js?v=4.5"></script><script src="../js/compiled-addons.min.js?v=4"></script><script src="../js/mdb-plugins-gathered.min.js?v=4"></script><script src="../js/bootstrap3-typeahead.js"></script><script src="../js/jquery.validate.min.js?v1.19.2"></script><script src="../js/additional-methods.min.js?v1.19.2"></script><script src="../js/jquery.validate.lang_pt_BR.js?v1.19.2"></script><script src="../js/jquery.dataTables.js"></script><script src="../js/dataTables.responsive.min.js"></script><script src="../js/dataTables.bootstrap4.js"></script><script src="../js/dataTables.buttons.min.js"></script><script src="../js/dataTables.colReorder.min.js"></script><script src="../js/dataTables.moment.js"></script><script src="../js/buttons.flash.min.js"></script><script src="../js/buttons.html5.min.js"></script><script src="../js/buttons.print.min.js"></script><script src="../js/buttons.bootstrap4.js?v=20190701"></script><script src="../js/buttons.colVis.min.js"></script><script src="../js/fullcalendar-main.min.js"></script><script src="../js/fullcalendar-daygrid-main.js"></script><script src="../js/fullcalendar-interaction-main.js"></script><script src="../js/fullcalendar-bootstrap-main.js"></script><script src="../js/fullcalendar-local-pt-br.js"></script><script src="../js/dropzone_v5_2_0.js"></script><script src="../js/jszip.min.js"></script><script src="../js/pdfmake.min.js?v0.1.32"></script><script src="../js/vfs_fonts.js"></script><script src="../js/moment.min.js"></script><script src="../js/tinymce/tinymce.min.js"></script><script src="../js/markerjs2/markerjs2.js"></script><script src="../js/cropro.js"></script><script src="../js/lazyload.min.js"></script><script src="../js/classes/Siaupro.js?t=1726603518"></script>    <script>
        $(function(){
            //Classe principal Next Pulse
            $siaupro = new Siaupro("https://app.siaupro.com.br/", "production"); 
            $siaupro.usuario = {"id":"UUVwbzVDMlkrLzNKcGZEM25YUlhxdz09","id_conta":"TnZ0d2RjSDBkb0Z5M0V6VTY5Q3BRQT09","usuario":"renan","senha":"$2y$10$z9ji1bb1jZIVct7fXWR3BOjFHq1QiMIyO4uNWoxJIO5wiIhptimO6","email":"renanbayo@gmail.com","nome":"Renan Bayo","celular":"17988254580","cpf":"","permissoes":"10","acessar_documentos":"0","ver_apenas_vinculados":"0","ver_dados_financeiros":"1","tipo_comissionamento":null,"valor_comissionamento":null,"ultimo_acesso":"2025-02-21 15:51:51","confirmado":"1","hash_confirmacao":null,"email_notificacoes":"1","aceite_termos_uso":"1","aceite_politica_privacidade":"1","aceite_cookies":"1","criado":"2025-02-21 15:44:22","alterado":"2025-02-21 15:51:41","excluido":null,"id_criado_por":"81","id_alterado_por":"1325","id_excluido_por":null};
            $siaupro.usuario.hash_tawkto = '8baa64b0419352a5b3fb131ebefd96c397e3fbb6b6fb114db8611c6e969c586f';
            $siaupro.usuario.tags_tawkto = ["usuario-comum","conta-antiga","com-servicos"];
        });
    </script>

    
    <script src="../js/SIAUPRO_app.js?t=1612371510"></script>
    <script src="../js/views/servico/pericias.js?t=1726603518"></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"915905cddd0c01c2","version":"2025.1.0","r":1,"token":"00d9224b259f45508402dfd514ad7823","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}}}' crossorigin="anonymous"></script>
</body>
</html>
