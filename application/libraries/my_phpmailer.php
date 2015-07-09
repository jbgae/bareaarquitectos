<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_PHPMailer {
    public function My_PHPMailer(){
        require_once('PHPMailer/class.phpmailer.php');
    }
    
    public function Enviar($datosEmail){
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port 
 
        $mail->Username   = "jabgae@gmail.com";    // GMAIL username
        $mail->Password   = "/*J8a2V1i9E8r3";          // GMAIL password
             
        $mail->From       = $datosEmail['direccion'];
        $mail->FromName   = utf8_decode($datosEmail['nombre']);
        $mail->Subject    = utf8_decode($datosEmail['asunto']);
        $mail->AddAddress($datosEmail['destino']);
        $mail->IsHTML(TRUE);
        $mail->Body = utf8_decode($datosEmail['texto']);
             
        if(!$mail->Send()) {
           return FALSE;
        }
        
        else {
            return TRUE;
        }
    }
}
?>