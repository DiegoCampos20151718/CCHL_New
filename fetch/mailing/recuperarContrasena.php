<?php
date_default_timezone_set("America/Mexico_City");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once '../../fetch/database.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require_once '../../vendor/autoload.php';

if(isset($_POST['buscarCorreo'])){
    $db = new Database();

    $query = $db->connect()->prepare('SELECT correo FROM bd WHERE matricula = :identifier ');
    $query->execute(['identifier' => $_POST['buscarCorreo']]);
    $row = $query->fetch(PDO::FETCH_NUM);
    $respuesta = "prueba";
    if($row[0] == ""){
        $respuesta = "<small class='text-danger'>SU CORREO NO HA SIDO REGISTRADO AÚN
        contactenos en las siguientes direcciones o al telefono 449 975 2200 (ext. 41135)
        <br><br>
        Jose.vazquezo@imss.gob.mx<br>
        Victor.vazquezj@imss.gob.mx<br>
        Cristian.ruvalcaba@imss.gob.mx
        </small>";
    }else{
        $respuesta = "<small>El correo asociado a su matricula es <i class='text-primary'>".$row[0]."</i>, si esto es incorrecto comuniquese con el administrador, de lo contrario continue con la recuperación de la cuenta </small>";
    }
    echo $respuesta;
}

if(isset($_POST['enviarCodigo'])){
    $dataResponse = array();
    $respuesta = "";
    $hoy = date('Y-m-d');
    $db = new Database();
    $query = $db->connect()->prepare('SELECT correo FROM bd WHERE matricula = :identifier');
    $query->execute(['identifier' => $_POST['enviarCodigo']]);
    $row = $query->fetch(PDO::FETCH_NUM);
    //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer();

        // Settings
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';

        $mail->Host       = "relay.imss.gob.mx";    // SMTP server example
        //$mail->SMTPDebug  = 4;                     // enables SMTP debug information (for testing)
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->SMTPAuth   = false;                  // enable SMTP authentication
        $mail->Port       = 25;                    // set the SMTP port for the server
        $mail->Username   = "capacitacion.ags@imss.gob.mx";            // SMTP account username example
        $mail->Password   = "C4p4c$*0324";            // SMTP account password example

        // Content
        $mail->setFrom('capacitacion.ags@imss.gob.mx');  
        $mail->addAddress($row[0]);
          
        $mail->isHTML(true);// Set email format to HTML
        $mail->Subject = '¡RECUPERA TU CONTRASEÑA! - CCHL';
        $n1 = rand(0,9);
        $n2 = rand(0,9);
        $n3 = rand(0,9);
        $n4 = rand(0,9);
        $n5 = rand(0,9);
        $n6 = rand(0,9);
        $codigoverificador = $n1.$n2.$n3.$n4.$n5.$n6;
        $mail->Body    = '
          <html>
             <head>
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

            <p style="text-align:center; font-size:1.6em;"> EL CÓDIGO PARA RECUPERAR TU CUENTA CCHL ES <b>'.$codigoverificador.'</b><p/> 
            <p style="text-align:center">Regresa a la página y utiliza el código <a href="http://11.1.1.227:82/cchl/">página oficial</a> (http://11.1.1.227:82/cchl)  <i class="warning">recuerde acceder desde el navegador Chrome</i><p/>
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
        $data = array();
        $mail->AddEmbeddedImage('../../assets/img/cchl-email-banner.png', 'logo_2u');
        if(!$mail->send()){
            $data['message'] = "ERROR EL ENVIAR CORREO";
        }else{
            $data['message'] = "EL CORREO SE ENVÍO CON ÉXITO";
            try {
                $query = $db->connect()->prepare('UPDATE bd SET codigoverif = "'.$codigoverificador.'"  WHERE matricula = :identifier');
                $query->execute(['identifier' => $_POST['enviarCodigo']]);
            } catch (Exception $e) {
               $data['message'] = $e;
            }

        }
    echo json_encode($data);
    exit;
}

if(isset($_POST['verifyCode'])){
    $data = $_POST['verifyCode'];
    $matricula = $data['usrVerify'];
    $codigoVerificador = $data['usrVerifyCode'];
    $dataResponse = array();
      
    $db = new Database();
    $query = $db->connect()->prepare('SELECT codigoverif FROM bd WHERE matricula = :matricula AND codigoverif = :codigoverificador');
      $query->execute(['matricula' => $matricula, 'codigoverificador' => $codigoVerificador]);
    $row = $query->fetch(PDO::FETCH_NUM);
    $nrow = $query->rowCount();
    if($nrow < 1){
        $dataResponse['message'] = "El código verificador es incorrecto o no se encuentra al trabajador (".$matricula.")";
        $dataResponse['status'] = false;
    }else{
        $dataResponse['status'] = true;
        $dataResponse['message'] = "dddd";
    }
    echo json_encode($dataResponse);
    exit;
}
?>
 
