<?php
require_once "inc/head.php";
if(isset($_GET['s'])) extract($_GET);
if($s == "m"){
    if(isset($_SESSION['operativo'])) $operativo = 1;
    else $operativo = 0;
    $gest->update("users", ["master = 1", "operativo = ".$operativo], "id = ".$_SESSION['user_id']);
    $_SESSION['master'] = 1;
    $_SESSION['simulation'] = 0;
}
if($s == "o"){
    $gest->getUserData($_SESSION['user_id']);
    if($gest->results[0]['operativo'] == 1) $_SESSION['operativo'] = 1;
    $gest->update("users", ["operativo = 1", "master = 0"], "id = ".$_SESSION['user_id']);
    $_SESSION['master'] = 0;
    $_SESSION['simulation'] = 1;
}
if($s == "n"){
    $gest->getUserData($_SESSION['user_id']);
    if($gest->results[0]['operativo'] == 1) $_SESSION['operativo'] = 1;
    $gest->update("users", ["operativo = 0", "master = 0"], "id = ".$_SESSION['user_id']);
    $_SESSION['master'] = 0;
    $_SESSION['simulation'] = 1;
}
?>
<div class="container">
    <br><h1>Naviga il Gestionale come:</h1><br>
    <p><a href="session_simulator.php?s=m">Naviga come Master</a></p>
    <p><a href="session_simulator.php?s=o">Naviga come Operativo</a></p>
    <p><a href="session_simulator.php?s=n">Naviga come NON Operativo</a></p>
</div>
