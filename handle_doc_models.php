<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";
?>
    <br><h1>Gestione Modelli di Dichiarazione</h1><br>
    <a href="crud_doc_models.php?action=new" class="btn btn-info block-left mobile_hide"><i class="fa fa-plus"></i> Aggiungi</a>
<?php
$gest->getDoc_model();
$addFields = [
    "<a href='crud_doc_models.php?id=%id%&action=edit'>%edit%</a>",
    "<a href='crud_doc_models.php?id=%id%&action=preview&save=0'>%preview%</a>",
];
printTable("handle_doc_models", ['Titolo', 'Data Fissa', 'Generazione automatica dati Utente', 'Azioni'], $gest->results, "file", ['titolo', 'fixed_date', 'auto_user_data'], null, $addFields);