<?php
date_default_timezone_set("America/Mexico_City");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'fetch/database.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require_once 'vendor/autoload.php';

if(isset($_POST['emailCurso'])){
    $dataResponse = array();
    $respuesta = "";
    $hoy = date('Y-m-d');
    $db = new Database();
    $query = $db->connect()->prepare('SELECT P.CURSO, P.NUMCONTROL, P.FECHAINI, P.FECHAFIN, BD.nombre4, BD.nombre2, BD.nombre3, BD.correo FROM cchl_participantes P
        LEFT JOIN BD ON P.MATRICULA = BD.matricula 
        WHERE P.NUMCONTROL = "'.$_POST['emailCurso'].'" AND BD.correo <> "" AND CALIFICACION >= 80');
    $query->execute();
    
    //Create an instance; passing `true` enables exceptions
    try {
        $mail = new PHPMailer();

        // Settings
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';

        $mail->Host       = "relay.imss.gob.mx";    // SMTP server example
        $mail->SMTPDebug  = 4;                     // enables SMTP debug information (for testing)
        $mail->SMTPAuth   = false;                  // enable SMTP authentication
        $mail->Port       = 25;                    // set the SMTP port for the server
        $mail->Username   = "capacitacion.ags@imss.gob.mx";            // SMTP account username example
        $mail->Password   = "C4p4c$*0324";            // SMTP account password example

        // Content
        $mail->setFrom('capacitacion.ags@imss.gob.mx');   

        $nombreCurso = "";
        $fechaini = "";
        $fechafin = "";

        $cont = 0;
        foreach ($query as $row){
           $mail->addAddress($row[7]);
           $nombreCurso = $row[0];
           $fechaini = $row[2];
           $fechafin = $row[3];
           $cont+=1;
        }
        
        $mail->isHTML(true);// Set email format to HTML
        $mail->Subject = '¡Su constancia esta lista para descargar! - CCHL';
        $mail->Body    = '
          <html>
             <head>
             <link rel="stylesheet" href="http://eventsdirectorypk.com/projects/armorax/team/mail/templates/mailstyle.css" />
            </head>
            <body>
            <style type="text/css">
            .mainBox{width:100%; text-align:center; background:#f2edf5; }
            .mailBody{border-radius:5px; box-shadow:-1px 2px 8px 0px #333; 
            -webkit-box-shadow:-1px 2px 8px 0px #333;-moz-box-shadow:-1px 2px 8px 0px #333; border-top:5px solid #c48c18; 
            margin:10px auto;background:#fff; padding:20px; width:560px;}
            .banner{width:300px; text-align:center;}
            .warning{color:#c48c18;}
            </style>
            <div class="mainBox">
            <div class="mailBody">
            <img src="cid:logo_2u" alt="IMSS" class="banner" style="width:300;">
            <p style="text-align:center; font-size:1.6em;"> Su constancia del curso <b>'.$nombreCurso.'</b> con fecha del '.$fechaini.' al '.$fechafin. ' esta lista para descarga<p/> 
            <p style="text-align:center">Visite nuestra <a href="http://11.1.1.227:82/cchl/">página oficial</a> (http://11.1.1.227:82/cchl) y consulte todas sus constancias emitidas por la Oficina de Capacitación, <i class="warning">recuerde acceder desde el navegador Chrome</i><p/>
            <br>


            <br><br>
            <br><br>
            <br><br>
            <i>AVISO DE CONFIDENCIALIDAD:</i> Instituto Mexicano del Seguro Social, Avenida Paseo de la Reforma número 476, Col. Juárez, Alcaldía Cuauhtémoc, Ciudad de México, C.P. 06600, Tel: 5552382700 www.imss.gob.mx. 
        
                Este mensaje y sus anexos pueden contener información confidencial. Si usted no es el destinatario de este mensaje, se le notifica que cualquier revisión, retransmisión, distribución, copiado u otro uso o acto realizado con base en lo relacionado con el contenido de este mensaje y sus anexos, están prohibidos. Si usted ha recibido este mensaje y sus anexos por error, le suplicamos lo notifique al remitente respondiendo el presente correo electrónico y borre el presente y sus anexos de su sistema sin conservar copia de los mismos. 
                    
                Este correo electrónico no pretende ni debe ser considerado como constitutivo de ninguna relación legal, contractual o de otra índole similar.
             </div>
             </div>
             </body>
        </html>
        ' ;
       
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->AddEmbeddedImage('assets/img/cchl-email-banner.png', 'logo_2u');
        //$mail->send();
        if($mail->Send()){
            $dataResponse['message'] = 'Se envio el mensaje a '.$cont.' trabajadores';
        }else{
            $dataResponse['message'] = 'No se pudo enviar el mensaje, intente más tarde o consulte con el administrador';
        }
        
    } catch (Exception $e) {
        //echo "No se pudo enviar el mensaje. Mailer Error: {$mail->ErrorInfo}";
        $dataResponse['message'] = 'No se pudo enviar el mensaje, intente más tarde o consulte con el administrador';
    }

    echo json_encode ($data);
    exit;
}




?>