<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";
?>
<br><h1>Visualizza tutte le mail inviate dal Sistema</h1><br>
<div class="container">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Attive</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Scadute</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?php
            $gest->getKeys();
            printTable("mail_attive", ['Data', 'Tipo Mail', "Consulta"], $gest->results, "file/", ['gen_date', 'content'], null, ["<a href='view_doc_model_keys.php#%key%'>%select%</a>"])
            ?>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php
            $gest->getKeys(true);
            printTable("mail_scadute", ['Data', 'Tipo Mail', "Consulta"], $gest->results, "file/", ['gen_date', 'content'], null, ["<a href='view_doc_models.php#key=%key%'>%select%</a>"])
            ?>
        </div>
    </div>
</div>
<script>$("#mail_attive div.dropdown-filter-dropdown+div.dropdown-filter-dropdown").remove()</script>