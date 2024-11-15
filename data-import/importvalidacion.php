<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/html; charset=UTF-8');

include_once '../fetch/database.php';
$mensaje ="";
if(isset($_POST['import_validacion'])){
    // validate to check uploaded file is a valid csv file
    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            $db = new Database();
            $pdo = $db->connect();
            try {
                move_uploaded_file($_FILES['file']['tmp_name'], "temporal/validacion.csv");
                $url = dirname(__FILE__)."\\temporal\\validacion.csv";
                $url = str_replace("\\","/",$url);
                $pdo->beginTransaction();
                $pdo->exec('DROP TABLE IF EXISTS new_tblvalidacion');
                $pdo->exec('CREATE TABLE `new_tblvalidacion` (
                  `clave` varchar(255) NOT NULL,
                  `ooad` varchar(255) NOT NULL,
                  `direccion` varchar(255) NOT NULL,
                  `coordinacion` varchar(255) NOT NULL,
                  `nocontrol` varchar(255) NOT NULL,
                  `UDEI` varchar(255) NOT NULL,
                  `FP` varchar(255) NOT NULL,
                  `status` varchar(255) NOT NULL,
                  `planFormativo` varchar(255) NOT NULL,
                  `nombrePlanFormativo` text NOT NULL,
                  `observaciones` varchar(255) NOT NULL,
                  `nombredeleventodeCapacitacion` varchar(255) NOT NULL,
                  `areaSolicitante` varchar(255) NOT NULL,
                  `coordinacionNormativa` varchar(255) NOT NULL,
                  `coordinacionoJefaturaOOAD` varchar(255) NOT NULL,
                  `funcionesSustantivas` text NOT NULL,
                  `programaGeneral` varchar(255) NOT NULL,
                  `programaEspecifico` varchar(255) NOT NULL,
                  `tipodeAccion` varchar(255) NOT NULL,
                  `origen` varchar(255) NOT NULL,
                  `organizacion` varchar(255) NOT NULL,
                  `modalidad` varchar(255) NOT NULL,
                  `sede` varchar(255) NOT NULL,
                  `fInicio` varchar(255) NOT NULL,
                  `fTermino` varchar(255) NOT NULL,
                  `dias` varchar(255) NOT NULL,
                  `horas` varchar(255) NOT NULL,
                  `noPart` varchar(255) NOT NULL,
                  `mujeres` varchar(255) NOT NULL,
                  `hombres` varchar(255) NOT NULL,
                  `P42060257` varchar(255) NOT NULL,
                  `P42060704` varchar(255) NOT NULL,
                  `P42061101` varchar(255) NOT NULL,
                  `P42061303` varchar(255) NOT NULL,
                  `P42061309` varchar(255) NOT NULL,
                  `P42061603` varchar(255) NOT NULL,
                  `P42061619` varchar(255) NOT NULL,
                  `P42061620` varchar(255) NOT NULL,
                  `P42061623` varchar(255) NOT NULL,
                  `P42061624` varchar(255) NOT NULL,
                  `P42062314` varchar(255) NOT NULL,
                  `P42062403` varchar(255) NOT NULL,
                  `P42060802` varchar(255) NOT NULL,
                  `importeTotal` varchar(255) NOT NULL,
                  `proveedor` varchar(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;');
                //IGNORE 1 LINES, despuesde lines terminated 
                $pdo->exec('LOAD DATA INFILE "'.$url.'"
                    INTO TABLE new_tblvalidacion
                    CHARACTER SET LATIN1  
                    FIELDS TERMINATED BY ","
                    OPTIONALLY ENCLOSED BY "\""
                    LINES TERMINATED BY "\n"
                    (clave, ooad, @dummy1, @dummy2, direccion, coordinacion, nocontrol, UDEI, FP, status, planFormativo, nombrePlanFormativo, @dummy3, @dummy4, observaciones, nombredeleventodeCapacitacion, areaSolicitante, coordinacionNormativa, coordinacionoJefaturaOOAD, funcionesSustantivas, programaGeneral, programaEspecifico, tipodeAccion, origen, organizacion, modalidad, sede, fInicio, fTermino, dias, horas, noPart, mujeres, hombres, P42060257, P42060704, P42061101, P42061303, P42061309, P42061603, P42061619, P42061620, P42061623, P42061624, P42062314, P42062403, P42060802, importeTotal, proveedor);');
                $pdo->exec('INSERT INTO cchl_validacion (clave, ooad, direccion, coordinacion, nocontrol, UDEI, FP, status, planFormativo, nombrePlanFormativo, observaciones, nombredeleventodeCapacitacion, areaSolicitante, coordinacionNormativa, coordinacionoJefaturaOOAD, funcionesSustantivas, programaGeneral, programaEspecifico, tipodeAccion, origen, organizacion, modalidad, sede, fInicio, fTermino, dias, horas, noPart, mujeres, hombres, P42060257, P42060704, P42061101, P42061303, P42061309, P42061603, P42061619, P42061620, P42061623, P42061624, P42062314, P42062403, P42060802, importeTotal, proveedor)
                    SELECT b.clave, b.ooad, b.direccion, b.coordinacion, b.nocontrol, b.UDEI, b.FP, b.status, b.planFormativo, b.nombrePlanFormativo, b.observaciones, b.nombredeleventodeCapacitacion, b.areaSolicitante, b.coordinacionNormativa, b.coordinacionoJefaturaOOAD, b.funcionesSustantivas, b.programaGeneral, b.programaEspecifico, b.tipodeAccion, b.origen, b.organizacion, b.modalidad, b.sede, b.fInicio, b.fTermino, b.dias, b.horas, b.noPart, b.mujeres, b.hombres, b.P42060257, b.P42060704, b.P42061101, b.P42061303, b.P42061309, b.P42061603, b.P42061619, b.P42061620, b.P42061623, b.P42061624, b.P42062314, b.P42062403, b.P42060802, b.importeTotal, b.proveedor FROM new_tblvalidacion b WHERE b.clave = "01"
                    ON DUPLICATE KEY UPDATE clave = b.clave, ooad = b.ooad, direccion = b.direccion, coordinacion = b.coordinacion, UDEI = b.UDEI, FP = b.FP, status = b.status, planFormativo = b.planFormativo, nombrePlanFormativo = b.nombrePlanFormativo, observaciones = b.observaciones, nombredeleventodeCapacitacion = b.nombredeleventodeCapacitacion, areaSolicitante = b.areaSolicitante, coordinacionNormativa = b.coordinacionNormativa, coordinacionoJefaturaOOAD = b.coordinacionoJefaturaOOAD, funcionesSustantivas = b.funcionesSustantivas, programaGeneral = b.programaGeneral, programaEspecifico = b.programaEspecifico, tipodeAccion = b.tipodeAccion, origen = b.origen, organizacion = b.organizacion, modalidad = b.modalidad, sede = b.sede, fInicio = b.fInicio, fTermino = b.fTermino, dias = b.dias, horas = b.horas, noPart = b.noPart, mujeres = b.mujeres, hombres = b.hombres, P42060257 = b.P42060257, P42060704 = b.P42060704, P42061101 = b.P42061101, P42061303 = b.P42061303, P42061309 = b.P42061309, P42061603 = b.P42061603, P42061619 = b.P42061619, P42061620 = b.P42061620, P42061623 = b.P42061623, P42061624 = b.P42061624, P42062314 = b.P42062314, P42062403 = b.P42062403, P42060802 = b.P42060802, importeTotal = b.importeTotal, proveedor = b.proveedor');
                $pdo->exec('DROP TABLE IF EXISTS new_tblvalidacion');
                $pdo->commit();
                unlink("temporal/validacion.csv");
                $mensaje ="La base de datos fue actualizada con éxito";
            } catch (PDOException $e) {
                $pdo->rollback();
                $query=false;
                $mensaje ="Ocurrió un erro en el servidor, intente más tarde o consulte con el administrador.".$e;
            }
           
        } else { $mensaje ="Ocurrió un erro en el servidor, intente más tarde o consulte con el administrador."; }
    } else { $mensaje ="El archivo debe tener extensión .CSV"; }
    echo'<script type="text/javascript">
    alert("'.$mensaje.'");
    window.location.href="../actualizar-bd.php";
    </script>';
}


?>