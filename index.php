<?php 
    require_once 'connection.php'; 
    session_start();
    if(isset($_SESSION["logged"])){
        header("Location: dashboard.php");
    }else{
        header("Location: login.php");
    }
    exit();
?>