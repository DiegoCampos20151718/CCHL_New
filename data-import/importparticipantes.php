<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once '../fetch/database.php';
$mensaje ="";
if(isset($_POST['import_participantes'])){
    // validate to check uploaded file is a valid csv file
    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            $db = new Database();
            $pdo = $db->connect();
            try {
                move_uploaded_file($_FILES['file']['tmp_name'], "temporal/participantes.csv");
                $url = dirname(__FILE__)."\\temporal\\participantes.csv";
                $url = str_replace("\\","/",$url);
                $pdo->beginTransaction();
                $pdo->exec('DROP TABLE IF EXISTS new_tbl_participantes');
                $pdo->exec('CREATE TABLE `new_tbl_participantes` (
                  `CLAVEDEL` varchar(255) DEFAULT NULL,
                  `DELEGACION` varchar(255) DEFAULT NULL,
                  `DIRECCION` varchar(255) DEFAULT NULL,
                  `COORDINACION` varchar(255) DEFAULT NULL,
                  `UNIDADPRE` varchar(255) DEFAULT NULL,
                  `CCOSTOS` varchar(255) DEFAULT NULL,
                  `NUMCONTROL` varchar(255) DEFAULT NULL,
                  `CURSO` varchar(500) DEFAULT NULL,
                  `FECHAINI` varchar(255) DEFAULT NULL,
                  `FECHAFIN` varchar(255) DEFAULT NULL,
                  `MATRICULA` varchar(255) DEFAULT NULL,
                  `NOMBRE` varchar(255) DEFAULT NULL,
                  `CLAVEPUESTO` varchar(255) DEFAULT NULL,
                  `PUESTO` varchar(255) DEFAULT NULL,
                  `DEPARTAMENTO` varchar(255) DEFAULT NULL,
                  `DEPTO` varchar(255) DEFAULT NULL,
                  `SEXO` varchar(255) DEFAULT NULL,
                  `CALIFICACION` varchar(255) DEFAULT NULL,
                  `CLAVECONTRATACION` varchar(255) DEFAULT NULL,
                  `CONTRATACION` varchar(255) DEFAULT NULL,
                  `MODALIDAD` varchar(255) DEFAULT NULL,
                  `SEDE` varchar(255) DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;');
                //IGNORE 1 LINES, despuesde lines terminated 
                $pdo->exec('LOAD DATA INFILE "'.$url.'"
                    INTO TABLE new_tbl_participantes
                    CHARACTER SET LATIN1
                    FIELDS TERMINATED BY ","
                    OPTIONALLY ENCLOSED BY "\""
                    LINES TERMINATED BY "\n"
                    (CLAVEDEL, DELEGACION, @dummy1, DIRECCION, COORDINACION, @dummy2, UNIDADPRE, @dummy3, CCOSTOS, @dummy4, NUMCONTROL, @dummy5, CURSO, FECHAINI, FECHAFIN, MATRICULA, NOMBRE, CLAVEPUESTO, PUESTO, DEPARTAMENTO, DEPTO, SEXO, CALIFICACION, CLAVECONTRATACION, CONTRATACION, MODALIDAD, SEDE);');
                $pdo->exec('INSERT INTO cchl_participantes (CLAVEDEL, DELEGACION, DIRECCION, COORDINACION, UNIDADPRE, CCOSTOS, NUMCONTROL, CURSO, FECHAINI, FECHAFIN, MATRICULA, NOMBRE, CLAVEPUESTO, PUESTO, DEPARTAMENTO, DEPTO, SEXO, CALIFICACION, CLAVECONTRATACION, CONTRATACION, MODALIDAD, SEDE) 
                    SELECT b.CLAVEDEL, b.DELEGACION, b.DIRECCION, b.COORDINACION, b.UNIDADPRE, b.CCOSTOS, b.NUMCONTROL, b.CURSO, b.FECHAINI, b.FECHAFIN, b.MATRICULA, b.NOMBRE, b.CLAVEPUESTO, b.PUESTO, b.DEPARTAMENTO, b.DEPTO, b.SEXO, b.CALIFICACION, b.CLAVECONTRATACION, b.CONTRATACION, b.MODALIDAD, b.SEDE FROM new_tbl_participantes b WHERE b.CLAVEDEL = "01"
                    ON DUPLICATE KEY UPDATE CLAVEDEL = b.CLAVEDEL, DELEGACION = b.DELEGACION, DIRECCION = b.DIRECCION, COORDINACION =  b.COORDINACION, UNIDADPRE =  b.UNIDADPRE, CCOSTOS = b.CCOSTOS, NUMCONTROL = b.NUMCONTROL, CURSO = b.CURSO, FECHAINI = b.FECHAINI, FECHAFIN = b.FECHAFIN, MATRICULA = b.MATRICULA, NOMBRE = b.NOMBRE, CLAVEPUESTO = b.CLAVEPUESTO, PUESTO = b.PUESTO, DEPARTAMENTO = b.DEPARTAMENTO, DEPTO = b.DEPTO, SEXO = b.SEXO, CALIFICACION = b.CALIFICACION, CLAVECONTRATACION = b.CLAVECONTRATACION, CONTRATACION = b.CONTRATACION, MODALIDAD = b.MODALIDAD, SEDE = b.SEDE');
                $pdo->exec('DROP TABLE IF EXISTS new_tbl_participantes');
                $pdo->exec("UPDATE cchl_participantes SET PUESTO = REGEXP_REPLACE(PUESTO, ' \+', ' ')");
                $pdo->exec("UPDATE cveocupacion SET descripcion = REGEXP_REPLACE(descripcion, ' \+', ' ')");
                $pdo->commit();
                unlink("temporal/participantes.csv");
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
