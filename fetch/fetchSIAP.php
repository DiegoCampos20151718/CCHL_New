<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'database.php';
session_start();

if(isset($_POST['buscarfolio'])){ 
	$folioSIAP = $_POST['buscarfolio'];
	$data = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT nombrePlanFormativo, nombredeleventodeCapacitacion, finicio, ftermino, horas, IFNULL(instructores.nombre,0), adiestramientoMed, instructorAviso
		FROM cchl_validacion 
		LEFT JOIN instructores ON cchl_validacion.instructor = instructores.id
		WHERE nocontrol = :buscarfolio ');
	$query->execute(['buscarfolio' => $folioSIAP]);
	$existe = $query->rowCount();

	if($existe==0){
		$data['state'] = false;
		$data['message'] = " El curso con el Folio ".$folioSIAP." no fue encontrado.";
	}else{
		$row = $query->fetch(PDO::FETCH_NUM);
		if($row[6] == 1){
			$data['state'] = false;
			$data['message'] = " El curso con el Folio ".$folioSIAP." pertenece a los cursos de constancia manual o adiestramiento médico.";
		}else{
			$instructor = $row[5];
			$enviarCorreo = '';
			if($instructor=="0"){
				$instructor= ' <i class="text-danger">El instructor no ha sido asignado aún</i>';
			}else{
				if($row[7]=="0"){
					$enviarCorreo.= '<i class="text-danger"> El instructor no ha aceptado el Aviso de Privacidad</i>';
				}else{
					$enviarCorreo.= '<i class="text-success"> El instructor aceptó el Aviso de Privacidad</i> <button class="btn btn-sm btn-success" id="enviarCorreo">Enviar notificación <i class="bi bi-envelope-fill" style="font-size: 15px;"></i></button>';
				}
			}
			$data['state'] = true;
			$data['content'] ='<div class="col-12">
				<b>Folio SIAP:</b> <i id="folioc">'.$folioSIAP.'</i><br>
				<b>Nombre del plan formativo:</b> '.$row[0].' <br>
				<b>Nombre del evento de Capacitación:</b> '.$row[1].' <br>
				<b>Inicio :</b> '.$row[2].'<b> Termino :</b> '.$row[3].' ( '.$row[4].' horas )<br> 
			</div>
			<div class="col-6">
			<b>Instructor</b> '.$instructor.' <button class="btn btn-sm btn-warning rounded-pill" id="editarInstructor"> <i class="ri ri-edit-2-fill"> </i></button><br>
			</div>
			<div class="col-6">
				'.$enviarCorreo.'
			</div>';
			//cuando se imprimian todas las constancias del curso
			/*if($row[5]!="0"){
				$data['content'].= '<div class="col-12 d-flex justify-content-end"><button class="btn btn-sm btn-outline-info rounded-pill align-self-end" id="imprimirCCHL">IMPRIMIR CCHL</button></div>';
			}*/
			
			$query = $db->connect()->prepare('SELECT CP.MATRICULA, BD.curp, CP.NOMBRE, CP.PUESTO, CVE.cve_actual, CP.CALIFICACION, BD.correo FROM cchl_participantes CP 
				LEFT JOIN cchl_validacion CV ON CP.NUMCONTROL = CV.nocontrol
				LEFT JOIN bd ON CP.MATRICULA = bd.matricula
				LEFT JOIN cveocupacion CVE ON CP.PUESTO = CVE.descripcion
				WHERE CP.NUMCONTROL = "'.$folioSIAP.'"');
			$query->execute();
			$rows = $query->rowCount();
			$cont = 1;
			$table = '<div class="col-12 mt-2"><table class="table table-sm table-bordered table-hover"><thead>
			<th>#</th>
			<th>MATRICULA</th>
			<th>CURP</th>
			<th>NOMBRE</th>
			<th>CATEGORIA</th>
			<th>CORREO <BR> ELECTRÓNICO</th>
			<th>CVE</th>
			<th>CALIFICACION</th>
			</thead><tbody>';
			foreach ($query as $row ) {
				$table.= '<tr>';
				$table.= '<td>'.$cont.'</td>';
				$table.= '<td>'.$row[0].'</td>';
				$table.= '<td>'.$row[1].'</td>';
				$table.= '<td>'.$row[2].'</td>';
				$table.= '<td>'.$row[3].'</td>';
				$table.= '<td>'.$row[6].'</td>';
				$table.= '<td>'.$row[4].'</td>';
				$table.= '<td>'.$row[5].'</td>';
				$table.= '</tr>';
				$cont++;
			}
			$table.= '</tbody></table></div>';
			$data['content'] = $data['content'].$table;
		}
		

	}
	echo json_encode($data);
}

if(isset($_POST['buscarInstructor'])){ 
	$data = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT instructor, nombreCapacitador, rfcagente FROM cchl_validacion WHERE nocontrol = :folioSIAP ');
	$query->execute(['folioSIAP' => $_POST['buscarInstructor']]);
	$row_cont = $query->rowCount(); 
	$row = $query->fetch(PDO::FETCH_NUM);
	if($row_cont == 0){
		$data['instructor']=$row[0];
		$data['capacitador']=$row[1];
		$data['rfc']=$row[2];
	}else{
		$data['instructor']="0";
		$data['capacitador']="INSTITUTO MEXICANO DEL SEGURO SOCIAL";
		$data['rfc']="IMS 421231 I45";
	}
	
	echo json_encode($data);
}

if(isset($_POST['modificarInstructor']) && isset($_POST['instructor']) && isset($_POST['capacitador']) && isset($_POST['rfc'])){
	$data = array();
	$db = new Database();

	try {
	    $query = $db->connect()->prepare('UPDATE cchl_validacion SET instructor = :instructor, nombreCapacitador = :capacitador, rfcagente = :rfc WHERE nocontrol = :folioSIAP ');
		$query->execute([':instructor' => $_POST['instructor'], ':capacitador' => $_POST['capacitador'], ':rfc' => $_POST['rfc'], ':folioSIAP' => $_POST['modificarInstructor']]);
		$data['status'] = true;
		$data['message'] = "Instructor asignado con éxito";
	} catch (Exception $e) {
	    $data['status'] = false;
	    $data['message'] = "Error al actualizar Instructor, intente más tarde o consulte con el administrador";
	}

	echo json_encode($data);
}

if(isset($_POST['checkfolio'])){ 
	$folioSIAP = $_POST['checkfolio'];
	$data = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT nombrePlanFormativo FROM cchl_validacion 
		WHERE nocontrol = :buscarfolio ');
	$query->execute(['buscarfolio' => $folioSIAP]);
	$existe = $query->rowCount();

	if($existe==0){
		$data['state'] = false;
		$data['message'] = " El curso con el Folio ".$folioSIAP." no fue encontrado.";
	}else{
		$data['state'] = true;
	}
	echo json_encode($data);
}


if(isset($_POST['matricula'])){ 
	$matricula = $_POST['matricula'];
	$data = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT P.NUMCONTROL, P.CURSO, P.FECHAINI, P.FECHAFIN, V.instructor, V.instructorAviso, P.DESCARGADO
	FROM cchl_participantes P
	LEFT JOIN cchl_validacion V ON P.NUMCONTROL = V.nocontrol
	WHERE MATRICULA = :matricula ');
	$query->execute(['matricula' => $matricula]);
	$existe = $query->rowCount();

	if($existe==0){
		$data['state'] = false;
		$data['message'] = " No se encontraron cursos asociados al trabajador  (".$matricula.") ";
	}else{
		$data['state'] = true;		
		$table = '<div class="col-12 mt-2"><table class="table table-sm table-bordered table-hover"><thead>
		<th>CURSO</th>
		<th>FECHA DE INICIO</th>
		<th>FECHA DE FIN</th>
		<th>CONSTANCIA</th>
		</thead><tbody>';
		foreach ($query as $row ) {
			$table.= '<tr>';
			$table.= '<td>'.$row[1].'</td>';
			$table.= '<td>'.$row[2].'</td>';
			$table.= '<td>'.$row[3].'</td>';
			if($row[4] != 0 && $row[5] == 1){
				if($row[6]==1){
					$table.= '<td><a class="generarCCHL btn btn-success" data-matricula="'.$_POST['matricula'].'" data-foliosiap="'.$row[0].'"><i class="bi bi-check-all"></i></a></td>';
				}else{
					$table.= '<td><a class="generarCCHL btn btn-warning" data-matricula="'.$_POST['matricula'].'" data-foliosiap="'.$row[0].'"><i class="bi bi-cloud-arrow-down"></i></a></td>';
				}
				
			}else{
				$table.= '<td>NO DISPONIBLE</td>';
			}
			$table.= '</tr>';
		}
		$data['content'] = $table;
		$data['state'] = true;
		
	}
	echo json_encode($data);
}

if(isset($_POST['buscarOcupacionE'])){ 
	$puesto = $_POST['buscarOcupacionE'];
	$data = array();
	$db = new Database();
	$query = $db->connect()->prepare('SELECT cve_actual FROM cveocupacion WHERE descripcion = :puesto');
	$query->execute(['puesto' => $puesto]);
	$row = $query->fetch(PDO::FETCH_NUM);
	echo $row[0];
}
?>