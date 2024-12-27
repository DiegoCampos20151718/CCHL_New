<?php
// Ruta donde están las carpetas y los certificados PDF
$directorio = "assets/Certificados/";

$carpetas = [];

if (is_dir($directorio)) {
    $carpetas = array_diff(scandir($directorio), array('..', '.')); // Listar carpetas en el directorio
}

$resultado = [];

foreach ($carpetas as $carpeta) {
    $rutaCarpeta = $directorio . $carpeta;
    if (is_dir($rutaCarpeta)) {
        $archivos = array_diff(scandir($rutaCarpeta), array('..', '.')); // Listar archivos dentro de la carpeta
        
        // Filtrar solo los archivos PDF
        $pdfs = array_filter($archivos, function($archivo) {
            return pathinfo($archivo, PATHINFO_EXTENSION) === 'pdf';
        });

        // Agregar la carpeta y los archivos PDF encontrados
        if (!empty($pdfs)) {
            $resultado[] = [
                'carpeta' => $carpeta,
                'archivos' => $pdfs
            ];
        }
    }
}

// Devolver los resultados en formato JSON
echo json_encode($resultado);
?>
