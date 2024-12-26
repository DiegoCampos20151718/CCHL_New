<?php
if (isset($_POST['carpeta'], $_POST['archivo'], $_POST['nuevoNombre'])) {
    $carpeta = $_POST['carpeta'];
    $archivo = $_POST['archivo'];
    $nuevoNombre = $_POST['nuevoNombre'];

    // Ruta del archivo
    $rutaCarpeta = 'assets/Certificados/' . $carpeta . '/';
    $rutaArchivoActual = $rutaCarpeta . $archivo;
    $rutaArchivoNuevo = $rutaCarpeta . $nuevoNombre;

    // Verificar si el archivo existe
    if (file_exists($rutaArchivoActual)) {
        // Renombrar el archivo
        if (rename($rutaArchivoActual, $rutaArchivoNuevo)) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
}
?>
