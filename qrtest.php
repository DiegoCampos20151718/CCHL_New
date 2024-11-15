<?php
date_default_timezone_set("America/Mexico_City");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'fetch/database.php';
session_start();

$hoy = date('Y-m-d');
$db = new Database();
$query = $db->connect()->prepare('SELECT P.CURSO, P.NUMCONTROL, P.FECHAINI, P.FECHAFIN, BD.nombre4, BD.nombre2, BD.nombre3, BD.correo FROM cchl_participantes P
    LEFT JOIN BD ON P.MATRICULA = BD.matricula 
    WHERE STR_TO_DATE(P.FECHAINI, "%d/%m/%Y") = "'.$hoy.'" AND (BD.correo IS NOT NULL OR BD.correo <> "") ');
$query->execute();
foreach ($query as $row){
   echo $row[0]."***".$hoy."<br>";
}


/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once 'vendor/autoload.php';

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
    $mail->addAddress('sergio.gonzalezr@imss.gob.mx');

    $mail->isHTML(true);                       // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Se envio el mensaje';
} catch (Exception $e) {
    echo "No se pudo enviar el mensaje. Mailer Error: {$mail->ErrorInfo}";
}
*/


?>