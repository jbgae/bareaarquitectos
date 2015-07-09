<section>
    <div class="container-fluid container">
        <div class="row-fluid">
            <div class="span3">                
                <div class="well sidebar-nav">
                    <ul class="nav nav-list"> 
                        <?php if($this->uri->segment(2) == 'datos'):?>
                            <li class='active'><?= anchor('cliente/datos', '<u>1</u>.-  Mis datos', array('accesskey'=>'1','title'=>"Datos personales del cliente")); ?></li>
                        <?php else:?>
                            <li><?= anchor('cliente/datos', '<u>1</u>.-  Mis datos', array('accesskey'=>'1','title'=>"Datos personales del cliente")); ?></li>
                        <?php endif;?>
                        
                        <?php if($this->uri->segment(2) == 'modificar'):?>    
                            <li class='active'><?= anchor('cliente/modificar', '<u>2</u>.-  Modificar datos', array('accesskey'=>'2',"title"=>"Formulario para modificar los datos personales del cliente")); ?></li>
                        <?php else:?>
                            <li><?= anchor('cliente/modificar', '<u>2</u>.-  Modificar datos', array('accesskey'=>'2',"title"=>"Formulario para modificar los datos personales del cliente")); ?></li>
                        <?php endif;?>    
                        
                        <?php if($this->uri->segment(3) == 'solicitar'):?>    
                            <li class='active'><?= anchor('cliente/presupuesto/solicitar', '<u>3</u>.-  Solicitar presupuesto', array('accesskey'=>'3','class'=>'subenlaces', 'id'=>'solicitud', "title"=>"Formulario de solicitud de nuevo presupuesto"));?></li>
                        <?php else:?>    
                            <li><?= anchor('cliente/presupuesto/solicitar', '<u>3</u>.-  Solicitar presupuesto', array('accesskey'=>'3','class'=>'subenlaces', 'id'=>'solicitud', "title"=>"Formulario de solicitud de nuevo presupuesto"));?></li>
                        <?php endif;?>
                            
                        <?php if($this->uri->segment(2) == 'presupuesto' && $this->uri->segment(3) == 'listado'):?>     
                            <li class='active'><?= anchor('cliente/presupuesto/listado', '<u>4</u>.-  Listado de presupuestos', array('accesskey'=>'4','class' =>'subenlaces', 'id'=>'mostrar', 'title'=>'Listado de los presupuestos solicitados por el cliente')); ?></li>
                        <?php else:?>    
                            <li><?= anchor('cliente/presupuesto/listado', '<u>4</u>.-  Listado de presupuestos', array('accesskey'=>'4','class' =>'subenlaces', 'id'=>'mostrar', 'title'=>'Listado de los presupuestos solicitados por el cliente')); ?></li>
                        <?php endif;?>
                            
                        <?php if($this->uri->segment(2) == 'proyecto'):?>      
                            <li class='active'><?= anchor('cliente/proyecto/listado', '<u>5</u>.-  Proyectos',array('accesskey'=>'5','id'=>'proyectos', 'title'=>'Listado de los proyectos adquiridos por el cliente'));?></li>
                        <?php else:?>
                            <li><?= anchor('cliente/proyecto/listado', '<u>5</u>.-  Proyectos',array('accesskey'=>'5','id'=>'proyectos', 'title'=>'Listado de los proyectos adquiridos por el cliente'));?></li>
                        <?php endif;?>
                         
                        <li><?= anchor('cliente/cerrar', '<u>6</u>.- Cerrar Sesión',array('accesskey'=>'6','id'=>'sesion','data-confirm'=>"Va a abandonar la sesión,¿Estás seguro?", 'title'=>"Cerrar sesión del cliente"));?></li>    
                    </ul>
                </div>
            </div>