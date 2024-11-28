<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['cchl']['rol']) || ($_SESSION['cchl']['rol'] != 1 && $_SESSION['cchl']['rol'] != 2)) {
    header('location: index.php');
    exit;
}

// Función para obtener carpetas y archivos
function listFoldersAndFiles($dir) {
    $items = [];
    if (is_dir($dir)) {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $items[] = [
                    'type' => 'folder',
                    'name' => $item,
                    'contents' => listFoldersAndFiles($path)
                ];
            } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'pdf') {
                $items[] = [
                    'type' => 'file',
                    'name' => $item
                ];
            }
        }
    }
    return $items;
}

// Obtener estructura del directorio
$certificadosPath = 'assets/Certificados';
$foldersAndFiles = listFoldersAndFiles($certificadosPath);

// Renderizar lista de carpetas y archivos
function renderFolderList($items) {
    $html = '';
    foreach ($items as $item) {
        if ($item['type'] === 'folder') {
            $html .= '<li class="list-group-item">';
            $html .= '<strong>' . htmlspecialchars($item['name']) . '</strong>';
            $html .= '<form method="POST" class="d-inline" action="delete.php">
                        <input type="hidden" name="path" value="assets/Certificados/' . htmlspecialchars($item['name']) . '">
                        <button type="submit" class="btn btn-danger btn-sm ms-2">Eliminar</button>
                      </form>';
            $html .= '<ul class="list-group ms-4">';
            $html .= renderFolderList($item['contents']);
            $html .= '</ul>';
            $html .= '</li>';
        } elseif ($item['type'] === 'file') {
            $html .= '<li class="list-group-item">';
            $html .= '<a href="assets/Certificados/' . htmlspecialchars($item['name']) . '" target="_blank">' . htmlspecialchars($item['name']) . '</a>';
            $html .= '</li>';
        }
    }
    return $html;
}

