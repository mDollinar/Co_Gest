<?php
require_once "inc/head-nm.php";
require_once "crux/check_session.php";
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?php echo $asso_ext_name; ?>Gestionale Volontari</title>

<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="inc/excel-like-bt/excel-bootstrap-table-filter-bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.0.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="inc/excel-like-bt/excel-bootstrap-table-filter-style.css">
    <link rel="stylesheet" href="css/sidebar-demo.css" class="rel">
    <link rel="stylesheet" href="css/style.0.1.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <a class="navbar-brand" href="home.php"><?php echo $asso_ext_name; ?>Gestionale Volontari</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="documents" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Documenti di identità</a>
                <div class="dropdown-menu" aria-labelledby="documents">
                    <a class="dropdown-item" href="handle_document.php">Gestisci i tuoi Documenti </a>
                </div>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="view_users.php?rub=true">Rubrica</a>
            </li>

            <!--<li class="nav-item">
            TODO: specializzazioni
                <a class="nav-link" href="varchi.php">Specializzazioni</a>
            </li>-->

            <li class="nav-item active">
                <a class="nav-link" href="handle_personal_doc_models.php">Le tue comunicazioni/attivazioni</a>
            </li>
            <!-- TODO: calendario attività
            <li class="nav-item active">
                <a class="nav-link" href="#">Calendario attività</a>
            </li>-->

        </ul>
        <div class="nav-item dropdown pull-right">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Gestione Account </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">
                <a class="dropdown-item" href="view_user.php">Modifica Dati Personali</a>
                <a class="dropdown-item" href="crud_user.php?sec=pwd">Modifica Password</a>
                <?php
                    if($gest->checkSuperUser() || $_SESSION['simulation'] == 1){
                ?>
                <a class="dropdown-item" href="session_simulator.php">Simulatore Utenti</a>
                <?php } ?>
                <a class="dropdown-item" href="crux/logout.php">Log out</a>
            </div>
        </div>
    </div>
</nav>

<?php if($gest->checkSuperUser()) echo '
<div class="top-bar" id="main">
  <button class="btn btn-primary" id="gestione">☰ Gestisci Associazione</button>  
<h1>
Gestionale Associazione di Protezione Civile
</h1></div>
';?>
<?php if($gest->checkSuperUser())include 'inc/sidebar.php';?>
<br>
