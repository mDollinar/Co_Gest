<?php
function printField($voice, $path = null){
    global $gest;

    if(strtotime($voice)) return date("d/m/Y", strtotime($voice));
    else if ($path != null && is_file($path.$voice)){
        $string = "<a href='".$path.$voice."' target='_new'>Consulta</a>";
        if($gest->checkSuperUser()) $string .= "/<a href='".$path.$voice."' target='_new' download>Scarica</a>";
        return $string;
    }
    else if ($voice === "1") return "<span class='y'>S&igrave;</span>";
    else if ($voice === "0") return "<span class='n'>No</span>";
    else if ($voice === null) return "<span class='null'>n.n.</span>";
    else return $voice;
}

function printTable($idcss, $thead, $tbody, $path, $ordine = null, $subFields = null, $addField = null){
    echo "<table class='table table-hover overflow-auto' id='$idcss'>
            <thead>
                <tr>";
    for($i = 0; $i<count($thead); $i++){
        echo "<th scope='col'>".$thead[$i]."</th>";
    }
    echo"</tr></thead>";
    echo"<tbody>";
    if(is_null($ordine)){
        for($i = 0; $i<(count($tbody)); $i++){
            echo "<tr>";
            for($j = 0; $j<count($tbody[$i]); $j++){
                echo "<td>";
               echo printField($tbody[$i][$j]);
                echo "</td>";
            }
            if(!is_null($addField)){
                echo "<td>";
                for($z = 0; $z<count($addField); $z++){
                    $addField[$z] = str_replace("%id%", $tbody[$i]['id'], $addField[$z]);
                    $addField[$z] = str_replace("%id_document%", $tbody[$i]['documents'], $addField[$z]);
                    echo $addField[$z];
                    if($z<(count($addField)-1)) echo "/";
                }
                echo "</td>";
            }
            echo "</tr>";
        }
    }else{
        for($i = 0; $i<(count($tbody)); $i++){
            echo "<tr>";
            for($j = 0; $j<count($ordine); $j++){
                if($j==0) echo "<td scope=row>";
                else echo "<td>";
                if(!is_null($subFields) && in_array($ordine[$j], $subFields)){
                    echo printField($tbody[$i][$ordine[$j]][0][0], $path);
                }else{
                    echo printField($tbody[$i][$ordine[$j]], $path);
                }
                echo "</td>";
            }
            if(!is_null($addField)){
                echo "<td>";
                for($z = 0; $z<count($addField); $z++){
                    $addField[$z] = str_replace("%id%", $tbody[$i]['id'], $addField[$z]);
                    echo $addField[$z];
                    if($z<(count($addField)-1)) echo "/";
                    $addField[$z] = str_replace($tbody[$i]['id'], "%id%", $addField[$z]);
                }
                echo "</td>";
            }
            echo "</tr>";
        }
    }
    echo "</table><script>$('table').excelTableFilter();
</script>";
}

function printForm($edit, $action, $method, $fields = null, $hidden = null, $buttons = null){
    echo "<form action='$action&o=".$_GET['o']."' method='$method' class='form-group' enctype='multipart/form-data'>";
    if(!is_null($hidden)){
        for($i = 0; $i<count($hidden); $i++){
            echo "<input type='hidden' name='".$hidden[$i]['name']."' id='".$hidden[$i]['name']."' value='".htmlspecialchars($hidden[$i]['value'], ENT_QUOTES)."'>";
        }
    }
    if(!is_null($fields)) {
        for ($i = 0; $i < count($fields); $i++) {
            echo "<div class='input-group mb-3'>";
            echo "<div class='input-group-prepend'><span class='input-group-text'>" . $fields[$i]['label'] . ": </span></div>";
            if ($fields[$i]['type'] == "select") {
                echo "<select name='" . $fields[$i]['name'] . "' class='custom-select'>";
                if ($edit == false) echo "<option selected>Seleziona...</option>";
                else echo "<option>Seleziona...</option>";
                for ($j = 0; $j < count($fields[$i]['values']); $j++) {
                    echo "<option value='" . $fields[$i]['values'][$j][$fields[$i]['idField']] . "'";
                    if (isset($fields[$i]['values'][$j]['selected'])) echo " selected";
                    echo ">";
                    for ($z = 0; $z < count($fields[$i]["showElem"]); $z++) {
                        echo $fields[$i]['values'][$j][$fields[$i]['showElem'][$z]] . " ";
                    }
                    echo "</option>";
                }
                echo "</select>";
            } else {
                echo "<input type='" . $fields[$i]['type'] . "' class='form-control' name='" . $fields[$i]['name'] . "' id='" . $fields[$i]['name'] . "'";

                if ($edit) {
                    if (!($fields[$i]['disabled'])) echo " value='" . htmlspecialchars($fields[$i]['value'], ENT_QUOTES) . "'";
                    else echo " value='" . printField($fields[$i]['value']) . "'";
                }
                if ($fields[$i]['required']) echo " required";
                if ($fields[$i]['disabled']) echo " disabled";

                echo " />";
                if (!is_null($fields[$i]['linkValue'])) echo "<a href='" . $fields[$i]['linkValue'] . "' target='new'>" . $fields[$i]['linkText'] . "</a>";
            }
            echo "</div>";

        }
    }
    if(!is_null($buttons)){
        for($i = 0; $i<count($buttons);$i++){
            if($buttons[$i]['action'] == "submit"){
                echo "<input type ='submit' class='btn";
                if($buttons[$i]['class']) echo " ".$buttons[$i]['class'];
                else echo " btn-info";
                echo "' value='".$buttons[$i]['value']."'>";
            }
            if($buttons[$i]['action'] == "link"){
                echo "<a href='".$buttons[$i]['url']."'><button type='button' class='btn";
                if($buttons[$i]['class']) echo " ".$buttons[$i]['class'];
                else echo " btn-info";
                echo "'>".$buttons[$i]['value']."</button></a>";
            }
        }
    }
    echo "</form>";
}

