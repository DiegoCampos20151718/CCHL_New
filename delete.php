<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $path = $_POST['path'];

    if (is_dir($path)) {
        // Eliminar carpeta y su contenido
        function deleteFolder($folder) {
            foreach (scandir($folder) as $item) {
                if ($item === '.' || $item === '..') continue;
                $itemPath = $folder . DIRECTORY_SEPARATOR . $item;
                is_dir($itemPath) ? deleteFolder($itemPath) : unlink($itemPath);
            }
            rmdir($folder);
        }
        deleteFolder($path);
    }

    header('Location: Carga_Cer.php');
    exit;
}
?>