// Manejo de la subida de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folderName']) && isset($_FILES['pdfFiles'])) {
    $folderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folderName']); // Sanitizar nombre de carpeta
    $targetDir = "assets/Certificados/$folderName/";

    // Crear directorio si no existe
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Guardar archivos subidos
    foreach ($_FILES['pdfFiles']['tmp_name'] as $index => $tmpName) {
        $originalName = $_POST['originalNames'][$index]; // Nombre original enviado desde el formulario
        $newName = $_POST['newNames'][$index]; // Nombre nuevo proporcionado por el usuario
        $sanitizedNewName = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($newName, PATHINFO_FILENAME)) . '.pdf'; // Sanitizar nombre nuevo

        $targetFile = $targetDir . basename($sanitizedNewName);

        if (move_uploaded_file($tmpName, $targetFile)) {
            echo "<p>Archivo '$originalName' guardado como '$sanitizedNewName' en '$targetDir'</p>";
        } else {
            echo "<p>Error al guardar el archivo '$originalName'.</p>";
        }
    }
}
?>
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

    <title>Carga y visualización de certificados</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        h1, h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>
     <!-- ======= Header ======= -->
     <?php include_once 'page-format/header.php'; ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include_once 'page-format/sidebar.php'; ?>
    <!-- End Sidebar -->
     <!-- Modal para "Cursos y certificados" -->
    <div class="modal fade" id="certificadosModal" tabindex="-1" aria-labelledby="certificadosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certificadosModalLabel">Cursos y certificados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="folderList" class="list-group">
                        <?= renderFolderList($foldersAndFiles); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para la barra de progreso -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">Subiendo Archivos...</h5>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main id="main" class="main container mt-9">
        <h1 class="text-center mb-4">Carga de certificados</h1>
        <div class="p-4 bg-light rounded mb-4">
            <div class="mb-3">
                <label for="folderName" class="form-label">Nombre del curso</label>
                <input type="text" id="folderName" class="form-control" placeholder="Ingresa el nombre" required>
            </div>
            <button id="selectFolder" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-folder"></i> Selecciona la carpeta con los PDF's
            </button>
            <button id="uploadFiles" class="btn btn-success w-100 hidden">Guardar los PDFs</button>
        </div>
        <ul id="fileList" class="list-group mt-4"></ul>

        <!-- Sección de visualización de carpetas y PDFs -->
        <h2 class="text-center mb-4">Cursos y certificados</h2>
        <ul id="folderList" class="list-group">
            <?= renderFolderList($foldersAndFiles); ?>
        </ul>
    </main>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        const showCertificadosButton = document.getElementById('showCertificados');
        const certificadosModal = new bootstrap.Modal(document.getElementById('certificadosModal'));

        // Mostrar el modal al hacer clic en el botón
        showCertificadosButton.addEventListener('click', () => {
            certificadosModal.show();
        });
    </script>
    <script>
        const selectFolderButton = document.getElementById('selectFolder');
        const uploadFilesButton = document.getElementById('uploadFiles');
        const folderNameInput = document.getElementById('folderName');
        const fileList = document.getElementById('fileList');
        const progressModal = new bootstrap.Modal(document.getElementById('progressModal'));
        const progressBar = document.getElementById('progressBar');

        let selectedFiles = [];

        selectFolderButton.addEventListener('click', async () => {
            try {
                const folderHandle = await window.showDirectoryPicker();
                selectedFiles = [];
                fileList.innerHTML = '';

                for await (const [name, handle] of folderHandle.entries()) {
                    if (handle.kind === 'file' && name.endsWith('.pdf')) {
                        const file = await handle.getFile();
                        selectedFiles.push(file);

                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item d-flex align-items-center';

                        const input = document.createElement('input');
                        input.type = 'text';
                        input.className = 'form-control me-2';
                        input.value = name.replace('.pdf', ''); // Nombre sin extensión
                        input.dataset.originalName = name;

                        const label = document.createElement('span');
                        label.textContent = '.pdf';

                        listItem.appendChild(input);
                        listItem.appendChild(label);
                        fileList.appendChild(listItem);
                    }
                }

                if (selectedFiles.length > 0) {
                    uploadFilesButton.classList.remove('hidden');
                } else {
                    alert('No se encontraron archivos PDF en la carpeta seleccionada.');
                }
            } catch (error) {
                console.error('Error al seleccionar la carpeta:', error);
            }
        });

        uploadFilesButton.addEventListener('click', async () => {
            const folderName = folderNameInput.value.trim();
            if (!folderName) {
                alert('Por favor, ingresa un nombre para la carpeta.');
                return;
            }

            const formData = new FormData();
            formData.append('folderName', folderName);

            const inputs = fileList.querySelectorAll('input');
            selectedFiles.forEach((file, index) => {
                formData.append('pdfFiles[]', file);
                formData.append('originalNames[]', inputs[index].dataset.originalName); // Nombre original
                formData.append('newNames[]', inputs[index].value.trim() + '.pdf'); // Nuevo nombre
            });

            progressModal.show();
            progressBar.style.width = '0%';
            progressBar.textContent = '0%';

            try {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= $_SERVER['PHP_SELF']; ?>', true);

                xhr.upload.onprogress = (event) => {
                    if (event.lengthComputable) {
                        const percentComplete = (event.loaded / event.total) * 100;
                        progressBar.style.width = `${percentComplete.toFixed(2)}%`;
                        progressBar.textContent = `${percentComplete.toFixed(2)}%`;
                    }
                };

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        alert('¡Archivos subidos correctamente!');
                        location.reload();
                    } else {
                        alert('Error al subir los archivos.');
                    }
                };

                xhr.send(formData);
            } catch (error) {
                console.error('Error durante la subida de archivos:', error);
                alert('Hubo un problema al subir los archivos.');
            } finally {
                progressModal.hide();
            }
        });
    </script>
</body>
</html>
