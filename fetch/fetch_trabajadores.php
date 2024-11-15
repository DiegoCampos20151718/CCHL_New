<?php
include_once 'database.php';
session_start();

if(isset($_POST['nuevoTrabajador'])){
	$data = $_POST['nuevoTrabajador'];
	$db = new Database();
    try {
    	$nombreC = $data['appaterno']."/".$data['apmaterno']."/".$data['nombre'];
        $query = $db->connect()->prepare("INSERT INTO bd (matricula, curp, nombre, nombre2, nombre3, nombre4) VALUES ('".$data['matricula']."','".$data['curp']."','".$nombreC."','".$data['appaterno']."','".$data['apmaterno']."','".$data['nombre']."') ")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'TRABAJADOR REGISTRADO CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIÓ UN ERROR AL REGISTRAR TRABAJADOR, INTENTE MÁS TARDE O CONSULTE CON EL ADMINISTRADOR'.$e;
	}
	echo json_encode($mensaje);
}
?>