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
}?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Generar Constancias MANUALES</title>
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

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Aug 30 2023 with Bootstrap v5.3.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
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
          <li class="breadcrumb-item active">CCHL Manual</li>
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

                <!--<div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>-->

                <div class="card-body form-group">
                  <h5 class="card-title"> GENERADOR DE CCHL <span>(CONSTANCIA MANUAL)</span></h5>
                  <div class="row">
                    <div class="col-lg-3">
                      <label>Buscar por Folio SIAP:</label>
                      <input type="text" class="form-control form-control-sm"  id="folioSIAP">
                    </div>
                    <div class="col-lg-2 align-self-center">
                      <button class="btn btn-sm btn-primary mt-4" id="buscar">BUSCAR</button>
                    </div>
                  </div>
                  <div id="cursoRes">
                    
                  </div>


              </div>
            </div><!-- End Sales Card -->


          </div>

        </div>
      </div><!-- End Left side columns -->


    </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once 'page-format/footer.php'; ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>

</html>
<script type="text/javascript">  
var timeout = null;

function cargarCCHL(folioSIAP){
  $.ajax({
        url: 'fetch/fetch_cursos.php',
        type: 'post',
        data: {cursoDetalle:folioSIAP},
        dataType: 'json',
        success:function(data){
          $('#cursoRes').html(data.content);
        }
  });
}

$(document).on('click', '#buscar', function () {
  var folioSIAP = $('#folioSIAP').val();
  cargarCCHL(folioSIAP);
});

$(document).on('keyup', '#buscMat', function () {
  timeout = setTimeout(function() {
    matricula = $('#buscMat').val();
    $.ajax({
          url: 'fetch/fetch_cursos.php',
          type: 'post',
          data: {matricula:matricula},
          dataType: 'json',
          success:function(data){
            if(data.status){
              $('#matricula').val(data.matricula);
              $('#resNomb').val(data.nombre);
            }else{
              $('#buscMat').val("");
              $('#resNomb').val("");
              $('#matricula').val("");
              alert(data.message);
            }
          }
    });
  }, 1500);
});

$(document).on('keydown', '#buscMat', function () {
  clearTimeout(timeout);
});

$(document).on('click', '#agregarParticipante', function () {
  if( $('#matricula').val() == "" || $('#resNomb').val() == "" || $('#calificacion').val() == "" || $('#calificacion').val() < 0 || $('#calificacion').val() > 100){
    alert("EL PARTICIPANTE NO PUEDE SER AGREGADO, VERIFIQUE LOS DATOS, INTENTE MÁS TARDE O CONSULTE CON EL ADMINISTRADOR");
  }else{
    var datos = {};
    datos['matricula'] = $('#matricula').val();
    datos['foliosiap'] = $('#foliosiap').val();
    datos['calificacion'] = $('#calificacion').val();
    $.ajax({
          url: 'fetch/fetch_cursos.php',
          type: 'post',
          data: {agregarParticipante:datos},
          dataType: 'json',
          success:function(data){
            if(data.state){
              /*$('#buscMat').val("");
              $('#resNomb').val("");
              $('#matricula').val("");
              $('#calificacion').val("");*/
              cargarCCHL($('#foliosiap').val());
            }else{
              alert(data.message);
            }
          }
    });
  }
});

</script>