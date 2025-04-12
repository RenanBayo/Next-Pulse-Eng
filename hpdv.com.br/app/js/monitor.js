if (!String.prototype.format) {
    String.prototype.format = function() {
      var args = arguments;
      return this.replace(/{(\d+)}/g, function(match, number) { 
        return typeof args[number] != 'undefined'
          ? args[number]
          : match
        ;
      });
    };
}

$(function(){ 
    $('#modal_debug').modal('show');

    // Setando o número de notificacoes não lidas ao span
    var qtd_notificacoes = $('#qtd_notificacoes').val();
    if (qtd_notificacoes > 0) {
        $('#unread').text(qtd_notificacoes);
    }
    
    // SideNav Initialization
    $(".button-collapse").sideNav();

    var container = document.getElementById('slide-out');
    if(container){
        var ps = new PerfectScrollbar(container, {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }

    minDateFilter = "";
    maxDateFilter = "";

    // Data Picker Initialization
    $.extend($.fn.pickadate.defaults, {
        monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        today: 'Hoje',
        clear: 'Limpar',
        close: 'Fechar',
        selectYears: true,
        selectMonths: true,
        format: 'dd/mm/yyyy',   
        labelMonthNext: 'Próximo mês',
        labelMonthPrev: 'Mês anterior',
        labelMonthSelect: 'Selecione um mês',
        labelYearSelect: 'Selecione um ano',
    })
    $('.datepicker').pickadate({

        editable: true,

        //closeOnSelect: false,
        onOpen: function() {
            //Se o campo é somente leitura, não abre o datepicker
            if ($(this.$node).is('.readonly'))
                this.close();
        },

        onClose: function() {
            if ( $(this.$node).attr('name') == 'data_agendamento')
            {  
                $servicos.modalHora();
            } 
        },

        selectMonths: true,
        selectYears: 15,
        // Título dos botões de navegação
        labelMonthNext: 'Próximo Mês',
        labelMonthPrev: 'Mês Anterior',
        // Título dos seletores de mês e ano
        labelMonthSelect: 'Selecione o Mês',
        labelYearSelect: 'Selecione o Ano',
        // Meses e dias da semana
        monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        weekdaysFull: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        // Letras da semana
        weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        //Botões
        today: 'Hoje',
        clear: 'Limpar',
        close: 'Fechar',
        // Formato da data que aparece no input
        format: 'dd/mm/yyyy',
        // onSet: function(set) {
        //     if (!set.highlight && (this.get('id') === 'from' || this.get('id') === 'to')) {
        //         if (this.get('id') === 'from') {
        //             minDateFilter = moment(this.get('value'), 'DD/MM/YYYY').unix();
        //         } else {
        //             maxDateFilter = moment(this.get('value'), 'DD/MM/YYYY').unix();
        //             if (minDateFilter && maxDateFilter < minDateFilter) {
        //                 $siaupro.modal('Aviso', 'A data máxima de pesquisa não pode ser menor que a mínima', 'warning');
        //                 this.set('clear');
        //             }
        //         }
        //         var table = $('.tabela_servicos').DataTable();
        //         table.draw();
        //     }
        // }
    });

    // Tooltips Initialization
    $('[data-toggle="tooltip"]').tooltip();

    //dizendo para o dataTables como entender o fomato de data e hora que usaremos
    $.fn.dataTable.moment( 'DD/MM/YYYY');
    $.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss');

    // DEFAULTS para todas as tabelas do SIAUPRO. Botões, linguagem, títulos, paginação, todos os defaults globais.
    $.extend(true, $.fn.dataTable.defaults, {

        /* Define the table control elements to appear on the page and in what order.
        Mais info em: https://datatables.net/reference/option/dom */
        "dom":
            "<'row'<'col-sm-12 col-md-9'f><'col-sm-12 col-md-3'<'dropdown text-md-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'tfooterCustom'<'row small'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center pt-2'i><'col-sm-12 col-md-4'p>>>",
        //Termina a config do 'dom'.
        /* "colReorder": true, // desabilitado por enquanto
        colReorder: {
            realtime: false
        }, */
        /* "responsive": true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ], */
        "stateSave": true, //ativa o save de cookies para os filtros aplicados pelo cliente
        "stateDuration": 0, //duração dos cookies em segundos*. Zero = infinito, um dia seria = "stateDuration": 60 * 60 * 24
        //"scrollX": true,
        "lengthMenu": [[10, 25, 50, 200], [10, 25, 50, 200]],
        buttons: [
            {
                extend: 'colvis',
                titleAttr: 'Esconder ou Exibir colunas',
            },
            {
                extend: 'collection',
                text: '<i class="fa fa-copy" aria-hidden="true"></i>',
                titleAttr: 'Copiar / Exportar',
                buttons:
                [   {
                        extend: 'print',
                        text: 'Imprimir',
                        exportOptions:
                        {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'copy',
                        text: 'Copiar texto',
                        exportOptions:
                        {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Exportar para Excel',
                        exportOptions:
                        {
                            format: {
                                
                                body: function ( data, row, column, node ) {
                                    //tira as tags do valor da coluna
                                    data = stripHtml(data);
                                    //trata valores em reais
                                    data = trata_valor(data);
                                    return data;  
                                },
                            },   
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Exportar para PDF',
                        orientation: 'portrait',
                        exportOptions:
                        {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'Exportar para CSV',
                        exportOptions:
                        {
                            columns: ':visible'
                        }
                    },
                ]
            }
        ],
        language: {
            "decimal": ",",
            "thousands": ".",
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Exibindo de _START_ até _END_ (Total de _TOTAL_)",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "<br><b class='text-primary'>(Filtrados de _MAX_ registros)</b>",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "Paginação _MENU_",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "<i title='Pesquisa Rápida' class='fa fa-search-plus' aria-hidden='true'></i>",
            "oPaginate": {
                "sNext": "<i class='fa fa-angle-right' aria-hidden='true'></i>",
                "sPrevious": "<i class='fa fa-angle-left' aria-hidden='true'></i>",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            oAria: {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            buttons: { //Definindo o texto dos botões
                columnToggle: 'Filtrar colunas',
                colvis: '<i class="fa fa-eye" aria-hidden="true"></i>',
                copyTitle: 'Copiado',
                copyKeys: 'Pressione <i>ctrl</ i> ou <i>\u2318</ i> + <i>C</ i> para copiar os dados da tabela para a área de transferência. <br> <br> Para cancelar, clique nesta mensagem ou pressione Esc.',
                copySuccess: {
                    _: '%d linhas copiadas',
                    1: '1 linha copiada'
                },
            }
        },
        drawCallback: function( settings ) {
            $('.table').DataTable().$('span.status').each(function(){
                var classe = $(this).attr('class');
                $(this).parents('tr').addClass(classe);
            });      
            //Validação de tamanho dos datatables que precisam de rolagem horizontal
            if ($(this)[0].offsetWidth > $(this).parent()[0].offsetWidth) {
                //Se a largura da table for maior que o container
                //Adiciona a classe para tornar responsiva
                $(this).addClass('table-responsive');
            }
        }

    });

    //Inicialiando o SELECT do MDB
    $('.mdb-select').materialSelect();
    // //Ajustes do SELECT do MDB
    // $('body').on('mousedown', '.select-dropdown', function(){
    //     var campo = $(this);
    //     if (campo.val() == 'Selecione...') {
    //         setTimeout(function(){
    //             campo.next().find('.search').focus();
    //             campo.next().find('li:contains("Selecione...")').hide();
    //         });
    //     }
    // });

    //Editor HTML 
    tinymce.init({
        language: 'pt_BR',
        selector: '.editorHTML',
        menubar: false,
        inline: true,
        theme: 'inlite',
        plugins: [
        'autolink',
        'codesample',
        'contextmenu',
        'link',
        //'linkchecker',
        'lists',
        //'mediaembed',
        //'powerpaste',
        'table',
        'textcolor',
        'image'
        ],
        toolbar: [
        'undo redo | bold italic underline | fontselect fontsizeselect',
        'forecolor backcolor | alignleft aligncenter alignright alignfull | link unlink | numlist bullist outdent indent'
        ],
        insert_toolbar: 'quicktable image',
        selection_toolbar: 'bold italic | h2 h3 | blockquote quicklink',
        contextmenu: 'inserttable | cell row column deletetable',
        //powerpaste_word_import: 'clean',
        //powerpaste_html_import: 'clean'
    });

    //Mascara usada na validação dos campos:
    //TO-DO: Substituir por atributo HTML 'data-mask'
    $(".mascara_telefone").mask("(00) 90000-0000");
    $(".mascara_celular").mask("(00) 90000-0000");
    $(".mascara_celular_sem_ddd").mask("90000-0000");
    $(".mascara_cpf").mask("000.000.000-00");
    $(".mascara_cnpj").mask("00.000.000/0000-00");     
    $(".mascara_cep").mask("00000-000");
    $('.mascara_celular, .mascara_telefone').keyup(function(event) {
        if($(this).val().length == 15){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
           $(this).mask('(00) 00000-0009');
        } else {
           $(this).mask('(00) 0000-00009');
        }
     });
     $('.mascara_celular_sem_ddd').keyup(function(event) {
        if($(this).val().length == 10){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
           $(this).mask('00000-0009');
        } else {
           $(this).mask('0000-00009');
        }
     }); 
    
    //Desativar a detecção automática de campos dropzone
    Dropzone.autoDiscover = false;
    // Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop files here to upload";
    // Dropzone.prototype.defaultOptions.dictFallbackMessage = "Your browser does not support drag'n'drop file uploads.";
    // Dropzone.prototype.defaultOptions.dictFallbackText = "Please use the fallback form below to upload your files like in the olden days.";
    // Dropzone.prototype.defaultOptions.dictFileTooBig = "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.";
    Dropzone.prototype.defaultOptions.dictInvalidFileType = "Este tipo de arquivo não é permitido.";
    // Dropzone.prototype.defaultOptions.dictResponseError = "Server responded with {{statusCode}} code.";
    // Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancel upload";
    // Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "Are you sure you want to cancel this upload?";
    // Dropzone.prototype.defaultOptions.dictRemoveFile = "Remove file";
    Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "Você não pode enviar mais arquivos.";

    //Editor HTML 
    tinymce.init({
        language: 'pt_BR',
        selector: '.editorHTML',
        menubar: false,
        inline: true,
        theme: 'inlite',
        plugins: [
        'autolink',
        'codesample',
        'contextmenu',
        'link',
        //'linkchecker',
        'lists',
        //'mediaembed',
        //'powerpaste',
        'table',
        'textcolor',
        'image'
        ],
        toolbar: [
        'undo redo | bold italic underline | fontselect fontsizeselect',
        'forecolor backcolor | alignleft aligncenter alignright alignfull | link unlink | numlist bullist outdent indent'
        ],
        insert_toolbar: 'quicktable image',
        selection_toolbar: 'bold italic | h2 h3 | blockquote quicklink',
        contextmenu: 'inserttable | cell row column deletetable',
        //powerpaste_word_import: 'clean',
        //powerpaste_html_import: 'clean'
    });

    //Ajustes de cores menu principal
    // $("#slide-out li ul li a").each(function(){
    //     var url = $(this).attr('href').replace($siaupro.url, '');
    //     switch(url) {
    //         case 'servico/dashboard':
    //             classe = 'grey lighten-3';
    //             //classe = 'light-blue accent-1';
    //             break;
    //         case 'servico':
    //             classe = 'white';
    //             //classe = 'yellow';
    //             break;
    //         case 'recebimentos':
    //             classe = 'grey lighten-3';
    //             //classe = 'light-green accent-2';
    //             break;
    //         case 'cobranca/selecionar_cobranca':
    //             classe = 'white';
    //             //classe = 'red accent-1';
    //             break;
    //         case 'pagamentos':
    //             classe = 'grey lighten-3';
    //             //classe = 'orange lighten-3';
    //             break;
    //     }
    //     $(this).parent().addClass(classe);
    // });

    //Verifica se o valor passado possui <tags> e retira
    function stripHtml(html){
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    };
    //Verifica se o valor passado possui R$ no começo, caso possua faz a conversao de R$ 0,00 -> 0.00
    function trata_valor(data){
        //verifica se tem R$ no começo
        if(data.startsWith("R$")){
            data = data.replace( /[R$]/g, '');
            data = data.replace(/\./g, '');
            data = data.replace(/,/g, '.');
            //Caso o valor possua um valor em parenteses com a quantidade de vezes esse valor é retirado
            data = data.replace(/ *\([^)]*\) */g, '');
        }
        //o valor retornado vai ser igual ao informado caso nao possua R$
        return data;
    };
});