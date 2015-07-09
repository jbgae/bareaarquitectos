<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['admin'] = "usuario/iniciarSesion";
$route['administrador'] = "usuario/iniciarSesion";
$route['admin/copia'] = "backup/copia";
$route['admin/calendario/dia/(:num)/(:num)/(:num)/(:num)'] = 'evento/calendarioDia/$1/$2/$3/$4';
$route['admin/calendario/dia/(:num)'] = 'evento/calendarioDia/$1';
$route['admin/calendario/semana/(:num)/(:num)/(:num)/(:num)'] = 'evento/calendarioSemana/$1/$2/$3/$4';
$route['admin/calendario/semana/(:num)'] = 'evento/calendarioSemana/$1';
$route['admin/calendario/(:num)/(:num)/(:num)'] = 'evento/calendarioMes/$1/$2/$3';
$route['admin/calendario/(:num)'] = 'evento/calendarioMes/$1';
$route['admin/calendario/crear'] = 'evento/registrar';
$route['admin/calendario'] = 'evento/calendarioMes';
$route['admin/chat'] = 'chat/index';
$route['admin/clientes/borrar/(:any)'] = 'usuario/borrar/$1'; 
$route['admin/clientes/borrar'] = 'usuario/borrar'; 
$route['admin/clientes/buscar/(:any)/(:any)/(:any)/(:any)'] = 'cliente/buscar/$1/$2/$3/$4';
$route['admin/clientes/buscar'] = 'cliente/buscar';
$route['admin/clientes/crear'] = 'cliente/registrar';
$route['admin/clientes/editar/(:any)'] = 'cliente/modificar/$1';
$route['admin/clientes/enviar/(:any)'] = 'empleado/email/$1';
$route['admin/clientes/(:any)/(:any)/(:num)/(:num)'] = 'cliente/listar/$1/$2/$3/$4';
$route['admin/clientes/(:any)/(:any)'] = 'cliente/listar/$1/$2';
$route['admin/clientes'] = 'cliente/listar';


$route['admin/constructora/borrar/(:any)'] = 'empresa/borrar/$1';
$route['admin/constructora/borrar'] = 'empresa/borrar';
$route['admin/constructora/buscar/(:num)'] = 'constructora/buscar/$1';
$route['admin/constructora/buscar'] = 'constructora/buscar';
$route['admin/constructora/crear'] = 'constructora/registrar';
$route['admin/constructora/editar/(:any)'] = 'constructora/modificar/$1';
$route['admin/constructora/enviar/(:any)'] = 'empleado/email/$1';
$route['admin/constructora/(:any)/(:any)/(:num)/(:num)'] = 'constructora/listar/$1/$2/$3/$4';
$route['admin/constructora/(:any)/(:any)'] = 'constructora/listar/$1/$2';
$route['admin/constructora'] = 'constructora/listar';

$route['admin/copia'] = 'backup/copia';
$route['admin/copia_seguridad'] = 'backup/copiaSeguridad';
$route['admin/restaurar'] = 'backup/restaurar';


$route['admin/consultor/borrar/(:any)'] = 'consultor/borrar/$1';
$route['admin/consultor/borrar'] = 'consultor/borrar';
$route['admin/consultor/buscar/(:any)/(:any)/(:any)/(:any)'] = 'consultor/buscar/$1/$2/$3/$4';
$route['admin/consultor/buscar'] = 'consultor/buscar';
$route['admin/consultor/crear'] = 'consultor/registrar';
$route['admin/consultor/editar/(:any)'] = 'consultor/modificar/$1';
$route['admin/consultor/enviar/(:any)'] = 'empleado/email/$1';
$route['admin/consultor/(:any)/(:any)/(:num)/(:num)'] = 'consultor/listar/$1/$2/$3/$4';
$route['admin/consultor/(:any)/(:any)'] = 'consultor/listar/$1/$2';
$route['admin/consultor'] = 'consultor/listar';

$route['admin/datos'] = 'empleado/modificar';

