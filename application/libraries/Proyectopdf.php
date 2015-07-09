<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define('FPDF_FONTPATH', 'fonts');
require('fpdf.php');

class Proyectopdf extends FPDF{

    function Cabecera($nombre){
        $flotado_x = 20;
        $flotado_y = 18;
        $ancho = 20;
        $this->Image(BASEPATH.'/../images/logo2.png',$flotado_x, $flotado_y, $ancho);
        
        // Título
        $this->Ln(15);
        $this->SetFont('Arial','B',20);        
        $this->Cell(20);
        $this->Cell(0,0,'Barea Arquitectos',0,0,'L');
       
        //Subtitulo
        $this->Ln(7);
        $this->SetFont('Arial','B',10);
        $this->Cell(20);
        $this->Cell(0,0,  utf8_decode('Estudio de arquitectura, urbanismo e ingeniería'),0,0,'L');
        
        $this->Ln(15);
        $this->SetFont('Arial','B',12);
        $this->Cell(1); // Movernos a la derecha   
        $this->Cell(0,0,  utf8_decode("PROYECTO: $nombre "),0,0,'L');
        
        
        // Salto de línea
        $this->Ln(10);
    }
    
    function Cuerpo($proyecto, $presupuesto, $tareas, $empleados){
        $this->Ln(1);
        $this->SetFont('Arial','B',13);
        $this->Cell(50,0,  utf8_decode($presupuesto->Nombre.' '.$presupuesto->ApellidoP.' '.$presupuesto->ApellidoM),0,0,'L');
        $this->Ln(6);
        $this->SetFont('Arial','B',11);
        $this->Cell(50,0,  utf8_decode($presupuesto->Ciudad.' ('.$presupuesto->Provincia.')' ),0,0,'L');
        $this->Ln(15);
        
        $this->SetFont('Arial','',11);
        $this->Cell(1); // Movernos a la derecha
        $this->SetTextColor(6,6,6);
        $this->Cell(40,0,  utf8_decode("Fecha de soicitud:"),0,0,'L');
        $this->SetFont('Arial','B',11);
        $this->Cell(20,0,  utf8_decode(date("d/m/Y", strtotime($presupuesto->FechaSolicitud))),0,0,'L');  
        
        $this->Ln(6);
        
        $this->Cell(1); // Movernos a la derecha
        $this->SetFont('Arial','',11);
        $this->SetTextColor(6,6,6);
        $this->Cell(40,0,  utf8_decode("Precio:"),0,0,'L');
        $this->SetFont('Arial','B',12);
        $this->Cell(20,0,  utf8_decode($presupuesto->precio(). ' euros'),0,0,'L');
       
        $this->Ln(6);
        
        $this->Cell(1); // Movernos a la derecha
        $this->SetFont('Arial','',11);
        $this->SetTextColor(6,6,6);
        $this->Cell(40,0,  utf8_decode("Estado:"),0,0,'L');
        if($proyecto->Estado == 'Ejecución'){
            $this->SetTextColor(0,170,0);
            $this->SetFont('Arial','B',12);
            $this->Cell(20,0,  utf8_decode($proyecto->Estado),0,0,'L');            
        }
        else{
            $this->SetTextColor(220,50,50);
            $this->SetFont('Arial','B',12);
            $this->Cell(20,0,  utf8_decode($proyecto->Estado),0,0,'L');   
        }
        
        $this->Ln(6);
        $this->SetFont('Arial','',11);
        $this->Cell(1); // Movernos a la derecha
        $this->SetTextColor(6,6,6);
        $this->Cell(40,0,  utf8_decode("Fecha de comienzo:"),0,0,'L');
        $this->SetFont('Arial','B',11);
        $this->Cell(20,0,  utf8_decode(date("d/m/Y", strtotime($proyecto->FechaComienzo))),0,0,'L');            
        $this->Ln(6);
        $this->SetFont('Arial','',11);
        $this->Cell(1); // Movernos a la derecha
        $this->SetTextColor(6,6,6);
        if($proyecto->FechaFinPrevista !=NULL){
            $this->Cell(40,0,  utf8_decode("Fecha fin prevista:"),0,0,'L');
            $this->SetFont('Arial','B',11);
            $this->Cell(20,0,  utf8_decode(date("d/m/Y", strtotime($proyecto->FechaFinPrevista))),0,0,'L');
        }
        if(!empty($empleados)){
            $this->Ln(15);
            $this->SetFont('Arial','B',13);
            $this->Cell(1); // Movernos a la derecha
            $this->Cell(40,0,  utf8_decode("Empleados:"),0,0,'L');
            foreach($empleados as $empleado){
                $this->Ln(6);
                $this->SetTextColor(6,6,6);
                $this->SetFont('Arial','',11);
                $this->Cell(1); // Movernos a la derecha
                $this->Cell(20,0,  utf8_decode($empleado->Nombre.' '.$empleado->ApellidoP.' '.$empleado->ApellidoM),0,0,'L');            
            }
        }
        
        if(!empty($tareas)){
            $this->Ln(15);
            $this->SetFont('Arial','B',13);
            $this->Cell(1); // Movernos a la derecha
            $this->Cell(40,0,  utf8_decode("Tareas:"),0,0,'L');
            foreach($tareas as $tarea){
                $this->Ln(6);
                $this->SetTextColor(6,6,6);
                $this->SetFont('Arial','',11);
                $this->Cell(1); // Movernos a la derecha
                $this->Cell(40,0,  utf8_decode(ucfirst($tarea->Titulo)),0,0,'L');
                $this->SetFont('Arial','B',11);
                if($tarea->Estado == 'ejecucion'){
                    $this->SetTextColor(0,170,0);
                    $this->SetFont('Arial','B',12);
                    $this->Cell(50,0,  utf8_decode('Ejecución'),0,0,'L');
                    $this->SetTextColor(6,6,6);
                }
                else{
                    $this->SetTextColor(220,50,50);
                    $this->SetFont('Arial','B',12);
                    $this->Cell(20,0,  utf8_decode('Cerrado'),0,0,'L');   
                }
            }
        }
        
    }
    
    // Pie de página
    function Pie(){
        $this->SetTextColor(6,6,6);
        // Posición: a 1,5 cm del final
        $this->SetY(-40);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('C/Vega 1 1º A, Chiclana de la Frontera ('. date('d-m-Y') .'), Cádiz. Tlfno: 956403042 ' ),0,0,'C');
        $this->Ln(5);
        $this->Cell(0,10,utf8_decode('barea@arquitectosdecadiz.com'),0,0,'C');
    }
}

?>