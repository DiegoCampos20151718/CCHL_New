<?php
// Inicio de sesión
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
$pdo = new PDO('mysql:host=localhost;dbname=cchl', 'root', '');

// Función para buscar información del folio
function getFolioInfo($pdo, $folio)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM cchl_validacion WHERE nocontrol = :folio");
        $stmt->bindParam(':folio', $folio, PDO::PARAM_STR); // Especificar el tipo de parámetro
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Log de errores (no enviar mensajes detallados al cliente)
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'validateFolio') {
    $folio = isset($_POST['folio']) ? trim($_POST['folio']) : '';

    if (empty($folio)) {
        echo json_encode(['exists' => false, 'error' => 'Folio no proporcionado']);
        exit;
    }

    $folioInfo = getFolioInfo($pdo, $folio);

    if ($folioInfo) {
        echo json_encode(['exists' => true, 'data' => $folioInfo]);
    } else {
        echo json_encode(['exists' => false, 'error' => 'Folio no encontrado']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folderName']) && isset($_FILES['pdfFiles'])) {
    $folderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folderName']); // Sanitizar nombre de carpeta
    $targetDir = "assets/Certificados/$folderName/";

    // Crear directorio si no existe
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folderName']) && isset($_FILES['pdfFiles'])) {
        // Se toma el folio como nombre de la carpeta
        $folderName = trim($_POST['folderName']);

        // Sanitizamos el nombre de la carpeta
        $folderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $folderName);
        $targetDir = "assets/Certificados/$folderName/";

        // Crear directorio si no existe
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Validamos si los nombres de los archivos están presentes
        if (
            isset($_POST['originalNames']) && isset($_POST['newNames']) &&
            is_array($_POST['originalNames']) && is_array($_POST['newNames'])
        ) {

            // Guardar archivos subidos
            foreach ($_FILES['pdfFiles']['tmp_name'] as $index => $tmpName) {
                $originalName = $_POST['originalNames'][$index] ?? 'desconocido'; // Valor por defecto
                $newName = $_POST['newNames'][$index] ?? 'archivo_' . time(); // Valor por defecto
                $sanitizedNewName = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($newName, PATHINFO_FILENAME)) . '.pdf'; // Sanitizar nombre nuevo

                $targetFile = $targetDir . basename($sanitizedNewName);

                if (move_uploaded_file($tmpName, $targetFile)) {
                    echo "<p>Archivo '$originalName' guardado como '$sanitizedNewName' en '$targetDir'</p>";
                } else {
                    echo "<p>Error al guardar el archivo '$originalName'.</p>";
                }
            }
        } else {
            echo "<p>Error: Los nombres de archivo no fueron enviados correctamente.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Save PDF files to a user-defined folder">
    <meta name="keywords" content="PDF upload, folder creation, modern design">

    <!-- Favicons -->
    <link href="assets/img/imss-green-icon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="assets/vendor/dropzone/dropzone.min.css" rel="stylesheet" />

    <title>Gestión de Certificados</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }

        .hidden {
            display: none;
        }

        #loadingSpinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Button Styles */
        #manageFilesButton {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #manageFilesButton:hover {
            background-color: #0056b3;
        }

        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        #manageFilesButton {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 16px 24px;
            /* Aumentar el padding */
            border-radius: 6px;
            font-size: 18px;
            /* Aumentar el tamaño de la fuente */
            cursor: pointer;
            transition: all 0.3s ease;
        }


        #manageFilesButton:hover {
            background-color: #0056b3;
        }

        /* Modal Overlay */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease;
        }

        /* Modal Content */
        .modal-content {
            background-color: #f9f9f9;
            margin: 10% auto;
            padding: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80%;
            overflow-y: auto;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.4s ease;
        }

        /* Close Button */
        .close {
            color: #333;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #d9534f;
        }

        .file-manager-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
            /* Aumentar el padding */
            max-height: 80vh;
            /* Aumentar la altura máxima */
            overflow-y: auto;
        }


        /* Folder Styles */
        .folder {
            background: #007bff;
            color: black;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .folder:hover {
            background: #0056b3;
        }

        .folder-content {
            margin-left: 20px;
            padding: 10px;
            border-left: 3px solid #007bff;
            display: none;
            /* Hidden by default */
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            /* Aumentar el padding */
            margin: 8px 0;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            /* Aumentar el tamaño del texto */
        }



        /* Animations */
        @keyframes fadeIn {
            from {
                background-color: rgba(0, 0, 0, 0);
            }

            to {
                background-color: rgba(0, 0, 0, 0.6);
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .loader {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 20px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        /* Folder Styles */
        details.folder {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        summary {
            cursor: pointer;
            font-weight: bold;
            padding: 5px;
        }

        /* File Item Styles */
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 20px;
            margin: 5px 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</head>

<body>
    <mai>
        <!-- ======= Header ======= -->
        <?php include_once 'page-format/header.php'; ?>
        <!-- End Header -->
         <!-- ======= Sidebar ======= -->
    <?php include_once 'page-format/sidebar.php'; ?>
    <!-- End Sidebar-->
    </mai>
    <main id="main" class="main container mt-4">
        <h1 class="text-center mb-4 mt-5">Gestión de Certificados</h1>


        <!-- Sección de búsqueda de folio -->
        <div class="bg-light p-4 rounded mb-4 d-flex flex-column align-items-center">
            <label for="folioInput" class="form-label">Ingrese el folio</label>
            <input type="text" id="folioInput" class="form-control mb-2 w-50" placeholder="Folio" required>
            <button id="searchFolio" class="btn btn-primary w-50">Buscar Folio</button>
            <button id="manageFilesButton" class="btn btn-primary w-50 mt-4">Gestionar Archivos</button>
            <input type="text" id="folderNameInput" class="form-control hidden" placeholder="Nombre de la carpeta" />
        </div>

        <!-- Modal -->
        <div id="fileManagerModal" class="modal hidden">
            <div class="modal-content">
                <span id="closeModal" class="close">&times;</span>
                <h2>Gestión de Archivos</h2>
                <div id="fileManagerContent" class="file-manager-content">
                    <!-- Contenido dinámico generado con JS -->
                </div>
            </div>
        </div>
        <!-- Sección de detalles del folio -->
        <div id="folioDetails" class="hidden">
            <h2>Detalles del Folio</h2>
            <ul class="list-group">
                <li class="list-group-item w-50"><strong>No. Control:</strong> <span id="nocontrol"></span></li>
                <li class="list-group-item w-50"><strong>Estatus:</strong> <span id="status"></span></li>
                <li class="list-group-item w-50"><strong>Plan Formativo:</strong> <span id="planFormativo"></span></li>
                <li class="list-group-item w-50"><strong>Nombre Plan Formativo:</strong> <span
                        id="nombrePlanFormativo"></span></li>
                <li class="list-group-item w-50"><strong>Área Solicitante:</strong> <span id="areaSolicitante"></span>
                </li>
                <li class="list-group-item w-50"><strong>Fecha Inicio:</strong> <span id="fInicio"></span></li>
                <li class="list-group-item w-50"><strong>Fecha Fin:</strong> <span id="fTermino"></span></li>
            </ul>
        </div>
        </div>
        <!-- Botones para subir archivos -->
        <div id="uploadSection" class="hidden mt-4">
            <h3>Subir Certificados PDF</h3>
            <button id="selectFolder" class="btn btn-primary w-100 mb-3">Seleccionar Carpeta con PDFs</button>
            <button id="uploadFiles" class="btn btn-success w-100 hidden">Guardar los PDFs</button>
        </div>
        <div id="loadingSpinner" style="display: none; text-align: center;"></div>


        <ul id="fileList" class="list-group mt-4"></ul>
    </main>
</body>

</html>
<script>

    const manageFilesButton = document.getElementById('manageFilesButton');
    const fileManagerModal = document.getElementById('fileManagerModal');
    const closeModal = document.getElementById('closeModal');
    const fileManagerContent = document.getElementById('fileManagerContent');

    // Función para mostrar un indicador de carga
    const showLoader = (message = "Cargando...") => {
        fileManagerContent.innerHTML = `<div class="loader">${message}</div>`;
    };

    // Función para cargar las carpetas y archivos
    const loadFiles = async () => {
        showLoader();
        try {
            const response = await fetch('manage_files.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'listFiles' }),
            });

            const data = await response.json();
            if (data.status === 'success') {
                renderFolders(data.folders);
            } else {
                fileManagerContent.innerHTML = `<p class="error-message">No se pudieron cargar los archivos. Intenta de nuevo.</p>`;
            }
        } catch (error) {
            console.error('Error al cargar los archivos:', error);
            fileManagerContent.innerHTML = `<p class="error-message">Ocurrió un error al cargar los datos.</p>`;
        }
    };

    // Función para renderizar carpetas y archivos
    const renderFolders = (folders) => {
        fileManagerContent.innerHTML = ''; // Limpiar contenido anterior

        folders.forEach((folder) => {
            const folderElement = document.createElement('details');
            folderElement.className = 'folder';

            const folderSummary = document.createElement('summary');
            folderSummary.textContent = folder.folder;
            folderElement.appendChild(folderSummary);

            folder.files.forEach((file) => {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'file-item';

                const fileLabel = document.createElement('span');
                fileLabel.textContent = file;

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Eliminar';
                deleteButton.className = 'btn btn-danger btn-sm ms-2';
                deleteButton.addEventListener('click', () => handleFileDeletion(folder.folder, file));

                fileDiv.appendChild(fileLabel);
                fileDiv.appendChild(deleteButton);
                folderElement.appendChild(fileDiv);
            });

            fileManagerContent.appendChild(folderElement);
        });
    };

    // Función para manejar la eliminación de archivos
    const handleFileDeletion = async (folder, file) => {
        if (confirm(`¿Seguro que deseas eliminar el archivo "${file}"?`)) {
            try {
                const response = await fetch('manage_files.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'deleteFile',
                        folder,
                        file,
                    }),
                });

                const data = await response.json();
                if (data.status === 'success') {
                    alert('Archivo eliminado con éxito.');
                    loadFiles(); // Recargar la lista de archivos
                } else {
                    alert(`Error al eliminar el archivo: ${data.message}`);
                }
            } catch (error) {
                console.error('Error al eliminar el archivo:', error);
                alert('Ocurrió un error al intentar eliminar el archivo.');
            }
        }
    };

    // Eventos
    manageFilesButton.addEventListener('click', () => {
        fileManagerModal.style.display = 'block';
        loadFiles(); // Cargar archivos al abrir el modal
    });

    closeModal.addEventListener('click', () => {
        fileManagerModal.style.display = 'none';
    });


    const folioInput = document.getElementById('folioInput');
    const searchFolioButton = document.getElementById('searchFolio');
    const folioDetails = document.getElementById('folioDetails');
    const uploadSection = document.getElementById('uploadSection');
    const selectFolderButton = document.getElementById('selectFolder');
    const uploadFilesButton = document.getElementById('uploadFiles');
    // Función para mostrar detalles del folio
    function showFolioDetails(data) {
        document.querySelector('#nocontrol').textContent = data.nocontrol || 'N/A';
        document.querySelector('#status').textContent = data.status || 'N/A';
        document.querySelector('#planFormativo').textContent = data.planFormativo || 'N/A';
        document.querySelector('#nombrePlanFormativo').textContent = data.nombrePlanFormativo || 'N/A';
        document.querySelector('#areaSolicitante').textContent = data.areaSolicitante || 'N/A';
        document.querySelector('#fInicio').textContent = data.fInicio || 'N/A';
        document.querySelector('#fTermino').textContent = data.fTermino || 'N/A';

        folioDetails.classList.remove('hidden');
        uploadSection.classList.remove('hidden');
    }

    // Función para ocultar detalles del folio
    function hideFolioDetails() {
        folioDetails.classList.add('hidden');
        uploadSection.classList.add('hidden');
    }

    // Evento al buscar folio
    searchFolioButton.addEventListener('click', async () => {
        const folio = folioInput.value.trim();

        if (!folio) {
            alert('Por favor, ingresa un folio.');
            return;
        }

        try {
            const response = await fetch('<?= $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'validateFolio', folio })
            });

            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const data = await response.json();

            if (data.exists) {
                showFolioDetails(data.data);
            } else {
                alert('Folio no encontrado.');
                hideFolioDetails();
            }
        } catch (error) {
            console.error('Error buscando el folio:', error);
            alert('Hubo un error al buscar el folio. Inténtalo de nuevo más tarde.');
            hideFolioDetails();
        }
    });



    // Configurar el botón de selección de carpeta
    let selectedFiles = [];  // Definimos la variable selectedFiles

    // Configurar el botón de selección de carpeta
    selectFolderButton.addEventListener('click', async () => {
        try {
            const folderHandle = await window.showDirectoryPicker();
            const files = [];
            fileList.innerHTML = '';  // Limpiamos la lista de archivos mostrados

            // Limpiamos los archivos seleccionados anteriores
            selectedFiles = [];

            for await (const [name, handle] of folderHandle.entries()) {
                console.log(handle.kind, name);  // Depurar tipo de archivo y nombre

                // Comprobamos si es un archivo PDF, ignorando mayúsculas/minúsculas
                if (handle.kind === 'file' && name.toLowerCase().endsWith('.pdf')) {
                    const file = await handle.getFile();
                    selectedFiles.push(file);  // Agregamos el archivo a la lista de archivos seleccionados

                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex align-items-center';

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control me-2';
                    input.value = name.replace('.pdf', '');  // Nombre sin la extensión

                    const label = document.createElement('span');
                    label.textContent = '.pdf';

                    listItem.appendChild(input);
                    listItem.appendChild(label);
                    fileList.appendChild(listItem);
                }
            }

            // Verificar si hemos encontrado archivos PDF
            if (selectedFiles.length > 0) {
                uploadFilesButton.classList.remove('hidden');
            } else {
                alert('No se encontraron archivos PDF en la carpeta seleccionada.');
            }
        } catch (error) {
            console.error('Error seleccionando carpeta:', error);
        }
    });

    searchFolioButton.addEventListener('click', async () => {
        const folio = folioInput.value.trim();
        if (!folio) {
            alert('Por favor, ingresa un folio.');
            return;
        }

        try {
            const response = await fetch('<?= $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'validateFolio', folio })
            });

            const data = await response.json();
            if (data.exists) {
                // Mostrar detalles del folio
                document.querySelector('#nocontrol').textContent = data.nocontrol || 'N/A';
                document.querySelector('#status').textContent = data.status || 'N/A';
                document.querySelector('#planFormativo').textContent = data.planFormativo || 'N/A';
                document.querySelector('#nombrePlanFormativo').textContent = data.nombrePlanFormativo || 'N/A';
                document.querySelector('#areaSolicitante').textContent = data.areaSolicitante || 'N/A';
                document.querySelector('#fInicio').textContent = data.fInicio || 'N/A';
                document.querySelector('#fTermino').textContent = data.fTermino || 'N/A';

                folioDetails.classList.remove('hidden');
                uploadSection.classList.remove('hidden');

                // Establecer el nombre de la carpeta al valor del folio
                document.getElementById('folderNameInput').value = data.data.folio;
            } else {
                alert('Folio no encontrado.');
                folioDetails.classList.add('hidden');
                uploadSection.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error buscando el folio:', error);
            alert('Hubo un error buscando el folio.');
        }
    });

    // Subir los archivos seleccionados
    // Subir los archivos seleccionados
    uploadFilesButton.addEventListener('click', async () => {
        const folderName = folioInput.value.trim(); // Usamos el folio como nombre de la carpeta
        if (!folderName) {
            alert('Por favor, ingresa un nombre para la carpeta.');
            return;
        }

        const formData = new FormData();
        formData.append('folderName', folderName);

        // Agregamos los archivos seleccionados a la solicitud
        selectedFiles.forEach((file, index) => {
            const inputs = fileList.querySelectorAll('input');
            formData.append('pdfFiles[]', file);
            formData.append('originalNames[]', inputs[index].dataset.originalName); // Nombre original
            formData.append('newNames[]', inputs[index].value.trim() + '.pdf'); // Nuevo nombre
        });

        try {
            const response = await fetch('<?= $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: formData,
            });

            if (response.ok) {
                alert('¡Archivos subidos correctamente!');
                location.reload();
            } else {
                alert('Error al subir los archivos.');
            }
        } catch (error) {
            console.error('Error durante la subida de archivos:', error);
            alert('Hubo un problema al subir los archivos.');
        }
    });
    // Configurar el spinner de Spin.js
    var spinner = new Spinner({
        lines: 13, // número de líneas
        length: 28, // longitud de las líneas
        width: 14, // grosor de las líneas
        radius: 42, // radio
        color: '#000', // color
        speed: 1, // velocidad
        trail: 60, // desvanecimiento de las líneas
        shadow: true, // sombra
        hwaccel: true, // aceleración de hardware
    }).spin(document.getElementById('loadingSpinner'));

    // Función para mostrar el spinner
    function showSpinner() {
        document.getElementById('loadingSpinner').style.display = 'block'; // Mostrar el spinner
    }

    // Función para ocultar el spinner
    function hideSpinner() {
        document.getElementById('loadingSpinner').style.display = 'none'; // Ocultar el spinner
    }

    // Subir los archivos seleccionados
    uploadFilesButton.addEventListener('click', async () => {
        const folderName = folioInput.value.trim(); // Usamos el folio como nombre de la carpeta
        if (!folderName) {
            alert('Por favor, ingresa un nombre para la carpeta.');
            return;
        }

        const formData = new FormData();
        formData.append('folderName', folderName);

        // Agregamos los archivos seleccionados a la solicitud
        selectedFiles.forEach((file, index) => {
            const inputs = fileList.querySelectorAll('input');
            formData.append('pdfFiles[]', file);
            formData.append('originalNames[]', inputs[index].dataset.originalName); // Nombre original
            formData.append('newNames[]', inputs[index].value.trim() + '.pdf'); // Nuevo nombre
        });

        // Mostrar el spinner antes de comenzar la subida
        showSpinner();

        try {
            const response = await fetch('<?= $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: formData,
            });

            if (response.ok) {
                alert('¡Archivos subidos correctamente!');
                location.reload();
            } else {
                alert('Error al subir los archivos.');
            }
        } catch (error) {
            console.error('Error durante la subida de archivos:', error);
            alert('Hubo un problema al subir los archivos.');
        } finally {
            // Ocultar el spinner una vez terminada la subida
            hideSpinner();
        }
    });


</script>