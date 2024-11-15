<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once '../fetch/database.php';
$mensaje ="";
if(isset($_POST['import_data'])){
    // validate to check uploaded file is a valid csv file
    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            $db = new Database();
            $pdo = $db->connect();
            try {
                move_uploaded_file($_FILES['file']['tmp_name'], "temporal/db.csv");
                $url = dirname(__FILE__)."\\temporal\\db.csv";
                $url = str_replace("\\","/",$url);
                $pdo->beginTransaction();
                $pdo->exec('DROP TABLE IF EXISTS new_tbl');
                $pdo->exec('CREATE TABLE `new_tbl` (
                  `matricula` varchar(15) NOT NULL,
                  `curp` varchar(30) NOT NULL,
                  `nombre2` varchar(50) NOT NULL,
                  `nombre3` varchar(50) NOT NULL,
                  `nombre4` varchar(50) NOT NULL,
                  `puesto` varchar(200) NOT NULL,
                  `correo` varchar(200) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;');
                //IGNORE 1 LINES, despuesde lines terminated 
                $pdo->exec('LOAD DATA INFILE "'.$url.'"
                    INTO TABLE new_tbl
                    CHARACTER SET UTF8
                    FIELDS TERMINATED BY ","
                    OPTIONALLY ENCLOSED BY "\""
                    LINES TERMINATED BY "\n"
                    (matricula, curp, nombre2, nombre3, nombre4, puesto, @dummy, @dummy, @dummy, correo);');
                $pdo->exec('INSERT INTO bd (matricula, curp, nombre2, nombre3, nombre4, contrasena, puesto, correo)
                    SELECT b.matricula, b.curp, b.nombre2, b.nombre3, b.nombre4, b.matricula, b.puesto, b.correo FROM new_tbl b
                    ON DUPLICATE KEY UPDATE curp = b.curp, nombre2 = b.nombre2, nombre3 = b.nombre3, nombre4 = b.nombre4, puesto = b.puesto, correo = b.correo');
                $pdo->exec('UPDATE bd SET nombre2 = REPLACE(nombre2, "&", "Ñ"), nombre3 = REPLACE(nombre3, "&", "Ñ"), nombre4 = REPLACE(nombre4, "&", "Ñ")');
                $pdo->exec('DROP TABLE IF EXISTS new_tbl');
                $pdo->commit();
                unlink("temporal/db.csv");
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