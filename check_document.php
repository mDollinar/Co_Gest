<?php
require_once "inc/head.php";
require_once "def/printFunctions.php";
extract($_GET);
?>
<br />
<h1>Gestione aggiornamenti Documenti</h1>
<br />

<?php
if($action=="eval"){
    $gest->select(true, "id_document, attach_front, attach_back, attach_master", "link_document_users", "id = $id");
    $gest->subSelectOnField("document", "require_attach_front, require_attach_back", "type_document", "id", "id_document");

    $fields = [];
    echo "<ul class='list-group' style='margin: 0 auto;display: flex;width: 36rem;'>";
    if($gest->results[0]['document'][0]['require_attach_front'] == 1) {
        echo "<li class='list-group-item list-group-item-light'>Documento Fronte: ";
        echo printField($gest->results[0]['attach_front'], "file/document/")."</li>";
    }
    if($gest->results[0]['document'][0]['require_attach_back'] == 1) {
        echo "<li class='list-group-item list-group-item-dark'>Documento Retro: ";
        echo printField($gest->results[0]['attach_back'], "file/document/")."</li>";
    }
    echo "</ul>";
    array_push($fields, array("type"=>"file", "name"=>"attach_master", "label"=>"Allegato Master"));
    if($gest->results[0]['attach_master'] != null) {
        $fields[count($fields) - 1]['linkValue'] = "file/document/".$gest->results[0]['attach_master'];
        $fields[count($fields) - 1]['linkText'] = "Consulta l&apos;attuale allegato finale ->";
    }
    $buttons=[
        array("action"=>"submit", "value"=>"Approva"),
        array("action"=>"link", "url"=>"check_document.php?action=n&id=$id", "class"=>"btn-danger", "value"=>"Rifiuta")
    ];

    printForm(true, "check_document.php?action=approve&id=$id", "post", $fields, null, $buttons);

}elseif(!isset($id)) {
    $thead = ['Documento', 'Cognome', 'Nome', 'Azione'];

    $gest->select(true, "id, id_document, id_user", "link_document_users", "abilis = 0 and rejected = 0");
    $gest->subSelectOnField("document", "nome", "type_document", "id", "id_document");
    $gest->subSelectOnField("cognome", "cognome", "users", "id", "id_user");
    $gest->subSelectOnField("nome", "nome", "users", "id", "id_user");

    $addFields = ["<a href='check_document.php?action=eval&id=%id%'>%view%</a>"];

    printTable("check_document", $thead, $gest->results, "file/document", ['document', 'cognome', 'nome'], ['document', 'cognome', 'nome'], $addFields);
}elseif($action == "approve") {
    $gest->approveDocument($id);
}elseif ($action == "n") {
    $fields = [array("type"=>"text", "label"=>"Motivazione", "ph"=>"Il documento non &egrave; leggibile", "name"=>"cause", "required" => true)];
    $buttons = [array("action"=>"submit", "value" =>"Rifiuta", "class"=>"btn-danger")];
    printForm(false, "check_document.php?action=refuse&id=".$id, "post", $fields, null, $buttons);
}elseif ($action == "refuse") {
    extract($_POST);
    $gest->refuseDocument($id, $cause);
    $buttons = [array("url" => "check_document.php", "class" => "danger", "value" => "Torna indietro")];
    printAlert("danger", "Documento rigettato.", $buttons, "danger");
}
