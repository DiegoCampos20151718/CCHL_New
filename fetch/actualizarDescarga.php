<?php
require_once 'database.php';

if(isset($_POST['matricula']) && isset($_POST['folioCCHL'])) {
    $matricula = $_POST['matricula'];
    $folioCCHL = $_POST['folioCCHL'];

    // Verifica si las variables están siendo recibidas correctamente
    error_log("Matricula: $matricula, Folio: $folioCCHL");

    try {
        $db = new Database();
        $connection = $db->connect();

        $queryF = $connection->prepare('UPDATE cchl_participantes SET DESCARGADO = 1 WHERE MATRICULA = :matricula AND NUMCONTROL = :numcontrol');
        $queryF->execute(['matricula' => $matricula, 'numcontrol' => $folioCCHL]);

        echo json_encode(["status" => "success", "message" => "Descarga actualizada correctamente"]);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Datos no válidos"]);
}

?>
