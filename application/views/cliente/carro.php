
<?php if(count($this->cart->contents()) == 0) :?>
    <div class="span6">
        <div class="alert alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Advertencia</h4>
            Actualmente no se ha seleccionado ningún presupuesto.
        </div>
    </div>
<?php else:?>
    <?php echo form_open('https://www.sandbox.paypal.com/cgi-bin/webscr', array('name'=>'formTpv')); ?>
        <input name="cmd" type="hidden" value ="_cart">
        <input name="upload" type="hidden" value="1">
        <input name="business" type ="hidden" value="jbgae@hotmail.com">
        <input name="shopping_url" type ="hidden" value="<?=base_url();?>cliente/presupuesto/listado">
        <input name="currency_code" type ="hidden" value="EUR">
        <input name="return" type="hidden" value="<?=base_url();?>cliente/cesta/exito">
        <input type='hidden' name='cancel_return' value='<?=base_url();?>cliente/cesta/error'>
        <input name="notify_url" type="hidden" value="<?=base_url();?>cliente/cesta/paypal">
        <input name="rm" type="hidden" value="2">
        <div class="span9">        
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Sub-Total</th>
                        <th></th>  
                    </tr>
                </thead>        
                <tbody>

                <?php $i = 1; ?>
                <?php foreach ($this->cart->contents() as $items): ?>
                    <input name="item_number_<?= $i; ?>" type="hidden" value="<?=  $items['rowid']; ?>">
                    <input name="item_name_<?= $i; ?>" type="hidden" value="<?= $items['name']; ?>">
                    <input name="amount_<?= $i; ?>" type="hidden" value="<?= $items['price']; ?>">
                    <input name="quantity_<?= $i; ?>" type="hidden" value="<?= $items['qty']; ?>">

                    <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
                        <tr>
                          <td>
                            <?php echo $items['name']; ?>
                                <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
                                    <p>
                                        <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                                            <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
                                        <?php endforeach; ?>
                                    </p>
                                <?php endif; ?>
                          </td>
                          <td><?php echo $this->cart->format_number($items['price']); ?></td>
                          <td><?php echo $this->cart->format_number($items['subtotal']); ?></td>
                          <td><?= anchor("cliente/cesta/actualizar/".$items['rowid'], '<i class="icon icon-trash"></i> Eliminar', array('class' => 'eliminar')); ?></td>
                        </tr>

                <?php $i++; ?>
                <?php endforeach; ?>

                    <tr>
                        <td colspan="1"> </td>
                        <td class="right"><strong>Total</strong></td>
                        <td class="right"><?php echo $this->cart->format_number($this->cart->total()); ?> €</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php echo form_submit('', 'Comprar','class = "btn pull-right"'); ?>
<?php endif;?>
     </div> 
    </div>
</section>