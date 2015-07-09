<div class="visible-phone">
    <ul class="nav nav-pills nav-stacked">
        <?php if($this->uri->segment(2) == 'novedades'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>        
             <?= anchor("empleados/novedades", '<i class="icon-home"></i> Novedades')?>  
        </li>           
        
        <?php if($this->uri->segment(2) == 'calendario'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor("empleados/calendario/", '<i class="icon-calendar"></i> Calendario')?>
        </li>
        
        <?php if($this->uri->segment(2) == 'proyecto'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?> 
            <?= anchor("empleados/proyecto", '<i class="icon-folder-close"></i> Proyectos')?>
        </li>
        
        <?php if($this->uri->segment(2) == 'chat'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('empleados/chat', '<i class="icon-comment"></i>Mensajes')?>
        </li>
                     
        <?php if($this->uri->segment(2) == 'clientes'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor("empleados/clientes", '<i class="icon-user"></i> Clientes')?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'consultor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>      
            <?= anchor("empleados/consultor", '<i class="icon-briefcase"></i> Consultores')?>
        </li>
        
        <?php if($this->uri->segment(2) == 'constructora'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>     
            <?= anchor("empleados/constructora", '<i class="icon-picture"></i> Constructoras')?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'proveedor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor("empleados/proveedor", '<i class="icon-shopping-cart"></i> Proveedores')?>
        </li>
        
             
    </ul>
</div>

<div id="menu-lateral" class="hidden-phone">        
    <ul id="dashboard-menu" class="nav nav-list">
        <?php if($this->uri->segment(2) == 'novedades'): ?> 
        <li class="active" id="novedades-empleado">               
        <?php else:?>
        <li class="" id="novedades-empleado">
        <?php endif;?>        
             <?= anchor('empleados/novedades', '<i class="icon-home"></i> <u>N</u>ovedades', array('accesskey'=>'N'))?>  
        </li>           
        
        <?php if($this->uri->segment(2) == 'calendario'): ?> 
        <li class="active" id="calendario-empleado">               
        <?php else:?>
        <li class="" id="calendario-empleado">
        <?php endif;?>
            <?= anchor('empleados/calendario', '<i class="icon-calendar"></i> Calenda<u>r</u>io', array('accesskey'=>'R'))?>
        </li>
        
        <?php if($this->uri->segment(2) == 'proyecto'): ?> 
        <li class="active" id="proyecto-empleado">               
        <?php else:?>
        <li class="" id="proyecto-empleado">
        <?php endif;?> 
            <?= anchor('empleados/proyecto', '<i class="icon-folder-close"></i> Pro<u>y</u>ectos', array('accesskey'=>'Y'))?>
        </li>
        
         <?php if($this->uri->segment(2) == 'chat'): ?> 
        <li class="active" id="chat-empleado">               
        <?php else:?>
        <li class="" id="chat-empleado">
        <?php endif;?>
            <?= anchor('empleados/chat', '<i class="icon-comment"></i>C<u>h</u>at', array('accesskey'=>''))?>
        </li> 
             
        <?php if($this->uri->segment(2) == 'clientes'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('empleados/clientes', '<i class="icon-user"></i> Cl<u>i</u>entes', array('accessKey'=>'I'))?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'consultor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>      
            <?= anchor('empleados/consultor', '<i class="icon-briefcase"></i> <u>C</u>onsultores', array('accesskey'=>'C'))?>
        </li>
        
        <?php if($this->uri->segment(2) == 'constructora'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>     
            <?= anchor('empleados/constructora', '<i class="icon-picture"></i> Constructora<u>s</u>', array('accesskey'=>'S'))?>
        </li>          
        
        <?php if($this->uri->segment(2) == 'proveedor'): ?> 
        <li class="active">               
        <?php else:?>
        <li class="">
        <?php endif;?>
            <?= anchor('empleados/proveedor', '<i class="icon-shopping-cart"></i> Pro<u>v</u>eedores', array('accessKey'=>'V'))?>
        </li>   
    </ul>
</div>