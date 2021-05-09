<?php $v->layout("_admin_theme"); ?>

<section class="main_admin">

    <div class="main_admin_content">
        <header class="radius">
            <?php if ($tickets): ?>
            <h1>Lista de Cupons</h1>
            <table class="main_admin_content_table">
                <thead>
                <tr>
                    <td><strong>Cupom</strong></td>
                    <td><strong>Vencimento</strong></td>
                    <td><strong>Valor</strong></td>
                    <td></td>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($tickets as $ticket):?>

                <tr>
                    <td><?= $ticket->ticket_code; ?></td>
                    <td><?= $ticket->due_date; ?></td>
                    <td>
                        <?= (($ticket->type != "s") ? 'R$' : '%' );?>
                        <?= $ticket->value;?>
                    </td>
                    <td>
                        <a href="<?= urlLink("/admin/cupom/editar/{$ticket->id}")?>"><i class="icon-pencil" alt='Editar' title="Editar"></i></a>
                        <a href="<?= urlLink("/admin/cupom/excluir/{$ticket->id}")?>"><i class="icon-bin" alt='Excluir' title="Excluir"></i></a>
                        <i class="icon-ticket no-active" alt='Inativo' title="Inativo"></i>
                    </td>
                </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
            <?php
                else: ?>
                <h1>Ainda não existem cupons Cadastrados!</h1>
            <?php endif; ?>
        </header>
        <header class="radius">

                <?php if (!empty($edit)): ?>
                <h1>Editar Cupom</h1>
                <form action="<?= urlLink("/admin/cupom/editar/$edit->id"); ?>" enctype="multipart/form-data" method="post">
                    <div class="radio_ticket_type">
                        <input type="radio" value="s" name="type" id="campo-radio1"  <?= ($edit->type == "s" ? 'checked' : '') ?> />
                        <label for="campo-radio1">Desconto em %</label>
                        <input type="radio" value="v" name="type" id="campo-radio2" <?= ($edit->type == "v" ? 'checked' : '') ?> />
                        <label for="campo-radio2">Desconto em R$</label>
                    </div>

                    <label for="value">Valor / %</label>
                    <input name="value" type="number" value="<?= $edit->value ?>">
                    <label for="due_date">Data do Vencimento</label>
                    <input name="due_date" type="text" value="<?= $edit->due_date ?>">
                    <button type="submit">Atualizar Cupom</button>
                <?php else: ?>

                    <h1>Cadastrar Cupom</h1>
                    <form action="<?= urlLink("/admin/cupom"); ?>" enctype="multipart/form-data" method="post">
                        <div class="radio_ticket_type">
                            <input type="radio" value="s" name="ticket_type" id="campo-radio1" checked />
                            <label for="campo-radio1">Desconto em %</label>
                            <input type="radio" value="v" name="ticket_type" id="campo-radio2" />
                            <label for="campo-radio2">Desconto em R$</label>
                        </div>

                        <label for="value">Valor / %</label>
                        <input name="value" type="number">
                        <label for="due_date">Duração em Dias</label>
                        <input name="due_date" type="number">
                        <button type="submit">Cadastrar Cupom</button>

                <?php endif; ?>

            </form>
        </header>
    </div>
</section>