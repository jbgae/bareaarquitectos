<header>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <?= anchor('inicio','Barea Arquitectos', array('class'=>'brand')); ?>
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                  <span class="icon-bar"></span>
                </button>                
                <div class="nav-collapse collapse">
                    <ul class="nav pull-right">
                        <li class="dropdown">
                            <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-user icon-white"></i> <?= $nombre;?>
                                <i class="icon-chevron-down icon-white"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if($this->session->userdata('usuario') == 'admin'):?>
                                    <li><?= anchor('admin/datos', '<i class="icon-user"></i> Mis datos');?></li>
                                <?php else:?>
                                    <li><?= anchor('empleado/datos', '<i class="icon-user"></i> Mis datos');?></li>
                                <?php endif;?>
                                <li class="divider"></li>
                                <li><?= anchor('empleados/cerrar', '<i class="icon-off"></i> Cerrar sesión', array('data-confirm'=>"Va a abandonar la sesión,¿Estás seguro?"));?></li>
                            </ul>
                        </li>                    
                    </ul>                    
                </div>                
            </div>
        </div>
    </div>
</header>
            
