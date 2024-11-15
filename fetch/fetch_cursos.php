<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'database.php';
session_start();

if(isset($_POST['altaCurso'])){
	$data = $_POST['altaCurso'];
	$db = new Database();
    try {
    	$inicio = date("d/m/Y", strtotime($data['cursoInicio']));
    	$fin = date("d/m/Y", strtotime($data['cursoFin']));

        $query = $db->connect()->prepare("INSERT INTO cchl_validacion (nocontrol, planFormativo, nombrePlanFormativo, nombredeleventodeCapacitacion, fInicio, fTermino, horas, programaEspecifico, instructor, nombreCapacitador, rfcagente, adiestramientoMed) VALUES ('".$data['cursoFolio']."','".$data['cursoNombre']."','".$data['cursoNombre']."','".$data['cursoNombre']."','".$inicio."','".$fin."','".$data['cursoDuracion']."','".$data['cursoPrograma']."','".$data['cursoInstructor']."','".$data['cursoCapacitador']."','".$data['cursoRFC']."','".$data['cursoMedicos']."')")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = ''.$e;
		if ($e->errorInfo[1] == 1062) {
		    $mensaje['message'] = 'YA EXISTE UN CURSO REGISTRADO CON ESE FOLIO, VERIFIQUE LOS DATOS O EDITE EL EN LA PESTAÑA CONFIGURACION.';
		}
	}
	echo json_encode($mensaje);
}

if(isset($_POST['folioSIAP']) ){
	$output = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT CV.nocontrol, CV.nombredeleventodeCapacitacion, CV.programaEspecifico, CV.fInicio, CV.fTermino, CV.horas, IFNULL(I.nombre, "SIN ASIGNAR") nombreInstructor, CV.nombreCapacitador, CV.rfcagente 
		FROM cchl_validacion CV
		LEFT JOIN instructores I ON CV.instructor = I.id LIMIT 500
	');
	$query->execute();
	$rows = $query->rowCount();

	foreach ($query as $row ) {
		$output[] = array($row['nocontrol'], $row['nombredeleventodeCapacitacion'], $row['programaEspecifico'], $row['fInicio'], $row['fTermino'], $row['horas'], $row['nombreInstructor'], $row['nombreCapacitador'], $row['rfcagente'], '<button class="btn btn-sm btn-primary editarCurso"><i class="ri ri-edit-box-fill"></i></button>');
	}
	$data['data'] = $output;
	echo json_encode($data);
}

if(isset($_POST['buscarCurso'])){
	$output = array();
	$db = new Database();

	$query = $db->connect()->prepare('SELECT nocontrol, nombredeleventodeCapacitacion, programaEspecifico, STR_TO_DATE(fInicio, "%d/%m/%Y"),  STR_TO_DATE(fTermino, "%d/%m/%Y"), horas, instructor, nombreCapacitador, rfcagente FROM cchl_validacion WHERE nocontrol = :identifier');
	$query->execute(['identifier' => $_POST['buscarCurso']]);
	$row = $query->fetch(PDO::FETCH_NUM);

	$data = array (
	'foliosiap' => $row[0],
	'nombredelevento' => $row[1],
	'cursoprograma' => $row[2],
	'fechainicio' => $row[3],
	'fechafin' => $row[4],
	'horas' => $row[5],
	'cursoinstructor' => $row[6],
	'nombreagentecapac' => $row[7],
	'rfcagente' => $row[8]
	);

	echo json_encode ($data);
	exit;
}

