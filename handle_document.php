<?php

require_once "inc/head.php";
require_once "crux/printFunctions.php";

?>
<br />
<div id="maincontent">
<div class="container">       
<h1>Gestisci i tuoi Documenti</h1>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#docID">
  Clicca per la Guida in linea
</button>

<!-- Modal -->
<div class="modal fade" id="docID" tabindex="-1" role="dialog" aria-labelledby="docIDLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="docIDLabel">Aggiungi documento di identità</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        In questa sezione puoi caricare le copie dei tuoi documenti di identità. Invia il fronte e il retro in file separati.<br> Clicca sul pulsante <strong>"Aggiungi"</strong>, seleziona cliccando sul <strong>"+"</strong> quale documento vuoi caricare e segui la procedura.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
        
      </div>
    </div>
  </div>
</div>
<br />
<a href="crud_document.php?action=choose" class="btn btn-info add-button">Aggiungi</a>
<?php
$gest->collectDocuments_all($_SESSION['user_id']);

$subFields = ['nome'];
$addFields = ["<a href='crud_document.php?action=edit&id=%id%'>%edit%</a>", "<a href='crud_document.php?action=del&id=%id%'>%delete%</a>"];
for($i = 0; $i<count($gest->results); $i++){
    if($gest->results[$i]['abilis'] == 0 && $gest->results[$i]['rejected'] == 0) $gest->results[$i]['stato'] = "<span class='badge badge-warning'> In Approvazione</span>";
    elseif($gest->results[$i]['rejected'] == 1) $gest->results[$i]['stato'] = "<span class='badge badge-danger'> Rigettato</span> <br />".$gest->results[0]['cause'];
    elseif($gest->results[$i]['abilis'] == 1) $gest->results[$i]['stato'] = "<span class='badge badge-success'> Approvato</span>";
}

printTable("handle_document", [ "Nome", "Scadenza", "Numero", "Allegato Fronte", "Allegato Retro", "Allegato Master", "Stato", "Modifica/Cancella"],
    $gest->results,
    "file/document/",
    ["nome", "scadenza", "numero", "attach_front", "attach_back", "attach_master", "stato"],
    $subFields,
    $addFields
);?>
</div>
</div>