$route['admin/empleados/borrar/(:any)'] = 'empleado/borrar/$1';
$route['admin/empleados/borrar'] = 'empleado/borrar';
$route['admin/empleados/buscar/(:any)/(:any)/(:any)/(:any)'] = 'empleado/buscar/$1/$2/$3/$4';
$route['admin/empleados/buscar'] = 'empleado/buscar';
$route['admin/empleados/crear'] = 'empleado/registrar';
$route['admin/empleados/editar/(:any)'] = 'empleado/modificar/$1';
$route['admin/empleados/enviar/(:any)'] = 'empleado/email/$1';
$route['admin/empleados/(:any)/(:any)/(:num)/(:num)'] = 'empleado/listar/$1/$2/$3/$4';
$route['admin/empleados/(:any)/(:any)'] = 'empleado/listar/$1/$2';
$route['admin/empleados'] = 'empleado/listar';


$route['admin/noticias/borrar/(:num)'] =  'noticias/borrar/$1';
$route['admin/noticias/borrar'] = 'noticias/borrar';
$route['admin/noticias/buscar/(:num)'] = 'noticias/buscar/$1';
$route['admin/noticias/buscar'] = 'noticias/buscar';
$route['admin/noticias/crear'] = 'noticias/registrar';
$route['admin/noticias/editar/(:num)'] = 'noticias/modificar/$1';
$route['admin/noticias/(:any)/(:any)/(:num)/(:num)'] = 'noticias/listar/$1/$2/$3/$4';
$route['admin/noticias/(:any)/(:any)'] = 'noticias/listar/$1/$2';
$route['admin/noticias'] = 'noticias/listar';

$route['administrador/novedades'] = 'admin/novedades';

$route['admin/material/eliminar/(:num)/(:num)'] = 'material/eliminar/$1/$2';

$route['admin/presupuesto/borrar/(:any)'] = 'presupuesto/borrar/$1'; 
$route['admin/presupuesto/borrar'] = 'presupuesto/borrar'; 
$route['admin/presupuesto/buscar/(:any)/(:any)/(:any)/(:any)'] = 'presupuesto/buscar/$1/$2/$3/$4';
$route['admin/presupuesto/buscar'] = 'presupuesto/buscar';
$route['admin/presupuesto/crear/(:num)'] = 'presupuesto/modificar/$1';
$route['admin/presupuesto/crear'] = 'presupuesto/registrar';
$route['admin/presupuesto/descargar/(:num)'] = 'archivo/descargar/$1';
$route['admin/presupuesto/(:any)/(:any)/(:num)/(:num)'] = 'presupuesto/listar/$1/$2/$3/$4';
$route['admin/presupuesto/(:any)/(:any)'] = 'presupuesto/listar/$1/$2';
$route['admin/presupuesto'] = 'presupuesto/listar';

$route['admin/proveedor/borrar/(:any)'] = 'empresa/borrar/$1';
$route['admin/proveedor/borrar'] = 'empresa/borrar';
$route['admin/proveedor/buscar/(:num)'] = 'proveedor/buscar/$1';
$route['admin/proveedor/buscar'] = 'proveedor/buscar';
$route['admin/proveedor/crear/(:num)'] = 'proveedor/registrar/$1';
$route['admin/proveedor/crear'] = 'proveedor/registrar/';
$route['admin/proveedor/editar/(:any)'] = 'proveedor/modificar/$1';
$route['admin/proveedor/enviar/(:any)'] = 'empleado/email/$1';
$route['admin/proveedor/(:any)/(:any)/(:num)/(:num)'] = 'proveedor/listar/$1/$2/$3/$4';
$route['admin/proveedor/(:any)/(:any)'] = 'proveedor/listar/$1/$2';
$route['admin/proveedor'] = 'proveedor/listar';


