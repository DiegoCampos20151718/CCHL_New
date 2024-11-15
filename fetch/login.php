<?php
include_once 'fetch/database.php';
session_start();

  $username = $_POST['username'];
  $password = $_POST['password'];

  $db = new Database();
  $query = $db->connect()->prepare('SELECT usuario, contrasena, rol FROM usuarios
            WHERE usuario = :username');
  $query->execute(['username' => $username]);

  $row = $query->fetch(PDO::FETCH_NUM);
  if($row == true){
        if(password_verify($password,$row[1])) {
        $_SESSION['cchl']['username'] = $row[0];
        $_SESSION['cchl']['rol'] = $row[2];
        $rol = $row[2];
        switch($rol){
            case "A":
                header('location: admin.php');
            break;

            case "U":
                header('location: user.php');
            break;
            default:
        }
    }else{
         $message.= '<div class="alert alert-danger text-center" role="alert">';
         if($row[1]==$password){
            $message.="ES NECESARIO CAMBIAR SU CONTRASEÑA <i class='link-light'></i>";
         }elseif(!password_verify($password,$row[1])){
            $message.= 'CONTRASEÑA INCORRECTA<br>';
         }
         $message.= '</div>';
    }

    }else{
        $message.= '<div class="alert alert-danger text-center" role="alert">NO SE ENCONTRO EL USUARIO INGRESADO</div>';
    }

$mensaje['ok'] = true;
echo json_encode($mensaje);
?>