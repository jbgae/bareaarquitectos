<div id="main-menu"  class="hidden-phone">        
    <ul class="nav nav-tabs">    
        <?php if($this->uri->segment(3) =='editar'):?> 
            <li class="" id="primero">
                <?= anchor('admin/web', '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-edit"></i> Editar página</a>
            </li>
        <?php elseif($this->uri->segment(3) =='buscar'): ?>
            <li class="" id="primero">
                <?= anchor('admin/web', '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-search"></i> Buscar página</a>
            </li>
        <?php else:?>
            <li class="active" id="primero">
                <?= anchor('admin/web', '<i class="icon-th-list"></i> Listado');?>
            </li>
        <?php endif;?>
    </ul>
</div> 
