<div  itemscope itemtype="http://schema.org/LocalBusiness" itemref="_address3 _email8 _telephone9 _name11" id="wrapper">
            <div class="content">        
                <header>
                    <div class="container-fluid container">
                        <div class="row-fluid">
                            <div class="span7">
                                <img  itemprop="image" id="logo" src="<?= base_url();?>images/logo2.png" alt="logo">
                                <div id="cabecera">
                                    <div id="cab1">barea<span>Arquitectos</span></div>
                                    <div id="cab2">ESTUDIO DE ARQUITECTURA, URBANISMO E INGENIERÍA</div>
                                </div>    
                            </div>
                            <div class="span5">
                                <div class="menuDerecha">
                                    <?php if(isset($nombre)):?>
                                        <?php if($usuario == 'cliente'):?>
                                            <?= anchor('cliente/inicio', $nombre. ' (<u>K</u>)', array('accesskey'=>'K', "title"=>"Página de inicio de sesión de usuario"));?>
                                        <?php elseif($usuario == 'empleado'):?>
                                            <?= anchor('empleado/novedades', $nombre. ' (<u>K</u>)', array('accesskey'=>'K', "title"=>"Página de inicio de sesión de usuario"));?>
                                        <?php elseif($usuario == 'admin'):?>
                                            <?= anchor('admin/novedades', $nombre. ' (<u>K</u>)', array('accesskey'=>'K', "title"=>"Página de inicio de sesión de usuario"));?>
                                        <?php endif;?>
                                    <?php endif;?>
                                    <div><?= anchor('accesibilidad', '<u>A</u>ccesibilidad', array('accesskey'=>'A', "title"=>"Pautas de accesibilidad del sitio web"));?> / <?= anchor('mapa', 'Mapa <u>W</u>eb', array('accesskey'=>'W', "title"=>"Listado de páginas del sitio web")); ?></div>
                                    <div><?= anchor('#', 'Aumentar tamaño <u>+</u>', array('accesskey'=>'+', 'class'=>'aumentar', "title"=>"Aumentar el tamaño del texto")); ?> </div>
                                    <div><?= anchor('#', 'Disminuir tamaño <u>-</u>', array('accesskey'=>'-', 'class'=>'disminuir', "title"=>"Disminuir el tamaño del texto")); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <nav>
                    <div class="navbar navbar-static-top navbar-inverse">
                        <div class="navbar-inner">                   
                            <div class="container">
                                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <div class="nav-collapse collapse">
                                    <ul class="nav">
                                      <?php if($this->uri->segment(1) == '' || $this->uri->segment(1) == 'inicio'):?>  
                                        <li class="active"><?= anchor('inicio', '<u>I</u>nicio', array('accessKey' => 'I', "title"=>"Página principal")); ?></li>
                                      <?php else:?>
                                        <li><?= anchor('inicio', '<u>I</u>nicio', array('accessKey' => 'I', "title"=>"Página principal")); ?></li>
                                      <?php endif;?>

                                      <?php if($this->uri->segment(1) == 'estudio'):?>  
                                        <li class="active"><?= anchor('estudio', 'E<u>s</u>tudio', array('accessKey' => 'S', "title"=>"Presentación del estudio de arquitectura")); ?></li>
                                      <?php else:?>
                                        <li><?= anchor('estudio', 'E<u>s</u>tudio', array('accessKey' => 'S', "title"=>"Presentación del estudio de arquitectura")); ?></li>
                                      <?php endif;?>

                                      <?php if($this->uri->segment(1) == 'proyectos' || $this->uri->segment(1) == 'proyecto'):?>  
                                        <li class="active"><?= anchor('proyectos', '<u>P</u>royectos', array('accessKey' => 'P', "title"=>"Selección de proyectos realizados")); ?> </li>
                                      <?php else:?>
                                        <li><?= anchor('proyectos', '<u>P</u>royectos', array('accessKey' => 'P', "title"=>"Selección de proyectos realizados")); ?> </li>
                                      <?php endif;?>


                                      <?php if($this->uri->segment(1) == 'noticias' || $this->uri->segment(1) == 'noticia'):?>  
                                        <li class="active"><?= anchor('noticias', '<u>N</u>oticias', array('accessKey' => 'N',"title"=>"Sección de noticias del estudio de arquitectura")); ?></li>
                                      <?php else:?>  
                                        <li><?= anchor('noticias', '<u>N</u>oticias', array('accessKey' => 'N', "title"=>"Sección de noticias del estudio de arquitectura")); ?></li>
                                      <?php endif;?> 

                                      <?php if($this->uri->segment(1) == 'contacto'):?>    
                                        <li class='active'><?= anchor('contacto', 'C<u>o</u>ntacto', array('accessKey' => 'O',"title"=>"Formulario de contacto")); ?></li>
                                      <?php else:?>  
                                        <li><?= anchor('contacto', 'C<u>o</u>ntacto', array('accessKey' => 'O', "title"=>"Formulario de contacto")); ?></li>
                                      <?php endif;?>  

                                    </ul>
                                    <ul class="nav pull-right">
                                      <li>
                                        <?= form_open('buscar','class="navbar-search pull-left form-inline"');?>
                                              <label for="buscador" id="buscar" accesskey="U"> B<u>u</u>scar: </label><input type="text" name="buscador" class="search-query" id="buscador">
                                        <?= form_close();?>
                                      </li>
                                      <?php if($this->uri->segment(1) == 'privado' || $this->uri->segment(1) == 'cliente' || $this->uri->segment(1) == 'restablecer' || $this->uri->segment(1) == 'registrar'):?>      
                                        <li class='active'><?= anchor('privado', '<u>C</u>lientes', array('accessKey' => 'C')); ?></li>
                                      <?php else:?>  
                                        <li><?= anchor('privado', '<u>C</u>lientes', array('accessKey' => 'C', 'title'=>"Zona de registro de nuevos clientes y acceso a zona personal")); ?></li>
                                      <?php endif;?>  
                                    </ul>
                                </div>
                            </div>
                        </div>        
                    </div>
                </nav>
