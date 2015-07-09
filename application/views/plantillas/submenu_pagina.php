<section>
    <div class="container-fluid container">
        <div class="row-fluid">
            <div class="span3">                
                <div class="well sidebar-nav">
                    <ul class="nav nav-list"> 
                        <?php if($this->uri->segment(1) == 'rehabilitacion'):?>
                            <li class="active"><?= anchor('rehabilitacion', 'Re<u>h</u>abilitación', array('accesskey'=>'H','accesskey'=>'H',"title"=>"Servicios de rehabilitación ofrecidos y listado de obras de rehabilitación llevadas a cabo.")); ?></li>
                        <?php else:?>    
                            <li><?= anchor('rehabilitacion', 'Re<u>h</u>abilitación', array('accesskey'=>'H',"title"=>"Servicios de rehabilitación ofrecidos y listado de obras de rehabilitación llevadas a cabo.")); ?></li>
                        <?php endif;?> 
                        <?php if($this->uri->segment(1) == 'obra'):?>    
                            <li class="active"><?= anchor('obra', 'Obra nue<u>v</u>a', array('accesskey'=>'V',"title"=>"Servicios de obra nueva ofrecidos y listado de obras nuevas llevadas a cabo.")); ?></li>
                        <?php else:?>
                            <li><?= anchor('obra', 'Obra nue<u>v</u>a', array('accesskey'=>'V',"title"=>"Servicios de obra nueva ofrecidos y listado de obras nuevas llevadas a cabo.")); ?></li>
                        <?php endif;?>
                        <?php if($this->uri->segment(1) == 'locales'):?>      
                            <li class="active"><?= anchor('locales', '<u>L</u>ocales', array('accesskey'=>'L',"title"=>"Servicios de adecuación de locales ofrecidos y listado de adecuación de locales llevadas a cabo.")); ?></li>
                        <?php else:?>    
                            <li><?= anchor('locales', '<u>L</u>ocales', array('accesskey'=>'L',"title"=>"Servicios de adecuación de locales ofrecidos y listado de adecuación de locales llevadas a cabo.")); ?></li>
                        <?php endif;?>
                        <?php if($this->uri->segment(1) == 'eficiencia'):?>      
                            <li class="active"><?= anchor('eficiencia', 'E<u>f</u>iciencia energética', array('accesskey'=>'F',"title"=>"Ventajas de la eficiencia energética")); ?></li>                        
                        <?php else:?>    
                            <li><?= anchor('eficiencia', 'E<u>f</u>iciencia energética', array('accesskey'=>'F',"title"=>"Ventajas de la eficiencia energética")); ?></li>
                        <?php endif;?>
                        <?php if($this->uri->segment(1) == 'informes'):?>     
                            <li class="active"><?= anchor('informes', 'Info<u>r</u>mes', array('accesskey'=>'R',"title"=>"Listado de informes que el estudio de arquitectura puede proporcionar")); ?></li>
                        <?php else:?>
                            <li><?= anchor('informes', 'Info<u>r</u>mes', array('accesskey'=>'R',"title"=>"Listado de informes que el estudio de arquitectura puede proporcionar")); ?></li>
                        <?php endif;?> 
                        <?php if($this->uri->segment(1) == 'subvenciones'):?>        
                            <li class="active"><?= anchor('subvenciones', 'Gestión <u>d</u>e subvenciones', array('accesskey'=>'D',"title"=>"Ayuda en la tramitación de subvenciones")); ?></li>
                        <?php else:?>    
                            <li><?= anchor('subvenciones', 'Gestión <u>d</u>e subvenciones', array('accesskey'=>'D',"title"=>"Ayuda en la tramitación de subvenciones")); ?></li>
                        <?php endif;?>    
                        <?php if($this->uri->segment(1) == 'gestion'):?>    
                            <li class="active"><?= anchor('gestion', '<u>G</u>estión de proyectos', array('accesskey'=>'G','title'=>'Servicios ofrecidos en la gestión de proyectos')); ?></li>
                        <?php else:?>
                            <li><?= anchor('gestion', '<u>G</u>estión de proyectos', array('accesskey'=>'G','title'=>'Servicios ofrecidos en la gestión de proyectos')); ?></li>
                        <?php endif;?>    
                        <?php if($this->uri->segment(1) == 'llave'):?>    
                            <li class="active"><?= anchor('llave', 'Llave en <u>m</u>ano', array('accesskey'=>'M','title'=>'Servicio de gestión de proyectos y realización de obra')); ?></li>
                        <?php else:?>
                            <li><?= anchor('llave', 'Llave en <u>m</u>ano', array('accesskey'=>'M','title'=>'Servicio de gestión de proyectos y realización de obra')); ?></li>
                        <?php endif;?>    
                        <?php if($this->uri->segment(1) == 'ite'):?>    
                            <li class="active"><?= anchor('ite', 'I<u>T</u>E', array('accesskey'=>'H',"title"=>"Inspección Técnica de Edificios")); ?></li>
                        <?php else:?>
                            <li><?= anchor('ite', 'I<u>T</u>E', array('accesskey'=>'T',"title"=>"Inspección Técnica de Edificios")); ?></li>
                        <?php endif;?>    
                    </ul>
                </div>
            </div>