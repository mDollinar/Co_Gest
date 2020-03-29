<?php
require_once "inc/head-nm.php";
require_once "def/check_session.php";
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?php echo $asso_ext_name; ?>Gestionale Volontari</title>
    <link rel="stylesheet" href="css/style.css">
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="inc/excel-like-bt/excel-bootstrap-table-filter-bundle.js"></script>
    <link rel="stylesheet" href="inc/excel-like-bt/excel-bootstrap-table-filter-style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                <a class="nav-link dropdown-toggle" href="#" id="documents" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Documenti Identificativi</a>
                <div class="dropdown-menu" aria-labelledby="documents">
                    <a class="dropdown-item" href="crud_document.php?action=choose">Aggiungi un Documento </a>
                    <a class="dropdown-item" href="handle_document.php">Gestisci i tuoi Documenti </a>
                    <?php if($gest->checkSuperUser()) echo '
                        <a class="dropdown-item" href="check_document.php">Approva Documenti</a>
                        <a class="dropdown-item" href="crud_document.php?action=choose&v=mas">Assegna un Documento</a>
                        <a class="dropdown-item" href="crud_document_type.php?action=add">Aggiungi un tipo di Documento</a>
                        <a class="dropdown-item" href="handle_document_type.php">Gestisci i tipi di Documento</a>
                        <a class="dropdown-item" href="view_documents.php">Anagrafica Documenti</a>
                        ';?>
                </div>
            </li>
            <!--<li class="nav-item">
                <a class="nav-link" href="varchi.php">Specializzazioni</a>
            </li>-->
            <?php if($gest->checkSuperUser()) echo '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="anag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Utenti</a>
                <div class="dropdown-menu" aria-labelledby="anag">
                     <a class="dropdown-item" href="view_users.php">Anagrafica Utenti</a>
                    <a class="dropdown-item" href="view_users.php?op=true">Anagrafica Operativi</a>
                    <a class="dropdown-item" href="view_users.php?nop=true">Anagrafica NON Operativi</a>
                    <a class="dropdown-item" href="view_users.php?dim=true">Anagrafica Dimessi</a>
                    <a class="dropdown-item" href="check_users.php">Approvazione nuovi Utenti</a>
                  </div>
            </li>';?>
            <?php if($gest->checkSuperUser()) echo '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="anag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Registro Isc.</a>
                <div class="dropdown-menu" aria-labelledby="anag">
                    <a class="dropdown-item" href="crud_registro.php?action=choose">Aggiungi una nota</a>
                    <a class="dropdown-item" href="handle_registro.php">Visualizza</a>
                  </div>
            </li>';?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="anag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dichiarazioni Firmate</a>
                <div class="dropdown-menu" aria-labelledby="anag">
                    <a class="dropdown-item" href="handle_personal_doc_models.php">Consulta le tue dichiarazioni</a>
                    <?php if($gest->checkSuperUser()) echo '
                    <a class="dropdown-item" href="crud_doc_models.php?action=new">Aggiungi un modello</a>
                    <a class="dropdown-item" href="handle_doc_models.php">Gestisci i modelli</a>
                    <a class="dropdown-item" href="print_doc_models.php">Stampa/Invia una dichiarazione</a>
                  </div>
            </li>';?>
        </ul>
        <div class="nav-item dropdown pull-right">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Gestione Account </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">
                <a class="dropdown-item" href="crud_user.php">I miei Dati</a>
                <?php
                    if($gest->checkSuperUser() || $_SESSION['simulation'] == 1){
                ?>
                <a class="dropdown-item" href="session_simulator.php">Simulatore Utenti</a>
                <?php } ?>
                <a class="dropdown-item" href="core/logout.php">Log out</a>
            </div>
        </div>
    </div>
</nav>