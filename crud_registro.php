<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";
extract($_GET);

if($_GET['action']== "choose"){
    ?>
    <br><h1>Seleziona il Socio</h1><br>
<?php
    $gest->select(true, "id, cognome, nome", "users", null, "cognome, nome");
    $addFields = ["<a href='crud_registro.php?action=add&id=%id%'>%add%</a>"];
    printTable("crud_registro", ['Cognome', 'Nome', 'Azioni'], $gest->results, "file", ['cognome','nome'], null, $addFields);
}elseif ($action == "add"){
    echo "<br><h1>Inserisci le informazioni</h1><br>";
    $fields = [
        array("type"=>"date", "label"=>"Data Evento", "name"=>"giorno"),
        array("type"=>"select", "label"=>"Seleziona L&apos;evento", "name" => "ingresso", "idField"=>"value", "showElem"=>['label'], "values"=>[
            array("value" => 1, "label"=>"Ingresso"),
            array("value" => 0, "label"=>"Dimissione")
        ])
    ];
    $buttons = [array("action"=>"submit", "value"=>"Aggiungi")];
    printForm(false, "crud_registro.php?id=$id&action=save", "post", $fields, null, $buttons);
}elseif ($action == "save"){
    extract($_POST);
    $gest->addRegistro($giorno, $ingresso, $id);
    printAlert("success", "Nota aggiunta con successo al Registro", [array("url"=>"handle_registro.php", "class"=>"info", "value"=>"Torna Indietro")]);
}elseif ($action == "edit"){
    echo "<br><h1>Aggiorna le informazioni</h1><br>";
    $gest->select(true, "giorno, ingresso", "registro_iscrizioni", "id = $id");
    $fields = [
        array("type"=>"date", "label"=>"Data Evento", "name"=>"giorno", "value"=>$gest->results[0]['giorno']),
        array("type"=>"select", "label"=>"Seleziona L&apos;evento", "name" => "ingresso", "idField"=>"value", "showElem"=>['label'], "values"=>[
            array("value" => 1, "label"=>"Ingresso"),
            array("value" => 0, "label"=>"Dimissione")
        ])
    ];
    if($gest->results[0]['ingresso'] == 1) $fields[count($fields)-1]['values'][0]['selected'] = true;
    else $fields[count($fields)-1]['values'][1]['selected'] = true;
    $buttons = [array("action"=>"submit", "value"=>"Aggiorna")];
    printForm(true, "crud_registro.php?id=$id&action=update", "post", $fields, null, $buttons);
}elseif ($action == "update") {
    extract($_POST);
    $gest->updateRegistro($giorno, $ingresso, $id);
    printAlert("success", "Nota aggiornata con successo", [array("url" => "handle_registro.php", "class" => "info", "value" => "Torna Indietro")]);
}elseif ($action == "del") {
extract($_POST);
$gest->deleteRegistro($id);
printAlert("danger", "Nota cancellata!", [array("url" => "handle_registro.php", "class" => "danger", "value" => "Torna Indietro")]);
}