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

  <title>Configuración</title>
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

<body id="pagina" data-value="configuracion">

  <!-- ======= Header ======= -->
  <?php include_once 'page-format/header.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once 'page-format/sidebar.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Configuración</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Administrador</a></li>
          <li class="breadcrumb-item active">Configuración</li>
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

                <div class="card-body">

                   <!-- Default Tabs -->
                  <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active submenu" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab" aria-controls="usuarios" aria-selected="true">Usuarios</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link submenu" id="instructores-tab" data-bs-toggle="tab" data-bs-target="#instructores" type="button" role="tab" aria-controls="instructores" aria-selected="false">Instructores</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link submenu" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos" type="button" role="tab" aria-controls="cursos" aria-selected="false">Cursos</button>
                    </li>
                  </ul>
                  <div class="tab-content pt-2" id="myTabContent" style="overflow-x:scroll;">
                    <div class="tab-pane fade show active" id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab">

                      <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                          <button class="btn btn-sm btn-primary" id="nuevoUsuario"><b class="bi bi-plus">Nuevo Usuario</b></button>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12">
                          <table id="example" class="display table table-sm table-active table-hover" >
                            <thead>
                                <tr>
                                  <th>USUARIO</th>
                                  <th>NOMBRE</th>
                                  <th>PATERNO</th>
                                  <th>MATERNO</th>
                                  <th>ROL</th>
                                  <th>ACTIVO</th>
                                  <th>OPCIONES</th>
                                </tr>
                            </thead>
                          </table>
                        </div>
                      </div>

                    </div>

                    <!-- PESTAÑA INSTRUCTORES-->
                    <div class="tab-pane fade" id="instructores" role="tabpanel" aria-labelledby="instructores-tab">
                      <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                          <button class="btn btn-sm btn-primary" id="nuevoInstructor"><b class="bi bi-plus">Nuevo Instructor</b></button>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12">
                          <table id="tblinstructores" class="display table table-sm table-active table-hover" >
                            <thead>
                                <tr>
                                  <th>ID</th>
                                  <th>NOMBRE DEL INSTRUCTOR</th>
                                  <th>ACTIVO</th>
                                  <th>GRADO DE ESTUDIO</th>
                                  <th>CENTRO LABORAL</th>
                                  <th>OPCIONES</th>
                                </tr>
                            </thead>
                          </table>
                        </div>
                      </div>

                    </div>

                    <!--tab cursos-->
                    <div class="tab-pane fade" id="cursos" role="tabpanel" aria-labelledby="cursos-tab">

                      <div class="row">
                        <div class="col-12">
                          <table id="tblCursos" class="display table table-sm table-active table-hover" >
                            <thead>
                                <tr>
                                  <th>FOLIO SIAP</th>
                                  <th>NOMBRE DEL EVENTO DE CAPACITACION</th>
                                  <th>PROGRAMA ESPECIFICO</th>
                                  <th>FECHA INICIO</th>
                                  <th>FECHA FIN</th>
                                  <th>HORAS</th>
                                  <th>INSTRUCTOR</th>
                                  <th>NOMBRE DEL AGENTE CAPACITADOR</th>
                                  <th>RFC AGENTE</th>
                                  <th>OPCIONES</th>
                                </tr>
                            </thead>
                          </table>
                        </div>
                      </div>

                    </div>

                  </div><!-- End Default Tabs -->
                </div>

              </div>
            </div><!-- End Sales Card -->

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
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Matrícula</label>
                        <input type="text" class="form-control form-control-sm" id="usrMatricula" required>
                      </div>
                      <div class="col-6">
                        <label>CURP</label>
                        <input type="text" class="form-control form-control-sm" id="usrCURP" required>
                      </div>
                      
                    </div> 
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Nombre(s)</label>
                        <input type="text" class="form-control form-control-sm" id="usrNombre" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Apellido Paterno</label>
                        <input type="text" class="form-control form-control-sm" id="usrPaterno" required>
                      </div>
                      <div class="col-6">
                        <label>Apellido Materno</label>
                        <input type="text" class="form-control form-control-sm" id="usrMaterno" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Correo electrónico</label>
                        <input type="text" class="form-control form-control-sm" id="usrCorreo" required>
                      </div>
                    </div>
                    <div class="row mt-2">
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
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div><!-- End usuario modal-->

          <!--start modificar usuario modal-->
          <div class="modal fade" id="modifUsuarioModal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Modificar Usuario</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modificarUsuario" autocomplete="off" method="post">
                  <div class="modal-body form-group">
                    <small class="text-warning">Si el usuario no se encuentra en la base de datos de trabajadores o validación es posible que no se puedan consultar los datos adicionales.</small>
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Matrícula</label>
                        <input type="text" class="form-control form-control-sm" id="modMatricula" required readonly>
                      </div>
                      <div class="col-6">
                        <label>CURP</label>
                        <input type="text" class="form-control form-control-sm" id="modCURP" required>
                      </div>
                      
                    </div> 
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Nombre(s)</label>
                        <input type="text" class="form-control form-control-sm" id="modNombre" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Apellido Paterno</label>
                        <input type="text" class="form-control form-control-sm" id="modPaterno" required>
                      </div>
                      <div class="col-6">
                        <label>Apellido Materno</label>
                        <input type="text" class="form-control form-control-sm" id="modMaterno" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Correo electrónico</label>
                        <input type="text" class="form-control form-control-sm" id="modCorreo" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Categoría</label>
                        <select class="form-control form-control-sm" id="modCategoria" required>
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
                        <select class="form-control form-control-sm" id="modTipo" required>
                          <option value="">Seleccione una opcion</option>
                          <option value="2">Usuario</option>
                          <option value="1">Administrador</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div><!-- End modificar usuario modal-->


          <!--start nuevo instructor modal-->
          <div class="modal fade" id="nuevoInstructorModal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Nuevo Instructor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="altaInstForm" autocomplete="off" method="post">
                  <div class="modal-body form-group">
                    <small class="text-warning">El nombre del instructor aparecera en las constancias y documentos como usted lo registre. Verifique con cuidado antes de guardar.</small>
                    <div class="row">
                      <div class="col-6">
                        <label>Matricula</label>
                        <input type="text" class="form-control form-control-sm" id="instMatricula" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Nombre del Instructor</label>
                        <input type="text" class="form-control form-control-sm" id="instNombre" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Grado de estudio</label>
                        <input type="text" class="form-control form-control-sm" id="instGrado" required>
                      </div>
                    </div> 
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Centro Laboral</label>
                          <select class="form-control" id="instCentroLab" required> 
                            <option value="">SELECCIONE UNA OPCIÓN</option>
                            <?php 
                                $db = new Database();
                                $query = $db->connect()->prepare('SELECT id, nombre FROM unidades');
                                $query->execute();

                                while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
                                  echo ("<option value='".$user['id']."'>".$user['nombre']."</option>\n");
                                }
                            ?>
                           </select>
                      </div>
                    </div> 
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Instructor habilitado</label>
                        <select class="form-control form-control-sm" id="instHabilitado" required> 
                            <option value="">SELECCIONE UNA OPCIÓN</option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                      </div>
                      <div class="col-6">
                        <label>Folio de Constancia</label>
                        <input type="text" class="form-control form-control-sm" id="instFolioConstancia" >
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Fecha de Constancia</label>
                        <input type="date" class="form-control form-control-sm" id="instFechaConstancia" >
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div><!-- End nuevo instructor modal-->

          <!--start editar instructor modal-->
          <div class="modal fade" id="editatInstructorModal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Editar Instructor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modInstForm" autocomplete="off" method="post">
                  <div class="modal-body form-group">
                    <small class="text-warning">El nombre del instructor aparecera en las constancias y documentos como usted lo registre. Verifique con cuidado antes de guardar.</small>
                    <div class="row">
                      <div class="col-6">
                        <label>Matricula</label>
                        <input type="text" class="form-control form-control-sm" id="modInstMat" required disabled>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-12">
                        <input type="hidden" id="modInstId">
                        <label>Nombre del Instructor</label>
                        <input type="text" class="form-control form-control-sm" id="modInstNombre" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Grado de estudio</label>
                        <input type="text" class="form-control form-control-sm" id="modInstGrado" required>
                      </div>
                    </div> 
                    <div class="row mt-2">
                      <div class="col-12">
                        <label>Centro Laboral</label>
                          <select class="form-control form-control-sm" id="modInstCentroLab"> 
                            <option value="">SELECCIONE UNA OPCIÓN</option>
                            <?php 
                                $db = new Database();
                                $query = $db->connect()->prepare('SELECT id, nombre FROM unidades');
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
                        <label>Activo</label>
                        <select class="form-control form-control-sm" id="modInstActivo" required>
                          <option value="">Seleccione una opción</option>
                          <option value="1">SI</option>
                          <option value="0">NO</option>
                        </select>
                      </div>
                    </div> 
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Instructor habilitado</label>
                        <select class="form-control form-control-sm" id="modInstHabilitado" required> 
                            <option value="">SELECCIONE UNA OPCIÓN</option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                      </div>
                      <div class="col-6">
                        <label>Folio de Constancia</label>
                        <input type="text" class="form-control form-control-sm" id="modInstFolioConstancia" >
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6">
                        <label>Fecha de Constancia</label>
                        <input type="date" class="form-control form-control-sm" id="modInstFechaConstancia" >
                      </div>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div><!-- End editar instructor modal-->


          <div class="modal fade" id="modalCurso" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Editar curso</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modCursoForm" autocomplete="off" method="post">
                  <div class="modal-body form-group">
                      <div class="row">
                        <div class="col-lg-3">
                          <label>Folio SIAP</label>
                          <input type="text" id="folioSIAP" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-lg-9">
                          <label>Nombre del Evento de Capacitación</label>
                          <input type="text" id="nombredelevento" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-lg-6">
                          <label>Programa Especifico</label>
                          <select class="form-control form-control-sm" id="cursoPrograma" required>  
                            <option value="">Seleccione una opción</option>
                              <?php 
                                  $db = new Database();
                                  $query = $db->connect()->prepare('SELECT area, cve_actual FROM cvetematica ORDER BY area ASC');
                                  $query->execute();

                                  while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo ("<option value='".$user['area']."'>".$user['area']."</option>\n");
                                  }
                              ?>
                          </select>
                        </div>
                        <div class="col-lg-3">
                          <label>Fecha de Inicio:</label>
                          <input type="date" class="form-control form-control-sm" style="width: 140px;" id="fechaInicio">
                        </div>
                        <div class="col-lg-3">
                          <label>Fecha de Finalización</label>
                          <input type="date" class="form-control form-control-sm" style="width: 140px;" id="fechaFin">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-lg-4">
                          <label>Horas</label>
                          <input type="number" id ="horas" min="0" step="1" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-lg-6">
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
                        <div class="col-lg-8">
                          <label>Nombre del Agente Capacitador</label>
                          <input type="text" class="form-control form-control-sm" id="nombreAgenteCapacitador">
                        </div>
                        <div class="col-lg-4">
                          <label>RFC del Agente capacitador</label>
                          <input type="text" class="form-control form-control-sm" id="RFCagente">
                        </div>
                      </div>
                  </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
                </form>
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
  var timeout = null;

  var usuario = "" 
  nombre = "" 
  paterno = ""
  materno = "";

  var instructornombre = ""
  instructorusuario = ""
  instructorunidad = "";

  var folioSIAP ="";

