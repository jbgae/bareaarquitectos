<?php if(isset($nombreProyecto)):?>

<div>
    <legend> <?= ucfirst($nombreProyecto);?> </legend>
</div>
<div class="tabbable span10"> 
    <ul class="nav nav-tabs">
        <?php if($this->uri->segment(3) == 'tareas' || $this->uri->segment(3) == 'tarea'):?>
            <?php if($user == 'admin'):?>
                <li>
                    <?= anchor('admin/proyecto/info/'.$codigo, '<i class="icon-book"></i> Información');?>
                </li>    
               <!-- <li>
                    <?// anchor('admin/proyecto/fases/'.$codigo, '<i class="icon-list-alt"></i> Fases');?>              
                </li>-->
            <?php endif;?>    
            <li class="active">
                <a href="" class="disabled">
                    <i class="icon-white icon-tasks"></i> Tareas
                </a>
            </li>
            <li>
                <?= anchor("$user/proyecto/notas/".$codigo, '<i class="icon-comment"></i> Notas');?>
            </li>
            <li>
                <?= anchor("$user/proyecto/archivos/".$codigo, '<i class="icon-file"></i> Archivos');?>
            </li>
            <?php if($user == 'admin'):?>
                <li>
                    <?= anchor('admin/proyecto/empleados/'.$codigo, '<i class="icon-user"></i> Empleados');?>
                </li>
            <?php endif;?> 
        <?php elseif($this->uri->segment(3) == 'archivos'):?>
            <?php if($user == 'admin'):?>
                <li>
                    <?= anchor('admin/proyecto/info/'.$codigo, '<i class="icon-book"></i> Información');?>
                </li>
                <!--<li>
                    <?// anchor('admin/proyecto/fases/'.$codigo, '<i class="icon-list-alt"></i> Fases');?>              
                </li>-->
            <?php endif;?> 
            <li>
                <?= anchor("$user/proyecto/tareas/".$codigo, '<i class="icon-tasks"></i> Tareas');?>
            </li>
            <li>
                <?= anchor("$user/proyecto/notas/".$codigo, '<i class="icon-comment"></i> Notas');?>
            </li>
            <li class="active">
                <a href="" class="disabled">
                    <i class="icon-white icon-file"></i> Archivos
                </a>
            </li>
            <?php if($user == 'admin'):?>
                <li>
                    <?= anchor('admin/proyecto/empleados/'.$codigo, '<i class="icon-user"></i> Empleados');?>
                </li>
            <?php endif;?> 
        <?php elseif($this->uri->segment(3) == 'empleados' && $user == 'admin'):?>
            <li>
                <?= anchor('admin/proyecto/info/'.$codigo, '<i class="icon-book"></i> Información');?>
            </li>
            <!--<li>
                <?// anchor('admin/proyecto/fases/'.$codigo, '<i class="icon-list-alt"></i> Fases');?>              
            </li>-->
            <li>
                <?= anchor('admin/proyecto/tareas/'.$codigo, '<i class="icon-tasks"></i> Tareas');?>
            </li>
            <li>
                <?= anchor('admin/proyecto/notas/'.$codigo, '<i class="icon-comment"></i> Notas');?>
            </li>
            <li>
                <?= anchor('admin/proyecto/archivos/'.$codigo, '<i class="icon-file"></i> Archivos');?>
            </li>
            <li class="active">
                <a href="" class="disabled">
                    <i class="icon-white icon-user"></i> Empleados
                </a>
            </li>
        <?php elseif($this->uri->segment(3) == 'notas' || $this->uri->segment(3) == 'nota'):?> 
            <?php if($user == 'admin'):?>
                <li>
                    <?= anchor('admin/proyecto/info/'.$codigo, '<i class="icon-book"></i> Información');?>
                </li>
                <!--<li>
                    <? // anchor('admin/proyecto/fases/'.$codigo, '<i class="icon-list-alt"></i> Fases');?>              
                </li>-->
            <?php endif;?>
            <li>
                <?= anchor("$user/proyecto/tareas/".$codigo, '<i class="icon-tasks"></i> Tareas');?>
            </li>
            <li class="active">
                <a href="" class="disabled">
                    <i class="icon-white icon-comment"></i> Notas
                </a>
            </li>
            <li>
                <?= anchor("$user/proyecto/archivos/".$codigo, '<i class="icon-file"></i> Archivos');?>
            </li>
            <?php if($user == 'admin'):?>
                <li>
                    <?= anchor('admin/proyecto/empleados/'.$codigo, '<i class="icon-user"></i> Empleados');?>
                </li>
            <?php endif;?>
        <?php elseif($this->uri->segment(3) == 'info'):?> 
            <?php if($user == 'admin'):?>
                <li class="active">
                    <a href="" class="disabled">
                        <i class="icon-white icon-book"></i> Información
                    </a>
                </li>
                <!--<li>
                    <? //anchor('admin/proyecto/fases/'.$codigo, '<i class="icon-list-alt"></i> Fases');?>              
                </li>-->
            <?php endif;?>
            <li>
                <?= anchor("$user/proyecto/tareas/".$codigo, '<i class="icon-tasks"></i> Tareas');?>
            </li>
            <li>
                <?= anchor("$user/proyecto/notas/".$codigo, '<i class="icon-comment"></i> Notas');?>
            </li>
            <li>
                <?= anchor("$user/proyecto/archivos/".$codigo, '<i class="icon-file"></i> Archivos');?>
            </li>
            <?php if($user == 'admin'):?>
                <li>
                    <?= anchor('admin/proyecto/empleados/'.$codigo, '<i class="icon-user"></i> Empleados');?>
                </li>
            <?php endif;?>
        <?php endif;?>
    </ul>
</div>

<?php endif;?>