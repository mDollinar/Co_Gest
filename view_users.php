<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";

if(isset($_GET['op'])){
    $gest->getUserData(null, true);
?>
<br />
<h1>Anagrafica Operativi (<?php echo count($gest->results) ?>)</h1>
<br />
<?php
    $o = "op=true";
}elseif (isset($_GET['dim'])){
    $gest->getUserData(null, null, true);
    ?>
    <br />
    <h1>Anagrafica Dimessi (<?php echo count($gest->results) ?>)</h1>
    <br />
    <?php
    $o = "dim=true";
}elseif (isset($_GET['nop'])){
    $gest->getUserData(null, false);
    ?>
    <br />
    <h1>Anagrafica NON Operativi (<?php echo count($gest->results) ?>)</h1>
    <br />
    <?php
    $o = "nop=true";
}else{
    $gest->getUserData();
?>
<br />
<h1>Anagrafica Utenti (<?php echo count($gest->results) ?>)</h1>
<br />
<?php

}

$addField = ["<a href='crud_user.php?&id=%id%&o=".$o."'>%edit%</a>"];

printTable("view_users", ['Cognome', 'Nome', 'Mail', 'Telefono', 'Codice Fiscale', 'Data di Nascita', 'Numero Socio', 'Foto Tessera', 'Operativo', 'Iscrizione', 'Dimissione' ,'Admin', 'Master', 'Azioni'], $gest->results, "file/personal_photos/", ['cognome', 'nome', 'mail', 'tel', 'CF', 'nascita', 'numero_socio', 'photo', 'operativo', 'iscrizione', 'dimissione', 'adm', 'master'], ['iscrizione', 'dimissione'], $addField);
?>