$(document).ready(function () {

  /*Inicio declaracion tabla usuarios*/
  $('#example thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#example thead');
 
   var table = $('#example').DataTable({
      language: {
         url: 'assets/vendor/datatables-language/es-ar.json' //Ubicacion del archivo con el json del idioma.
      },
      ajax:{
         url: 'fetch/fetch_usuarios.php',
         type: 'post',
         data: function(d) {
            d.idusuario = usuario,
            d.nombre = nombre,
            d.paterno = paterno,
            d.materno = materno
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
                $('#filterROL').prop('disabled', true);
                $('#filterACTIVO').prop('disabled', true);
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
   /*fin de declaracion tabla usuarios*/

   /*Inicio declaracion tabla instructores*/
   $('#tblinstructores thead tr')
        .clone(true)
        .addClass('filtersInstr')
        .appendTo('#tblinstructores thead');
 
   var tableinstructores = $('#tblinstructores').DataTable({
      language: {
         url: 'assets/vendor/datatables-language/es-ar.json' //Ubicacion del archivo con el json del idioma.
      },
      ajax:{
         url: 'fetch/fetch_instructores.php',
         type: 'post',
         data: function(d) {
            d.instructornombre = instructornombre,
            d.instructorunidad = instructorunidad,
            d.instructorusuario = instructorusuario
         },
         dataType: 'json',
      },
      orderCellsTop: false,
      fixedHeader: true,
      filter: false,
      ordering: false,
      initComplete: function() {
         var api = this.api();
         // For each column
         api.columns().eq(0).each(function(colIdx) {
            // Set the header cell to contain the input element
               var cell = $('.filtersInstr th').eq($(api.column(colIdx).header()).index());
               var title = $(cell).text();
               $(cell).html( '<input type="text" placeholder="'+title+'" id="filterinst'+title.split(" ")[0]+'" />' );
                $('#filterinstID').prop('disabled', true);
                $('#filterinstACTIVO').prop('disabled', true);
                $('#filterinstOPCIONES').prop('disabled', true);
                $('#filterinstGRADO').prop('disabled', true);
               
                $('input', $('.filtersInstr th').eq($(api.column(colIdx).header()).index()) )
                  .off('keyup change')
                  .on('keyup change', function (e) {
                    //alert($('#filterUSUARIO').val());
                      var typingTimer = null;
                      clearTimeout(typingTimer);
                      typingTimer = setTimeout(doneTypingInst, 1000);
                  });
         });
      },
   });
   /*fin de declaracion tabla instructores*/


   /*Inicio declaracion tabla cursos*/
   $('#tblCursos thead tr')
        .clone(true)
        .addClass('filtersCursos')
        .appendTo('#tblCursos thead');
 
   var tablecursos = $('#tblCursos').DataTable({
      language: {
         url: 'assets/vendor/datatables-language/es-ar.json' //Ubicacion del archivo con el json del idioma.
      },
      ajax:{
         url: 'fetch/fetch_cursos.php',
         type: 'post',
         data: function(d) {
            d.folioSIAP = folioSIAP
         },
         dataType: 'json',
      },
      orderCellsTop: false,
      fixedHeader: true,
      filter: false,
      ordering: false,
      initComplete: function() {
         var api = this.api();
         // For each column
         api.columns().eq(0).each(function(colIdx) {
            // Set the header cell to contain the input element
               var cell = $('.filtersCursos th').eq($(api.column(colIdx).header()).index());
               var title = $(cell).text();
               $(cell).html( '<input type="text" placeholder="'+title+'" id="filtercursos'+title.split(" ")[0]+'" />' );
                /*$('#filterinstID').prop('disabled', true);
                $('#filterinstACTIVO').prop('disabled', true);
                $('#filterinstOPCIONES').prop('disabled', true);
                $('#filterinstGRADO').prop('disabled', true);*/
               
                $('input', $('.filtersCursos th').eq($(api.column(colIdx).header()).index()) )
                  .off('keyup change')
                  .on('keyup change', function (e) {
                    //alert($('#filterUSUARIO').val());
                      var typingTimer = null;
                      clearTimeout(typingTimer);
                      typingTimer = setTimeout(doneTypingCursos, 1000);
                  });
         });
      },
   });
   /*Fin declaracion tabla cursos*/

   function doneTyping() {
      usuario = $('#filterUSUARIO').val();
      nombre = $('#filterNOMBRE').val();
      rol = $('#filterROL').val();
      activo = $('#filterACTIVO').val();
      paterno = $('#filterPATERNO').val();
      materno = $('#filterMATERNO').val();
      table.ajax.reload();
    }

    function doneTypingInst() {
      instructornombre = $('#filterinstNOMBRE').val();
      instructorunidad = $('#filterinstCENTRO').val();
      instructorusuario =  $('#filterinstUSUARIO').val();
      tableinstructores.ajax.reload();
    }

    function doneTypingCursos() {
      folioSIAP = $('#filtercursosFOLIO').val();
      tablecursos.ajax.reload();
    }
   

  $(document).on('click', '.baja', function() {
    var folio = $(this).parent().siblings().eq(0).text();
    $.ajax({
         url: 'fetch/fetch_usuarios.php',
         type: 'post',
         data: {baja:folio},
         dataType: 'json',
         success:function(data){
            alert(data.message);
            if(data.state){
               table.ajax.reload(null, false);
            }
         }
    });
  });

  $(document).on('click', '#nuevoUsuario', function() {
    $('#usuariosModal').modal('show');
  });

  $(document).on('click', '#nuevoInstructor', function() {
    $('#nuevoInstructorModal').modal('show');
  });

$("#altaForm").submit(function(e){
   e.preventDefault();
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
               table.ajax.reload(null, false);
            }
         }
    });
  });

  $("#altaInstForm").submit(function(e){
   e.preventDefault();
    var altaInst = {};
    altaInst['instMatricula'] = $('#instMatriculaX').val();
    altaInst['instNombre'] = $('#instNombre').val();
    altaInst['instGrado'] = $('#instGrado').val();
    altaInst['instCentroLab'] = $('#instCentroLab').val();
    altaInst['instHabilitado'] = $('#instHabilitado').val();
    altaInst['instFolioConstancia'] = $('#instFolioConstancia').val();
    altaInst['instFechaConstancia'] = $('#instFechaConstancia').val();
    
    $.ajax({
         url: 'fetch/fetch_instructores.php',
         type: 'post',
         data: {altaInst:altaInst},
         dataType: 'json',
         success:function(data){
            alert(data.message);
            if(data.state){
               tableinstructores.ajax.reload(null, false);
               alert("Instructor registrado con éxito");
            }
         }
    });
  });

$(document).on('keyup', '#instMatricula', function () {
    matricula = $(this).val();
    timeout = setTimeout(function() {
    $.ajax({
          url: 'fetch/fetch_instructores.php',
          type: 'post',
          data: {matricula:matricula},
          dataType: 'json',
          success:function(data){
            if(data.status){
              $('#instNombre').val(data.nombre);
              //$('#modInstNombre').val(data.nombre);
            }else{
              $('#instNombre').val("NO SE ENCONTRO EL TRABAJADOR CON LA MATRICULA "+matricula);
              //$('#modInstNombre').val("NO SE ENCONTRO EL TRABAJADOR CON LA MATRICULA "+matricula);
            }
          }
    });
  }, 1500);
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

  $("#modInstForm").submit(function(e){
    e.preventDefault();

    var modInst = {};
    modInst['modInstId'] = $('#modInstId').val();
    modInst['modInstNombre'] = $('#modInstNombre').val();
    modInst['modInstActivo'] = $('#modInstActivo').val();
    modInst['modInstGrado'] = $('#modInstGrado').val();
    modInst['modInstCentroLab'] = $('#modInstCentroLab').val();
    modInst['modInstHabilitado'] = $('#modInstHabilitado').val();
    modInst['modInstFolioConstancia'] = $('#modInstFolioConstancia').val();
    modInst['modInstFechaConstancia'] = $('#modInstFechaConstancia').val();
    $.ajax({
           url: 'fetch/fetch_instructores.php',
           type: 'post',
           data: {modInst:modInst},
           dataType: 'json',
           success:function(data){
              alert(data.message);
              if(data.state){
                 tableinstructores.ajax.reload(null, false);
              }
           }
    });
  });


  $('#nuevoInstructorModal').on('hidden.bs.modal', function () {
    $('#instMatricula').val("");
    $('#instNombre').val("");
    $('#instGrado').val("");
    $('#instCentroLab').val("");
    $('#instHabilitado').val("");
    $('#instFolioConstancia').val("");
    $('#instFechaConstancia').val("");
  })

   $(document).on('click', '.submenu', function() {
    var opcion = $(this).attr('aria-controls');
    eval(opcion);
    });

   function usuarios(){
    table.ajax.reload(null, false);
    tableinstructores.rows().remove().draw();
  }

  function instructores(){
    tableinstructores.ajax.reload(null, false);
    table.rows().remove().draw();
  }

  function cursos(){
    tablecursos.ajax.reload(null, false);
    table.rows().remove().draw();
    tableinstructores.rows().remove().draw();
  }

  $("#modificarUsuario").submit(function(e){
     e.preventDefault();
      var modUser = {};
      modUser['matricula'] = $('#modMatricula').val();
      modUser['curp'] = $('#modCURP').val();
      modUser['nombre'] = $('#modNombre').val();
      modUser['appaterno'] = $('#modPaterno').val();
      modUser['apmaterno'] = $('#modMaterno').val();
      modUser['tipo'] = $('#modTipo').val();
      modUser['categoria'] = $('#modCategoria').val();
      modUser['correo'] = $('#modCorreo').val();
      
      $.ajax({
           url: 'fetch/fetch_usuarios.php',
           type: 'post',
           data: {modUser:modUser},
           dataType: 'json',
           success:function(data){
              alert(data.message);
              if(data.state){
                 table.ajax.reload(null, false);
              }
           }
      });
  });


  $("#modCursoForm").submit(function(e){
     e.preventDefault();
      var modCurso = {};
      modCurso['folioSIAP'] = $('#folioSIAP').val();
      modCurso['nombredelevento'] = $('#nombredelevento').val();
      modCurso['cursoPrograma'] = $('#cursoPrograma').val();
      modCurso['fechaInicio'] = $('#fechaInicio').val();
      modCurso['fechaFin'] = $('#fechaFin').val();
      modCurso['horas'] = $('#horas').val();
      modCurso['cursoInstructor'] = $('#cursoInstructor').val();
      modCurso['nombreAgenteCapacitador'] = $('#nombreAgenteCapacitador').val();
      modCurso['RFCagente'] = $('#RFCagente').val();
      
      $.ajax({
           url: 'fetch/fetch_cursos.php',
           type: 'post',
           data: {modCurso:modCurso},
           dataType: 'json',
           success:function(data){
              alert(data.message);
              if(data.state){
                 tablecursos.ajax.reload(null, false);
              }
           }
      });
  });

});

$('#usuariosModal').on('hidden.bs.modal', function () {
  $('#usrUsuario').val("");
  $('#usrTipo').val("");
})

$(document).on('click', '.editarInst', function() {
  $('#editatInstructorModal').modal('show');
  var busqInst = $(this).parent().siblings().eq(0).text();
  $.ajax({
         url: 'fetch/fetch_instructores.php',
         method: 'post',
         data: {busqInst:busqInst},
         success:function(data){
               var json = data,
               obj = JSON.parse(json);
               $("#modInstMat").val(obj.id),
               $("#modInstId").val(obj.id),
               $("#modInstNombre").val(obj.nombre),
               $("#modInstGrado").val(obj.gradoestudio),
               $("#modInstCentroLab").val(obj.centrolab),
               $("#modInstActivo").val(obj.activo),
               $("#modInstHabilitado").val(obj.insthabilitado),
               $("#modInstFolioConstancia").val(obj.folioconstancia),
               $("#modInstFechaConstancia").val(obj.fechaconstancia);
         }
   });
});

  $(document).on('click', '.editarUsuario', function() {
    $('#modifUsuarioModal').modal('show');
    var matricula = $(this).parent().siblings().eq(0).text();
    $.ajax({
         url: 'fetch/fetch_usuarios.php',
         method: 'post',
         data: {busqUsuario:matricula},
         success:function(data){
               var json = data,
               obj = JSON.parse(json);
               $("#modMatricula").val(obj.matricula),
               $("#modCURP").val(obj.curp),
               $("#modNombre").val(obj.nombre),
               $("#modPaterno").val(obj.appaterno),
               $("#modMaterno").val(obj.apmaterno),
               $("#modTipo").val(obj.rol),
               $("#modCorreo").val(obj.correo),
               $("#modCategoria").val(obj.categoria);
         }
   });
  });


/*CURSOS*/
$(document).on('click', '.editarCurso', function() {
  $('#modalCurso').modal('show');
  var folioSIAP = $(this).parent().siblings().eq(0).text();
  $.ajax({
         url: 'fetch/fetch_cursos.php',
         method: 'post',
         data: {buscarCurso:folioSIAP},
         success:function(data){
               var json = data,
               obj = JSON.parse(json);
               $("#folioSIAP").val(obj.foliosiap),
               $("#nombredelevento").val(obj.nombredelevento),
               $("#cursoPrograma").val(obj.cursoprograma),
               $("#fechaInicio").val(obj.fechainicio),
               $("#fechaFin").val(obj.fechafin),
               $("#horas").val(obj.horas),
               $("#cursoInstructor").val(obj.cursoinstructor),
               $("#nombreAgenteCapacitador").val(obj.nombreagentecapac),
               $("#RFCagente").val(obj.rfcagente);
         }
   });
});

/*FIN DE CURSOS*/

$('#usuariosModal').on('hidden.bs.modal', function () {
  $('#usrMatricula').val('');
  $('#usrCURP').val('');
  $('#usrNombre').val('');
  $('#usrPaterno').val('');
  $('#usrMaterno').val('');
  $('#usrTipo').val('');
  $("#bttnUsr").prop('disabled', false);
});
</script>