<?php $v->layout("_theme"); ?>

<?php $v->start("nav"); ?>

    <nav class="main_header_content_menu">
        <ul>
            <li><a href="<?= urlLink(); ?>" class="icon-home">INICIO</a></li>
            <li><a href="<?= urlLink("/login"); ?>" class="icon-key">FAZER LOGIN</a></li>
        </ul>
    </nav>

    <nav class="main_header_content_menu_mobile">
        <ul>
            <li>
                <span class="main_header_content_menu_mobile_obj icon-menu icon-notext "></span>
                <ul class="main_header_content_menu_mobile_sub ds_none">
                    <li><a href="<?= urlLink(); ?>" class="icon-home">INICIO</a></li>
                    <li><a href="<?= urlLink("/login"); ?>" class="icon-key">FAZER LOGIN</a></li>
                </ul>
            </li>
        </ul>
    </nav>

<?php $v->stop(); ?>

<?php if (!empty($erro)): ?>
    <section class="main_content_product">
        <div class="message message-warning icon-warning"><?= $erro; ?></div>
    </section>
<?php endif; ?>

<?php if ($product): ?>

    <section class="main_content_product">

        <header>
            <img src="<?= url("storage/") . $product->cover; ?>" alt="">
        </header>
        <header>
            <h1><?= $product->name ?></h1>
            <p><?= $product->category ?></p>
            <div>
                <?php if (!empty($discount)): ?>
                    <span id="price-risc" class="price-risc">R$ <?= $product->value ?></span>
                    <span id="price" class="price">R$ <?= $discount ?></span>
                <?php else: ?>
                    <span id="price" class="price">R$ <?= $product->value ?></span>
                <?php endif; ?>

            </div>
            <p><?= $product->description ?></p>
            <form class='form-ticket' action="<?= urlLink("/produto/{$product->id}"); ?>" enctype="multipart/form-data"
                  method="post">
                <label for="">Cupom de Desconto</label>
                <input name="ticket" class="input-value-ticket" type="text">

                <button id="button-ticket" type="submit">Aplicar Cupom</button>
            </form>
        </header>
    </section>
<?php else: ?>
    Não foram encontrados produtos para esta busca.
<?php endif; ?>