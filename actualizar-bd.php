<?php
session_start();
include_once 'fetch/database.php';
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

  <title>Actualizar Base de Datos</title>
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

<body id="pagina" data-value="actualizarbd">

  <!-- ======= Header ======= -->
  <?php include_once 'page-format/header.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once 'page-format/sidebar.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Actualizar BD</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Administrador</a></li>
          <li class="breadcrumb-item active">Actualizar BD</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-6 col-md-6">
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

                <div class="card-body">
                  <h5 class="card-title"><span>Actualizar </span> Pestaña de Participantes</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bx-user-pin"></i>
                    </div>
                    <div class="ps-3">
                      <form align="center" action="data-import/importparticipantes.php" method="post" enctype="multipart/form-data" id="import_form">
                        <div class="row mb-2">
                          <div class="col-12 text-start">
                            <small>Selecciona un ARCHIVO (.csv)*</small>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-12">
                            <input type="file" name="file" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <input type="submit" class="btn btn-success" name="import_participantes" value="IMPORTAR" id="IMPORTAR">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Opciones</h6>
                    </li>

                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCurso">Registrar Curso</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title"><span>Actualizar </span> Pestaña de Validación</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-files"></i>
                    </div>
                    <div class="ps-3">
                      <form align="center" action="data-import/importvalidacion.php" method="post" enctype="multipart/form-data" id="import_form">
                        <div class="row mb-2">
                          <div class="col-12 text-start">
                            <small>Selecciona un ARCHIVO (.csv)*</small>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-12">
                            <input type="file" name="file" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <input type="submit" class="btn btn-success" name="import_validacion" value="IMPORTAR" id="IMPORTAR">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-6 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Opciones</h6>
                    </li>

                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#usuariosModal">Registrar trabajador</a></li>
                  </ul>
                </div>
                
                <div class="card-body" style="overflow: hidden;">
                  <h5 class="card-title"><span>Actualizar </span> Trabajadores</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <form align="center" action="data-import/importbdpersonal.php" method="post" enctype="multipart/form-data" id="import_form">
                        <div class="row mb-2">
                          <div class="col-12 text-start">
                            <small>Selecciona un ARCHIVO (.csv)*</small>
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-12">
                            <input type="file" name="file" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <input type="submit" class="btn btn-success" name="import_data" value="IMPORTAR" id="IMPORTAR">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

            </div><!-- End Customers Card -->

          </div>
        </div><!-- End Left side columns -->


      </div>


      <!--start usuario modal-->
          <div class="modal fade" id="usuariosModal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Nuevo Usuario</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="altaForm" autocomplete="off" method="post">
                  <div class="modal-body form-group">
                    <small class="text-warning">Si el usuario no se encuentra en la base de datos de trabajadores o validación es posible que no se puedan consultar los datos adicionales.</small>
                    <div class="row mt2">
                      <div class="col-6">
                        <label>Matrícula</label>
                        <input type="text" class="form-control form-control-sm" id="usrMatricula" required>
                      </div>
                      <div class="col-6">
                        <label>CURP</label>
                        <input type="text" class="form-control form-control-sm" id="usrCURP" required>
                      </div>
                      
                    </div> 
                    <div class="row mt2">
                      <div class="col-12">
                        <label>Nombre(s)</label>
                        <input type="text" class="form-control form-control-sm" id="usrNombre" required>
                      </div>
                    </div>
                    <div class="row mt2">
                      <div class="col-6">
                        <label>Apellido Paterno</label>
                        <input type="text" class="form-control form-control-sm" id="usrPaterno" required>
                      </div>
                      <div class="col-6">
                        <label>Apellido Materno</label>
                        <input type="text" class="form-control form-control-sm" id="usrMaterno" required>
                      </div>
                    </div>
                    <div class="row mt2">
                      <div class="col-12">
                        <label>Correo electrónico</label><br>
                        <small>Proporcione un correo electronico activo</small>
                        <input type="text" class="form-control form-control-sm" id="usrCorreo" required>
                      </div>
                    </div>
                    <div class="row mt2">
                      <div class="col-6">
                        <label>Categoría</label>
                        <select class="form-control form-control-sm" id="usrCategoria" required>
                          <option value="">Seleccione una opcion</option>
                          <?php 
                            $db = new Database();
                            $query = $db->connect()->prepare('SELECT descripcion FROM cveocupacion ORDER BY descripcion ASC');
                            $query->execute();

                            while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
                              echo ("<option value='".$user['descripcion']."'>".$user['descripcion']."</option>\n");
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-6">
                        <label>Tipo de Usuario</label>
                        <select class="form-control form-control-sm" id="usrTipo" required>
                          <option value="">Seleccione una opcion</option>
                          <option value="2">Usuario</option>
                          <option value="1">Administrador</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="bttnAlta">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div><!-- End usuario modal-->


      <!--Modal curso-->
      <div class="modal fade" id="modalCurso" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Registrar Curso</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="registrarCurso" autocomplete="off" method="post">
              <div class="modal-body form-group">
                <div class="row">
                  <div class="col-12">
                    <small>Registre un curso de forma manual, <i class="text-theme">verifique los datos antes de realizar el registro.</i></small>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-6">
                    <label>Folio SIAP</label>
                    <input type="text" id="cursoFolio" class="form-control form-control-sm" required>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <label>Nombre del curso</label>
                    <input type="text" id="cursoNombre" class="form-control form-control-sm" required>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-6">
                    <label>Inicio del curso</label>
                    <input type="date" id="cursoInicio" class="form-control form-control-sm" required> 
                  </div>
                  <div class="col-6">
                    <label>Fin del curso</label>
                    <input type="date" id="cursoFin" class="form-control form-control-sm" required>
                  </div>
                  <div class="col-6">
                    <label>Duración en horas</label>
                    <input type="number" step="1" min="1" max="1000" id="cursoDuracion" class="form-control form-control-sm" onkeypress="return isNumber(event)" required>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-lg-8">
                    <label>Programa Especifico</label>
                    <select class="form-control form-control-sm" id="cursoPrograma" required>  
                      <option value="">Seleccione una opción</option>
                        <?php 
                            $db = new Database();
                            $query = $db->connect()->prepare('SELECT area, cve_actual FROM cvetematica ORDER BY area ASC');
                            $query->execute();

                            while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
                              echo ("<option value='".$user['cve_actual']."'>".$user['area']."</option>\n");
                            }
                        ?>
                    </select>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <label>Nombre del instructor</label>
                    <select class="form-control form-control-sm" id="cursoInstructor" required> 
                      <option value="" selected="selected">Selecciona un instructor</option>
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
                <div class="row mt-2">
                  <div class="col-12">
                    <label>Nombre del Capacitador</label>
                    <input type="text" id="cursoCapacitador" class="form-control form-control-sm" required>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <label>RFC AGENTE STPS</label>
                    <input type="text" id="cursoRFC" class="form-control form-control-sm" required>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-6">
                    <label>¿Es un curso de Adiestramiento Médico?</label>
                    <select id="cursoMedicos" class="form-control form-control-sm" required>
                      <option value="">Selecciona una opción</option>
                      <option value="1">SI</option>
                      <option value="0">NO</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm" id="bttnCurso">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div><!-- End Basic Modal-->

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