$route['admin/proyecto/archivos/(:num)/(:num)'] = 'archivo/listar/$1/$2';
$route['admin/proyecto/archivos/(:num)'] = 'archivo/listar/$1';
$route['admin/proyecto/archivos/crear/(:num)/(:num)'] = 'archivo/crear/$1/$2';
$route['admin/proyecto/archivos/crear/(:num)'] = 'archivo/crear/$1';
$route['admin/proyecto/archivos/editar/(:num)/(:num)'] = 'archivo/editar/$1/$2';
$route['admin/proyecto/archivos/descargar/(:num)'] = 'archivo/descargar/$1';
$route['admin/proyecto/archivos/eliminar/(:num)/(:any)/(:num)'] = 'archivo/borrar/$1/$2/$3';
$route['admin/proyecto/archivos/eliminar/(:num)/(:any)'] = 'archivo/borrar/$1/$2';
$route['admin/proyecto/archivos/eliminar/(:num)'] = 'archivo/borrar/$1';
$route['admin/proyecto/archivos/registrar/(:num)/(:num)'] = 'archivo/registrar/$1/$2';
$route['admin/proyecto/archivos/registrar/(:num)'] = 'archivo/registrar/$1';
$route['admin/proyecto/descargar/archivos/(:num)/(:num)'] = 'archivo/descargarZip/$1/$2';
$route['admin/proyecto/descargar/archivos/(:num)'] = 'archivo/descargarZip/$1';
$route['admin/proyecto/info/(:num)'] = 'proyecto/informacion/$1';
$route['admin/proyecto/informe/(:num)'] = 'proyecto/informe/$1';
$route['admin/proyecto/tarea/(:num)/(:num)'] = 'tarea/verTarea/$1/$2';
$route['admin/proyecto/tareas/(:num)'] = 'tarea/registrar/$1';
$route['admin/proyecto/tareas/borrar/(:num)/(:num)'] = 'tarea/borrar/$1/$2';
$route['admin/proyecto/tareas/editar/(:num)/(:num)'] = 'tarea/editar/$1/$2';
$route['admin/proyecto/respuesta/borrar/(:num)/(:num)'] = 'respuesta/borrar/$1/$2';
$route['admin/proyecto/nota/(:num)/(:num)'] = 'notas/nota/$1/$2';
$route['admin/proyecto/notas/(:num)'] = 'notas/registrar/$1';
$route['admin/proyecto/notas/borrar/(:num)/(:num)'] = 'notas/borrar/$1/$2';
$route['admin/proyecto/notas/editar/(:num)/(:num)'] = 'notas/editar/$1/$2';
$route['admin/proyecto/empleados/(:num)'] = 'proyecto/empleados/$1';
$route['admin/proyecto/borrar/(:any)'] = 'proyecto/borrar/$1'; 
$route['admin/proyecto/borrar'] = 'proyecto/borrar'; 
$route['admin/proyecto/buscar/(:any)/(:any)/(:any)/(:any)'] = 'proyecto/buscar/$1/$2/$3/$4';
$route['admin/proyecto/buscar'] = 'proyecto/buscar';
$route['admin/proyecto/crear/(:num)'] = 'proyecto/registrar/$1';
$route['admin/proyecto/crear'] = 'proyecto/registrar';
//$route['admin/proyecto/descargar/(:num)'] = 'proyecto/descargar/$1';
$route['admin/proyecto/respuesta/borrar/(:num)/(:num)/(:num)'] = 'tarea/borrarRespuesta/$1/$2/$3';
$route['admin/proyecto/(:any)/(:any)/(:num)/(:num)'] = 'proyecto/listar/$1/$2/$3/$4';
$route['admin/proyecto/(:any)/(:any)'] = 'proyecto/listar/$1/$2';
$route['admin/proyecto/(:num)'] = 'proyecto/listar/$1';
$route['admin/proyecto'] = 'proyecto/listar';

$route['admin/web/buscar/(:any)/(:any)/(:any)/(:any)'] = 'paginas/buscar/$1/$2/$3/$4';
$route['admin/web/buscar'] = 'paginas/buscar';
$route['admin/web/editar/(:any)/(:any)'] = 'paginas/modificar/$1/$2';
$route['admin/web/(:any)/(:any)/(:num)/(:num)'] = 'paginas/listar/$1/$2/$3/$4';
$route['admin/web/(:any)/(:any)'] = 'paginas/listar/$1/$2';
$route['admin/web'] = 'paginas/listar';

