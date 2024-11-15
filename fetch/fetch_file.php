<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if (!empty($_FILES) && isset($_POST['directory'])) {
	if (!file_exists('../assets/files/'.utf8_decode($_POST['directory']))) {
	    mkdir('../assets/files/'.utf8_decode($_POST['directory']), 0777, true);
	}
	$uploadDir = '../assets/files/'.utf8_decode($_POST['directory']).'/';
 	$tmpFile = $_FILES['file']['tmp_name'];
 	$filename = $uploadDir.'/'. $_FILES['file']['name'];
 	move_uploaded_file($tmpFile,$filename);
}

if(isset($_POST['listarArchivos'])){
	$archivos = array('pdf' => 'assets/img/pdf.png');
	$output="
	<div class='row pb-1 mt-4'>
		<div class='col-12 d-flex justify-content-center text-center'>
			ARCHIVOS GUARDADOS
		</div>
	</div>
	<div class='row pb-1'>
		<div class='col-12 d-flex justify-content-end'>
			<button class='mdi mdi-trash-can-outline btn btn-danger' id='eliminar'><i class='ri ri-delete-bin-6-line
'></i></button>
		</div>
	</div>
	<div class='row d-flex pb-1 '>";

	if(file_exists('../assets/files/'.utf8_decode($_POST['listarArchivos']))){
		$directory = new DirectoryIterator('../assets/files/'.utf8_decode($_POST['listarArchivos']));
		foreach ($directory as $fileinfo) {
	    if ($fileinfo->isFile()) {
	      if($fileinfo->getExtension() == 'pdf' || $fileinfo->getExtension() == 'PDF'){
	      	$extension = $fileinfo->getExtension();
	                $output.='
	                <div class="col-3 mt-3">
			            <div class="img-cuadricula imagen shadow-lg rounded p-2" style="position: relative;">
			                <span>
			                <img src="'.$archivos[$extension].'" class="img-fluid">
			                <input type="checkbox" value="'.$_POST['listarArchivos'].'/'.$fileinfo.'" class="img-del" name="img-path"> </span>
			                <i>'.$fileinfo.'</i>
			            </div>
			        </div>';
	      }
	    }
	   }
	}
	
  	$output.='</div>';
	echo $output;
}

if(isset($_POST['eliminar'])){
	$data = array();
	$listaimgs = $_POST['eliminar'];
	$data['state'] = true;
	foreach($listaimgs as $key){
		try {
		    unlink('../assets/files/'.utf8_decode($key));
		} catch (Exception $e) {
		    $data['state'] = false;
		    $data['message'] = "Ocurrió un error al eliminar los arhivos, intente más tarde o consulte con el administrador.";
		}
	}
	if($data['state'] = true){
		$data['message'] = "Archivos eliminados con éxito";
	}
	echo json_encode($data);
}
?>