<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";

if(isset($_GET['op'])){
    $gest->getUserData(null, true);
?>
<br />
<div id="maincontent">
<h1>Anagrafica Operativi (<?php echo count($gest->results) ?>)</h1>
<?php
    $o = "op=true";
}elseif (isset($_GET['dim'])){
    $gest->getUserData(null, null, true);
    ?></div>
    <br />
    <div id="maincontent">
    <h1>Anagrafica Dimessi (<?php echo count($gest->results) ?>)</h1>
    <?php
    $o = "dim=true";
}elseif (isset($_GET['nop'])){
    $gest->getUserData(null, false);
    ?></div>
    <br />
    <div id="maincontent">
    <h1>Anagrafica NON Operativi (<?php echo count($gest->results) ?>)</h1>
    <?php
    $o = "nop=true";
}elseif(isset($_GET['rub'])){
    $gest->getUserData();
    ?></div>
    <br />

    <div id="maincontent">
    <h1>Rubrica Utenti (<?php echo count($gest->results) ?>)</h1>

<?php
}else{
    $gest->getUserData();
?></div>
<br />

<div id="maincontent">
<h1>Anagrafica Utenti (<?php echo count($gest->results) ?>)</h1>
<?php

}
?>
    <h4 class="text-center">Utenti visualizzati (<span id="counter"><?php echo count($gest->results) ?></span>)</h4><br />
    <div class="btn-utili"><button class="btn btn-primary" id="stampa">Scarica elenco</button></div>

    
    <?php
    if(isset($_GET['rub'])){
        printTable("view_users", ['Cognome', 'Nome', 'Mail', 'Telefono'], $gest->results, null , ['cognome', 'nome', 'mail', 'tel']);
    }
    elseif($gest->checkSuperUser()) {

        $addField = ["<a href='view_user.php?&id=%id%&o=".$o."'>%edit%</a>"];

        printTable("view_users", ['Cognome', 'Nome', 'Mail', 'Telefono', 'Codice Fiscale', 'Data di Nascita', 'Numero Socio', 'Foto Tessera', 'Operativo', 'Iscrizione', 'Dimissione' ,'Admin', 'Master', 'Modifica/Aggiorna'], $gest->results, "file/personal_photos/", ['cognome', 'nome', 'mail', 'tel', 'CF', 'nascita', 'numero_socio', 'photo', 'operativo', 'iscrizione', 'dimissione', 'adm', 'master'], ['iscrizione', 'dimissione'], $addField);
    }
    else {
    printTable("view_users", ['Cognome', 'Nome', 'Mail', 'Telefono'], $gest->results, null , ['cognome', 'nome', 'mail', 'tel']);
    }?>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<script>
var doc = new jsPDF();
orientation: 'landscape'
var specialElementHandlers = {
    '#maincontent': function (element, renderer) {
        return true;
    }
};

$('#stampa').click(function () {
    doc.fromHTML($('#maincontent').html(), 15, 15, {
        'width': 1000,
            'elementHandlers': specialElementHandlers
    });
    doc.save('rubrica-volontari.pdf');
});
</script>