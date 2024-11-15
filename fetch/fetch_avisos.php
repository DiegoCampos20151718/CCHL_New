<?php
date_default_timezone_set('America/Mexico_City');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'database.php';
session_start();

if(isset($_POST['foliosiap']) && isset($_POST['planformativo']) && isset($_POST['finicio']) && isset($_POST['ffin']) && isset($_POST['avisoautorizado'])){
	$output = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT nocontrol, nombredeleventodeCapacitacion, programaEspecifico, fInicio, fTermino, dias, horas, noPart, instructorAviso FROM cchl_validacion WHERE instructor = '.$_SESSION['cchl']['username'].' ORDER BY instructorAviso ASC, fInicio ASC');
	$query->execute();
	$rows = $query->rowCount();

	foreach ($query as $row ) {
		$autorizado = array('<button class="btn btn-danger autorizar" data-folio="'.$row['nocontrol'].'">PENDIENTE</button>','<div class="alert alert-success p-1 m-1 text-center" role="alert">AUTORIZADO</div>');
		$output[] = array($row['nocontrol'], $row['nombredeleventodeCapacitacion'], $row['fInicio'], $row['fTermino'], $autorizado[$row['instructorAviso']]);
	}
	$data['data'] = $output;
	echo json_encode($data);
}

if(isset($_POST['autorizar'])){
	$db = new Database();
	$hoy = date('d-m-Y');
    try {
		//$query = $db->connect()->prepare("DELETE from usuarios WHERE username='$_POST['activo']'")->execute();
        $query = $db->connect()->prepare("UPDATE cchl_validacion SET instructorAviso = 1, fechaAutAviso = $hoy WHERE nocontrol = '".$_POST['autorizar']."'")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'AVISO ACEPTADO CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIÓ UN ERROR AL GUARDAR EL AVISO DE PRIVACIDAD. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR';
	}
	echo json_encode($mensaje);
}
?>