<?php
// Inicio de sesión
session_start();
if (!isset($_SESSION['cchl']['rol']) || !in_array($_SESSION['cchl']['rol'], [1, 2])) {
    header('location: index.php');
    exit;
}

// Conexión a la base de datos
$pdo = new PDO('mysql:host=localhost;dbname=cchl', 'root', '');

// Función para buscar información del folio
function getFolioInfo($pdo, $folio) {
    $stmt = $pdo->prepare("SELECT * FROM siap WHERE folio = :folio");
    $stmt->bindParam(':folio', $folio);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Verificar si el folio fue enviado por AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'validateFolio') {
    $folio = trim($_POST['folio']);
    $folioInfo = getFolioInfo($pdo, $folio);

    if ($folioInfo) {
        echo json_encode(['exists' => true, 'data' => $folioInfo]);
    } else {
        echo json_encode(['exists' => false]);
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
        if (isset($_POST['originalNames']) && isset($_POST['newNames']) &&
            is_array($_POST['originalNames']) && is_array($_POST['newNames'])) {
            
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Save PDF files to a user-defined folder">
    <meta name="keywords" content="PDF upload, folder creation, modern design">

    <!-- Favicons -->
    <link href="assets/img/imss-green-icon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <title>Gestión de Certificados</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        .hidden { display: none; }

        #loadingSpinner {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}


    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>

</head>
<body>
    <!-- Header -->
    <?php include_once 'page-format/header.php'; ?>
    <!-- Sidebar -->
    <?php include_once 'page-format/sidebar.php'; ?>

    <main id="main" class="main container mt-4">
        <h1 class="text-center mb-4">Gestión de Certificados</h1>

        <!-- Sección de búsqueda de folio -->
        <div class="bg-light p-4 rounded mb-4">
            <label for="folioInput" class="form-label">Ingrese el folio</label>
            <input type="text" id="folioInput" class="form-control mb-3" placeholder="Folio" required>
            <button id="searchFolio" class="btn btn-primary w-100">Buscar Folio</button>
            <input type="text" id="folderNameInput" class="form-control hidden" placeholder="Nombre de la carpeta" />

        </div>

        <!-- Sección de detalles del folio -->
        <div id="folioDetails" class="hidden">
            <h2>Detalles del Folio</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Folio:</strong> <span id="folio"></span></li>
                <li class="list-group-item"><strong>Estatus:</strong> <span id="estatus"></span></li>
                <li class="list-group-item"><strong>Descripción:</strong> <span id="descrip"></span></li>
                <li class="list-group-item"><strong>Tipo:</strong> <span id="tipo"></span></li>
                <li class="list-group-item"><strong>Fecha Inicio:</strong> <span id="iniProgra"></span></li>
                <li class="list-group-item"><strong>Fecha Fin:</strong> <span id="finProgra"></span></li>
            </ul>
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

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        
        
        
        const folioInput = document.getElementById('folioInput');
        const searchFolioButton = document.getElementById('searchFolio');
        const folioDetails = document.getElementById('folioDetails');
        const uploadSection = document.getElementById('uploadSection');
        const uploadFilesButton = document.getElementById('uploadFiles');
        const selectFolderButton = document.getElementById('selectFolder');
        const fileList = document.getElementById('fileList');

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
                    folioDetails.querySelector('#folio').textContent = data.data.folio;
                    folioDetails.querySelector('#estatus').textContent = data.data.estatus || 'N/A';
                    folioDetails.querySelector('#descrip').textContent = data.data.descrip || 'N/A';
                    folioDetails.querySelector('#tipo').textContent = data.data.tipo || 'N/A';
                    folioDetails.querySelector('#iniProgra').textContent = data.data.iniProgra || 'N/A';
                    folioDetails.querySelector('#finProgra').textContent = data.data.finProgra || 'N/A';

                    folioDetails.classList.remove('hidden');
                    uploadSection.classList.remove('hidden');
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
            folioDetails.querySelector('#folio').textContent = data.data.folio;
            folioDetails.querySelector('#estatus').textContent = data.data.estatus || 'N/A';
            folioDetails.querySelector('#descrip').textContent = data.data.descrip || 'N/A';
            folioDetails.querySelector('#tipo').textContent = data.data.tipo || 'N/A';
            folioDetails.querySelector('#iniProgra').textContent = data.data.iniProgra || 'N/A';
            folioDetails.querySelector('#finProgra').textContent = data.data.finProgra || 'N/A';

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
</body>
</html>
