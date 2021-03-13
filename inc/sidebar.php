<script src="https://code.jquery.com/jquery-3.4.0.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/sidebar-demo.css" class="rel">


 <div id="mySidebar" class="sidebar-class">
 <div class="accordion" id="accordionExample">
 <div class="card-sidebar" id="primo-pulsante">
   <div class="sidebarlist-header" id="headingOne">
     <h5 class="mb-0">
       <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
       <i class="bi bi-people-fill"></i> Gestisci Volontari
       </button>
     </h5>
   </div>

   <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
     <div class="sidebarlist-body">
     <ul class="list-group list-group-flush">
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="view_users.php">Visualizza tutti</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="view_users.php?op=true">Operativi</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="view_users.php?nop=true">Non operativi</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="view_users.php?dim=true">Dimessi</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="check_users.php">Autorizza nuovo </a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="check_users.php">Aggiorna Volontario</a></li>
     </ul>
     </div>
   </div>
 </div>
 <!-- TODO: ->
 <div class="card-sidebar">
   <div class="sidebarlist-header" id="headingTwo">
     <h5 class="mb-0">
       <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
       <i class="bi bi-award"></i> Gestione Specializzazioni
       </button>
     </h5>
   </div>
   <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
     <div class="sidebarlist-body">
     <ul class="list-group list-group-flush">
 <li class="list-group-item sidebarlist">
   <a class="list-group-item list-group-item-action" href="#">Spec. Comune</a>
 </li>
 <li class="list-group-item sidebarlist">
   <a class="list-group-item list-group-item-action" href="#">Spec. Regione</a>
 </li>
</ul>
     </div>
   </div>
 </div>

 <div class="card-sidebar">
    <div class="sidebarlist-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        <i class="bi bi-truck"></i>  Mezzi e Attrezzature
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="sidebarlist-body">
      <ul class="list-group list-group-flush">
  <li class="list-group-item sidebarlist">
    <a class="list-group-item list-group-item-action" href="#">Mezzi</a>
  </li>
  <li class="list-group-item sidebarlist">
    <a class="list-group-item list-group-item-action" href="#">Attrezzature</a>
  </li>
</ul> 
      </div>
    </div>
  </div>-->

 <div class="card-sidebar">
   <div class="sidebarlist-header" id="headingFour">
     <h5 class="mb-0">
       <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
       <i class="bi bi-file-person"></i> Documenti di identità
       </button>
     </h5>
   </div>
   <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
     <div class="sidebarlist-body">
     <ul class="list-group list-group-flush">
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="view_documents.php">Archivio Documenti</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="handle_document_type.php">Tipi di Documento</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="check_document.php">Approva Documenti</a></li>
 </ul>
     </div>
   </div>
 </div>
<!-- TODO->
 <div class="card-sidebar">
   <div class="sidebarlist-header" id="headingFive">
     <h5 class="mb-0">
       <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
       <i class="bi bi-shield-check"></i> Assicurazioni
       </button>
     </h5>
   </div>
   <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
     <div class="sidebarlist-body">
     <ul class="list-group list-group-flush">
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="#">Assicurazioni Volontari</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="#">Assicurazioni Mezzi</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="#">Assicurazioni Attrezzature</a></li>
     </ul>
     </div>
   </div>
 </div>-->

 <div class="card-sidebar">
   <div class="sidebarlist-header" id="headingSix">
     <h5 class="mb-0">
       <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
       <i class="bi bi-files"></i> Registro eventi
       </button>
     </h5>
   </div>
   <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
     <div class="sidebarlist-body">
     <ul class="list-group list-group-flush">
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="handle_registro.php">Registro iscrizioni</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="crud_registro.php?action=choose">Aggiungi note</a></li>
     </ul>
     </div>
   </div>
 </div>

 <div class="card-sidebar">
   <div class="sidebarlist-header" id="headingSeven">
     <h5 class="mb-0">
       <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
       <i class="bi bi-envelope"></i> Comunicazioni
       </button>
     </h5>
   </div>
   <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
     <div class="sidebarlist-body">
     <ul class="list-group list-group-flush">
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="print_doc_models.php">Stampa/Invia Nuova</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="view_keys.php">Tutte le comunicazioni</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="view_doc_model_keys.php">Firmate e inviate</a></li>
     <li class="list-group-item sidebarlist"><a class="list-group-item list-group-item-action" href="handle_doc_models.php">Gestisci Note e modelli</a></li>
     </ul>
     </div>
   </div>
 </div>

</div>
</div>

<!--
<div id="main">
  <button class="btn btn-primary" id="gestione">☰ Gestisci Associazione</button>  
</div>
-->
<script>
    $(document).ready(function(){
        $("div#mySidebar").css("left","-260px");
        //TODO completare il toggle
        })

        $("div#main>button").click(function (){
            if($(this).hasClass("btn-primary")) {
                $(this).removeClass("btn-primary").addClass("btn-danger")
                $("div#mySidebar").css("left","0px");
                $("div#maincontent").css("margin-left","260px");
            }
            else{
                $(this).removeClass("btn-danger").addClass("btn btn-primary")
                $("div#mySidebar").css("left","-260px");
                $("div#maincontent").css("margin-left","0px");
            }
        })
    
</script>



