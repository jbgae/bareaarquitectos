<div id="main-menu"  class="hidden-phone">        
    <ul class="nav nav-tabs">
        <?php if($this->uri->segment(3) == 'crear'): ?>
            <li class="" id="primero">
                <?= anchor('admin/noticias', '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <?= anchor('admin/noticias/crear', '<i class="icon-plus"></i> Crear noticia');?>
            </li>          
        <?php elseif($this->uri->segment(3) =='editar'):?> 
            <li class="" id="primero">
                <?= anchor('admin/noticias', '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="" >
                <?= anchor('admin/noticias/crear', '<i class="icon-plus"></i> Crear noticia ( + )', array('accesskey'=>'+'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-edit"></i> Editar noticia</a>
            </li>
         <?php elseif($this->uri->segment(3) =='buscar'):?> 
            <li class="" id="primero">
                <?= anchor('admin/noticias', '<i class="icon-th-list"></i> Listado( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="" >
                <?= anchor('admin/noticias/crear', '<i class="icon-plus"></i> Crear noticia ( + )', array('accesskey'=>'+'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-search"></i> Buscar noticia</a>
            </li>
        <?php else: ?>
             <li class="active" id="primero">
                <?= anchor('admin/noticias', '<i class="icon-th-list"></i> Listado');?>
            </li>
            <li class="" >
                <?= anchor('admin/noticias/crear', '<i class="icon-plus"></i> Crear noticia ( + )', array('accesskey'=>'+'));?>
            </li>
        <?php endif;?>
    </ul>
</div> 
