<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";

$gest->collectDocuments_all();
?>
<br />
<h1>Gestione Documenti</h1>
<br />

<?php
printTable(
    "handle_document_type",
        ['Nome', 'Scadenza Richiesta?', 'Numero Richiesto?', '&Egrave; un documento Richiesto?', 'Richiede un&apos;Allegato?', 'Fronte/Retro?', 'Modifica/Cancella'],
        $gest->results,
        null,
        ['nome', 'scadenza', 'numero', 'required', 'require_attach_front', 'require_attach_back'],
    null,
    ["<a href='crud_document_type.php?action=edit&id=%id%'>%edit%</a>", "<a href='crud_document_type.php?action=del&id=%id%'>%delete%</a>"]
);
?>