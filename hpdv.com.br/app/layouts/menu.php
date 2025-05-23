<link rel="stylesheet" href="../css/menu.css">
<nav>
    <div id='menu'>
        <button class="btn btn-enter" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuSuperior" aria-controls="offcanvasTop">Menu</button>
    </div>
    <div class="offcanvas offcanvas-top" tabindex="-1" id="menuSuperior" aria-labelledby="offcanvasTopLabel">
        <div class="offcanvas-header">
            <h7 class="offcanvas-title" id="offcanvasTopLabel"><?= "Usuário: " . $user_logged->usuario_acesso . '<br> Permissão: ' . $user_logged->tipo_permissao ?> </h7>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="logo-container">
            <div class="row">
            <div style="text-align: center;">
            <a href="home.php" class="pl-0"> 
                <img src="../assets/icon/android-chrome-512x512.png" alt="Logo da Empresa" class="logo" style="max-width: 200px;">
            </div>
            </div>   
        </div>
        <div class="offcanvas-body">
            <ul class="menu-container">
                <li class="menu-box" onclick="window.location.href='home';">
                    <a href="home">
                        <i class="fa-solid fa-house menu-icon"></i>
                        Home
                    </a>
                </li>

                <!-- restrições cadastro -->
                <?php
                if ($user_logged->tipo_permissao == "cadastro" || $user_logged->tipo_permissao == "administrador") { ?>
                    <div class="menu-box dropbottom">
                        <li class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-address-card menu-icon"></i>
                            Cadastros
                        </li>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li onclick="window.location.href='cadastro-cliente';"><a class="dropdown-item" href="cadastro-cliente"><i class="fa-solid fa-user-plus menu-icon"></i> Cliente</a></li>
                            <li onclick="window.location.href='cadastro-fornecedor';"><a class="dropdown-item" href="cadastro-fornecedor"><i class="fa-solid fa-truck-fast menu-icon"></i> Fornecedor</a></li>
                            <li onclick="window.location.href='cadastro-produto';"><a class="dropdown-item" href="cadastro-produto"><i class="fa-solid fa-tags menu-icon"></i> Produto</a></li>
                        </ul>
                    </div>
                <?php } ?>

                <!-- restrições venda -->
                <?php
                if ($user_logged->tipo_permissao == "venda" || $user_logged->tipo_permissao == "administrador") { ?>
                    <li class="menu-box" onclick="window.location.href='historico-de-vendas';">
                        <a href="historico-de-vendas">
                            <i class="fa-solid fa-clock-rotate-left menu-icon"></i>
                            Histórico de Compras
                        </a>
                    </li>
                <?php } ?>

                <li class="menu-box" onclick="window.location.href='relatorio';">
                    <a href="relatorio">
                        <i class="fa-solid fa-file-invoice menu-icon"></i>
                        Relatórios
                    </a>
                </li>

                <!-- restrições venda -->
                <?php
                if ($user_logged->tipo_permissao == "venda" || $user_logged->tipo_permissao == "administrador") { ?>
                    <li class="menu-box" onclick="window.location.href='compras';">
                        <a href="compras" target="_blank">
                            <i class="fa-solid fa-cart-arrow-down menu-icon"></i>
                            Incluir Compra
                        </a>
                    </li>
                <?php } ?>

                <!-- restrições administrador -->
                <?php
                if ($user_logged->tipo_permissao == "administrador") { ?>
                    <div class="menu-box dropbottom">
                        <li class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-gear menu-icon"></i>
                            Configurações
                        </li>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li onclick="window.location.href='conta-de-usuario';"><a class="dropdown-item" href="conta-de-usuario"><i class="fa-solid fa-users menu-icon"></i> Contas de Usuários</a></li>
                            <li onclick="window.location.href='detalhe-licenca';"><a class="dropdown-item" href="detalhe-licenca"><i class="fa-solid fa-key menu-icon"></i> Detalhes da Licença</a></li>
                            <li onclick="window.location.href='editar-perfil';"><a class="dropdown-item" href="editar-perfil"><i class="fa-solid fa-user-gear menu-icon"></i> Editar Perfil</a></li>
                        </ul>
                    </div>
                <?php } ?>

                <li class="menu-box" onclick="window.location.href='sair';">
                    <a href="sair">
                        <i class="fa-solid fa-person-walking-arrow-right menu-icon"></i>
                        Sair / Efetuar Logoff
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="loader-container"></div>