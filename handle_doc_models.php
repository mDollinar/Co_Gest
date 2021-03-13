<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";
?>
    <br>
    <div id="maincontent"><h1>Gestione Modelli di Dichiarazione</h1><br>
    <a href="crud_doc_models.php?action=new" class="btn btn-info add-button">Aggiungi</a>
<div class="container"><?php
$gest->getDoc_model();
$addFields = [
    "<a href='crud_doc_models.php?id=%id%&action=edit'>%edit%</a>",
    "<a href='crud_doc_models.php?id=%id%&action=preview&save=0'>%preview%</a>",
];
printTable("handle_doc_models", ['Titolo', 'Data Fissa', 'Generazione automatica dati Utente', 'Firma del Presidente', 'Livello d&apos;accesso', 'Azioni'], $gest->results, "file", ['titolo', 'fixed_date', 'auto_user_data', 'pres_sign', 'access_level'], null, $addFields);
?></div>
</div>