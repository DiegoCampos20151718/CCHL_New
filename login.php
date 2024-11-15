<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_include_path(dirname(__FILE__));
include_once 'fetch/database.php';
session_start();
$message = "";

if(isset($_SESSION['cchl']['rol'])){
  switch($_SESSION['cchl']['rol']){
    case 1:
      header('location: imprimir-cchl.php');
    break;

    case 2:
      header('location: imprimir-cchl.php');
    break;
    default:
  }
}

if(isset($_POST['username']) && isset($_POST['password'])){
  $username = $_POST['username'];
  $password = $_POST['password'];

  $db = new Database();
  $query = $db->connect()->prepare('SELECT matricula, contrasena, rol, IFNULL(id, 0), instructores.activo es_instructor FROM bd 
   LEFT JOIN instructores ON bd.matricula = instructores.id
   WHERE matricula = :username');
  $query->execute(['username' => $username]);

  $row = $query->fetch(PDO::FETCH_NUM);
  if($row == true){
      if(password_verify($password,$row[1])) {
        $_SESSION['cchl']['username'] = $row[0];
        $_SESSION['cchl']['rol'] = $row[2];

         if($row[3] == 0){
            $_SESSION['cchl']['instructor'] = 0;
         }else{
            $_SESSION['cchl']['instructor'] = 1;
         }

        $rol = $row[2];
        switch($rol){
            case 1:
                header('location: imprimir-cchl.php');
            break;

            case 2:
                header('location: imprimir-cchl.php');
            break;
            default:
        }
    }else{
         /*if($row[1]==$password){
            echo '<script type="text/javascript">
            window.addEventListener("load", function() {
               $("#modalCambiarContra").modal("show");
               $("#usuario").val("'.$_POST['username'].'");
            })
            </script>';
         }elseif(!password_verify($password,$row[1])){*/
            $message.= '<div class="alert alert-danger text-center" role="alert">CONTRASEÑA INCORRECTA<br></div>';
         /*}*/
    }

    }
}
    
?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <title>CCHL | Iniciar Sesión</title>
      <meta content="" name="description">
      <meta content="" name="keywords">
      <!-- Favicons -->
      <link href="assets/img/imss-green-icon.png" rel="icon">
      <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
      <!-- Google Fonts -->
      <link href="https://fonts.gstatic.com" rel="preconnect">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
      <!-- Vendor CSS Files -->
      <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
      <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
      <link href="assets/vendor/boxicons/css/boxicons.css" rel="stylesheet">
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
   <body class="imss-background">
      <main>
         <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
               <div class="container">
                  <div class="row justify-content-center">
                     <div class="col-lg-5 col-md-7 col-sm-10 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4">
                           <a href="index.php" class="logo d-flex align-items-center w-auto">
                              <img src="assets/img/imss-logo-green.png" style="max-height: 80px !important;" alt="">
                              <b class="text-secondary display-5">CCHL</b>
                              <!--d-none d-sm-block -->
                           </a>
                        </div>
                        <!-- End Logo -->
                        <div class="card mb-3 shadow-lg">
                           <div class="card-body">
                              <div class="pt-4 pb-2">
                                 <h5 class="card-title text-center pb-0 fs-4">INICIAR SESIÓN</h5>
                                 <p class="text-center small">Ingresa usando tu usuario y contraseña</p>
                              </div>
                              <form class="row g-3 needs-validation" novalidate method="post" action="">
                                 <div class="col-12">
                                    <label for="yourUsername" class="form-label">Usuario</label>
                                    <div class="input-group has-validation">
                                       <span class="input-group-text" id="inputGroupPrepend">
                                          <i class="bx bxs-user"></i>
                                       </span>
                                       <input type="text" name="username" class="form-control" id="yourUsername" required>
                                       <div class="invalid-feedback">Favor de ingresar su usuario.</div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <label for="yourPassword" class="form-label">Contraseña</label>
                                    <div class="input-group has-validation">
                                       <span class="input-group-text" id="inputGroupPrepend">
                                          <i class="bi bi-eye-fill"></i>
                                       </span>
                                       <input type="password" name="password" class="form-control" id="yourPassword" required>
                                       <div class="invalid-feedback">Favor de ingresar su contraseña</div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <button class="btn btn-success rounded-pill w-100" type="submit">INGRESAR</button>
                                 </div>
                                 <div class="col-12">
                                    <span><?php echo $message; ?></span>
                                 </div>
                                 <div class="credits text-center">
                                    <i class="btn btn-link link-warning" data-bs-toggle="modal" data-bs-target="#infoModal">¿Olvidaste tu contraseña? Da clic para recueprar</i>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Modal-->
            <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">RECUPERACIÓN DE LA CUENTA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body text-justify form-group">
                        <div class="row">
                           <div class="col-12">
                              <p class="text-success"><strong>PASO 1: PROPORCIONA TU USUARIO Y DA CLIC EN ENVIAR CODIGO</strong></p>
                           </div>
                        </div>

                        <form id="enviarCodigo" autocomplete="off" method="post">
                           <div class="row mb-4">
                              <div class="col-8">
                                 <label>Ingresa tu usuario</label>
                                 <input type="text" id="usrSendMail" class="form-control" required>
                              </div>
                              <div class="col-4 align-self-end">
                                 <button type="submit" class="btn btn-sm btn-info">ENVIAR CÓDIGO</button>
                              </div>
                           </div>
                        </form>

                        <div class="row">
                           <div class="col-12">
                              <p id="mensajeCorreo"></p>
                           </div>
                        </div>

                        <p class="text-success"><strong>PASO 2: RECIBIRÁS UN CORREO CON UN CODIGO DE 6 DIGITOS, REGRESA A ESTA PANTALLA Y DA CLIC EN EL SIGUIENTE BOTON, INGRESA TU USUARIO Y EL CODIGO VERIFICADOR</strong></p>

                        <form id="verificarCodigo" autocomplete="off" method="post">
                           <div class="row mb-4">
                              <div class="col-6 align-self-center">
                                 <label>Usuario</label>
                                 <input type="text" id="usrVerify" class="form-control" required>
                              </div>
                              <div class="col-6 align-self-center">
                                 <label>Código verificador</label>
                                 <input type="text" id="usrVerifyCode" class="form-control" required>
                              </div>
                           </div>
                           <div class="row mb-4">
                              <div class="col-12 align-self-center">
                                 <button type="submit" class="btn btn-sm btn-primary text-center w-100">USAR CÓDIGO VERIFICADOR</button>
                              </div>
                           </div>
                           <div class="row mb-4">
                              <div class="col-12 align-self-center" id="msjVerificarCodigo">
                                 
                              </div>
                           </div>
                        </form>

                     </div>
                     <div class="modal-footer">
                     </div>
                  </div>
               </div>
            </div>

            <!-- Modal Cambiar contraseña-->
            <div class="modal fade" id="modalCambiarContra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-success" id="exampleModalLabel">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form class="row g-3 needs-validation" novalidate id="cambiarContrasena" method="POST">
                     <div class="modal-body">
                        <div class="row mb-4">
                           <div class="col-12 text-success">
                              Ingrese los datos que se solicitan
                           </div>
                        </div>
                        <div class="row mb-3">
                           <label for="usuario" class="form-label">Usuario</label>
                           <div class="input-group has-validation">
                              <span class="input-group-text" id="inputGroupPrepend">
                                 <i class="bi bi-eye-fill"></i>
                              </span>
                              <input type="text" name="usuario" class="form-control" id="usuario" readonly required>
                              <div class="invalid-feedback">Favor de ingresar su contraseña actual</div>
                           </div>
                        </div>
                        <div class="row mb-3">
                           <label for="nueva" class="form-label">Ingresa la contraseña nueva</label>
                           <div class="input-group has-validation">
                              <span class="input-group-text" id="inputGroupPrepend">
                                 <i class="bi bi-eye-fill"></i>
                              </span>
                              <input type="password" name="nueva" class="form-control" id="nueva" required>
                              <div class="invalid-feedback">Favor de ingresar su contraseña nueva</div>
                           </div>
                        </div>
                        <div class="row mb-4">
                           <label for="confirmar" class="form-label">Confirma la contraseña nueva</label>
                           <div class="input-group has-validation">
                              <span class="input-group-text" id="inputGroupPrepend">
                                 <i class="bi bi-eye-fill"></i>
                              </span>
                              <input type="password" name="confirmar" class="form-control" id="confirmar" required>
                              <div class="invalid-feedback">Favor de confirmar su contraseña nueva</div>
                           </div>
                        </div>
                        <div class="row mb-1">
                           <div class="col-12 text-center">
                              <div id="passResponse" role="alert">

                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                       <button type="submit" class="btn btn-success">Actualizar</button>
                     </div>
                  </form>
                </div>
              </div>
            </div>

         </div>
      </main>
      <div class="overlayload" id="overlayload" style="display: none;">
         <div class="loader-msg text-white">
            Espere un momento...
         </div>
         <div class="loader">
            
         </div>
      </div>
      <!-- End #main -->
      <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
         <i class="bi bi-arrow-up-short"></i>
      </a>
      <!-- Vendor JS Files -->
      <!--<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>-->
      <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!--<script src="assets/vendor/chart.js/chart.umd.js"></script>
      <script src="assets/vendor/echarts/echarts.min.js"></script>
      <script src="assets/vendor/quill/quill.min.js"></script>
      <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>-->
      <script src="assets/vendor/tinymce/tinymce.min.js"></script>
      <script src="assets/js/jquery-3.7.1.min.js"></script>
      <!-- Template Main JS File -->
      <script src="assets/js/main.js"></script>
   </body>
</html>
<script type="text/javascript">
 
   function modalCambioContrasena() {
      $("#infoModal").modal('show');
   }

   $("#modalCambiarContra").submit(function(e){
      e.preventDefault();
      var cambiarContrasena = {};
      cambiarContrasena['usuario'] = $('#usuario').val();
      cambiarContrasena['nueva'] = $('#nueva').val();
      cambiarContrasena['confirmar'] = $('#confirmar').val();
      $.ajax({
         url: 'fetch/fetch_usuarios.php',
         type: 'post',
         data: {cambiarContrasena:cambiarContrasena},
         dataType: 'json',
         success:function(data){
            if(data.state){
               $("#passResponse").removeClass().addClass("alert alert-success");
               setTimeout(function(){
                  $('#modalCambiarContra').modal('hide');
               }, 2000);
            }else{
               $("#passResponse").removeClass().addClass("alert alert-danger");
            }
            $("#passResponse").text(data.message);
         }
      });
   });


   $(document).on('keyup', '#usrSendMail', function () {
     timeout = setTimeout(function() {
       matricula = $('#usrSendMail').val();
       $.ajax({
             url: 'fetch/mailing/recuperarContrasena.php',
             type: 'post',
             data: {buscarCorreo:matricula},
             success:function(data){
               $('#mensajeCorreo').html(data);
             }
       });
     }, 2000);
   });


   $("#enviarCodigo").submit(function(e){
      e.preventDefault();
      $('#infoModal').modal('hide');
      $('#overlayload').show();
      var enviarCodigo = $('#usrSendMail').val();
      $.ajax({
         url: 'fetch/mailing/recuperarContrasena.php',
         type: 'post',
         data: {enviarCodigo:enviarCodigo},
         dataType: 'json',
         success:function(data){
            alert(data.message);
            $('#overlayload').hide();
         }
      });
   });


   $("#verificarCodigo").submit(function(e){
      e.preventDefault();
      var verifyCode = {};
      verifyCode['usrVerify'] = $('#usrVerify').val();
      verifyCode['usrVerifyCode'] = $('#usrVerifyCode').val();
      $.ajax({
         url: 'fetch/mailing/recuperarContrasena.php',
         type: 'post',
         data: {verifyCode:verifyCode},
         dataType: 'json',
         success:function(data){
            if(data.status){
               $('#infoModal').modal('hide');
               $('#modalCambiarContra').modal('show');
               $('#usuario').val($('#usrVerify').val());
            }else{
               alert(data.message);
            }
         }
      });
   });


$('#infoModal').on('hidden.bs.modal', function () {
   $('#usrSendMail').val('');
   $('#usrVerify').val('');
   $('#usrVerifyCode').val('');
});
</script>

