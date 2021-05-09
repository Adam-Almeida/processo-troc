<?php $v->layout("_theme"); ?>

<section class="main_product radius">

    <?php if ($products): ?>
        <header class="main_product_header">
            <h1 class="icon-cart">Últimos Produtos Cadastrados</h1>
            <p>Aqui você encontra os produtos que podem ser adicionados os cupons cadastrados</p>
        </header>
        <?php foreach ($products as $product): ?>

            <article>
                <a href="<?= urlLink("/produto/{$product->id}") ?>">
                    <img src="<?= url("storage/") . $product->cover; ?>" alt="<?= $product->name ?>"
                         title="<?= $product->name ?>">
                </a>
                <p><a href="#" class="category"><?= $product->category ?></a></p>
                <span><?= $product->name ?></span>
                <p class="product-valor">R$ <?= $product->value ?></p>
                <h2><?= $product->description ?></h2>
            </article>

        <?php endforeach;
    else: ?>
        <header class="main_product_header">
            <h1 class="icon-cart">Não Existem Produtos Cadastrados</h1>
        </header>
    <?php endif; ?>
</section>
<article class="main_optin">
    <div class="main_optin_content">
        <header>
            <h1>Quer receber um cupom de desconto em seu E-mail?</h1>
            <p>Informe seu nome e email no campo ao lado, e clique em Ok!</p>
        </header>
        <form action="#">
            <input type="text" placeholder="Seu Nome">
            <input type="email" placeholder="Seu Email">
            <button type="submit">Ok</button>
        </form>
    </div>
</article>

<section id="ticket-id" class="main_tickets">
    <header>
        <h1 class="icon-ticket">Consfira os Últimos Cupons Cadastrados</h1>
        <p>Utilize os cupons para obter descontos, mas não esqueça de obeservar a data de expiração.</p>
    </header>
    <div class="main_tickets_content">
        <?php if ($tickets):
            foreach ($tickets as $ticket):
                ?>

                <a style="cursor: pointer;" id="copy-ticket-transfer-area">
                    <article>
                        <div class="main_tickets_article_left">
                            <h2 id="value-real-ticket"><?= $ticket->ticket_code ?></h2>
                            <p class="icon-calendar">Expira em <?= $ticket->due_date; ?></p>
                        </div>
                        <div class="main_tickets_article_right">
                            <p><?= (($ticket->type != "s") ? 'R$' : '%'); ?> <?= $ticket->value ?></p>
                        </div>
                    </article>
                </a>

            <?php
            endforeach;
        else: ?>
            <article style="padding: 50px">
                <h2>Ainda não existem cupons Cadastrados!</h2>
            </article>
        <?php endif; ?>

    </div>
</section>