<?php
include_once 'fetch/database.php';
session_start();
if (!isset($_SESSION)) { 
  session_start(); 
} 
if (!isset($_SESSION['cchl']['rol'])) {
  header('location: index.php');
} else {
  if ($_SESSION['cchl']['rol'] != 1) {
    header('location: index.php');
  }
}

// Conexión a la base de datos
$host = "localhost"; // Cambia según tu configuración
$user = "root";      // Cambia según tu configuración
$password = "";      // Cambia según tu configuración
$dbname = "cchl";

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Inicializar variables
$errorMessage = "";
$data = null;

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nocontrol = $_POST['nocontrol'];

    if (!empty($nocontrol)) {
        // Consulta para obtener solo las columnas relevantes
        $stmt = $conn->prepare("SELECT nocontrol, status, planFormativo, nombrePlanFormativo, areaSolicitante, fInicio, fTermino FROM cchl_validacion WHERE nocontrol = ?");
        $stmt->bind_param("s", $nocontrol);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            $errorMessage = "No se encontraron resultados para el número de control: $nocontrol";
        }

        $stmt->close();
    } else {
        $errorMessage = "Por favor, ingresa un número de control.";
    }
}

// Cerrar conexión
$conn->close();
?>

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
  <link href="assets/img/imss-green-icon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="assets/vendor/dropzone/dropzone.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
      .hidden {
            display: none;
        }
  </style>
</head>

<body id="pagina" data-value="cargarcer">

  <?php include_once 'page-format/header.php'; ?>
  <?php include_once 'page-format/sidebar.php'; ?>

  <main id="main" class="main">
    <div class="container">
      <h1>Carga de certificados</h1>
      <form id="consultForm" method="POST" action="">
          <div class="mb-3">
              <label for="nocontrol" class="form-label">Número de Control:</label>
              <input type="text" id="nocontrol" name="nocontrol" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Consultar</button>
      </form>

      <?php if ($errorMessage): ?>
          <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
      <?php endif; ?>

      <?php if ($errorMessage): ?>
                <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <?php if ($data): ?>
            <div id="folioDetails" class="hidden" data-nocontrol="<?php echo htmlspecialchars($data['nocontrol']); ?>"
                data-status="<?php echo htmlspecialchars($data['status']); ?>" data-plan="<?php echo htmlspecialchars($data['planFormativo']); ?>"
                data-nombreplan="<?php echo htmlspecialchars($data['nombrePlanFormativo']); ?>" data-area="<?php echo htmlspecialchars($data['areaSolicitante']); ?>"
                data-finicio="<?php echo htmlspecialchars($data['fInicio']); ?>" data-ftermino="<?php echo htmlspecialchars($data['fTermino']); ?>">
                <h2 class="mt-5">Información del Curso</h2>
                <table class="table table-bordered">
                    <tr>
                        <th>No. Control</th>
                        <td id="nocontrolDetail"></td>
                    </tr>
                    <tr>
                        <th>Estatus</th>
                        <td id="statusDetail"></td>
                    </tr>
                    <tr>
                        <th>Plan Formativo</th>
                        <td id="planDetail"></td>
                    </tr>
                    <tr>
                        <th>Nombre del Plan Formativo</th>
                        <td id="nombreplanDetail"></td>
                    </tr>
                    <tr>
                        <th>Área Solicitante</th>
                        <td id="areaDetail"></td>
                    </tr>
                    <tr>
                        <th>Fecha de Inicio</th>
                        <td id="finicioDetail"></td>
                    </tr>
                    <tr>
                        <th>Fecha de Fin</th>
                        <td id="fterminoDetail"></td>
                    </tr>
                </table>

                <!-- Botones -->
                <div class="mt-4">
                    <button id="uploadCertificatesBtn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal">Subir Certificados</button>
                    <button id="viewCertificatesBtn" class="btn btn-info">Consultar Carpetas y Certificados Cargados</button>
                </div>

                <!-- Contenedor para mostrar certificados cargados -->
<!-- Aquí se mostrarán las carpetas y archivos -->
<div id="certificadosList"></div>

            </div>
            <!-- Modal para mostrar las carpetas y archivos -->
<div class="modal fade" id="certificadosModal" tabindex="-1" aria-labelledby="certificadosModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="certificadosModalLabel">Carpetas y Certificados Cargados</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="certificadosModalContent">
        <!-- Aquí se cargarán las carpetas y archivos -->
        <p>Cargando...</p>
      </div>
    </div>
  </div>
</div>

            <?php endif; ?>


    </div>
  </main>

  <?php include_once 'page-format/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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
  <script src="assets/js/main.js"></script>

  <!-- Incluir el archivo de script externo -->
  <script src="assets/js/consulta.js"></script>

</body>

</html>
        