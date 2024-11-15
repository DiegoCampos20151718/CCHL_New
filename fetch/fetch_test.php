<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'database.php';
session_start();
	
	$meses = array(
    1 => array("Enero",0),
    2 => array("Febrero",0),
    3 => array("Marzo",0),
    4 => array("Abril",0),
    5 => array("Mayo",0),
    6 => array("Junio",0),
    7 => array("Julio",0),
    8 => array("Agosto",0),
    9 => array("Septiembre",0),
    10 => array("Octubre",0),
    11 => array("Noviembre",0),
    12 => array("Diciembre",0)
);
	
	$db = new Database();
	$query = $db->connect()->prepare('SELECT SUM(monto), month(fecha) 
    FROM `devengos` 
    WHERE devengos.contratoID = 1
    GROUP BY month(fecha) 
    ORDER BY month(fecha) ASC');
	$query->execute();

	$output = array();
	foreach ($query as $row ) {
		$output[] = array($row['month(fecha)'], $row['SUM(monto)']);
	}

	/*for ($i=1; $i <= 12; $i++) { 
		for ($c=0; $c < count($output); $c++) { 
			if($i == $output[][]){

			}
		}
	}*/

	for ($c=0; $c < count($output); $c++) { 
		//$meses[$output[$c][0]] = $output[$c];
		//echo $meses[$output[$c][0]];
		$meses[$output[$c][0]][1] =  $output[$c][1];
	}
	
	foreach($meses as $mes){
		echo $mes[0]."-".$mes[1]."<br>";
	}
?>


