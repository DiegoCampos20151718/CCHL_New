<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'database.php';
session_start();

if(isset($_POST['cambiarContrasena'])){ 
	$datos = $_POST['cambiarContrasena'];
	$data = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT contrasena FROM usuarios WHERE usuario = :usuario ');
	$query->execute(['usuario' => $datos['usuario']]);
	$row = $query->fetch(PDO::FETCH_NUM);

	if($row[0] == $datos['actual'] || password_verify($datos['actual'], $row[0])){
		if($datos['nueva'] == $datos['confirmar']){
			try {
				$passwd = password_hash($datos['nueva'], PASSWORD_BCRYPT); 
				$db = new Database();
				$query = $db->connect()->prepare('UPDATE usuarios SET contrasena = :contrasena WHERE usuario = :usuario');
				$query->execute(array(':contrasena' => $passwd, ':usuario' => $datos['usuario']));

				$data['state'] = true;
				$data['message'] = "CONTRASEÑA ACTUALIZADA CON ÉXITO";
			} catch (PDOException $e) {
				$data['state'] = false;
				$data['message'] = "OCURRIO UN ERROR AL ACTUALIZAR LA CONTRASEÑA, INTENTE MÁS TARDE O CONSULTE CON EL ADMINISTRADOR";
			}
		}else{
			$data['state'] = false;
			$data['message'] = "LA CONTRASEÑA NUEVA NO COINCIDE CON LA CONFIRMACIÓN";
		}
	}else{
		$data['state'] = false;
		$data['message'] = "LA CONTRASEÑA ACTUAL NO ES CORRECTA";
	}
	
	echo json_encode($data);
}

if(isset($_POST['idusuario']) && isset($_POST['nombre'])){
	$activo= array("NO","SI");
	$toggle = array('<button class="btn btn-sm btn-danger baja"><i class="bi bi-toggle2-off"></i></button>','<button class="btn btn-sm btn-success baja"><i class="bi bi-toggle2-on"></i></button>');
	$output = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT U.usuario usuario, U.rol rol, U.activo activo, bd.nombre nombre 
		FROM usuarios U 
	LEFT JOIN bd ON U.usuario = bd.matricula WHERE u.usuario LIKE "'.$_POST['idusuario'].'%" && bd.nombre  LIKE "%'.$_POST['nombre'].'%" LIMIT 500
	');
	$query->execute();
	$rows = $query->rowCount();

	foreach ($query as $row ) {
		$output[] = array($row['usuario'], $row['nombre'], $row['rol'], $activo[$row['activo']], $toggle[$row['activo']]);
	}
	$data['data'] = $output;
	echo json_encode($data);
}


if(isset($_POST['baja'])){
	$db = new Database();
    try {
		//$query = $db->connect()->prepare("DELETE from usuarios WHERE username='$_POST['activo']'")->execute();
        $query = $db->connect()->prepare("UPDATE usuarios SET activo = NOT activo WHERE usuario = '".$_POST['baja']."'")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIÓ UN ERROR AL MODIFICAR USUARIO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR';
	}
	echo json_encode($mensaje);
}

if(isset($_POST['altaInst'])){
	$data = $_POST['altaInst'];
	$db = new Database();
    try {
        $query = $db->connect()->prepare("INSERT INTO instructores (id, nombre, activo, grado_estudio, centro_lab, instructor_hab, folio_constancia, fecha_constancia) VALUES ('".$data['instMatricula']."','".$data['instNombre']."', 1, '".$data['instGrado']."', ".$data['instCentroLab'].", ".$data['instHabilitado'].", '".$data['instFolioConstancia']."', '".$data['instFechaConstancia']."') ")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'INSTRUCTOR REGISTRADO CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = ''.$e;
	}
	echo json_encode($mensaje);
}

if(isset($_POST['instructornombre']) && isset($_POST['instructorunidad']) ){
	$activo= array("NO","SI");
	$output = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT i.id id, i.nombre nombre, i.activo activo, i.grado_estudio grado_estudio, u.nombre  centro_lab FROM instructores i
		LEFT JOIN unidades u ON i.centro_lab = u.id
	 WHERE i.nombre LIKE "%'.$_POST['instructornombre'].'%" AND u.nombre LIKE "%'.$_POST['instructorunidad'].'%" ');
	$query->execute();
	$rows = $query->rowCount();

	foreach ($query as $row ) {
		$output[] = array($row['id'], $row['nombre'], $activo[$row['activo']], $row['grado_estudio'], $row['centro_lab'], '<button class="btn btn-sm btn-primary editarInst"><i class="ri ri-edit-box-fill"></i></button>');
	}
	$data['data'] = $output;
	echo json_encode($data);
}

if(isset($_POST['modInst'])){
	$data = $_POST['modInst'];
	$db = new Database();
    try {
        $query = $db->connect()->prepare("UPDATE instructores SET nombre = '".$data['modInstNombre']."', activo = '".$data['modInstActivo']."', grado_estudio = '".$data['modInstGrado']."', centro_lab = ".$data['modInstCentroLab'].", instructor_hab = ".$data['modInstHabilitado'].", folio_constancia = '".$data['modInstFolioConstancia']."', fecha_constancia = '".$data['modInstFechaConstancia']."' WHERE id = '".$data['modInstId']."'")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIÓ UN ERROR AL MODIFICAR EL INSTRUCTOR. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR';
	}
	echo json_encode($mensaje);
}


if(isset($_POST['busqInst'])){
	$output = array();
	$db = new Database();

	$query = $db->connect()->prepare('SELECT id, nombre, activo, grado_estudio, centro_lab, instructor_hab, folio_constancia, fecha_constancia FROM instructores WHERE id = :identifier ');
	$query->execute(['identifier' => $_POST['busqInst']]);
	$row = $query->fetch(PDO::FETCH_NUM);

	$data = array (
	'id' => $row[0],
	'nombre' => $row[1],
	'activo' => $row[2],
	'gradoestudio' => $row[3],
	'centrolab' => $row[4],
	'insthabilitado' => $row[5],
	'folioconstancia' => $row[6],
	'fechaconstancia' => $row[7]
	);

	echo json_encode ($data);
	exit;
}

if(isset($_POST['matricula'])){
	$output = array();
	$db = new Database();

	$query = $db->connect()->prepare('SELECT nombre4, nombre3, nombre2 FROM bd WHERE matricula = :identifier');
	$query->execute(['identifier' => $_POST['matricula']]);
	$row = $query->fetch(PDO::FETCH_NUM);

	$rows = $query->rowCount();
	if ($rows > 0) {
		$data = array ('nombre' => $row[0]." ".$row[1]." ".$row[2],'status' => true);
	}else{
		$data = array ('status' => false);
	}
	

	echo json_encode ($data);
	exit;
}
?>