<div id="main-menu" class="hidden-phone">        
    <ul class="nav nav-tabs">
        <?php if($this->uri->segment(3) == 'crear'): ?>
            <li class="" id="primero">
                <?= anchor("$user/calendario", '<i class="icon-calendar"></i> Calendario ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <?= anchor("$user/calendario/crear", '<i class="icon-plus"></i> Crear cita');?>
            </li>          
        <?php else: ?>
            <li class="active" id="primero">
                <?= anchor("$user/calendario", '<i class="icon-calendar"></i> Calendario');?>
            </li>
            <li class="">
                <?= anchor("$user/calendario/crear", '<i class="icon-plus"></i> Crear cita ( + )', array('accesskey'=>'+'));?>
            </li>
        <?php endif;?>
    </ul>
</div> 
