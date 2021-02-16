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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="inc/excel-like-bt/excel-bootstrap-table-filter-style.css">
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
                <a class="nav-link dropdown-toggle" href="#" id="documents" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Documenti Identificativi</a>
                <div class="dropdown-menu" aria-labelledby="documents">
                    <a class="dropdown-item" href="handle_document.php">Gestisci i tuoi Documenti </a>
                    <?php if($gest->checkSuperUser()) echo '
                        <a class="dropdown-item" href="view_documents.php">Archivio Documenti</a>
                        ';?>
                </div>
            </li>
            <!--<li class="nav-item">
            TODO: specializzazioni
                <a class="nav-link" href="varchi.php">Specializzazioni</a>
            </li>-->
            <?php if($gest->checkSuperUser()) echo '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="anag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Utenti</a>
                <div class="dropdown-menu" aria-labelledby="anag">
                     <a class="dropdown-item" href="view_users.php">Attivi</a>
                    <a class="dropdown-item" href="view_users.php?op=true">Operativi</a>
                    <a class="dropdown-item" href="view_users.php?nop=true">NON Operativi</a>
                    <a class="dropdown-item" href="view_users.php?dim=true">Dimessi</a>
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
                <a class="nav-link dropdown-toggle" href="#" id="anag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Note e Modelli</a>
                <div class="dropdown-menu" aria-labelledby="anag">
                    <a class="dropdown-item" href="handle_personal_doc_models.php">Consulta le tue dichiarazioni</a>
                    <?php if($gest->checkSuperUser()) echo '
                    <a class="dropdown-item" href="print_doc_models.php">Stampa/Invia</a>';?>
                  </div>
            </li>
                    
            <?php if($gest->checkSuperUser()) echo '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="anag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mail</a>
                <div class="dropdown-menu" aria-labelledby="anag">
                    <a class="dropdown-item" href="view_keys.php">Tutte le mail</a>
                    <a class="dropdown-item" href="view_doc_model_keys.php">Note e Modelli via mail</a>
                  </div>
            </li>';?>
        </ul>
        <div class="nav-item dropdown pull-right">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Gestione Account </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">
                <a class="dropdown-item" href="view_user.php">Modifica Account</a>
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
<div class="sidebar position-fixed">
    <ul class="list-group list">
        <li class="list-group-item"><h5>Gestione di Sistema</h5></li>
        <li class="list-group-item dropdown">
                <a href="#">Documenti Identificativi</a>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a class="dropdown-item" href="handle_document_type.php">Gestisci i tipi di Documento</a></li>
                    <li class="list-group-item"><a class="dropdown-item" href="check_document.php">Approva Documenti</a></li>
                </ul>
            </li>
            <li class="list-group-item dropdown">
                <a href="#">Utenti</a>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a class="dropdown-item" href="check_users.php">Approvazione nuovi Utenti</a></li>
                </ul>
            </li>
            <li class="list-group-item dropdown">
                <a href="#">Note e Modelli</a>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a class="dropdown-item" href="handle_doc_models.php">Gestisci le note e i modelli</a></li>
                </ul>
            </li>
    </ul>
</div>
<div id="side-toggle" class="position-fixed mobile_hide"><i class="fa fa-arrow-right"></i> Gestione</div>

<script>
    $(document).ready(function(){
        $("div.sidebar").hide()
        $("div.sidebar ul.list-group ul.list-group").hide()
        //TODO completare il toggle
        $("div.sidebar li.dropdown a").click(function(){
            $("div.sidebar ul.list-group ul.list-group").hide()
            $(this).parent().children("ul.list-group").show()
        })

        $("div#side-toggle>i").click(function (){
            if($(this).hasClass("fa-arrow-right")) {
                $(this).removeClass("fa-arrow-right").addClass("fa-arrow-left").css("margin-left", "405px")
                $("div.sidebar").show()
            }
            else{
                $(this).removeClass("fa-arrow-left").addClass("fa-arrow-right").css("margin-left", "5px")
                $("div.sidebar").hide()
            }
        })
    })
</script>
'; ?>
