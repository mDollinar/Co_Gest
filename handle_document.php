<?php

require_once "inc/head.php";
require_once "def/printFunctions.php";

?>
<br />
<h1>Gestisci i tuoi Documenti</h1>
<br />

<?php
$gest->collectDocuments_all($_SESSION['user_id']);

$subFields = ['nome'];
$addFields = ["<a href='crud_document.php?action=edit&id=%id%'>%edit%</a>", "<a href='crud_document.php?action=del&id=%id%'>%delete%</a>"];
for($i = 0; $i<count($gest->results); $i++){
    if($gest->results[$i]['abilis'] == 0 && $gest->results[$i]['rejected'] == 0) $gest->results[$i]['stato'] = "<span class='badge badge-warning'> In Approvazione</span>";
    elseif($gest->results[$i]['rejected'] == 1) $gest->results[$i]['stato'] = "<span class='badge badge-danger'> Rigettato</span> ".$gest->results[0]['cause'];
    elseif($gest->results[$i]['abilis'] == 1) $gest->results[$i]['stato'] = "<span class='badge badge-success'> Approvato</span>";
}

printTable("handle_document", [ "Nome", "Scadenza", "Numero", "Allegato Fronte", "Allegato Retro", "Allegato Master", "Stato", "Modifica/Cancella"],
    $gest->results,
    "file/document/",
    ["nome", "scadenza", "numero", "attach_front", "attach_back", "attach_master", "stato"],
    $subFields,
    $addFields
);

