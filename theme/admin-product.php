<?php $v->layout("_admin_theme"); ?>

<section class="main_admin">

    <?php if (!empty($message) && $message['text'] != null):  ?>
        <div class="message message-<?= $message['type']; ?> icon-checkmark2"><?= $message['text']; ?></div>
    <?php endif; ?>

    <div class="main_admin_content">
        <header class="radius">
            <?php if (!empty($products)): ?>
                <h1>Lista de Produtos</h1>
                <div class="main_admin_content_product">

                    <?php foreach ($products as $product): ?>
                        <div class="main_product_content">
                            <img src=<?= url("/storage/{$product->cover}"); ?>>
                            <h1><?= $product->name; ?></h1>
                            <p>R$ <?= $product->value; ?></p>
                            <p><?= $product->category; ?></p>
                            <p><?= $product->description; ?></p>
                            <div class="main_product_content_action">
                                <span class="icon-pencil"><a href="<?= urlLink("/admin/produto/editar/{$product->id}")?>">Editar</a></span>
                                <span class="icon-bin"><a href="<?= urlLink("/admin/produto/excluir/{$product->id}")?>">Exluir</a></span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php else: ?>
            <h1>Não existem produtos cadastrados</h1>
            <?php endif; ?>
        </header>
        <header class="radius">

            <?php if (!empty($edit)): ?>

                <h1>Editar Produto</h1>
                <form action="<?= urlLink("/admin/produto/editar/{$edit->id}/true") ?>" method="post" enctype="multipart/form-data">
                    <label for='selecao-arquivo'><i class="icon-file-picture"></i>Anexar Imagem</label>
                    <input id='selecao-arquivo' name="file" type="file" placeholder="Escolha uma imagem">
                    <label for="">Categoria</label>
                    <input name="category" value="<?= $edit->category; ?>" type="text">
                    <label for="">Nome do Produto</label>
                    <input name="name" value="<?= $edit->name; ?>" type="text">
                    <label for="">Valor</label>
                    <input name="value" value="<?= $edit->value; ?>" type="number">
                    <label for="">Descrição</label>
                    <input name="description" value="<?= $edit->description; ?>" type="text">
                    <button type="submit">Atualizar Produto</button>
                </form>
            <?php else:?>
                <h1>Cadastrar Produto</h1>
                <form action="<?= urlLink("/admin/produto/true") ?>" method="post" enctype="multipart/form-data">
                    <label for='selecao-arquivo'><i class="icon-file-picture"></i>Anexar Imagem</label>
                    <input id='selecao-arquivo' name="file" type="file" placeholder="Escolha uma imagem">
                    <label for="">Categoria</label>
                    <input name="category" type="text">
                    <label for="">Nome do Produto</label>
                    <input name="name" type="text">
                    <label for="">Valor</label>
                    <input name="value" type="number">
                    <label for="">Descrição</label>
                    <input name="description" type="text">
                    <button type="submit">Cadastrar Produto</button>
                </form>
            <?php endif; ?>

        </header>
    </div>
</section>