<?php
// Obtener los datos de la solicitud POST
if (isset($_POST['carpeta']) && isset($_POST['archivo'])) {
    $carpeta = $_POST['carpeta'];
    $archivo = $_POST['archivo'];

    // Ruta del archivo en el servidor
    $rutaArchivo = 'assets/Certificados/' . $carpeta . '/' . $archivo;

    // Verificar si el archivo existe
    if (file_exists($rutaArchivo)) {
        // Eliminar el archivo
        if (unlink($rutaArchivo)) {
            echo 'success';  // Responder con éxito
        } else {
            echo 'error';    // Responder con error si no se pudo eliminar
        }
    } else {
        echo 'error'; // El archivo no existe
    }
} else {
    echo 'error'; // Datos faltantes en la solicitud
}
?>
