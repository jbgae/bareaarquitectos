<div id="main-menu" class="hidden-phone">        
    <ul class="nav nav-tabs">
        <?php if($this->uri->segment(3) == 'crear'): ?>
            <li class="" id="primero">
                <?= anchor("$user/constructora", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <?= anchor("$user/constructora/crear", '<i class="icon-plus"></i> Crear constructora');?>
            </li>          
        <?php elseif($this->uri->segment(3) =='editar'):?> 
            <li class="" id="primero">
                <?= anchor("$user/constructora", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="" >
                <?= anchor("$user/constructora/crear", '<i class="icon-plus"></i> Crear constructora ( + )', array('accesskey'=>'+'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-edit"></i> Editar constructora</a>
            </li>
         <?php elseif($this->uri->segment(3) =='buscar'):?> 
            <li class="" id="primero">
                <?= anchor("$user/constructora", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="" >
                <?= anchor("$user/constructora/crear", '<i class="icon-plus"></i> Crear constructora ( + )', array('accesskey'=>'+'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-search"></i> Buscar constructora</a>
            </li>
        <?php elseif($this->uri->segment(3) =='enviar'):?> 
            <li class="" id="primero">
                <?= anchor("$user/constructora", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="" >
                <?= anchor("$user/constructora/crear", '<i class="icon-plus"></i> Crear constructora ( + )', array('accesskey'=>'+'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-envelope"></i> Enviar email</a>
            </li>    
        <?php else: ?>
             <li class="active" id="primero">
                <?= anchor("$user/constructora", '<i class="icon-th-list"></i> Listado');?>
            </li>
            <li class="" >
                <?= anchor("$user/constructora/crear", '<i class="icon-plus"></i> Crear constructora ( + )', array('accesskey'=>'+'));?>
            </li>
        <?php endif;?>
    </ul>
</div> 
