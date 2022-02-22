<?php
require_once('../includes/variables.php');

if(!isset($_SESSION['teacher_username'])){
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prof. <?php echo strtoupper($_SESSION['teacher_lastname']) .' '. $_SESSION['teacher_firstname']; ?></title>
    <link rel="stylesheet" href="styles/style-index.css"/>
    <link rel="icon" type="image/png" href="../ressources/favicon_192x192.png" sizes="192x192"/>
    <script>
    function onloadBody(){
        var element, name, arr, id;
        id = location.pathname.substring(location.pathname.lastIndexOf("/") + 1).split('.')[0];
        if (id == ""){
            id = "index";
        }
        element = document.getElementById(id);
        name = "side-active";
        arr = element.className.split(" ");
        if (arr.indexOf(name) == -1) {
            element.className += " " + name;
        }
    }
    </script>
</head>
<body onload="onloadBody()">
    <noscript>
        Java script non activé. Veuillez le réactiver.
    </noscript>
    <div class="parent">
        <header class="header-side"><?php
if($_SESSION['teacher_gender'] == 'male'){
    echo 'Bienvenue, M.' . strtoupper($_SESSION['teacher_lastname']) .' '. $_SESSION['teacher_firstname'];
}else{
    echo 'Bienvenue, Mme.' . strtoupper($_SESSION['teacher_lastname']) .' '. $_SESSION['teacher_firstname'];
}
        ?></header>
        <main class="main-side">
            <div class="main-page">