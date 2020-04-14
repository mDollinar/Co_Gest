<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";
?>
<br />
<h1>Anagrafica Documenti</h1>
<br />
<a href="crud_document.php?action=choose&v=mas" class="btn btn-info block-left mobile_hide"><i class="fa fa-plus"></i> Aggiungi</a>
<?php
$ret = $gest->buildDocumentMatrix();

printTable("view_documents", $ret['head'], $ret['body'], "file/document", $ret['order']);
