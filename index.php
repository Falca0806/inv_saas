<?php require "./include/session_start.php";?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "./include/head.php";?>
    
</head>
<body>
    <?php
// Codigo para no editar los URL
        if (!isset($_GET['vista']) || $_GET['vista'] =="") {
            $_GET['vista'] = "login";
        }

        if (is_file("./vistas/" . $_GET['vista'] . ".php") && $_GET['vista'] != "login" && $_GET['vista'] != "404") {

            //Cerrar sesion forzada
            if ((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")) {
                include("./vistas/logout.php");
                exit();
            }
            
            include "./include/navbar.php";
            
            include "./vistas/" . $_GET['vista'] .".php";

            include "./include/script.php";

        } else {
            if ($_GET['vista'] == "login") {
                include "./vistas/login.php";
            }else {
                include "./vistas/404.php";
            }
        }

    ?>
</body>
</html>