<div id="main-menu" class="hidden-phone">        
    <ul class="nav nav-tabs">
        <?php if($this->uri->segment(3) == 'crear'): ?>
            <li class="" id="primero">
                <?= anchor("$user/proyecto", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <?= anchor("empleados/proyecto/crear", '<i class="icon-plus"></i> Crear proyecto');?>
            </li>          
         <?php elseif($this->uri->segment(3) =='buscar'):?> 
            <li class="" id="primero">
                <?= anchor("empleados/proyecto", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-search"></i> Buscar proyecto</a>
            </li>
         <?php elseif($this->uri->segment(3) =='archivos' || $this->uri->segment(3) =='fases' || $this->uri->segment(3) =='notas'||$this->uri->segment(3) =='nota' || $this->uri->segment(3) =='tarea' ||$this->uri->segment(3) =='nota' || $this->uri->segment(3) =='tareas' || $this->uri->segment(3) =='empleados' ) :?> 
            <li class="" id="primero">
                <?= anchor("$user/proyecto", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
            <li class="active">
                <a href="" class="disabled"><i class="icon-folder-close"></i> Proyecto</a>
            </li>
        <?php else: ?>
             <li class="active" id="primero">
                <?= anchor("$user/proyecto", '<i class="icon-th-list"></i> Listado ( - )', array('accesskey'=>'-'));?>
            </li>
        <?php endif;?>
    </ul>
</div> 
