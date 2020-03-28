<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";
?>
    <br><h1>Registro Iscrizioni</h1><br>
<?php
$body = $gest->getRegistro();
for($i=0; $i<count($body); $i++){
    if($body[$i]['ingresso']) $body[$i]['ingresso'] = "<span class='y'>si &egrave iscritto</span>";
    else $body[$i]['ingresso'] = "<span class='n'>si &egrave dimesso</span>";
    $body[$i]['user_cn'] = $body[$i]['user'][0]['cognome']." ".$body[$i]['user'][0]['nome'];
}
$addFields = ["<a href='crud_registro.php?action=edit&id=%id%'>Modifica Nota</a>", "<a href='crud_registro.php?action=del&id=%id%'>Cancella Nota</a>"];
printTable("registro", ['Data', 'Evento', 'Socio', 'Azioni'], $body, "file", ['giorno', 'ingresso', 'user_cn'], null, $addFields);
