<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
require_once 'fetch/database.php';
include('vendor/phpqrcode/qrlib.php');
        
    
if(isset($_GET['folioCCHL']) && isset($_GET['matricula'])){
    $data = array();
    $db = new Database();
    $query = $db->connect()->prepare('SELECT CP.MATRICULA, BD.curp, BD.nombre4, BD.nombre3, BD.nombre2, CP.PUESTO, CVE.cve_actual, CP.CALIFICACION, CV.nombrePlanFormativo, CV.nombredeleventodeCapacitacion, CV.finicio, CV.ftermino, CV.horas, CVT.cve_actual, I.nombre, CV.nombreCapacitador FROM cchl_participantes CP 
            LEFT JOIN cchl_validacion CV ON CP.NUMCONTROL = CV.nocontrol
            LEFT JOIN bd ON CP.MATRICULA = bd.matricula
            LEFT JOIN cveocupacion CVE ON CP.PUESTO = CVE.descripcion
            LEFT JOIN cvetematica CVT ON CV.programaEspecifico = CVT.area
            LEFT JOIN instructores I ON CV.instructor = I.id
            WHERE CP.NUMCONTROL = :buscarfolio AND CP.MATRICULA = :matricula AND CP.CALIFICACION >= 80');
    $query->execute(['buscarfolio' => $_GET['folioCCHL'], 'matricula' => $_GET['matricula']]);
    
    /********crear codigos qr*/
    $queryF = $db->connect()->prepare('SELECT CV.nocontrol, CV.nombrePlanFormativo, CV.ftermino, CV.horas, I.nombre, I.grado_estudio, U.unidad, I.instructor_hab, I.folio_constancia, I.fecha_constancia FROM cchl_validacion CV 
        LEFT JOIN instructores I ON CV.instructor = I.id
        LEFT JOIN unidades U ON I.centro_lab = U.id
        WHERE nocontrol = :buscarfolio ');
    $queryF->execute(['buscarfolio' => $_GET['folioCCHL']]);
    $firmas = $queryF->fetch(PDO::FETCH_NUM);

    $param = "cualquier texto"; // remember to sanitize that - it is user input!
    // we need to be sure ours script does not output anything!!!
    // otherwise it will break up PNG binary!
    ob_start("callback");
    // here DB request or some processing
    $codeText = 'DEMO - '.$_GET['id'];
    $karla = '{
      "Folio": "'.$firmas[0].'",
      "Nombre Curso": "'.$firmas[1].'",
      "Fecha Fin": "'.$firmas[2].'",
      "Horas": "'.$firmas[3].'",
      "Matricula Instructor": "11304987",
      "Nombre Instructor": "LIC. KARLA TERESA LÓPEZ ÁLVAREZ",
      "Grado de Estudio": "LICENCIANDA EN PSICOLOGIA",
      "Cargo": "TITULAR DEL DEPARTAMENTO DE CAPACITACIÓN Y TRANSPARENCIA",
      "Centro Laboral": "SEDE DELAGACIONAL",
      "Representante de la Subcomision Mixta de Capacitacion ": "INSTITUCIONAL",
      "Fecha de Acta Constitutiva": "01 de noviembre de 2023"
    }';
    $estefania = '{
      "Folio": "'.$firmas[0].'",
      "Nombre Curso": "'.$firmas[1].'",
      "Fecha Fin": "'.$firmas[2].'",
      "Horas": "'.$firmas[3].'",
      "Matricula Instructor": "99012377",
      "Nombre Instructor": "ING. ESTEFANIA ACEVEDO VALADEZ",
      "Grado de Estudio": "INGENIERA INDUSTRIAL",
      "Cargo": "5TA CATEGORIA ESPECIALISTA DE PERSONAL",
      "Centro Laboral": "SEDE DELAGACIONAL",
      "Representante de la Subcomision Mixta de Capacitacion ": "SINDICAL",
      "Fecha de Acta Constitutiva": "01 de noviembre de 2023"
    }';
    $instructor = '{
      "Folio": "'.$firmas[0].'",
      "Nombre Curso": "'.$firmas[1].'",
      "Fecha Fin": "'.$firmas[2].'",
      "Horas": "'.$firmas[3].'",
      "Nombre Instructor":  "'.$firmas[4].'",
      "Grado de Estudio":  "'.$firmas[5].'",
      "Centro Laboral":  "'.$firmas[6].'",
      "Instructor Habilitado":  "'.$firmas[7].'",
      "Folio de Constancia":  "'.$firmas[8].'",
      "Fecha de Constancia":  "'.$firmas[9].'"
    }';
    // end of processing here
    $debugLog = ob_get_contents();
    ob_end_clean();
    // outputs image directly into browser, as PNG stream
    if (!file_exists("assets/temporaryqr/".$_GET['folioCCHL'])) {
        mkdir("assets/temporaryqr/".$_GET['folioCCHL'], 0777, true);
    }
    QRcode::png($karla, "assets/temporaryqr/".$_GET['folioCCHL']."/karla.png", QR_ECLEVEL_M);
    QRcode::png($estefania, "assets/temporaryqr/".$_GET['folioCCHL']."/estefania.png", QR_ECLEVEL_M);
    QRcode::png($instructor, "assets/temporaryqr/".$_GET['folioCCHL']."/instructor.png", QR_ECLEVEL_M);
    /********fin de codigos qr*/

    $pdf = new \setasign\Fpdi\Fpdi();
    foreach ($query as $row) {
        // initiate FPDI
        // add a page
        
        $pdf->AddPage();
        $pdf->setSourceFile('constancia.pdf');
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx, 0, 0);
        $pdf->SetFont('ARIAL','B');
        $pdf->SetFontSize(14);
        $pdf->text(34.54,75,utf8_decode($row[2])." ".utf8_decode($row[3])." ".utf8_decode($row[4]));
        $pdf->SetFont('ARIAL');
        $pdf->SetFontSize(14);
        $pdf->text(167.39,75,$row['0']);//matricula
        $pdf->text(34.04,89.50,$row['1']);//curp
        $pdf->text(147.32,89.25,$row['6']);//ocupacion especifica
            
        $pdf->text(66,174.88,$row['12']);//horas del curso
        $pdf->text(64.26,185.05,$row['13']);//clave tematica
        $pdf->SetFont('ARIAL','B');
        $pdf->SetFontSize(14);
        $finicio=explode("/",$row['10']);
        $ffin=explode("/",$row['11']);
        if(count($finicio)==3){
            $pdf->text(100.62,184.02,$finicio[2]);//año inicio
            $pdf->text(115.8,184.02,$finicio[1]);//mes inicio
            $pdf->text(126.4,184.02,$finicio[0]);//dia inicio
        }

        if(count($ffin)==3){
            $pdf->text(147.1,184.02,$ffin[2]);//año fin
            $pdf->text(163.8,184.02,$ffin[1]);//mes fin
            $pdf->text(176.35,184.02,$ffin[0]);//dia fin
        }
        
        
        $pdf->SetY(155); 
        $pdf->Cell(10);
            
        $pdf->MultiCell(180,7,utf8_decode($row['9']),0,'C');//nombre curso
            
        $pdf->SetFont('ARIAL');
        $pdf->SetY(193); 
        $pdf->Cell(10);
        $pdf->MultiCell(180,7,utf8_decode($row['15']),0,'C');//nombre capacitador
        $pdf->SetFont('ARIAL');

        $pdf->SetY(94); 
        $pdf->Cell(10);
        $pdf->MultiCell(180,7,$row['5'],0,'C');//CATEGORIA PUESTO
            
        $pdf->SetFont('ARIAL','B');
        $pdf->SetFontSize(10);
        $pdf->text(35.05,276.59,$_GET['folioCCHL']);//NUMERO CONSECUTIVO
        $pdf->SetFont('ARIAL');
        $pdf->SetFontSize(7);
            
        $pdf->SetFontSize(10);
        $pdf->SetY(255); 
        $pdf->Cell(6);
        $pdf->MultiCell(60,4,utf8_decode($row['14']),0,'C');//INSTRUCTOR
        $pdf->Image('assets/temporaryqr/'.$_GET['folioCCHL'].'/instructor.png', 33, 224, -250);
            
        $pdf->SetFontSize(10);
        $pdf->SetY(255); 
        $pdf->Cell(72);
        $pdf->MultiCell(60,4,utf8_decode('LIC. KARLA TERESA LOPEZ ALVAREZ'),0,'C');//PATRON O REPRESENTANTE LEGAR
        $pdf->Image('assets/temporaryqr/'.$_GET['folioCCHL'].'/karla.png', 93, 224, -250);

        $pdf->SetFontSize(10);
        $pdf->SetY(255); 
        $pdf->Cell(132);
        $pdf->MultiCell(60,4,utf8_decode('ING. ESTEFANIA ACEVEDO VALADEZ'),0,'C');//REPRESENTANTE DE LOS TRABAJADORES
        $pdf->Image('assets/temporaryqr/'.$_GET['folioCCHL'].'/estefania.png', 153, 224,-250);

        
    }

    $queryF = $db->connect()->prepare('UPDATE cchl_participantes SET DESCARGADO = 1
        WHERE MATRICULA = :matricula AND NUMCONTROL = :numcontrol ');
    $queryF->execute(['matricula' => $_GET['matricula'],'numcontrol' => $_GET['folioCCHL']]);


    //$pdf->Output();
    $pdf->Output('D','CCHL '.$firmas[0].'.pdf');
    ob_end_flush();
}
    
?>