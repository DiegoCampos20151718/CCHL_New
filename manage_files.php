<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Ruta base de las carpetas
        $baseDir = 'assets/Certificados/';

        if ($action === 'listFiles') {
            $folders = [];
            // Listar carpetas y archivos
            foreach (scandir($baseDir) as $folder) {
                if ($folder === '.' || $folder === '..') continue;

                $folderPath = $baseDir . $folder;
                if (is_dir($folderPath)) {
                    $files = [];
                    foreach (scandir($folderPath) as $file) {
                        if ($file === '.' || $file === '..') continue;

                        $filePath = $folderPath . '/' . $file;
                        if (is_file($filePath)) {
                            $files[] = $file;
                        }
                    }
                    $folders[] = [
                        'folder' => $folder,
                        'files' => $files,
                    ];
                }
            }
            echo json_encode(['status' => 'success', 'folders' => $folders]);
        } elseif ($action === 'deleteFile' && isset($_POST['folder'], $_POST['file'])) {
            $folder = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folder']);
            $file = basename($_POST['file']);
            $filePath = $baseDir . $folder . '/' . $file;

            if (is_file($filePath)) {
                unlink($filePath);
                echo json_encode(['status' => 'success', 'message' => 'Archivo eliminado.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Archivo no encontrado.']);
            }
        }
    }
}
?>
