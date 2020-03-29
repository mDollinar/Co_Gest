<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";
extract($_GET);
if(!isset($id_model)) {
    if ($gest->checkSuperUser()) {
        $gest->getDoc_model();
    } else {
        $gest->getUserData($_SESSION['user_id']);
        extract($gest->results[0]);
        if ($operativo == 1) $access_level = 1;
        else $access_level = 2;
        $gest->reset();
        $gest->getDoc_model(null, $access_level);
    } ?>
    <br><h1>Consulta le tue Dichiarazioni</h1><br>
    <?php
    $addFields = ['<a href=handle_personal_doc_models.php?id_model=%id%>%print%</a>'];
    printTable("print_doc_models_choose_model", ['Titolo', 'Scegli il Documento'], $gest->results, "file", ['titolo'], null, $addFields);
}else{
    $gest->reset();
    $gest->getDoc_model($id_model);
    extract($gest->results[0]);
    printDoc_model(0, $titolo, $fixed_date, $giorno, $t1, $t2, $t3, $particella, $auto_user_data, $data_field, null, $_SESSION['user_id'], $pres_sign, $header_doc_model);
    $gest->addLog("visualizzato il modello dal titolo ".$titolo." [personale]", true);
}