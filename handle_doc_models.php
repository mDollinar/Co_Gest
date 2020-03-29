<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";
?>
    <br><h1>Gestione Modelli di Dichiarazione</h1><br>
<?php
$gest->getDoc_model();
$addFields = [
    "<a href='crud_doc_models.php?id=%id%&action=edit'>%edit%</a>",
    "<a href='crud_doc_models.php?id=%id%&action=preview&save=0'>%preview%</a>",
];
printTable("handle_doc_models", ['Titolo', 'Data Fissa', 'Generazione automatica dati Utente', 'Azioni'], $gest->results, "file", ['titolo', 'fixed_date', 'auto_user_data'], null, $addFields);