<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

$("#altaForm").submit(function(e){
   e.preventDefault();
   $("#bttnAlta").prop('disabled', true);
    var alta = {};
    alta['usrMatricula'] = $('#usrMatricula').val();
    alta['usrCURP'] = $('#usrCURP').val();
    alta['usrNombre'] = $('#usrNombre').val();
    alta['usrPaterno'] = $('#usrPaterno').val();
    alta['usrMaterno'] = $('#usrMaterno').val();
    alta['usrTipo'] = $('#usrTipo').val();
    alta['usrRol'] = $('#usrRol').val();
    alta['usrCategoria'] = $('#usrCategoria').val();
    alta['usrCorreo'] = $('#usrCorreo').val();
    $.ajax({
         url: 'fetch/fetch_usuarios.php',
         type: 'post',
         data: {alta:alta},
         dataType: 'json',
         success:function(data){
            alert(data.message);
            if(data.state){
              $('#usuariosModal').modal('hide');
            }
         }
    });
});

$('#usuariosModal').on('hidden.bs.modal', function () {
  $('#usrMatricula').val('');
  $('#usrCURP').val('');
  $('#usrNombre').val('');
  $('#usrPaterno').val('');
  $('#usrMaterno').val('');
  $('#usrTipo').val('');
  $('#usrRol').val('');
  $('#usrCategoria').val('');
  $("#bttnAlta").prop('disabled', false);
});

///////CURSOS
$("#registrarCurso").submit(function(e){
   e.preventDefault();
   //$("#bttnCurso").prop('disabled', true);
    var altaCurso = {};
    altaCurso['cursoFolio'] = $('#cursoFolio').val();
    altaCurso['cursoNombre'] = $('#cursoNombre').val();
    altaCurso['cursoInicio'] = $('#cursoInicio').val();
    altaCurso['cursoFin'] = $('#cursoFin').val();
    altaCurso['cursoDuracion'] = $('#cursoDuracion').val();
    altaCurso['cursoPrograma'] = $('#cursoPrograma option:selected').text();
    altaCurso['cursoInstructor'] = $('#cursoInstructor').val();
    altaCurso['cursoCapacitador'] = $('#cursoCapacitador').val();
    altaCurso['cursoRFC'] = $('#cursoRFC').val();
    altaCurso['cursoMedicos'] = $('#cursoMedicos').val();

    $.ajax({
         url: 'fetch/fetch_cursos.php',
         type: 'post',
         data: {altaCurso:altaCurso},
         dataType: 'json',
         success:function(data){
            alert(data.message);
            if(data.state){
              $('#modalCurso').modal('hide');
            }
         }
    });
});

$('#modalCurso').on('hidden.bs.modal', function () {
  $('#cursoFolio').val('');
  $('#cursoNombre').val('');
  $('#cursoInicio').val('');
  $('#cursoFin').val('');
  $('#cursoDuracion').val('');
  $('#cursoPrograma').val('');
  $('#cursoInstructor').val('');
  $('#cursoCapacitador').val('');
  $('#cursoRFC').val('');
  $('#cursoMedicos').val('');
  $("#bttnCurso").prop('disabled', false);
});
</script>