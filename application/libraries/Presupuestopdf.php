<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define('FPDF_FONTPATH','fonts');
require('fpdf.php');

class Presupuestopdf extends FPDF{
    function Header(){
        $flotado_x = 20;
        $flotado_y = 18;
        $ancho = 20;
        $this->Image(BASEPATH.'/../images/logo2.png',$flotado_x, $flotado_y, $ancho);
        
        // Título
        $this->SetFont('Arial','B',20);        
        $this->Cell(20);
        $this->Cell(0,0,'Barea Arquitectos',0,0,'L');
       
        //Subtitulo
        $this->Ln(7);
        $this->SetFont('Arial','B',10);
        $this->Cell(20);
        $this->Cell(0,0,  utf8_decode('Estudio de arquitectura, urbanismo e ingeniería'),0,0,'L');
        
        $this->Ln(15);
        $this->SetFont('Arial','',12);
        //$this->Cell(33); // Movernos a la derecha   
        $this->Cell(0,0,  utf8_decode('PRESUPUESTO: Honorarios técnicos'),0,0,'C');
        
        
        // Salto de línea
        $this->Ln(10);
    }
    
    function datos_presupuesto($presupuesto){
          
        $this->SetFont('Arial','B',12);
        $this->Cell(0,0,  utf8_decode('DATOS PROYECTO:'),0,0,'L');
        $this->Ln(8);
        $this->SetFont('Arial','I',10);
        $this->Cell(0,0,  utf8_decode('Cliente: '.$presupuesto->Nombre.' '. $presupuesto->ApellidoP. ' ' . $presupuesto->ApellidoM ),0,0,'L');
        $this->Ln(5);
        $this->Cell(0,0,  utf8_decode($presupuesto->Direccion.', '. $presupuesto->Ciudad. ', (' . $presupuesto->Provincia .')' ),0,0,'L');
        $this->Ln(5);
        if($presupuesto->Tipo == 1)
           $this->Cell(0,0,  utf8_decode('Tipo de obra: Obra Nueva'),0,0,'L');
        elseif($presupuesto->Tipo == 2)
           $this->Cell(0,0,  utf8_decode('Tipo de obra: Peritación'),0,0,'L');
        elseif($presupuesto->Tipo == 3)
           $this->Cell(0,0,  utf8_decode('Tipo de obra: Rehabilitación'),0,0,'L');
        elseif($presupuesto->Tipo == 4)
           $this->Cell(0,0,  utf8_decode('Tipo de obra: Adecuación de local'),0,0,'L');
        elseif($presupuesto->Tipo == 5)
           $this->Cell(0,0,  utf8_decode('Tipo de obra: Tasación'),0,0,'L');
        elseif($presupuesto->Tipo == 6)
           $this->Cell(0,0,  utf8_decode('Tipo de obra: Informe'),0,0,'L');
        elseif($presupuesto->Tipo == 7)
           $this->Cell(0,0,  utf8_decode('Tipo de obra: Auditoría energética'),0,0,'L');
            
        $this->Ln(5);
        if($presupuesto->Superficie){
            $this->Cell(0,0,  utf8_decode('Superficie: ' . $presupuesto->Superficie . ' m2'),0,0,'L');
            $this->Ln(5);
        }
        $this->Cell(0,0,  utf8_decode('PEM Colegio Oficial Arquitectos de Cádiz: ' . $presupuesto->Pem.' euros'),0,0,'L');
        $this->Ln(5);
        
        // Salto de línea
        $this->Ln(10);
    }
    
    function honorarios($datos_lineas){
        $this->SetFont('Arial','B',12);
        $this->Cell(0,0,  utf8_decode('HONORARIOS TÉCNICOS:'),0,0,'L');
        $this->Ln(8);
        $this->SetFont('Arial','I',10);
     
        $this->Cell(60,7,'Concepto',1);
        $this->Cell(50,7,'Precio',1);
        //$this->Cell(35,7,'Descuento',1);
        $this->Cell(50,7,'Total',1);
        
        
        $this->Ln();
        $total = 0;
        foreach($datos_lineas as $lineas){
            $this->Cell(60,5,utf8_decode($lineas->Concepto),1);
            $this->Cell(50,5,utf8_decode($lineas->Cantidad.' euros') ,1);
           // $this->Cell(35,5,  utf8_decode($lineas->Descuento.' %'),1);
            $total_linea = $lineas->Cantidad - (($lineas->Cantidad * $lineas->Descuento)/100);
            $this->Cell(50,5, utf8_decode( $total_linea .' euros'),1);
            $total += $total_linea;
            $this->Ln();
        }
        
        $this->Ln(5);
        $this->Cell(0,0,  utf8_decode('TOTAL: '.$total.' euros'),0,0,'R');
    }

    // Pie de página
    function Footer(){
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('C/Vega 1 1º A, Chiclana de la Frontera ('. date('d-m-Y') .'), Cádiz. Tlfno: 956403042 ' ),0,0,'C');
        $this->Ln(5);
        $this->Cell(0,10,utf8_decode('barea@arquitectosdecadiz.com'),0,0,'C');
    }
    
    
    
    
}
 
?>