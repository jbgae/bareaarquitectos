<!DOCTYPE html>
<html lang ="es">
    <head>
        <title>Estudio de arquitectura Barea Arquitectos.- <?= ucfirst($titulo);?></title>
	<meta name="description" content="Estudio de arquitectura, urbanismo e ingeniería ubicado en Chiclana de la Frontera(Cádiz). Compuesto por profesionales que llevan más de una década desarrollando todos los servicios propios de la arquitectura en las provincias de Cádiz, Sevilla y Córdoba.">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" href="<?= base_url();?>images/favicon.ico">
        <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic" rel="stylesheet" type="text/css">
        <!--ESTILOS-->
        <!--[if lt IE 9]>
            <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?= base_url();?>css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?= base_url();?>css/bootstrap-responsive.css">

	<?php if($carpeta == 'administrador' || $carpeta == 'empleado'):?>
	        <link rel="stylesheet" type="text/css" href="<?= base_url();?>css/backend.css">
	<?php else:?>
 		<link rel="stylesheet" type="text/css" href="<?= base_url();?>css/general.css">
	<?php endif;?>
        <?php if(isset($estilo)):?>
            <?php if(!is_array($estilo)):?>
                <link rel="stylesheet" type="text/css" href="<?= base_url();?>css/<?= $estilo;?>.css">
            <?php else:?>
                <?php foreach($estilo as $css):?>
                    <link rel="stylesheet" type="text/css" href="<?= base_url();?>css/<?= $css;?>.css">
                <?php endforeach;?>
            <?php endif;?>        
        <?php endif;?>
        
        <!--SCRIPTS-->
        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>-->
        <script src="<?= base_url();?>js/jquery.js" type="text/javascript"></script>
        <script src="<?= base_url();?>js/bootstrap.js" type="text/javascript"></script>
        <script src="<?= base_url();?>js/bootstrap.file-input.js" type="text/javascript"></script>
        <script src="<?= base_url();?>js/confirmacion.js" type="text/javascript"></script>
        <script src="<?= base_url();?>js/tooltip.js" type="text/javascript"></script>
        <script src="http://js.pusher.com/1.12/pusher.min.js" type="text/javascript"></script>

        <?php if(isset($javascript)):?>
            <?php if(!is_array($javascript)):?>
                <script src="<?= base_url();?>js/<?= $javascript;?>.js" type="text/javascript"></script>
            <?php else:?>
                <?php foreach($javascript as $js):?>
                    <script src="<?= base_url();?>js/<?= $js;?>.js" type="text/javascript"></script>
                <?php endforeach;?>
            <?php endif;?>        
        <?php endif;?>
    </head>
    <body>        
        