if(isset($_POST['cursoDetalle'])){
	$output = array();
	$db = new Database();

	$query = $db->connect()->prepare('SELECT nocontrol, nombredeleventodeCapacitacion, programaEspecifico, fInicio, fTermino, horas, instructores.nombre, nombreCapacitador, rfcagente, adiestramientoMed 
		FROM cchl_validacion
		LEFT JOIN instructores ON cchl_validacion.instructor = instructores.id
		WHERE nocontrol = :identifier');
	$query->execute(['identifier' => $_POST['cursoDetalle']]);
	$existe = $query->rowCount();

	$resContent = "";
	if($existe<1){
		$resContent = '<div class="col-lg-12 mt-2"> <div class="alert alert-danger" role="alert">
		  	No se encontro el curso con el Folio '.$_POST['cursoDetalle'].'
			</div>
		</div>';
	}else{
		$row = $query->fetch(PDO::FETCH_NUM);
		if($row[9] == 0){
			$resContent = '<div class="col-lg-12 mt-2"> <div class="alert alert-warning" role="alert">
			  		El curso con el folio '.$_POST['cursoDetalle'].' es un cuso regular <br>
			  		Diríjase a la pestaña de <a href="generar-cchl.php">Generar CCHL Regular</a> para generar las constancias 
				</div>
			</div>';
		}else{
			$resContent = '
				<div class="row mt-2">
					<div class="col-12">
                      <label><small class="text-theme"><b>Verifique los datos antes de generar las constancias</b></small></label>
                    </div>
					<div class="col-lg-4">
                      <b>Folio SIAP</b><br>
                      <i>'.$row[0].'</i> 
                      <input id="foliosiap" type="hidden" value="'.$row[0].'">                  
                    </div>
                    <div class="col-lg-4">
                      <b>Nombre del Evento de Capacitación</b><br>
                      <i id="nombredelevento">'.$row[1].'</i>                   
                    </div>
                    <div class="col-lg-4">
                      <b>Programa Especifico</b><br>
                      <i id="cursoprograma">'.$row[2].'</i>                   
                    </div>
                    <div class="col-lg-4">
                      <b>Fecha de Inicio</b><br>
                      <i id="fechainicio">'.$row[3].'</i>                   
                    </div>
                    <div class="col-lg-4">
                      <b>Fecha de Fin</b><br>
                      <i id="fechafin">'.$row[4].'</i>                   
                    </div>
                    <div class="col-lg-4">
                      <b>Horas</b><br>
                      <i id="horas">'.$row[5].'</i>                   
                    </div>
                    <div class="col-lg-4">
                      <b>Instructor</b><br>
                      <i id="cursoinstructor">'.$row[6].'</i>                   
                    </div>
                    <div class="col-lg-4">
                      <b>Nombre del Agente de Capacitación</b><br>
                      <i id="nombreagentecapac">'.$row[7].'</i>                   
                    </div>
                    <div class="col-lg-4">
                      <b>RFC de Agente de Capacitación</b><br>
                      <i id="rfcagente">'.$row[8].'</i>                   
                    </div>
                </div>
                <br>
                <div class="row mt-2">
                	<div class="col-3">
                		<small><i class="bi bi-search"> Busca el participante por matricula</i></small>
                		<input type="text" class="form-control form-control-sm" id="buscMat">
                		<input type="hidden" id="matricula">
                	</div>
                	<div class="col-4">
                		<small><i>Nombre</i></small>
                		<input type="text" class="form-control form-control-sm" id="resNomb" readonly>
                	</div>
                	<div class="col-2">
                		<small><i>Calificación</i></small>
                		<input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm" id="calificacion">
                	</div>
                	<div class="col-3 align-self-end">
                		<button class="btn btn-sm btn-primary" id="agregarParticipante">AGREGAR PARTICIPANTE</button>
                	</div>
                </div>

                <div class="col-12 mt-2">
                	<div class="alert alert-dark alert-dismissible fade show p-2 text-center">Participantes</div>
                </div>';

                $queryPART = $db->connect()->prepare('SELECT MATRICULA, NOMBRE, CALIFICACION, PUESTO
									FROM cchl_participantes
									WHERE NUMCONTROL = :identifier');
								$queryPART->execute(['identifier' => $_POST['cursoDetalle']]);
								$existePART = $queryPART->rowCount();
								if($existePART<1){
									$resContent.= '<div class="row mt-2">
                		<div class="col-12">NO HAY PARITICPANTES REGISTRADOS EN ESTE CURSO</div>
                	</div
									';
								}else{
									$resContent.= '<div class="row mt-2">
                		<div class="col-3 text-theme"><b>MATRICULA</b></div>
                		<div class="col-3 text-theme"><b>NOMBRE</b></div>
                		<div class="col-3 text-theme"><b>PUESTO</b></div>
                		<div class="col-3 text-theme"><b>CALIFICACIÓN</b></div>
                	</div>';
                	foreach ($queryPART as $key) {
                			$resContent.= '<div class="row mt-2">
		                		<div class="col-3 text-theme">'.$key['MATRICULA'].'</div>
		                		<div class="col-3 text-theme">'.$key['NOMBRE'].'</div>
		                		<div class="col-3 text-theme">'.$key['PUESTO'].'</div>
		                		<div class="col-3 text-theme">'.$key['CALIFICACION'].'</div>
		                	</div>';
                	}
								}
		}
		
	}
	
	$data = array();
	$data['status'] = true;
	$data['content'] = $resContent;
	echo json_encode ($data);
	exit;
}

if(isset($_POST['matricula'])){
	$output = array();
	$db = new Database();

	$query = $db->connect()->prepare('SELECT matricula, CONCAT(nombre2, " ", nombre3, " ", nombre4) nombre FROM bd WHERE matricula = :identifier');
	$query->execute(['identifier' => $_POST['matricula']]);
	$existe = $query->rowCount();

	$data = array();
	if($existe<1){
		$data['status'] = false;
		$data['message'] = "NO ENCONTRADO";
		echo json_encode ($data);
		exit;
	}else{
		$row = $query->fetch(PDO::FETCH_NUM);
		$data['status'] = true;
		$data['matricula'] = $row[0];
		$data['nombre'] = $row[1];
		echo json_encode ($data);
		exit;
	}
}

if(isset($_POST['agregarParticipante'])){
	$datos = $_POST['agregarParticipante'];
	$matricula = $datos['matricula'];
	$foliosiap = $datos['foliosiap'];
	$calificacion = $datos['calificacion'];
	
	$output = array();
	$db = new Database();
	try{
		$pdo = $db->connect();
	    $pdo->beginTransaction();
	    $sql = $pdo->prepare("SELECT * FROM cchl_validacion WHERE nocontrol = :foliosiap");
	    $sql->execute(['foliosiap' => $foliosiap]);
	    $row = $sql->fetch(PDO::FETCH_ASSOC);

	    $sqlpartic = $pdo->prepare("SELECT * FROM bd WHERE matricula = :foliosiap");
	    $sqlpartic->execute(['foliosiap' => $matricula ]);
	    $rowpartic = $sqlpartic->fetch(PDO::FETCH_ASSOC);


	    $pdo->exec("INSERT INTO cchl_participantes (NUMCONTROL, CURSO, FECHAINI, FECHAFIN, MATRICULA, NOMBRE, PUESTO, CALIFICACION) VALUES ('".$row['nocontrol']."','".$row['programaEspecifico']."','".$row['fInicio']."','".$row['fTermino']."','".$rowpartic['matricula']."','".$rowpartic['nombre3'].'/'.$rowpartic['nombre4'].'/'.$rowpartic['nombre2']."','".$rowpartic['puesto']."','".$calificacion."')
	    	ON DUPLICATE KEY UPDATE CURSO='".$row['programaEspecifico']."', FECHAINI='".$row['fInicio']."', FECHAFIN='".$row['fInicio']."', NOMBRE='".$rowpartic['nombre3'].'/'.$rowpartic['nombre4'].'/'.$rowpartic['nombre2']."', PUESTO='".$rowpartic['puesto']."', CALIFICACION='".$calificacion."'");
	    	$output['state'] = true;
	    $pdo->commit();
	} catch (PDOException $e) {
		$pdo->rollback();
		$output['state'] = false; 
		$output['message'] = 'OCURRIO UN ERROR AL ASIGNAR EL INSUMO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}
	echo json_encode($output);
}


if(isset($_POST['modCurso'])){
	$datos = $_POST['modCurso'];
	$folioSIAP = $datos['folioSIAP'];
	$output = array();
	$db = new Database();
	try{
		$pdo = $db->connect();
	    $pdo->beginTransaction();
	    $pdo->exec("UPDATE cchl_validacion SET nombredeleventodeCapacitacion = '".$datos['nombredelevento']."', programaEspecifico = '".$datos['cursoPrograma']."', fInicio = '".$datos['fechaInicio']."', fTermino = '".$datos['fechaFin']."', horas = '".$datos['horas']."', instructor = '".$datos['cursoInstructor']."', nombreCapacitador = '".$datos['nombreAgenteCapacitador']."', rfcagente = '".$datos['RFCagente']."' WHERE nocontrol = '".$folioSIAP."'");
	    	$output['state'] = true;
	    	$output['message'] = 'EL CURSO SE MODIFICÓ CON ÉXITO';
	    $pdo->commit();
	} catch (PDOException $e) {
		$pdo->rollback();
		$output['state'] = false; 
		$output['message'] = 'OCURRIO UN ERROR AL MODIFICAR EL CURSO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'. $e;
	}
	echo json_encode($output);
}


?>