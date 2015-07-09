<div class ="span11">
     <div>
         En esta sección podrás indicar que empleados pueden trabajar en el proyecto.
     </div> 
     <br>
      <?= form_open(); ?>
          <?= form_multiselect('select1[]', $empleados, '', 'id="select1"');?>
          <?= form_multiselect('empleados[]', $empleadosProyectos, '', 'id="select2"');?>
          <br>
          <a href="#" id="add">Añadir <i class="icon-arrow-right"></i></a>    
          <a href="#" id="remove"><i class="icon-arrow-left"></i>  Eliminar </a>
          <div>
            <?= form_submit($boton, 'Cambiar empleados'); ?>
          </div>                   
      <?= form_close(); ?> 
</div>   