$route['empleado'] = "usuario/iniciarSesion";
$route['empleado/datos'] = 'empleado/modificar';
$route['empleados/calendario/dia/(:num)/(:num)/(:num)/(:num)'] = 'evento/calendarioDia/$1/$2/$3/$4';
$route['empleados/calendario/dia/(:num)'] = 'evento/calendarioDia/$1';
$route['empleados/calendario/semana/(:num)/(:num)/(:num)/(:num)'] = 'evento/calendarioSemana/$1/$2/$3/$4';
$route['empleados/calendario/semana/(:num)'] = 'evento/calendarioSemana/$1';
$route['empleados/calendario/(:num)/(:num)/(:num)'] = 'evento/calendarioMes/$1/$2/$3';
$route['empleados/calendario/(:num)'] = 'evento/calendarioMes/$1';
$route['empleados/calendario/crear'] = 'evento/registrar';
$route['empleados/calendario'] = 'evento/calendarioMes';
$route['empleados/cerrar'] = 'usuario/cerrarSesion';
$route['empleados/chat'] = 'chat/index';
$route['empleados/clientes/buscar/(:any)/(:any)/(:any)/(:any)'] = 'cliente/buscar/$1/$2/$3/$4';
$route['empleados/clientes/buscar'] = 'cliente/buscar';
$route['empleados/clientes/crear'] = 'cliente/registrar';
$route['empleados/clientes/editar/(:any)'] = 'cliente/modificar/$1';
$route['empleados/clientes/enviar/(:any)'] = 'empleado/email/$1';
$route['empleados/clientes/(:any)/(:any)/(:num)/(:num)'] = 'cliente/listar/$1/$2/$3/$4';
$route['empleados/clientes/(:any)/(:any)'] = 'cliente/listar/$1/$2';
$route['empleados/clientes'] = 'cliente/listar';
$route['empleados/consultor/buscar/(:any)/(:any)/(:any)/(:any)'] = 'consultor/buscar/$1/$2/$3/$4';
$route['empleados/consultor/buscar'] = 'consultor/buscar';
$route['empleados/consultor/crear'] = 'consultor/registrar';
$route['empleados/consultor/editar/(:any)'] = 'consultor/modificar/$1';
$route['empleados/consultor/enviar/(:any)'] = 'empleado/email/$1';
$route['empleados/consultor/(:any)/(:any)/(:num)/(:num)'] = 'consultor/listar/$1/$2/$3/$4';
$route['empleados/consultor/(:any)/(:any)'] = 'consultor/listar/$1/$2';
$route['empleados/consultor'] = 'consultor/listar';
$route['empleados/novedades'] = 'empleado/novedades';
$route['empleados/proyecto/buscar/(:any)/(:any)/(:any)/(:any)'] = 'proyecto/buscar/$1/$2/$3/$4';
$route['empleados/proyecto/buscar'] = 'proyecto/buscar';
$route['empleados/proyecto'] = 'proyecto/listar';
$route['empleados/proyecto/respuesta/borrar/(:num)/(:num)/(:num)'] = 'tarea/borrarRespuesta/$1/$2/$3';
$route['empleados/proyecto/descargar/archivos/(:num)/(:num)'] = 'archivo/descargarZip/$1/$2';
$route['empleados/proyecto/descargar/archivos/(:num)'] = 'archivo/descargarZip/$1';
$route['empleados/constructora/buscar/(:num)'] = 'constructora/buscar/$1';
$route['empleados/constructora/buscar'] = 'constructora/buscar';
$route['empleados/constructora/crear'] = 'constructora/registrar';
$route['empleados/constructora/editar/(:any)'] = 'constructora/modificar/$1';
$route['empleados/constructora/enviar/(:any)'] = 'empleado/email/$1';
$route['empleados/constructora/(:any)/(:any)/(:num)/(:num)'] = 'constructora/listar/$1/$2/$3/$4';
$route['empleados/constructora/(:any)/(:any)'] = 'constructora/listar/$1/$2';
$route['empleados/constructora'] = 'constructora/listar';

$route['empleados/proveedor/borrar/(:any)'] = 'empresa/borrar/$1';
$route['empleados/proveedor/borrar'] = 'empresa/borrar';
$route['empleados/proveedor/buscar/(:num)'] = 'proveedor/buscar/$1';
$route['empleados/proveedor/buscar'] = 'proveedor/buscar';
$route['empleados/proveedor/crear/(:num)'] = 'proveedor/registrar/$1';
$route['empleados/proveedor/crear'] = 'proveedor/registrar/';
$route['empleados/proveedor/editar/(:any)'] = 'proveedor/modificar/$1';
$route['empleados/proveedor/enviar/(:any)'] = 'empleado/email/$1';
$route['empleados/proveedor/(:any)/(:any)/(:num)/(:num)'] = 'proveedor/listar/$1/$2/$3/$4';
$route['empleados/proveedor/(:any)/(:any)'] = 'proveedor/listar/$1/$2';
$route['empleados/proveedor'] = 'proveedor/listar';

