
<div id="carro">
  <?php if($this->cart->contents()):?>
    <?php if(count($this->cart->contents()) == 1):?>  
        <?= anchor(base_url().'cliente/cesta', 'Mi ces<u>t</u>a (1 artículo)', array('accesskey'=>'T','class'=>'cesta', 'title'=>'Artículos introducidos en la cesta.Continuar con el proceso de compra.'));?>
    <?php else:?>
        <?= anchor(base_url().'cliente/cesta', 'Mi ces<u>t</u>a ('. count($this->cart->contents()) . ' artículos)', array('accesskey'=>'T','class'=>'cesta', 'title'=>'Artículos introducidos en la cestaContinuar con el proceso de compra.'));?>
    <?php endif;?>  
  <?php else:?>  
    <?= anchor(base_url().'cliente/cesta', 'Mi ces<u>t</u>a (0 artículos)', array('accesskey'=>'T','class'=>'cesta', 'title'=>'Artículos introducidos en la cesta. Continuar con el proceso de compra.'));?>
  <?php endif;?>  
</div>
<br>
