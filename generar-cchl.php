<?php
include_once 'fetch/database.php';
session_start();
if(!isset($_SESSION)){ 
  session_start(); 
} 
if(!isset($_SESSION['cchl']['rol'])){
  header('location: index.php');
}else{
  if($_SESSION['cchl']['rol'] != 1){
    header('location: index.php');
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Generar Constancias REGULARES</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/imss-green-icon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body id="pagina" data-value="generarcchl">

  <!-- ======= Header ======= -->
  <?php include_once 'page-format/header.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once 'page-format/sidebar.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Generar CCHL</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Administrador</a></li>
          <li class="breadcrumb-item active">Generar CCHL</li>
          <li class="breadcrumb-item active">CCHL regular</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-12 col-md-12">
              <div class="card info-card sales-card">

                <div class="card-body form-group">
                  <h5 class="card-title"> GENERADOR DE CCHL <span>(CONSTANCIAS DE COMPETENCIAS Y HABILIDADES LABORALES)</span></h5>
                  <div class="row">
                    <div class="col-lg-3">
                      <label>Buscar por Folio SIAP:</label>
                      <input type="text" class="form-control form-control-sm" id="folioSIAP">
                    </div>
                    <div class="col-lg-2 align-self-center">
                      <button class="btn btn-sm btn-primary mt-4" id="buscar">BUSCAR</button>
                    </div>
                  </div>
                  <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary rounded mt-4" id="downloadCertificates" style="display: none;">Descargar Certificados</button>
                  </div>
                  <div class="row justify-content-center mt-2">
                    <div class="col-6">
                      <div id="alertas" style="display: none;"></div>
                    </div>
                  </div>
                  <div class="row mt-2" id="result"></div>
                  
                </div>

              </div>
            </div><!-- End Sales Card -->

          </div>
        </div><!-- End Left side columns -->

      </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="modalInstructor" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Actualizar Instructor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body form-group">
            <div class="row">
              <label><b>Nombre del instructor</b></label>
              <div class="col-12 mb-2">
                <select class="form-control form-control-sm" id="instructor">
                  <option value="0" selected>Selecciona un instructor</option>
                  <?php 
                    $db = new Database();
                    $query = $db->connect()->prepare('SELECT id, nombre FROM instructores WHERE activo = 1');
                    $query->execute();

                    while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
                      echo ("<option value='".$user['id']."'>".$user['nombre']."</option>\n");
                    }
                  ?>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-12">
                <label><b>Nombre del Capacitador</b></label>
                <input type="text" class="form-control form-control-sm" id="capacitador">
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-12">
                <label><b>RFC AGENTE STPS</b></label>
                <input type="text" class="form-control form-control-sm" id="rfc">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="guardarInstructor">Guardar Cambios</button>
          </div>
        </div>
      </div>
    </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once 'page-format/footer.php'; ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>

  <!-- Template Main JS File -->
  <script type="text/javascript">
    function buscarFolioSIAP(folioSIAP) {
    $.ajax({
        url: 'fetch/fetchSIAP.php',
        type: 'post',
        data: { action: 'buscarFolio', buscarfolio: folioSIAP },
        dataType: 'json',
        success: function(response) {
            if (response.state) {
                $('#result').html(response.content);
                //Deshabilitar el botón "Descargar Certificados" según el estado del instructor
                if (response.instructorAssigned) {
                    $('#downloadCertificates').show().prop('disabled', false).text('Descargar Certificados');
                } else {
                    $('#downloadCertificates').show().prop('disabled', true).text('Asigne un instructor para descargar los certificados');
                }
            } else {
                $('#alertas').show().addClass('alert alert-danger text-center').text(response.message);
                // Ocultar el botón "Descargar Certificados" si la búsqueda falla
                $('#downloadCertificates').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
            console.log(xhr.responseText);
            // Ocultar el botón "Descargar Certificados" si hay un error en la búsqueda
            $('#downloadCertificates').hide();
        }
    });
}

    $(document).on('click', '#buscar', function () {
        var folioSIAP = $('#folioSIAP').val();
        buscarFolioSIAP(folioSIAP);
    });

    //Descargar todos los certificados
    $(document).on('click', '#downloadCertificates', function () {
        var folioSIAP = $('#folioSIAP').val();
        window.open("cchl-pdf.php?folioCCHL=" + folioSIAP, '_blank');
    });

$(document).on('click', '#editarInstructor', function () {
  $('#modalInstructor').modal('show');
  var folioSIAP = $('#folioc').text();
  $.ajax({
        url: 'fetch/fetchSIAP.php',
        type: 'post',
        data: {buscarInstructor:folioSIAP},
        dataType: 'json',
        success:function(data){
          $('#instructor').val(data.instructor);
          $('#capacitador').val(data.capacitador);
          $('#rfc').val(data.rfc);
          /*if(data.state){
            $('#result').html(data.content);
          }else{
            $('#alertas').show();
            $('#alertas').addClass('alert alert-danger text-center');
            $('#alertas').text(data.message);
          }*/
        }
  });
});

$(document).on('click', '#guardarInstructor', function () {
  var folioSIAP = $('#folioc').text();
  var instructor = $('#instructor').val();
  var capacitador = $('#capacitador').val();
  var rfc = $('#rfc').val();
  if(folioSIAP == "" || instructor == "0" || instructor == "" || capacitador == "" || rfc == ""){
    alert("Complete los datos del instructor");
  }else{
    $.ajax({
          url: 'fetch/fetchSIAP.php',
          type: 'post',
          data: {modificarInstructor:folioSIAP, instructor:instructor, capacitador:capacitador, rfc:rfc},
          dataType: 'json',
          success:function(data){
            /*$('#instructor').val(data.instructor);
            $('#capacitador').val(data.capacitador);
            $('#rfc').val(data.rfc);*/
            alert(data.message);
            if(data.status){
              $('#modalInstructor').modal('hide');
              buscarFolioSIAP(folioSIAP);
            }
          }
    });
  }
});

$('#guardarInstructor').on('hidden.bs.modal', function () {
  $('#folioc').text() = "";
  $('#instructor').val() = "";
  $('#capacitador').val() = "INSTITUTO MEXICANO DEL SEGURO SOCIAL";
  $('#rfc').val() = "IMS 421231 I45";
});

$(document).on('click', '#imprimirCCHL', function () {
  var folioSIAP = $('#folioc').text();
  window.open("cchl-pdf.php?folioCCHL="+folioSIAP,'_blank');
});

$(document).on('click', '#enviarCorreo', function () {
  var folioSIAP = $('#folioc').text();
  $.ajax({
        url: 'mailing.php',
        type: 'post',
        data: {emailCurso:folioSIAP},
        dataType: 'json',
        success:function(data){
         alert(data.message);
        }
  });
});
</script>

<style>
  #downloadCertificates {
    display: none;
  }
</style>

</body>

</html>