function printAlert($type, $message, $buttons = null, $class = null){
    echo "<p class='alert alert-".$type;
    if(!is_null($class)) echo " ".$class;
    echo "'>".$message."<br />";
    if(!is_null($buttons)){
        for($i = 0; $i<count($buttons); $i++) {
            echo "<a href='" . $buttons[$i]['url'] . "'><button type='button' class='btn";
            if (!is_null($buttons[$i]['class'])) {
                echo " btn-" . $buttons[$i]['class'];
            }
            echo "'>" . $buttons[$i]['value'] . "</button></a>";
        }
    }
    echo "</p>";
}
function printDoc_model($save = false, $titolo, $fixedDate = true, $date = null, $t1, $t2, $t3, $particella, $auto_userData, $dataField, $access_level, $user_id, $pres_sign, $header_doc_model){
    global $gest, $citta_sede, $president_sign, $president_sign_pic;
    $gest->reset();
    $gest->getUserData($user_id);
    echo"
    <link rel='stylesheet' href='css/head_doc_model.css' type='text/css'>
    <div class='container'>
        <br><h2 class='d-print-none text-center'>Titolo: $titolo</h2><br>";
        if($save == 1) echo "<h1 class='d-print-none'>ANTEPRIMA DELLA DICHIARAZIONE</h1><br>";
        require_once "inc/".$header_doc_model.".php";
        echo "<br>$t1
        <h3 class='text-center'>".mb_strtoupper($particella)."</h3><br>
        $t2";
    if($auto_userData) $dataField = "<p><strong>%COGNOME% %NOME%</strong>, nato/a a <strong>%NASCITA_CITTA% (%NASCITA_PR%)</strong> il <strong>%NASCITA%</strong> e residente in <strong>%INDIRIZZO%, %INDIRIZZO_CAP%, %INDIRIZZO_CITTA% (%INDIRIZZO_PR%)</strong>
    ";
    $print_data_field = str_replace("%NOME%", $gest->results[0]['nome'], $dataField);
    $print_data_field = str_replace("%COGNOME%", $gest->results[0]['cognome'], $print_data_field);
    $print_data_field = str_replace("%MAIL%", $gest->results[0]['mail'], $print_data_field);
    $print_data_field = str_replace("%TEL%", $gest->results[0]['tel'], $print_data_field);
    $print_data_field = str_replace("%CF%", $gest->results[0]['CF'], $print_data_field);
    $print_data_field = str_replace("%NASCITA%", printField($gest->results[0]['nascita']), $print_data_field);
    $print_data_field = str_replace("%NASCITA_CITTA%", $gest->results[0]['nascita_citta'], $print_data_field);
    $print_data_field = str_replace("%NASCITA_PR%", $gest->results[0]['nascita_pr'], $print_data_field);
    $print_data_field = str_replace("%INDIRIZZO%", $gest->results[0]['indirizzo'], $print_data_field);
    $print_data_field = str_replace("%INDIRIZZO_CAP%", $gest->results[0]['indirizzo_cap'], $print_data_field);
    $print_data_field = str_replace("%INDIRIZZO_CITTA%", $gest->results[0]['indirizzo_citta'], $print_data_field);
    $print_data_field = str_replace("%INDIRIZZO_PR%", $gest->results[0]['indirizzo_pr'], $print_data_field);
    $print_data_field = str_replace("%N_SOCIO%", $gest->results[0]['numero_socio'], $print_data_field);
    echo "$print_data_field<br>";
        echo "$t3<br>
        <p class=\"text-right\">$citta_sede, ";
    if($fixedDate) echo printField($date);
    else echo date("d/m/Y");
    if($pres_sign){
        echo "</p>
            <p class='text-right'>Il Presidente e Rappresentante Legale, <br> $president_sign</p>
            <img src='$president_sign_pic' alt='' class='sign'>
        ";
    }else{
        echo "</p>
            <p class='text-right'>".$gest->results[0]['cognome']." ".mb_strtoupper($gest->results[0]['nome'])."</p>
        ";
    }
    if($save == 1){
        $hiddens=[
            array("name"=>"titolo", "value"=>$titolo),
            array("name"=>"t1", "value"=>$t1),
            array("name"=>"t2", "value"=>$t2),
            array("name"=>"t3", "value"=>$t3),
            array("name"=>"particella", "value"=>mb_strtoupper($particella)),
            array("name"=>"auto_user_data", "value"=>$auto_userData),
            array("name"=>"data_field", "value"=>$dataField),
            array("name"=>"fixed_date", "value"=>$fixedDate),
            array("name"=>"date", "value"=>$date),
            array("name"=>"access_level", "value"=>$access_level),
            array("name"=>"pres_sign", "value"=>$pres_sign),
            array("name"=>"header_doc_model", "value"=>$header_doc_model)
        ];
        $buttons=[array("value"=>"Salva", "action"=>"submit"), array("value"=>"Torna Indietro", "action"=>"link", "url"=>"javascript:window.close();", "class"=>"btn-danger")];
        $action = "crud_doc_models.php?action=save";
        if($_GET['action'] == "previewUpdate") $action = "crud_doc_models.php?action=update&id=".$_GET['id'];
        printForm(false, $action, "post", null, $hiddens, $buttons);
    }
    echo "</div>";
}