<div class="visible-phone">
    <ul class="nav nav-pills nav-stacked">
        <?php if($this->uri->segment(2) == 'novedades'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>        
              <?= anchor("admin/novedades", '<i class="icon-home"></i> Novedades')?>
               
        </li>           
        
        <?php if($this->uri->segment(2) == 'calendario'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor("admin/calendario/", '<i class="icon-calendar"></i> Calendario')?>
        </li>
        
        <?php if($this->uri->segment(2) == 'proyecto'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?> 
            <?= anchor("admin/proyecto", '<i class="icon-folder-close"></i> Proyectos')?>
        </li>
        

        <?php if($this->uri->segment(2) == 'presupuesto'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/presupuesto', '<i class="icon-folder-open"></i>Presupuestos')?>
        </li>
        
        <?php if($this->uri->segment(2) == 'chat'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/chat', '<i class="icon-comment"></i>Mensajes')?>
        </li>

        <?php if($this->uri->segment(2) == 'empleados'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/empleados', '<i class="icon-user"></i> Empleados')?>
        </li>
   
             
        <?php if($this->uri->segment(2) == 'clientes'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/clientes', '<i class="icon-user"></i> Clientes')?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'consultor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>      
            <?= anchor('admin/consultor', '<i class="icon-briefcase"></i> Consultores')?>
        </li>
        
        <?php if($this->uri->segment(2) == 'constructora'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>     
            <?= anchor('admin/constructora', '<i class="icon-picture"></i> Constructoras')?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'proveedor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/proveedor', '<i class="icon-shopping-cart"></i> Proveedores')?>
        </li>
        
        
        <?php if($this->uri->segment(2) == 'noticias'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/noticias', '<i class="icon-pencil"></i> Noticias')?>
        </li>          

        <?php if($this->uri->segment(2) == 'web'): ?>
            <li class="active ">
        <?php else:?>
            <li class="">
        <?php endif;?>
            <?= anchor('admin/web', '<i class="icon-download-alt"></i> Páginas web')?>
        </li> 
        
        <?php if($this->uri->segment(2) == 'copia'): ?>
            <li class="active ">
        <?php else:?>
            <li class="">
        <?php endif;?>
            <?= anchor('admin/copia', '<i class="icon-hdd"></i> Copia seguridad')?>
        </li> 
     
    </ul>
</div>

<div id="menu-lateral" class="hidden-phone">        
    <ul id="dashboard-menu" class="nav nav-list">
        <?php if($this->uri->segment(2) == 'novedades'): ?> 
        <li class="active" id="novedades">               
        <?php else:?>
        <li class="" id ="novedades">
        <?php endif;?>        
            <?= anchor("admin/novedades", '<i class="icon-home"></i> <u>N</u>ovedades', array('accesskey'=>'N'))?>   
        </li>           
        
        <?php if($this->uri->segment(2) == 'calendario'): ?> 
        <li class="active" id="calendario">               
        <?php else:?>
        <li class="" id="calendario">
        <?php endif;?>
            <?= anchor('admin/calendario/', '<i class="icon-calendar"></i> Calenda<u>r</u>io', array('accesskey'=>'R'))?>
        </li>
        
        
        <?php if($this->uri->segment(2) == 'proyecto'): ?> 
        <li class="active" id="proyectos">               
        <?php else:?>
        <li class="" id="proyectos">
        <?php endif;?> 
            <?= anchor('admin/proyecto', '<i class="icon-folder-close"></i> Pro<u>y</u>ectos', array('accesskey'=>'Y'))?>
        </li>
        
        <?php if($this->uri->segment(2) == 'presupuesto'): ?> 
        <li class="active" id="presupuestos">               
        <?php else:?>
        <li class="" id="presupuestos">
        <?php endif;?>
            <?= anchor('admin/presupuesto', '<i class="icon-folder-open"></i>Pres<u>u</u>puestos', array('accesskey'=>'U'))?>
        </li>
        
        
        <?php if($this->uri->segment(2) == 'chat'): ?> 
        <li class="active" id="chat">               
        <?php else:?>
        <li class="" id="chat">
        <?php endif;?>
            <?= anchor('admin/chat', '<i class="icon-comment"></i>C<u>h</u>at', array('accesskey'=>''))?>
        </li> 

        
        <?php if($this->uri->segment(2) == 'empleados'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/empleados', '<i class="icon-user"></i> Em<u>p</u>leados', array('accesskey'=>'P'))?>
        </li>
        
        <?php if($this->uri->segment(2) == 'clientes'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/clientes', '<i class="icon-user"></i> Cl<u>i</u>entes', array('accessKey'=>'I'))?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'consultor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>      
            <?= anchor('admin/consultor', '<i class="icon-briefcase"></i> <u>C</u>onsultores', array('accesskey'=>'C'))?>
        </li>
        
        <?php if($this->uri->segment(2) == 'constructora'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>     
            <?= anchor('admin/constructora', '<i class="icon-picture"></i> Constructora<u>s</u>', array('accesskey'=>'S'))?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'proveedor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/proveedor', '<i class="icon-shopping-cart"></i> Pro<u>v</u>eedores', array('accessKey'=>'V'))?>
        </li>
        
        <?php if($this->uri->segment(2) == 'noticias'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('admin/noticias', '<i class="icon-pencil"></i> N<u>o</u>ticias', array('accesskey'=>'O'))?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'web'): ?>
            <li class="active ">
        <?php else:?>
            <li class="">
        <?php endif;?>
            <?= anchor('admin/web', '<i class="icon-download-alt"></i> Páginas <u>w</u>eb', array('accessKey'=>'W'))?>
        </li> 
        
        <?php if($this->uri->segment(2) == 'copia' || $this->uri->segment(2) == 'copia_seguridad'): ?>
            <li class="active ">
        <?php else:?>
            <li class="">
        <?php endif;?>
            <?= anchor('admin/copia', '<i class="icon-hdd"></i> Copia segurida<u>d</u>',array('accessKey'=>'D'))?>
        </li> 
    </ul>
</div>

