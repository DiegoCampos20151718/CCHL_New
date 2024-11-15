<?php
setlocale( LC_ALL,"es_ES@euro","es_ES","esp" );
session_start();
if(!isset($_SESSION)){ 
  session_start(); 
} 
if(!isset($_SESSION['cchl']['rol'])){
  header('location: index.php');
}else{
  if($_SESSION['cchl']['instructor'] != 1){
    header('location: index.php');
  }
}
$meses = array("","enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>AVISOS DE PRIVACIDAD | INSTRUCTOR</title>
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

<body id="pagina" data-value="aviso-privacidad">

  <!-- ======= Header ======= -->
  <?php include_once 'page-format/header.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once 'page-format/sidebar.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Mis cursos</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Instructor</a></li>
          <li class="breadcrumb-item active">Avisos de privacidad</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12" style="overflow-x: scroll;">
          <div class="row">
            <!-- Sales Card -->
            <div class="col-12">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <div class="row mt-3">
                    <div class="col-1 my-auto text-center">
                      <span><i class="bi bi-chat-left-dots" style="font-size: 2vw;"></i></span>
                    </div>
                    <div class="col-11">
                      <label style="font-size: 14px;">En este apartado encontraras el listado de los cursos en los que estas asociado, valida y autoriza aquellos cursos para expedir las constancias de los asistentes. <i class="text-danger">Si no reconoces algun curso comunicate a la oficina de Capacitación</i></label>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

          </div>

          <div class="row">
            <!-- Sales Card -->
            <div class="col-12">
              <div class="card info-card sales-card">
                <div class="card-body">
                   <div class="row">
                        <div class="col-12">
                          <?php echo $_SESSION['cchl']['instructor'];?>
                          <table id="example" class="display table table-sm table-active table-hover" >
                            <thead>
                                <tr>
                                  <th>FOLIO SIAP</th>
                                  <th>NOMBRE DEL EVENTO DE CAPACITACION</th>
                                  <th>INICIO</th>
                                  <th>TERMINO</th>
                                  <th>AVISO AUTORIZADO</th>
                                </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

          </div>
        </div><!-- End Left side columns -->


      </div>

      <div class="modal fade" id="modalAviso" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body form-group">
                    <div class="row mb-4">
                      <input type="hidden" id="folioSIAP">
                      <div class="col-3 justify-content-center d-flex">
                        <img src="assets/img/imss-logo-green.png" class="rounded" alt="IMSS" style="max-height: 125px;">
                      </div>
                      <div class="col-8 my-auto" style="text-align: justify; text-justify: inter-word;">
                        <h5>Aviso de Privacidad Simplificado para el uso de firma para la emisión de Constancias de Competencia y Habilidades Laborales del Instituto Mexicano del Seguro Social</h5>
                      </div>
                    </div>
                    <div class="row mt-4 justify-content-center">
                      <div class="col-10" style="text-align: justify; text-justify: inter-word;">
                        El Instituto Mexicano del Seguro Social (IMSS) es responsable del tratamiento de los datos personales que nos proporcione, los cuales serán protegidos conforme a lo dispuesto por la Ley General de Protección de Datos Personales en Posesión de Sujetos Obligados y demás normatividad que resulte aplicable. <br><br>

                        Sus datos personales serán utilizados los datos que se recaban de los instructores/as internos/as serán utilizados para para la elaboración de las Constancias de Competencia y Habilidades Laborales. <br><br>

                        Se informa que no se realizarán transferencias de datos personales. que requieran su consentimiento, salvo que sea estrictamente necesario para atender requerimientos de información de una autoridad competente, debidamente fundados y motivados. <br><br>

                        Usted podrá consultar el aviso de privacidad integral que se encuentra publicado en las instalaciones de las Oficina de Capacitación. <br><br>
                      </div>
                    </div>
                    <div class="row mt-2 justify-content-center">
                      <div class="col-10 float-right">
                        <i>Fecha de elaboración: <?php echo date('d')." ".$meses[(int)date('m')]." ".date('Y'); ?> </i>
                      </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-warning" id="aceptarAviso">ACEPTAR AVISO DE PRIVACIDAD</button>
                </div>
              </div>
            </div>
          </div><!-- End Large Modal-->

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

  <!-- DataTables  & Plugins -->
  <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="assets/vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="assets/vendor/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="assets/vendor/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="assets/vendor/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="assets/vendor/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="assets/vendor/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>

<script type="text/javascript">
var foliosiap = "" 
  planformativo = "" 
  finicio = ""
  ffin = ""
  avisoautorizado = "";

$(document).ready(function () {

  /*Inicio declaracion tabla historial para avisos de privacidad*/
  $('#example thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#example thead');
 
   var table = $('#example').DataTable({
      language: {
         url: 'assets/vendor/datatables-language/es-ar.json' //Ubicacion del archivo con el json del idioma.
      },
      ajax:{
         url: 'fetch/fetch_avisos.php',
         type: 'post',
         data: function(d) {
            d.foliosiap = foliosiap,
            d.planformativo = planformativo,
            d.finicio = finicio,
            d.ffin = ffin,
            d.avisoautorizado = avisoautorizado
         },
         dataType: 'json',
      },
      orderCellsTop: true,
      fixedHeader: true,
      filter: false,
      ordering: false,
      initComplete: function() {
         var api = this.api();
         // For each column
         api.columns().eq(0).each(function(colIdx) {
            // Set the header cell to contain the input element
               var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
               var title = $(cell).text();
               $(cell).html( '<input type="text" placeholder="'+title+'" id="filter'+title+'" />' );
                $('#filterOPCIONES').prop('disabled', true);
               // On every keypress in this input
               /*$('input', $('.filters th').eq($(api.column(colIdx).header()).index()) )
                  .off('keyup change')
                  .on('keyup change', function (e) {
                  e.stopPropagation();
                  // Get the search value
                  $(this).attr('title', $(this).val());
                  var regexr = '({search})'; //$(this).parents('th').find('select').val();
                  var cursorPosition = this.selectionStart;
                  // Search the column for that value
                  api
                     .column(colIdx)
                     .search((this.value != "") ? regexr.replace('{search}', '((('+this.value+')))') : "", this.value != "", this.value == "")
                     .draw();
                  $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                  });*/
                $('input', $('.filters th').eq($(api.column(colIdx).header()).index()) )
                  .off('keyup change')
                  .on('keyup change', function (e) {
                    //alert($('#filterUSUARIO').val());
                      var typingTimer = null;
                      clearTimeout(typingTimer);
                      typingTimer = setTimeout(doneTyping, 1000);
                  });
         });
      },
   });
   /*Fin declaracion tabla historial para avisos de privacidad*/


  function doneTyping() {
      usuario = $('#filterUSUARIO').val();
      nombre = $('#filterNOMBRE').val();
      rol = $('#filterROL').val();
      activo = $('#filterACTIVO').val();
      paterno = $('#filterPATERNO').val();
      materno = $('#filterMATERNO').val();
      table.ajax.reload();
  }


  $(document).on('click', '.autorizar', function () {
    $('#folioSIAP').val($(this).data('folio'));
    $('#modalAviso').modal('show');
   });

  $(document).on('click', '#aceptarAviso', function () {
    var folioSIAP = $('#folioSIAP').val();
    $.ajax({
         url: 'fetch/fetch_avisos.php',
         type: 'post',
         data: {autorizar:folioSIAP},
         dataType: 'json',
         success:function(data){
            alert(data.message);
            if(data.state){
            $('#modalAviso').modal('hide');
              table.ajax.reload(null, false);
            }
         }
    });
   });
});



/*$(document).on('click', '.generarCCHL', function () {
  var matricula = $(this).data('matricula');
  var folioCCHL = $(this).data('foliosiap');
  win = window.open('cchl-pdf.php?folioCCHL='+folioCCHL+'&matricula='+matricula,'_blank');
  $(win.document).ready(function(){
    alert("SU CCHL HA SIDO DESCARGADO");
    var matricula = $('#matricula').val();
    buscarCCHLs(matricula);

  });
  
});*/
</script>