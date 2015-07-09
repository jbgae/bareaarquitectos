#
# TABLE STRUCTURE FOR: Empresas
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Empresas` AS select `Empresa`.`Cif` AS `Cif`,`Empresa`.`RazonSocial` AS `RazonSocial`,ifnull(`Empresa`.`Direccion`,'Desconocida') AS `Direccion`,ifnull(`Empresa`.`Telefono`,'Desconocido') AS `Telefono`,ifnull(`Empresa`.`Fax`,'Desconocido') AS `Fax`,ifnull(`Empresa`.`Descripcion`,'Desconocida') AS `Descripcion`,ifnull(`Empresa`.`Web`,'Desconocida') AS `Web`,(case when (`Empresa`.`Provincia` is not null) then (select `Provincia`.`Provincia` from `Provincia` where (`Empresa`.`Provincia` like `Provincia`.`Codigo`)) else 'Desconocida' end) AS `Provincia`,(case when (`Empresa`.`Ciudad` is not null) then (select `Ciudad`.`Ciudad` from `Ciudad` where (`Empresa`.`Ciudad` like `Ciudad`.`Codigo`)) else 'Desconocida' end) AS `Ciudad`,ifnull(`Empresa`.`Email`,'Desconocida') AS `Email` from `Empresa`;

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Notas
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Notas` AS select `Nota`.`Codigo` AS `Codigo`,`Nota`.`Titulo` AS `Titulo`,`Nota`.`Contenido` AS `Contenido`,`Nota`.`FechaCreacion` AS `FechaCreacion`,`Nota`.`EmailEmpleado` AS `EmailEmpleado`,`Nota`.`CodigoProyecto` AS `CodigoProyecto`,`Nota`.`Permisos` AS `Permisos`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM` from (`Nota` join `Usuario`) where (`Nota`.`EmailEmpleado` like `Usuario`.`Email`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Noticias
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Noticias` AS select `Noticia`.`Codigo` AS `Codigo`,`Noticia`.`Titulo` AS `Titulo`,`Noticia`.`Contenido` AS `Contenido`,`Noticia`.`FechaCreacion` AS `FechaCreacion`,`Noticia`.`EmailAdministrador` AS `EmailAdministrador`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM` from (`Noticia` left join `Usuario` on((`Usuario`.`Email` = `Noticia`.`EmailAdministrador`)));

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Presupuestos
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Presupuestos` AS select `Presupuesto`.`Codigo` AS `Codigo`,`Presupuesto`.`Tipo` AS `Tipo`,`Presupuesto`.`Estado` AS `Estado`,`Presupuesto`.`Direccion` AS `Direccion`,`Presupuesto`.`Descripcion` AS `Descripcion`,(select `Provincia`.`Provincia` from `Provincia` where (`Presupuesto`.`Provincia` like `Provincia`.`Codigo`)) AS `Provincia`,(select `Ciudad`.`Ciudad` from `Ciudad` where (`Presupuesto`.`Ciudad` like `Ciudad`.`Codigo`)) AS `Ciudad`,`Presupuesto`.`EmailCliente` AS `EmailCliente`,(select `Usuario`.`Nombre` from `Usuario` where (`Usuario`.`Email` like `Presupuesto`.`EmailCliente`)) AS `Nombre`,(select `Usuario`.`ApellidoP` from `Usuario` where (`Usuario`.`Email` like `Presupuesto`.`EmailCliente`)) AS `ApellidoP`,(select `Usuario`.`ApellidoM` from `Usuario` where (`Usuario`.`Email` like `Presupuesto`.`EmailCliente`)) AS `ApellidoM`,`Presupuesto`.`Pem` AS `Pem`,`Presupuesto`.`CoeficienteSeguridad` AS `CoeficienteSeguridad`,`Presupuesto`.`Coeficiente` AS `Coeficiente`,`Presupuesto`.`Superficie` AS `Superficie`,`Presupuesto`.`FechaSolicitud` AS `FechaSolicitud`,(select `Archivo`.`Ruta` from `Archivo` where (`Archivo`.`CodigoPresupuesto` like `Presupuesto`.`Codigo`)) AS `Ruta`,(select `Archivo`.`Codigo` from `Archivo` where (`Presupuesto`.`Codigo` like `Archivo`.`CodigoPresupuesto`)) AS `CodigoArchivo` from `Presupuesto`;

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Proyectos
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Proyectos` AS select `Proyecto`.`Codigo` AS `Codigo`,`Proyecto`.`CodigoPresupuesto` AS `CodigoPresupuesto`,`Proyecto`.`FechaComienzo` AS `FechaComienzo`,`Proyecto`.`NombreProyecto` AS `NombreProyecto`,`Proyecto`.`Estado` AS `Estado`,`Proyecto`.`FechaFinPrevista` AS `FechaFinPrevista`,`Proyecto`.`CifConstructora` AS `CifConstructora`,`Proyecto`.`EmailAdmin` AS `EmailAdmin`,`Proyecto`.`Visible` AS `Visible`,`Proyecto`.`Contenido` AS `Contenido`,`Presupuestos`.`Tipo` AS `Tipo`,`Presupuestos`.`Direccion` AS `Direccion`,`Presupuestos`.`Ciudad` AS `Ciudad`,`Presupuestos`.`Provincia` AS `Provincia` from (`Proyecto` join `Presupuestos`) where (`Proyecto`.`CodigoPresupuesto` = `Presupuestos`.`Codigo`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Respuestas
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Respuestas` AS select `Respuesta`.`Codigo` AS `Codigo`,`Respuesta`.`CodigoTarea` AS `CodigoTarea`,`Respuesta`.`Contenido` AS `Contenido`,`Respuesta`.`Email` AS `Email`,`Respuesta`.`Fecha` AS `Fecha`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM`,`Tarea`.`Titulo` AS `Titulo` from ((`Respuesta` join `Usuario`) join `Tarea`) where ((`Respuesta`.`Email` = `Usuario`.`Email`) and (`Respuesta`.`CodigoTarea` = `Tarea`.`Codigo`));

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Usuarios
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Usuarios` AS select `Usuario`.`Email` AS `Email`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM`,`Usuario`.`FechaNacimiento` AS `FechaNacimiento`,`Usuario`.`Pass` AS `Pass`,`Usuario`.`Validado` AS `Validado`,`Usuario`.`FechaAltaSistema` AS `FechaAltaSistema`,`Usuario`.`FechaUltimoAcceso` AS `FechaUltimoAcceso`,`Usuario`.`Tipo` AS `Tipo`,`Usuario`.`NumeroIntentos` AS `NumeroIntentos`,`Usuario`.`FechaUltimoIntento` AS `FechaUltimoIntento`,ifnull(`Usuario`.`Direccion`,'Desconocida') AS `Direccion`,(case when (`Usuario`.`Ciudad` is not null) then (select `Ciudad`.`Ciudad` from `Ciudad` where (`Usuario`.`Ciudad` like `Ciudad`.`Codigo`)) else 'Desconocida' end) AS `Ciudad`,(case when (`Usuario`.`Provincia` is not null) then (select `Provincia`.`Provincia` from `Provincia` where (`Usuario`.`Provincia` like `Provincia`.`Codigo`)) else 'Desconocida' end) AS `Provincia`,ifnull(`Usuario`.`Telefono`,'Desconocida') AS `Telefono` from `Usuario`;

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Archivos
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Archivos` AS select `Archivo`.`Codigo` AS `Codigo`,`Archivo`.`CodigoProyecto` AS `CodigoProyecto`,`Archivo`.`Visible` AS `Visible`,`Archivo`.`CodigoTarea` AS `CodigoTarea`,`Archivo`.`Ruta` AS `Ruta`,`Archivo`.`Nombre` AS `Nombre`,`Archivo`.`Extension` AS `Extension`,`Archivo`.`Tamanyo` AS `Tamanyo`,`Archivo`.`Fecha` AS `Fecha`,`Archivo`.`CodigoRespuesta` AS `CodigoRespuesta`,`Archivo`.`Pertenece` AS `Pertenece`,`Archivo`.`EmailEmpleado` AS `EmailEmpleado`,`Archivo`.`FotoEmpleado` AS `FotoEmpleado`,`Archivo`.`ArchivoProyecto` AS `ArchivoProyecto`,`Usuario`.`Nombre` AS `NombreEmpl`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM` from (`Archivo` join `Usuario`) where (`Archivo`.`EmailEmpleado` = `Usuario`.`Email`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Constructoras
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Constructoras` AS select `Empresas`.`Cif` AS `Cif`,`Empresas`.`RazonSocial` AS `RazonSocial`,`Empresas`.`Direccion` AS `Direccion`,`Empresas`.`Telefono` AS `Telefono`,`Empresas`.`Fax` AS `Fax`,`Empresas`.`Descripcion` AS `Descripcion`,`Empresas`.`Web` AS `Web`,`Empresas`.`Provincia` AS `Provincia`,`Empresas`.`Ciudad` AS `Ciudad`,`Empresas`.`Email` AS `Email`,ifnull(`Constructora`.`Valoracion`,'Desconocido') AS `Valoracion`,(select count(`Proyecto`.`CifConstructora`) from `Proyecto` where (`Proyecto`.`CifConstructora` = `Constructora`.`Cif`)) AS `Proyectos` from (`Empresas` join `Constructora`) where (`Empresas`.`Cif` = `Constructora`.`Cif`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Empleados
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Empleados` AS select `Usuario`.`Email` AS `Email`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM`,`Usuario`.`FechaNacimiento` AS `FechaNacimiento`,`Usuario`.`Pass` AS `Pass`,`Usuario`.`Validado` AS `Validado`,`Usuario`.`FechaAltaSistema` AS `FechaAltaSistema`,`Usuario`.`FechaUltimoAcceso` AS `FechaUltimoAcceso`,`Usuario`.`Tipo` AS `Tipo`,`Usuario`.`NumeroIntentos` AS `NumeroIntentos`,`Usuario`.`FechaUltimoIntento` AS `FechaUltimoIntento`,`Usuario`.`Direccion` AS `Direccion`,`Usuarios`.`Ciudad` AS `Ciudad`,`Usuarios`.`Provincia` AS `Provincia`,`Usuarios`.`Telefono` AS `Telefono`,ifnull(`Empleado`.`Cargo`,'Desconocido') AS `Cargo`,ifnull(`Empleado`.`Salario`,'Desconocido') AS `Salario`,ifnull(`Empleado`.`FechaContratacion`,'Desconocida') AS `FechaContratacion`,ifnull(`Empleado`.`FechaFinContrato`,'Desconocida') AS `FechaFinContrato`,(select count(`ProyectoEmpleados`.`EmailEmpleado`) from `ProyectoEmpleados` where (`ProyectoEmpleados`.`EmailEmpleado` = `Empleado`.`Email`)) AS `Proyectos` from ((`Usuario` join `Usuarios`) join `Empleado`) where ((`Usuarios`.`Email` = `Empleado`.`Email`) and (`Usuario`.`Email` = `Empleado`.`Email`));

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Materiales
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Materiales` AS select `Material`.`Codigo` AS `Codigo`,`Material`.`Nombre` AS `Nombre`,`Material`.`CifProveedor` AS `CifProveedor`,`Material`.`CodigoProyecto` AS `CodigoProyecto`,`Empresa`.`RazonSocial` AS `RazonSocial` from (`Material` join `Empresa`) where (`Material`.`CifProveedor` = `Empresa`.`Cif`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: NotasEmpleados
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `NotasEmpleados` AS select `NotaEmpleados`.`Email` AS `Email`,`NotaEmpleados`.`CodigoNota` AS `CodigoNota`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM` from (`NotaEmpleados` join `Usuario`) where (`NotaEmpleados`.`Email` like `Usuario`.`Email`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Paginas
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Paginas` AS select `Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM`,`Texto`.`Texto` AS `Texto`,`Texto`.`NombrePagina` AS `NombrePagina`,`Texto`.`Posicion` AS `Posicion` from ((`Usuario` join `Texto`) join `Pagina`) where ((`Texto`.`NombrePagina` = `Pagina`.`Nombre`) and (`Pagina`.`EmailAdmin` = `Usuario`.`Email`));

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Proveedores
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Proveedores` AS select `Empresas`.`Cif` AS `Cif`,`Empresas`.`RazonSocial` AS `RazonSocial`,`Empresas`.`Direccion` AS `Direccion`,`Empresas`.`Telefono` AS `Telefono`,`Empresas`.`Fax` AS `Fax`,`Empresas`.`Descripcion` AS `Descripcion`,`Empresas`.`Web` AS `Web`,`Empresas`.`Provincia` AS `Provincia`,`Empresas`.`Ciudad` AS `Ciudad`,`Empresas`.`Email` AS `Email`,ifnull(`Proveedor`.`Servicios`,'Desconocido') AS `Servicios` from (`Empresas` join `Proveedor`) where (`Empresas`.`Cif` = `Proveedor`.`Cif`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: ProyectosEmpleados
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ProyectosEmpleados` AS select `ProyectoEmpleados`.`CodigoProyecto` AS `CodigoProyecto`,`ProyectoEmpleados`.`EmailEmpleado` AS `EmailEmpleado`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM`,`Proyecto`.`NombreProyecto` AS `NombreProyecto` from ((`ProyectoEmpleados` join `Usuario`) join `Proyecto`) where ((`ProyectoEmpleados`.`EmailEmpleado` like `Usuario`.`Email`) and (`ProyectoEmpleados`.`CodigoProyecto` like `Proyecto`.`Codigo`));

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Tareas
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Tareas` AS select `Tarea`.`Codigo` AS `Codigo`,`Tarea`.`Titulo` AS `Titulo`,`Tarea`.`Contenido` AS `Contenido`,`Tarea`.`FechaCreacion` AS `FechaCreacion`,`Tarea`.`Estado` AS `Estado`,`Tarea`.`EmailAdmin` AS `EmailAdmin`,`Tarea`.`CodigoProyecto` AS `CodigoProyecto`,`Tarea`.`FechaLimite` AS `FechaLimite`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM` from (`Tarea` join `Usuario`) where (`Tarea`.`EmailAdmin` = `Usuario`.`Email`);

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Consultores
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Consultores` AS select `Consultor`.`Identificador` AS `Identificador`,`Consultor`.`Nombre` AS `Nombre`,`Consultor`.`ApellidoP` AS `ApellidoP`,`Consultor`.`ApellidoM` AS `ApellidoM`,`Consultor`.`Email` AS `Email`,ifnull(`Consultor`.`Direccion`,'Desconocida') AS `Direccion`,(case when (`Consultor`.`Ciudad` is not null) then (select `Ciudad`.`Ciudad` from `Ciudad` where (`Consultor`.`Ciudad` like `Ciudad`.`Codigo`)) else 'Desconocida' end) AS `Ciudad`,(case when (`Consultor`.`Provincia` is not null) then (select `Provincia`.`Provincia` from `Provincia` where (`Consultor`.`Provincia` like `Provincia`.`Codigo`)) else 'Desconocida' end) AS `Provincia`,ifnull(`Consultor`.`Fax`,'Desconocido') AS `Fax`,ifnull(`Consultor`.`Telefono`,'Desconocido') AS `Telefono`,ifnull(`Consultor`.`Especialidad`,'Desconocida') AS `Especialidad` from `Consultor`;

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Clientes
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Clientes` AS select `Usuarios`.`Email` AS `Email`,`Usuarios`.`Nombre` AS `Nombre`,`Usuarios`.`ApellidoP` AS `ApellidoP`,`Usuarios`.`ApellidoM` AS `ApellidoM`,`Usuarios`.`FechaNacimiento` AS `FechaNacimiento`,`Usuarios`.`Pass` AS `Pass`,`Usuarios`.`Validado` AS `Validado`,`Usuarios`.`FechaAltaSistema` AS `FechaAltaSistema`,`Usuarios`.`FechaUltimoAcceso` AS `FechaUltimoAcceso`,`Usuarios`.`Tipo` AS `Tipo`,`Usuarios`.`NumeroIntentos` AS `NumeroIntentos`,`Usuarios`.`FechaUltimoIntento` AS `FechaUltimoIntento`,`Usuarios`.`Direccion` AS `Direccion`,`Usuarios`.`Ciudad` AS `Ciudad`,`Usuarios`.`Provincia` AS `Provincia`,`Usuarios`.`Telefono` AS `Telefono`,(select count(`Presupuesto`.`EmailCliente`) from `Presupuesto` where (`Presupuesto`.`EmailCliente` like `Usuarios`.`Email`)) AS `Presupuestos`,(select count(`Proyecto`.`CodigoPresupuesto`) from (`Proyecto` join `Presupuesto`) where ((`Proyecto`.`CodigoPresupuesto` = `Presupuesto`.`Codigo`) and (`Presupuesto`.`EmailCliente` = `Usuarios`.`Email`))) AS `Proyectos` from `Usuarios` where (`Usuarios`.`Tipo` = 'cliente');

utf8_general_ci;

#
# TABLE STRUCTURE FOR: Chat_mensajes
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Chat_mensajes` AS select `Chat`.`Id` AS `Id`,`Chat`.`EmailEmpleado` AS `EmailEmpleado`,`Chat`.`Mensaje` AS `Mensaje`,`Chat`.`Fecha` AS `Fecha`,`Usuario`.`Nombre` AS `Nombre`,`Usuario`.`ApellidoP` AS `ApellidoP`,`Usuario`.`ApellidoM` AS `ApellidoM` from (`Chat` join `Usuario`) where (`Chat`.`EmailEmpleado` like `Usuario`.`Email`);

utf8_general_ci;

