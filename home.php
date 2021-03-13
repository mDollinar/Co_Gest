<?php
require_once "inc/head.php";
?>
<script src="js/home.js" type="text/javascript"></script>
<div id="maincontent">
   
<?php if($gest->checkSuperUser()) echo '    
       <div class="container" style="margin-top:80px">
       <!-- Button trigger modal -->
<button type="button" class="btn btn-primary home-tip" data-toggle="modal" data-target="#homeTIP">
  Clicca per la Guida in linea
</button>

<!-- Modal -->
<div class="modal fade" id="homeTIP" tabindex="-1" role="dialog" aria-labelledby="homeTIPLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="homeTIPLabel">Gestisci associazione</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      Clicca sul pulsante in alto a sinistra <strong>"Gesione Associazione"</strong> per visualizzare il menù di amministrazione.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
        
      </div>
    </div>
  </div>
</div>
         
       </div>
   '?>

    <div class="card-deck">
            <div class="card home-tip" style="width: 18rem; max-width: 250px;">
                <div class="card-header">
                    Utilità
                </div>
                <div class="card-body">
                    <h5 class="card-title">Link e download utili</h5>
                    <p class="card-text">Scarica l'autocertificazione in PDF editabile<br> <a href="https://www.interno.gov.it/sites/default/files/2020-10/modello_autodichiarazione_editabile_ottobre_2020.pdf" target="_blank">Autocertificazione</a></p>
                </div>
            </div>
    <?php
        if($gest->checkSuperUser()) {
            if ($gest->checkPendingUsers()) $gest->printPendingUsers();
            if ($gest->checkPendingDocuments()) $gest->printPendingDocuments();
            if ($gest->checkEditedUsers()) $gest->printEditedUsers();
        }
            if (!$gest->checkUserData($_SESSION['user_id'])) $gest->printUserAlert();
    //ToDo: aggiungere alert documento rifiutato
    //ToDo: aggiungere alert documento mancante
    //ToDo: aggiungere alert documento scaduto

    ?>
    </div>
</div>