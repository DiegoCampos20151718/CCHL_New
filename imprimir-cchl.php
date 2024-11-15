<?php
session_start();
if(!isset($_SESSION)){ 
  session_start(); 
} 
if(!isset($_SESSION['cchl']['rol'])){
  header('location: index.php');
}else{
  if($_SESSION['cchl']['rol'] != 1 && $_SESSION['cchl']['rol'] != 2){
    header('location: index.php');
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Impresión de CCHL</title>
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

<body id="pagina" data-value="imprimircchl">

  <!-- ======= Header ======= -->
  <?php include_once 'page-format/header.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once 'page-format/sidebar.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Impimir CCHL</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Administrador</a></li>
          <li class="breadcrumb-item active">Constancias de Competencias Y Habilidades Laborales</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-12">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <div class="row mt-3">
                    <div class="col-lg-3">
                      <label>Buscar por Matricula:</label>
                      <input type="text" class="form-control form-control-sm" onkeypress="return isNumberKey(event);" id="matricula" <?php if($_SESSION['cchl']['rol'] == "2"){ ?> value="<?php echo $_SESSION['cchl']['username'];?>" readonly <?php } ?> >
                    </div>
                    <div class="col-lg-2 align-self-center">
                      <button class="btn btn-sm btn-primary mt-4" id="buscar">BUSCAR</button>
                    </div>
                  </div>
                  <div class="row justify-content-center mt-2">
                    <div class="col-6">
                      <div id="alertas" style="display: none;">
                      </div>
                    </div>
                  </div>
                  <div class="row mt-2" id="result">

                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

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
$(document).on('click', '#buscar', function () {
  var matricula = $('#matricula').val();
  buscarCCHLs(matricula);
});

function buscarCCHLs(matricula){
$('#result').empty();
  $('#alertas').hide();
  $('#alertas').removeClass();
  $('#alertas').text("");
  $('#result').empty();
  if(matricula == ""){
    $('#alertas').show();
    $('#alertas').addClass('alert alert-danger text-center');
    $('#alertas').text("Introduce una matricula válida");
  }else{
    $.ajax({
        url: 'fetch/fetchSIAP.php',
        type: 'post',
        data: {matricula:matricula},
        dataType: 'json',
        success:function(data){
          if(data.state){
            $('#result').html(data.content);
          }else{
            $('#alertas').show();
            $('#alertas').addClass('alert alert-danger text-center');
            $('#alertas').text(data.message);
          }
        }
    });
  }
}

$(document).on('click', '.generarCCHL', function () {
  var matricula = $(this).data('matricula');
  var folioCCHL = $(this).data('foliosiap');
  win = window.open('cchl-pdf.php?folioCCHL='+folioCCHL+'&matricula='+matricula,'_blank');
  $(win.document).ready(function(){
    var matricula = $('#matricula').val();
    buscarCCHLs(matricula);
    //alert("SU CCHL HA SIDO DESCARGADO");

  });
  
});
</script>