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
function getFolioInfo($pdo, $folio)
{
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    padding: 16px 24px; /* Aumentar el padding */
    border-radius: 6px;
    font-size: 18px; /* Aumentar el tamaño de la fuente */
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
    padding: 20px; /* Aumentar el padding */
    max-height: 80vh; /* Aumentar la altura máxima */
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
    padding: 12px 20px; /* Aumentar el padding */
    margin: 8px 0;
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-size: 16px; /* Aumentar el tamaño del texto */
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

</head>

<body>
    <!-- Header -->
    <?php include_once 'page-format/header.php'; ?>
    <!-- Sidebar -->
    <?php include_once 'page-format/sidebar.php'; ?>

    <main id="main" class="main container mt-4">
        <h1 class="text-center mb-4">Gestión de Certificados</h1>

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
        <li class="list-group-item w-50"><strong>Folio:</strong> <span id="folio"></span></li>
        <li class="list-group-item w-50"><strong>Estatus:</strong> <span id="estatus"></span></li>
        <li class="list-group-item w-50"><strong>Descripción:</strong> <span id="descrip"></span></li>
        <li class="list-group-item w-50"><strong>Tipo:</strong> <span id="tipo"></span></li>
        <li class="list-group-item w-50"><strong>Fecha Inicio:</strong> <span id="iniProgra"></span></li>
        <li class="list-group-item w-50"><strong>Fecha Fin:</strong> <span id="finProgra"></span></li>
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

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
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