$route['empleados/proyecto/archivos/(:num)'] = 'archivo/listar/$1';
$route['empleados/proyecto/archivos/(:num)/(:num)'] = 'archivo/listar/$1/$2';
$route['empleados/proyecto/archivos/crear/(:num)/(:num)'] = 'archivo/crear/$1/$2';
$route['empleados/proyecto/archivos/crear/(:num)'] = 'archivo/crear/$1';
$route['empleados/proyecto/archivos/editar/(:num)/(:num)'] = 'archivo/editar/$1/$2';
$route['empleados/proyecto/archivos/descargar/(:num)'] = 'archivo/descargar/$1';
$route['empleados/proyecto/archivos/eliminar/(:num)/(:any)/(:num)'] = 'archivo/borrar/$1/$2/$3';
$route['empleados/proyecto/archivos/eliminar/(:num)/(:any)'] = 'archivo/borrar/$1/$2';
$route['empleados/proyecto/archivos/eliminar/(:num)'] = 'archivo/borrar/$1';
$route['empleados/proyecto/archivos/registrar/(:num)/(:num)'] = 'archivo/registrar/$1/$2';
$route['empleados/proyecto/archivos/registrar/(:num)'] = 'archivo/registrar/$1';
$route['empleados/proyecto/nota/(:num)/(:num)'] = 'notas/nota/$1/$2';
$route['empleados/proyecto/notas/(:num)'] = 'notas/registrar/$1';
$route['empleados/proyecto/notas/borrar/(:num)/(:num)'] = 'notas/borrar/$1/$2';
$route['empleados/proyecto/notas/editar/(:num)/(:num)'] = 'notas/editar/$1/$2';

$route['empleados/proyecto/tareas/(:num)'] = 'tarea/tareas/$1';
$route['empleados/proyecto/tarea/(:num)/(:num)'] = 'tarea/verTarea/$1/$2';

$route['cliente/cerrar'] = 'usuario/cerrarSesion';
$route['cliente/cesta/actualizar/(:any)'] = 'carro/actualizarCarro/$1';
$route['cliente/cesta/exito'] = 'carro/exito';
$route['cliente/cesta/error'] = 'carro/error';
$route['cliente/cesta'] = 'carro/mostrarCarro';
$route['cliente/presupuesto/carro/(:num)'] = 'carro/anadirCarro/$1';
$route['cliente/presupuesto/descargar/(:num)'] = 'archivo/descargar/$1';
$route['cliente/presupuesto/solicitar'] = 'presupuesto/registrar';
$route['cliente/presupuesto/listado'] = 'presupuesto/listar';
$route['cliente/proyecto/listado'] = 'proyecto/listar';
$route['cliente/proyecto/descargar/(:num)'] = 'archivo/descargar/$1';


$route['accesibilidad'] = "paginas/accesibilidad";
$route['buscar/(:any)/(:any)/(:any)/(:any)'] = 'paginas/buscar/$1/$2/$3/$4';
$route['buscar'] = "paginas/buscar";
$route['certificado'] = "paginas/certificado";
$route['contacto'] = "paginas/contacto";
$route['estudio'] = "paginas/estudio";
$route['informes'] = "paginas/informes";
$route['inicio'] = "paginas/inicio";
$route['ite'] = "paginas/ite";
$route['eficiencia'] = "paginas/eficiencia";
$route['gestion'] = "paginas/gestion";
$route['llave'] = "paginas/llave";
$route['locales'] = "paginas/locales";
$route['mapa'] = "paginas/mapa";
$route['noticia/(:num)'] = "noticias/noticia/$1";
$route['noticias/(:num)'] = "noticias/listar/FechaCreacion/desc/$1";
$route['noticias'] = "noticias/listar";
$route['obra'] = "paginas/obra";
$route['politica'] = 'paginas/politica';
$route['validar/(:any)'] = 'cliente/validar/$1';
$route['privado'] = "usuario/iniciarSesion";
$route['proyectos'] = "proyecto/proyectos";
$route['rehabilitacion'] = "paginas/rehabilitacion";
$route['registrar'] = "cliente/registrar";
$route['restablecer'] = "cliente/restablecer";
$route['subvenciones'] = "paginas/subvenciones";


$route['default_controller'] = "paginas/inicio";
$route['404_override'] = 'paginas/my404';


/* End of file routes.php */
/* Location: ./application/config/routes.php */