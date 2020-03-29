<?php
require_once "inc/head.php";
require_once "def/printFunctions.php";
?>
<br />
<h1>Anagrafica Documenti</h1>
<br />
<?php
$ret = $gest->buildDocumentMatrix();

printTable("view_documents", $ret['head'], $ret['body'], "file/document", $ret['order']);
