<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Processo | Adam Almeida"; ?></title>

    <link rel="stylesheet" href="<?= url("theme/_cdn/fonticon.css"); ?>" type="text/css">
    <link rel="stylesheet" href="<?= url("theme/_cdn/boot.css"); ?>" type="text/css">
    <link rel="stylesheet" href="<?= url("theme/_cdn/style.css"); ?>" type="text/css">
</head>

<body>
<header class="main_header">
    <div class="main_header_content">
        <a href="<?= url(); ?>" class="logo">
            <img src="<?= url("theme/img/logo.svg"); ?>"
                 alt="Bem Vindo a Minha Aplicação para o processo de testes PHP Developer">
        </a>

        <?php if ($v->section("nav")):
            echo $v->section("nav");
            ?>
        <?php else: ?>

            <nav class="main_header_content_menu">
                <ul>
                    <li><a href="<?= url(); ?>" class="icon-home">INICIO</a></li>
                    <li><a href="#ticket-id" class="icon-ticket">MEUS CUPONS</a></li>
                    <li><a href="<?= urlLink("/login"); ?>" class="icon-key">FAZER LOGIN</a></li>
                </ul>
            </nav>

            <nav class="main_header_content_menu_mobile">
                <ul>
                    <li>
                        <span class="main_header_content_menu_mobile_obj icon-menu icon-notext "></span>
                        <ul class="main_header_content_menu_mobile_sub ds_none">
                            <li><a href="<?php urlLink(); ?>" class="icon-home">INICIO</a></li>
                            <li><a href="#ticket-id" class="icon-ticket">MEUS CUPONS</a></li>
                            <li><a href="<?= urlLink("/login"); ?>" class="icon-key">FAZER LOGIN</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

        <?php endif;
        ?>


    </div>
</header>

<main class="fadeIn">
    <!--  begin::content  -->
    <?= $v->section("content"); ?>
    <!--  end::content  -->
</main>

<footer class="main_footer">
    <div class="main_footer_content">
        <p>Aplicação desenvolvida por Adam Almeida para o processo seletivo de PHP Developer - Abril 2021</p>
    </div>
</footer>

<script src="<?= url("theme/_cdn/js/jquery-3.6.0.min.js"); ?>"></script>
<script src="<?= url("theme/_cdn/js/main.js"); ?>"></script>

</body>

</html>