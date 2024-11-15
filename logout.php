<?php
	session_start();
    unset($_SESSION['cchl']['username']);
    unset($_SESSION['cchl']['rol']);
	header("location: login.php");
?>