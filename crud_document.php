<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";
extract($_GET);

if($action == "edit") {
    $gest->getLinkDocumentData($id);
    ?>
    <br/>
    <h1>Aggiorna il Documento</h1>
    <br/>
    <?php
    $hidden = [array("name" => "id", "value" => $gest->results[0]['id'])];

    $fields = [];
    if ($gest->results[0]['data'][0]['scadenza']) array_push($fields, array("name" => "scadenza", "type" => "date", "label" => "Scadenza", "required" => true, "value" => $gest->results[0]['scadenza']));

    if ($gest->results[0]['data'][0]['numero']) array_push($fields, array("name" => "numero", "type" => "text", "label" => "Numero", "required" => true, "value" => $gest->results[0]['numero']));

    if ($gest->results[0]['data'][0]['require_attach_front']) {
        array_push($fields, array("name" => "attach_front", "type" => "file", "label" => "Allegato Fronte", "mobile_hide" => true));
        if (is_null($gest->results[0]['attach_front'])) {
            if($gest->checkSuperUser() == false) $fields[count($fields) - 1]['required'] = true;
        }
        else {
            $fields[count($fields) - 1]['linkValue'] = "file/document/" . $gest->results[0]['attach_front'];
            $fields[count($fields) - 1]['linkText'] = "Consulta l&apos;allegato Fronte caricato in precedenza ->";
        }
    }

    if ($gest->results[0]['data'][0]['require_attach_back']) {
        array_push($fields, array("name" => "attach_back", "type" => "file", "label" => "Allegato Retro", "mobile_hide" => true));
        if (is_null($gest->results[0]['attach_back'])){
            if($gest->checkSuperUser() == false) $fields[count($fields) - 1]['required'] = true;
        }
        else {
            $fields[count($fields) - 1]['linkValue'] = "file/document/" . $gest->results[0]['attach_back'];
            $fields[count($fields) - 1]['linkText'] = "Consulta l&apos;allegato Retro caricato in precedenza ->";
        }
    }
    if($gest->checkSuperUser()){
        array_push($fields, array("name" => "attach_master", "type" => "file", "label" => "Allegato Master", "mobile_hide" => true));
        if (isset($gest->results[0]['attach_master'])){
            $fields[count($fields) - 1]['linkValue'] = "file/document/" . $gest->results[0]['attach_master'];
            $fields[count($fields) - 1]['linkText'] = "Consulta l&apos;allegato Master caricato in precedenza ->";
        }
    }
    $buttons = [
        array("value" => "Modifica", "action" => "submit"),
        array("value" => "Annulla", "action" => "link", "url" => "handle_document.php", "class" => "btn-danger")
    ];
    printForm(true, "crud_document.php?action=update", "post", $fields, $hidden, $buttons);
}elseif ($action == "choose") {
    ?>
    <br/>
    <h1>Aggiungi un Documento</h1>
    <br/>
    <?php
    $gest->collectDocuments();
    $thead = ["Nome Documento", "Aggiungi"];
    printTable("crud_document", $thead, $gest->results, "file/document", ['nome'], null, ["<a href='crud_document.php?action=add&id_type=%id%&v=".$_GET['v']."'>%add%</a>"]);

}elseif ($action == "add"){
    extract($_GET)
    ?>
    <br/>
    <h1>Inserisci i dati del Documento</h1>
    <br/>
    <?php
    $gest->select(true, "id, nome, cognome, CF", "users", null, "cognome, nome");
    $personnel = $gest->results;
    $gest->reset();
    $gest->getDocumentData($id_type);
    $hidden = null;
    $fields = [];

    $hidden = [array("name" => "id", "value" => $id_type)];
    if($v == "mas") array_push($fields, array("name"=>"user_id", "type"=>"select", "label"=>"Socio", "required"=>true, "values"=>$personnel, "idField"=>"id", "showElem"=>['cognome', 'nome']));

    $buttons = [array("action"=>"submit", "class"=>"btn-info", "value"=>"Salva")];

    if ($gest->results[0]['scadenza']) array_push($fields, array("name" => "scadenza", "type" => "date", "label" => "Scadenza", "required" => true));

    if ($gest->results[0]['numero']) array_push($fields, array("name" => "numero", "type" => "text", "label" => "Numero", "required" => true));

    if ($gest->results[0]['require_attach_front']) {
        array_push($fields, array("name" => "attach_front", "type" => "file", "label" => "Allegato Fronte", "mobile_hide" => true));
        if($gest->checkSuperUser() == false) $fields[count($fields) - 1]['required'] = true;
    }

    if ($gest->results[0]['require_attach_back']) {
        array_push($fields, array("name" => "attach_back", "type" => "file", "label" => "Allegato Retro", "mobile_hide" => true));
        if($gest->checkSuperUser() == false) $fields[count($fields) - 1]['required'] = true;
    }
    if($v == "mas") array_push($fields, array("name" => "attach_master", "type" => "file", "label" => "Allegato Master", "mobile_hide" => true));

    printForm(false, "crud_document.php?action=save&v=".$_GET['v'], "post", $fields, $hidden, $buttons);

}elseif ($action=="update") {
    extract($_POST);
    extract($_FILES);
    $approve == false;
    if($gest->checkSuperUser()) $approve = true;
    $ret = $gest->editLinkDocument(false, $scadenza, $numero, $attach_front, $attach_back, $attach_master, $id, null, $approve);
    if($_GET['o']=="anag") $buttons = [array("url"=>"view_documents.php", "value"=>"Torna Indietro", "class" => "info")];
    else $buttons = [array("value" => "Torna Indietro", "class" => "info", "url" => "handle_document.php")];
    if ($ret == null) printAlert("success", "Complimenti il documento &egrave; stato aggiornato con successo!", $buttons);
    else {
        $message = "<span class='left'>" . $ret['text'];
        for ($i = 0; $i < count($ret['errori']); $i++) {
            $message .= "<br /> - " . $ret['errori'][$i];
        }
        $message .= "</span>";
        if($_GET['o']=="anag") $buttons = [array("url"=>"view_documents.php", "value"=>"Torna Indietro", "class" => "danger")];
        else $buttons = [array("value" => "Torna Indietro", "class" => "danger", "url" => "handle_document.php")];
        printAlert("danger", $message, $buttons, "left");
    }


}elseif ($action == "save"){
    extract($_POST);
    extract($_FILES);
    if($_GET['v'] != "mas") $user_id = $_SESSION['user_id'];
    $approve = false;
    if($gest->checkSuperUser()) $approve = true;

    $ret = $gest->editLinkDocument(true, $scadenza, $numero, $attach_front, $attach_back, $attach_master, $id, $user_id, $approve);
    if($_GET['v']=="mas") $buttons = [array("url"=>"view_documents.php", "value"=>"Torna Indietro", "class" => "info")];
    else $buttons = [array("value" => "Torna Indietro", "class" => "info", "url" => "handle_document.php")];
    if ($ret == null) printAlert("success", "Complimenti il documento &egrave; stato creato con successo!", $buttons);
    else {
        $message = "<span class='left'>" . $ret['text'];
        for ($i = 0; $i < count($ret['errori']); $i++) {
            $message .= "<br /> - " . $ret['errori'][$i];
        }
        $message .= "</span>";
        if($_GET['o']=="anag") $buttons = [array("url"=>"view_documents.php", "value"=>"Torna Indietro", "class" => "danger")];
        else $buttons = [array("value" => "Torna Indietro", "class" => "danger", "url" => "handle_document.php")];
        printAlert("danger", $message, $buttons, "left");
    }
} elseif ($action == "del"){
    $gest->delLinkDocument($_GET['id']);
    if($_GET['o']=="anag") $buttons = [array("url"=>"view_documents.php", "value"=>"Torna Indietro", "class" => "danger")];
    else $buttons = [array("url"=>"handle_document.php", "value"=>"Torna Indietro", "class" => "danger")];
    printAlert("danger", "L&apos;elemento &egrave; stato Cancellato!", $buttons);
}?>