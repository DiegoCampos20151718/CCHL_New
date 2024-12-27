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

  <title>Cargar CCHL's</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700"
        rel="stylesheet">
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
  <link href="assets/vendor/dropzone/dropzone.min.css" rel="stylesheet" />

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

<body id="pagina" data-value="cargarcchl">

  <!-- ======= Header ======= -->
  <?php include_once 'page-format/header.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once 'page-format/sidebar.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Cargar CCHL's</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Administrador</a></li>
          <li class="breadcrumb-item active">Cargar CCHL's</li>
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
                  <div class="row mt-4">
                    <div class="col-lg-3">
                      <label>Buscar por Folio SIAP:</label>
                      <input type="text" class="form-control form-control-sm" onkeypress="return isNumberKey(event);" id="folioSIAP">
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
                  <div id="result" style="display:none;">
                    <div class="row d-flex pb-1">
                        <div class="col-lg-12 text-center align-items-center justify-content-center text-info" >
                            Arrastre los archivos en este recuadro o de clic en el para abrir el explorador
                        </div>
                    </div>
                    <div class="row d-flex pb-1">
                        <div class="col-lg-12 align-items-center justify-content-center" >
                          <form action="fetch/fetch_file.php" class="dropzone border-info rounded-lg" id="my-awesome-dropzone" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="directory" id="directory" readonly />
                            <div class="dz-message" data-dz-message><span></span></div>
                          </form>
                        </div>
                    </div>
                    <div id="archivos">
                        
                    </div>
                  </div>

                </div>
                
              </div>
            </div><!-- End Sales Card -->

          </div>
        </div><!-- End Left side columns -->


      </div>



      <div class="modal" tabindex="-1" id="confirmar">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirmar</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>¿Está seguro de que desea eliminar los archivos de forma permanente?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="button" class="btn btn-sm btn-danger" id="confirmarEliminar">Si</button>
            </div>
          </div>
        </div>
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
  <script src="assets/vendor/dropzone/dropzone.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>

</html>
<script type="text/javascript">  

/*dictDefaultMessage: "{{ trans('messages.dict_default_message') }}",
dictFallbackMessage: "{{ trans('messages.dict_fallback_message') }}",
dictFallbackText: "{{ trans('messages.dict_fallback_text') }}",
dictFileTooBig: "{{ trans('messages.dict_file_too_big') }}",
dictInvalidFileType: "{{ trans('messages.dict_invalid_file_type') }}",
dictResponseError: "{{ trans('messages.dict_response_error') }}",
dictCancelUpload: "{{ trans('messages.dict_cancel_upload') }}",
dictUploadCanceled: "{{ trans('messages.dict_upload_canceled') }}",
dictCancelUploadConfirmation: "{{ trans('messages.dict_cancel_upload_confirmation') }}",
dictRemoveFile: "{{ trans('messages.dict_remove_file') }}",
dictRemoveFileConfirmation:   "{{ trans('messages.dict_remove_file_confirmation') }}",
dictMaxFilesExceeded: "{{ trans('messages.dict_max_files_exceeded') }}",*/



Dropzone.options.myAwesomeDropzone = { // camelized version of the `id`
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 7, // MB
    //maxFiles: 50,
    //addRemoveLinks: true,
    parallelUploads: 50,
    accept: function(file, done) {
            done();
    },
    init: function () {
      this.on("success", function (file) {
        console.log("success > " + file.name);
        file.previewElement.remove();
        listarArchivos($('#directory').val());
      });
    },
    acceptedFiles: '.pdf',
    dictInvalidFileType: "El archivo no es de tipo Imagen",
    dictFileTooBig: "El tamaño de la imagen sobrepasa los 7MB"
};

$(document).on('click', '#buscar', function () {
  buscarParticipantes($('#folioSIAP').val());
});

function buscarParticipantes(folioSIAP){
  Dropzone.forElement("#my-awesome-dropzone").removeAllFiles(true);
  $('#result').hide();
  $('#alertas').hide();
  $('#alertas').removeClass();
  $('#alertas').text("");
  $('#directory').val("");
  if(folioSIAP == ""){
    $('#alertas').show();
    $('#alertas').addClass('alert alert-danger text-center');
    $('#alertas').text("Introduce un Folio SIAP válido");
  }else{
    $.ajax({
        url: 'fetch/fetchSIAP.php',
        type: 'post',
        data: {checkfolio:folioSIAP},
        dataType: 'json',
        success:function(data){
          if(data.state){
            $('#result').show();
            $('#directory').val(folioSIAP);
            listarArchivos(folioSIAP);
          }else{
            $('#alertas').show();
            $('#alertas').addClass('alert alert-danger text-center');
            $('#alertas').text(data.message);
          }
        }
    });
  }
}

function listarArchivos(folioSIAP){
  $.ajax({
        url: 'fetch/fetch_file.php',
        type: 'post',
        data: {listarArchivos:folioSIAP},
        success:function(data){
            $('#archivos').html(data);
        }
    });
}

$(document).on('click', '#eliminar', function() {
  $('#confirmar').modal('show');
});

$(document).on('click', '#confirmarEliminar', function() {
    var eliminar = [];
    $("input:checkbox[name=img-path]:checked").each(function(){
        eliminar.push($(this).val());
        //alert($(this).val());
    });
    
    $.ajax({
      url: 'fetch/fetch_file.php',
      method: 'post',
      data: {eliminar:eliminar},
      dataType: 'json',
      success:function(data){
        alert(data.message);
        listarArchivos($('#directory').val());
      }
    });
    $('#confirmar').modal('hide');
});
</script>