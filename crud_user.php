<?php
require_once "crux/printFunctions.php";
require_once "inc/head.php";
extract($_GET);
?>
<div id="maincontent">

    <br />
    <h1>Aggiornamento Dati Personali</h1>
<br />
<?php
if($action != "save") {
    //operazioni preliminari
    if (isset($_GET['id'])) $id = $_GET['id'];
    else $id = $_SESSION['user_id'];
    $gest->funct("giorno", "registro_iscrizioni", "MAX", "ingresso = 1 and id_user = $id");
    $iscrizione = $gest->retResults();

    $gest->reset();
    $gest->funct("giorno", "registro_iscrizioni", "MAX", "ingresso = 0 and id_user = $id");
    $dimissione = $gest->retResults();

    $gest->reset();
    $gest->getUserData($id);


    $fields = [];

    if($sec == "anag"){//campi anagrafica
        if($gest->checkSuperUser()){
            array_push($fields, array("type" => "text", "name" => "nome", "label" => "Nome", "value" => $gest->results[0]['nome'], "required" => true, "ph"=>"Mario"));
            array_push($fields, array("type" => "text", "name" => "cognome", "label" => "Cognome", "value" => $gest->results[0]['cognome'], "required" => true, "ph"=>"Rossi"));
        }

        if(isset($iscrizione[0]['giorno'])) array_push($fields, array("type" => "text", "label" => "Data Iscrizione ", "value" => $iscrizione[0]['giorno'], "disabled" => true));
        if(isset($dimissione[0]['giorno'])) array_push($fields, array("type" => "text", "label" => "Data Dimissioni ", "value" => $dimissione[0]['giorno'], "disabled" => true));
        array_push($fields, array("type" => "text", "name" => "CF", "label" => "Codice Fiscale", "value" => $gest->results[0]['CF'], "ph"=>"RSSMRI89S25H501Y"));
        array_push($fields, array("type" => "date", "name" => "nascita", "label" => "Data di Nascita", "value" => $gest->results[0]['nascita']));
        array_push($fields, array("type" => "text", "name" => "nascita_citta", "label" => "Citt&agrave; di Nascita", "value" => $gest->results[0]['nascita_citta'], "ph"=>"Milano"));
        array_push($fields, array("type" => "text", "name" => "nascita_pr", "label" => "Provincia di Nascita (SIGLA)", "value" => $gest->results[0]['nascita_pr'], "ph"=>"MI"));

    }elseif($sec == "resdom") {//campi residenza/domicilio
        array_push($fields, array("type" => "text", "name" => "indirizzo", "label" => "Indirizzo di Residenza", "value" => $gest->results[0]['indirizzo'], "ph" => "Via brunori, 2"));
        array_push($fields, array("type" => "text", "name" => "indirizzo_cap", "label" => "CAP di residenza", "value" => $gest->results[0]['indirizzo_cap'], "ph" => "00100"));
        array_push($fields, array("type" => "text", "name" => "indirizzo_citta", "label" => "Citt&agrave; di Residenza", "value" => $gest->results[0]['indirizzo_citta'], "ph" => "Milano"));
        array_push($fields, array("type" => "text", "name" => "indirizzo_pr", "label" => "Provincia di Residenza (SIGLA)", "value" => $gest->results[0]['indirizzo_pr'], "ph" => "MI"));

    }elseif($sec == "cont") {// campi contatto
        array_push($fields, array("type" => "text", "name" => "mail", "label" => "Indirizzo Mail", "value" => $gest->results[0]['mail'], "ph" => "mario.rossi@mail.it"));
        array_push($fields, array("type" => "text", "name" => "tel", "label" => "Telefono di riferimento", "value" => $gest->results[0]['tel'], "ph" => "+39 06 44 03"));

    }elseif($sec == "pwd") {// password
        array_push($fields, array("type" => "password", "name" => "pwd", "label" => "Password", "value" => $gest->results[0]['pwd']));

    }elseif($sec == "photo") {// foto tessera
        array_push($fields, array("type" => "file", "name" => "photo", "label" => "Foto Tessera", "mobile_hide" => true));
        if (is_null($gest->results[0]['photo'])) {
            if (!$gest->checkSuperUser()) $fields[count($fields) - 1]['required'] = true;
        } else {
            $fields[count($fields) - 1]['linkValue'] = "file/personal_photos/" . $gest->results[0]['photo'];
            $fields[count($fields) - 1]['linkText'] = "Consulta la foto caricata in precedenza ->";
        }
        if (!$gest->checkSuperUser()) {
            for ($i = 0; $i < count($fields); $i++) {
                if ($fields[$i]['name'] != "photo") $fields[$i]['required'] = true;
            }
        }
    }elseif ($sec == "op"){// dati operatività
        if ($gest->checkSuperUser()) {
            array_push($fields, array("type" => "select", "name" => "operativo", "label" => "Socio Operativo", "idField" => "value", "showElem" => ['label']));
            if ($gest->results[0]['operativo'] == 1) {
                $fields[count($fields) - 1]['values'] = [array("selected" => true, "value" => 1, "label" => "S&igrave;"), array("value" => 0, "label" => "No")];
            } else {
                $fields[count($fields) - 1]['values'] = [array("value" => 1, "label" => "S&igrave;"), array("selected" => true, "value" => 0, "label" => "No")];
            }

            array_push($fields, array("type" => "select", "name" => "master", "label" => "Utente Master", "idField" => "value", "showElem" => ['label']));
            if ($gest->results[0]['master'] == 1) {
                $fields[count($fields) - 1]['values'] = [array("selected" => true, "value" => 1, "label" => "S&igrave;"), array("value" => 0, "label" => "No")];
            } else {
                $fields[count($fields) - 1]['values'] = [array("value" => 1, "label" => "S&igrave;"), array("selected" => true, "value" => 0, "label" => "No")];
            }
            array_push($fields, array("type" => "text", "name" => "numero_socio", "label" => "Numero Socio", "value" => $gest->results[0]['numero_socio'], "ph" => "22"));
        }
    }
    $buttons = [array("action" => "submit", "value" => "Aggiorna Dati")];
    $hidden = [array("name" => "id", "value" => $id)];
    printForm(true, "crud_user.php?action=save&id=".$id."&sec=".$sec , "post", $fields, $hidden, $buttons);
}else{
    extract($_POST);
    extract($_FILES);
    $gest->updateUserData($sec, $id);
    $url = "home.php";
    if(isset($_GET['o'])){
        $url = "view_user.php?id=$id&o=".$_GET['o'];
    }
    $buttons = [array("url"=>$url, "value"=>"Torna Indietro", "class"=>"info")];
    printAlert("success", "Account aggiornato con successo!", $buttons);
}


//todo: Art39

    ?>
</div>
