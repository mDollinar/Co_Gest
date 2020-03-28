<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";
extract($_GET);
extract($_POST);
?>
<?php
if($action == "y"){
    $gest->approveUser($id);
    $buttons = [array("url"=>"check_users.php", "class"=>"info", "value"=>"Torna indietro")];
    printAlert("success", "Utente approvato.", $buttons, "success");
}elseif ($action == "n") {
    ?>
    <br />
    <h1>Approvazione nuovi Utenti</h1>
    <br />
    <?php
    $fields = [array("type"=>"text", "label"=>"Motivazione", "ph"=>"E&grave; una persona sconosciuta", "name"=>"cause", "required" => true)];
    $buttons = [array("action"=>"submit", "value" =>"Rifiuta", "class"=>"btn-danger")];
    printForm(false, "check_users.php?action=refuse&id=".$id, "post", $fields, null, $buttons);
}elseif ($action == "refuse") {
    $gest->refuseUser($id, $cause);
    $buttons = [array("url" => "check_users.php", "class" => "danger", "value" => "Torna indietro")];
    printAlert("danger", "Utente rigettato.", $buttons, "danger");
}else {
    ?>
    <br />
    <h1>Approvazione nuovi Utenti</h1>
    <br />
    <?php
    $gest->getPendingUsers();
    $addFields = ["<a href='check_users.php?action=y&id=%id%'>Conferma</a>", "<a href='crud_document.php?action=n&id=%id%'>Rifiuta</a>"];

    printTable("check_users", ['Cognome', 'Nome', 'Mail', 'Azioni'], $gest->results, null, ['cognome', 'nome', 'mail'], null, $addFields);
}