<div id="main-menu"  class="hidden-phone">        
    <ul class="nav nav-tabs">
        <?php if($this->uri->segment(3) == 'crear'): ?>
            <li class="" id="primero">
                <?= anchor('admin/presupuesto', '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <?= anchor('admin/presupuesto/crear', '<i class="icon-plus"></i> Crear presupuesto');?>
            </li>          
         <?php elseif($this->uri->segment(3) =='buscar'):?> 
            <li class="" id="primero">
                <?= anchor('admin/presupuesto', '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="" >
                <?= anchor('admin/presupuesto/crear', '<i class="icon-plus"></i> Crear presupuesto ( + )', array('accesskey'=>'+'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-search"></i> Buscar presupuesto</a>
            </li>
        <?php else: ?>
             <li class="active" id="primero">
                <?= anchor('admin/presupuesto', '<i class="icon-th-list"></i> Listado');?>
            </li>
            <li class="" >
                <?= anchor('admin/presupuesto/crear', '<i class="icon-plus"></i> Crear presupuesto ( + )', array('accesskey'=>'+'));?>
            </li>
        <?php endif;?>
    </ul>
</div> 
