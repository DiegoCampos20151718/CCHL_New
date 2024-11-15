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
	$query = $db->connect()->prepare('SELECT contrasena FROM bd WHERE matricula = :usuario ');
	$query->execute(['usuario' => $datos['usuario']]);
	$row = $query->fetch(PDO::FETCH_NUM);

		if($datos['nueva'] == $datos['confirmar']){
			try {
				$passwd = password_hash($datos['nueva'], PASSWORD_BCRYPT); 
				$db = new Database();
				$query = $db->connect()->prepare('UPDATE bd SET contrasena = :contrasena WHERE matricula = :usuario');
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
	
	
	echo json_encode($data);
}

if(isset($_POST['idusuario']) && isset($_POST['nombre']) && isset($_POST['paterno']) && isset($_POST['materno'])){
	$activo= array("NO","SI");
	$toggle = array('<button class="btn btn-sm btn-danger baja"><i class="bi bi-toggle2-off"></i></button>','<button class="btn btn-sm btn-success baja"><i class="bi bi-toggle2-on"></i></button>');
	$output = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT bd.matricula matricula, rol_usuario.descripcion descripcion, bd.activo activo, bd.nombre2 nombre2, bd.nombre3 nombre3, bd.nombre4 nombre4 FROM bd
	LEFT JOIN rol_usuario ON bd.rol=rol_usuario.id WHERE bd.matricula LIKE "'.$_POST['idusuario'].'%" && bd.nombre2  LIKE "%'.$_POST['nombre'].'%" && bd.nombre3  LIKE "%'.$_POST['paterno'].'%" && bd.nombre4  LIKE "%'.$_POST['materno'].'%"  LIMIT 500
	');
	$query->execute();
	$rows = $query->rowCount();

	foreach ($query as $row ) {
		$output[] = array($row['matricula'], $row['nombre2'], $row['nombre3'], $row['nombre4'], $row['descripcion'], $activo[$row['activo']], $toggle[$row['activo']].' <button class="btn btn-sm btn-primary editarUsuario"><i class="ri ri-edit-box-fill"></i></button>');
	}
	$data['data'] = $output;
	echo json_encode($data);
}


if(isset($_POST['baja'])){
	$db = new Database();
    try {
		//$query = $db->connect()->prepare("DELETE from usuarios WHERE username='$_POST['activo']'")->execute();
        $query = $db->connect()->prepare("UPDATE bd SET activo = NOT activo WHERE matricula = '".$_POST['baja']."'")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIÓ UN ERROR AL MODIFICAR USUARIO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR';
	}
	echo json_encode($mensaje);
}

if(isset($_POST['alta'])){
	$data = $_POST['alta'];
	$db = new Database();
    try {
        $query = $db->connect()->prepare("INSERT INTO bd (matricula, curp, nombre2, nombre3, nombre4, contrasena, rol, activo, puesto, correo) VALUES ('".$data['usrMatricula']."','".$data['usrCURP']."', '".$data['usrNombre']."', '".$data['usrPaterno']."', '".$data['usrMaterno']."', '".$data['usrMatricula']."', '".$data['usrTipo']."',1, '".$data['usrCategoria']."', '".$data['usrCorreo']."')")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = ''.$e;
		if ($e->errorInfo[1] == 1062) {
		    $mensaje['message'] = 'YA EXISTE UN TRABAJADOR REGISTRADO CON ES MATRICULA, VERIFIQUE LOS DATOS O EDITE EL REGISTRO.';
		}
	}
	echo json_encode($mensaje);
}

if(isset($_POST['busqUsuario'])){
	$output = array();
	$db = new Database();

	$query = $db->connect()->prepare('SELECT matricula, curp, nombre2, nombre3, nombre4, rol, puesto, correo FROM bd WHERE matricula = :identifier ');
	$query->execute(['identifier' => $_POST['busqUsuario']]);
	$row = $query->fetch(PDO::FETCH_NUM);

	$data = array (
	'matricula' => $row[0],
	'curp' => $row[1],
	'nombre' => $row[2],
	'appaterno' => $row[3],
	'apmaterno' => $row[4],
	'rol' => $row[5],
	'categoria' => $row[6],
	'correo' => $row[7]
	);

	echo json_encode ($data);
	exit;
}

if(isset($_POST['modUser'])){
	$data = $_POST['modUser'];
	$db = new Database();
    try {
        $query = $db->connect()->prepare("UPDATE bd SET curp = '".$data['curp']."', nombre2 = '".$data['nombre']."', nombre3 = '".$data['appaterno']."', nombre4 = '".$data['apmaterno']."', rol = ".$data['tipo'].", puesto = '".$data['categoria']."', correo = '".$data['correo']."'  WHERE matricula = '".$data['matricula']."'")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = $e;
	}
	echo json_encode($mensaje);
}

?>