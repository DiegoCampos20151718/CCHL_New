<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'assets/Certificados/';
    $nocontrol = $_POST['nocontrol'];

    if (empty($nocontrol)) {
        echo json_encode(['success' => false, 'error' => 'Número de control no especificado.']);
        exit;
    }

    $courseDir = $uploadDir . $nocontrol . '/';
    if (!is_dir($courseDir)) {
        mkdir($courseDir, 0777, true);
    }

    $errors = [];
    foreach ($_FILES['certificates']['tmp_name'] as $key => $tmpName) {
        $originalName = $_FILES['certificates']['name'][$key];
        $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);

        // Validar que sea un archivo PDF
        if (strtolower($fileExtension) !== 'pdf') {
            $errors[] = "El archivo $originalName no es un PDF válido.";
            continue;
        }

        $targetPath = $courseDir . basename($originalName);

        if (!move_uploaded_file($tmpName, $targetPath)) {
            $errors[] = "Error al mover el archivo $originalName.";
        }
    }

    if (empty($errors)) {
        echo json_encode(['success' => true, 'message' => 'Todos los archivos se subieron correctamente.']);
    } else {
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
}
?>
