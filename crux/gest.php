<?php
require_once "connection.php";



class Gest extends MultiFunction {
    //tools
    public function checkSuperUser(){
        if($_SESSION['adm'] || $_SESSION['master']) return true;
        elseif (!$_SESSION['adm'] && !$_SESSION['master']) return false;
        else return null;
    }
    public function addLog($message, $preuser = false){
        if($preuser) $message = $_SESSION['user']." ha ".$message;
        $v = ["timedate, evento", "NOW(), '".htmlspecialchars($message, ENT_QUOTES)."'"];
        $this->insert("logs", $v);
    }
    public function sendMail($to, $subject, $message){
        global $asso_name;
        $ret;

        $from = $asso_name.' No-Reply <no-reply@intranet.gianninocaria.it>';

// To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
        $headers .= 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

// Compose a simple HTML email message
        $msg = '<html><body><p>Questa mail &egrave; generata dal sistema. Per qualunque altra informazione contattare i responsabili dell&apos;associazione</p>';
        $msg .= $message;
        $msg = $msg.'</body></html>';

// Sending email
        //ToDo: correggere la generazione della url dei due alert seguenti
        if (mail($to, $subject, $msg, $headers)) {
            $ret = true;
        } else {
            $ret = false;
        }
        $this->addLog("inviato una mail con oggetto ".$subject." a ".$to, true);
        return $ret;

    }

    //Keys

    public function addKey($key, $content, $values){
        $this->insert("key_strings", ["key_string, content", "'$key', '$content'"]);
        if($content == "doc_models"){
            $this->insert("keys_doc_models", ["key_string, id_model, id_user", "'$key', ".$values['id_model'].", ".$values['id_user']]);
        }
        $this->addLog("generato la chiave ".$key." per ".$content, true);
    }

    public function keyRecover($key){
        $this->copy("key_strings", "bu_key_strings", "gen_date < DATE_ADD(NOW(), INTERVAL -3 MONTH)");
        $this->copy("keys_doc_models", "bu_keys_doc_models", "gen_date < DATE_ADD(NOW(), INTERVAL -3 MONTH)");
        $this->delete("key_strings", "gen_date < DATE_ADD(NOW(), INTERVAL -3 MONTH)");
        $this->delete("keys_doc_models", "gen_date < DATE_ADD(NOW(), INTERVAL -3 MONTH)");

        $this->select(true, "content", "key_strings", "key_string = '$key'");
        if($this->results[0]['content'] == "doc_models"){
            $this->select("true", "id_user, id_model", "keys_doc_models", "key_string = '$key'");
            extract($this->results[1]);
            $this->getDoc_model($id_model);
            extract($this->results[2]);
            printDoc_model(false, $titolo, $fixed_date, $giorno, $t1, $t2, $t3, $particella, $auto_user_data, $data_field, 0, $id_user, $pres_sign, $header_doc_model);
            echo"
            <script>
                $(document).ready(function(){
                    $('body').append($('<button>').addClass('d-print-none').addClass('btn').addClass('btn-info').css('position', 'fixed').css('top', '10px').css('left', '10px').text('Stampa').click(function (){
                        window.print()
                    }));                    
                });
            </script>
            ";
        }else{
            echo "<p>Spiacenti... link scaduto...</p>";
        }
    }
    public function getKeys($old = false){
        if(!$old) {
            $this->reset();
            $this->select(true, "*", "key_strings", null, "gen_date DESC");
        }else{
            $this->reset();
            $this->select(true, "*", "bu_key_strings", null, "gen_date DESC");
        }
        for($i = 0; $i<count($this->results); $i++){
            if($this->results[$i]['content'] == "doc_models") $this->results[$i]['content'] = "Dichiarazione Firmata";
        }
    }
    public function getKeys_doc_models($old = false){
        if(!$old) {
            $this->reset();
            $this->select(true, "*", "keys_doc_models", null, "gen_date DESC");
        }else{
            $this->reset();
            $this->select(true, "*", "bu_keys_doc_models", null, "gen_date DESC");
        }
        $this->subSelectOnField("titolo", "titolo", "doc_models", "id", "id_model");
        $this->subSelectOnField("user", "nome, cognome", "users", "id", "id_user");
        for($i = 0; $i<count($this->results); $i++){
            $this->results[$i]['utente'] = $this->results[$i]['user'][0]['cognome']." ".$this->results[$i]['user'][0]['nome'];
        }
    }

    //Registrazione
    public function checkRegister($mail, $user){
        $this->funct("id", "users", "COUNT", "mail = '$mail' OR usr = '$user'");
        return $this->results[0]['id'];
    }
    public function RegisterUser($nome, $cognome, $username, $password, $mail, $code){
        $nome = $this->setText(ucfirst(mb_strtolower($nome)));
        $cognome = $this->setText(mb_strtoupper($cognome));
        $v = [
            "nome, cognome, usr, pwd, mail, assocode",
            "'$nome', '$cognome', '$username', '$password', '$mail', '$code'"
        ];
        $this->insert("users", $v);
        $this->addLog("Aggiunta richiesta di iscrizione di $cognome $nome.");
        //Todo:invio mail conferma riassuntiva
    }

    //Recupero Password
    public function checkRecover($mail){
        $this->funct("id", "users", "COUNT", "mail = '$mail'");
        return $this->results[0]['id'];
    }

    public function recoverPwd($mail){
        global $asso_name;
        $this->reset();
        $this->select(true, "nome, cognome, usr, pwd", "users", "mail = '$mail'");
        $sub = "Recupero password da $asso_name";
        $text = "<p> Gentile <strong>".$this->results[0]['nome']." ".$this->results[0]['cognome']."</strong> la sua password &egrave; <strong><em>".$this->results[0]['pwd']."</em></strong> mentre il suo nome utente &egrave; <strong><em>".$this->results[0]['usr']."</em></strong></p>";
        $this->sendMail($mail, $sub, $text);
    }

    //Documenti
    public function collectDocuments_all($id=null){
        if(is_null($id)) $this->select( "both", "*", "type_document", "", "nome");
        else{
            $this->select( "both", "scadenza, numero, attach_front, attach_back, attach_master, id_document, id, rejected, abilis, cause", "link_document_users", "id_user=$id", "scadenza");
            $this->subSelectOnField("nome", "nome", "type_document", "id", "id_document");
        }
    }
    public function collectDocuments(){
        $this->select( true, "id, nome","type_document",null,"nome");
    }
    public function getDocumentData($id){
        $this->select( "both", "*", "type_document", "id=$id");
    }
    public function editDocuments($insert, $nome, $scadenza, $numero, $required, $require_attach_front, $require_attach_back, $id=null){
        $nome = $this->setText($nome);
        $nome = trim($nome);
        if(is_null($scadenza)) $scadenza = 0;
        else $scadenza = 1;
        if(is_null($numero)) $numero = 0;
        else $numero = 1;
        if(is_null($required)) $required = 0;
        else $required = 1;
        if(is_null($require_attach_front)) $require_attach_front = 0;
        else $require_attach_front = 1;
        if(is_null($require_attach_back)) $require_attach_back = 0;
        else $require_attach_back = 1;
        if($insert){
            $v = ["nome, scadenza, numero, required, require_attach_front, require_attach_back",
                "'$nome', $scadenza, $numero, $required, $require_attach_front, $require_attach_back"];
            $this->insert("type_document", $v);
            $this->addLog("aggiunto un nuovo tipo di documento.", true);
        }else{
            $c = ["nome = '$nome'", "scadenza = $scadenza", "required = $required", "require_attach_front = $require_attach_front", "require_attach_back = $require_attach_front"];
            $this->update("type_document", $c, "id=$id");
            $this->addLog("modificato il tipo di documento con id = $id.", true);
        }

    }
    public function delDocument($id){
        $this->delete("type_document", "id = $id");
        $this->delete("link_document_users", "id_document = $id");
        $this->addLog("cancellato il tipo di documento con id = $id.", true);
    }
    public function getLinkDocumentData($id){
        $this->select( "both", "*", "link_document_users", "id=$id");
        $this->subSelectOnField("data", "scadenza, numero, require_attach_front, require_attach_back", "type_document", "id", "id_document");
    }
    public function editLinkDocument($insert, $scadenza, $numero, $attach_front, $attach_back, $attach_master, $id = null, $user_id = null, $approved = true){
        $load_front = false;
        $load_back = false;
        $load_numero = false;
        $load_scadenza = false;
        $load_master = false;
        if(strlen($attach_front['name'])>0) $load_front = true;
        if(strlen($attach_back['name'])>0) $load_back = true;
        if(strlen($attach_master['name'])>0) $load_master = true;
        if(strlen($scadenza)>0) $load_scadenza = true;
        if(strlen($numero)>0) $load_numero = true;

        if($insert){
            $v = ["id_document", "$id"];
            $v[0] .= ", id_user";
            if($user_id !=null) $ui = ", $user_id";
            else $ui = ", ".$_SESSION['user_id'];
            $v[1] .= $ui;

            $check = null;
            if($approved){
                $v[0] .= ", abilis";
                $v[1] .= ", 1";
            }
            if($load_scadenza){
                $v[0] .= ", scadenza";
                $v[1] .= ", '$scadenza'";
            }
            if($load_numero){
                $v[0] .= ", numero";
                $v[1] .= ", '$numero'";
            }
            if($load_front){
                $attach_front['name'] = $this->keyFileName($attach_front['name'],"link_document_users", "attach_front");
                $v[0] .= ", attach_front";
                $v[1] .= ", '".$attach_front['name']."'";
                $check = $this->uploadFile(false, "file/document/", "attach_front", "link_document_users", "attach_front", $attach_front['name']);
            }
            if($load_back){
                $attach_back['name'] = $this->keyFileName($attach_back['name'],"link_document_users", "attach_back");
                $v[0] .= ", attach_back";
                $v[1] .= ", '".$attach_back['name']."'";
                $check = $this->uploadFile(false, "file/document/", "attach_back", "link_document_users", "attach_back", $attach_back['name']);
            }
            if($load_master){
                $attach_master['name'] = $this->keyFileName($attach_master['name'],"link_document_users", "attach_master");
                $v[0] .= ", attach_master";
                $v[1] .= ", '".$attach_master['name']."'";
                $check = $this->uploadFile(false, "file/document/", "attach_master", "link_document_users", "attach_master", $attach_master['name']);
            }
            if(count($check['errori'])>0){
                return $check;
            }else {
                $this->insert("link_document_users", $v);
                $this->addLog("aggiunto un nuovo documento all'utente $ui.", true);
                return null;
            }
        }else{
            $c = [];
            $check = null;
            if($approved) array_push($c, "abilis = 1");
            else array_push($c, "abilis = 0", "rejected = 0");
            if($load_scadenza) array_push($c, "scadenza = '$scadenza'");
            if($load_numero) array_push($c, "numero = '$numero'");
            if($load_front){
                $attach_front['name'] = $this->keyFileName($attach_front['name'],"link_document_users", "attach_front");
                array_push($c, "attach_front = '".$attach_front['name']."'");
                if($id != null) $this->select(true, "attach_front", "link_document_users", "id=$id");
                $check = $this->uploadFile(true, "file/document/", "attach_front", "link_document_users", "attach_front", $attach_front['name'], $this->results[0]['attach_front']);
                $this->reset();
            }
            if($load_back){
                $attach_back['name'] = $this->keyFileName($attach_back['name'], "link_document_users", "attach_back");
                array_push($c, "attach_back = '".$attach_back['name']."'");
                if($id != null) $this->select(true, "attach_back", "link_document_users", "id=$id");
                $check = $this->uploadFile(true, "file/document/", "attach_back", "link_document_users", "attach_back", $attach_back['name'], $this->results[0]['attach_back']);
                $this->reset();
            }
            if($load_master){
                $attach_master['name'] = $this->keyFileName($attach_master['name'], "link_document_users", "attach_master");
                array_push($c, "attach_master = '".$attach_master['name']."'");
                if($id != null) $this->select(true, "attach_master", "link_document_users", "id=$id");
                $check = $this->uploadFile(true, "file/document/", "attach_master", "link_document_users", "attach_master", $attach_master['name'], $this->results[0]['attach_master']);
                $this->reset();
            }
            if(count($check['errori'])>0){
                return $check;
            }else{
                $this->update("link_document_users", $c, "id=$id");
                $this->addLog("modificato il documento con id = $id", true);
                return null;
            }

        }

    }
    public function delLinkDocument($id){
        $this->select(true, "attach_front,attach_back", "link_document_users", "id = $id");
        if(isset($this->results[0]['attach_front'])) unlink("file/document/".$this->results[0]['attach_front']);
        if(isset($this->results[0]['attach_back']))  unlink("file/document/".$this->results[0]['attach_back']);
        $this->delete("link_document_users", "id = $id");
        $this->addLog("cancellato il documento con id = $id.", true);
    }
    public function buildDocumentMatrix(){
        $ret = [];
        $ret['head'] = [];
        $ret['order'] = [];
        $body = [];
        $documents = array();
        $this->select(true, "id, nome, cognome", "users", "abilis = 1", "cognome, nome");
        $body = $this->results;
        $this->reset();

        for($i = 0; $i<count($body); $i++){
            $this->select(true, "id, id_document, attach_front, attach_back, scadenza, numero, attach_master", "link_document_users", "id_user = ".$body[$i]['id'], "scadenza");

            $body[$i]['documents'] = $this->results;
            $this->reset();
            for($j = 0; $j<count($body[$i]['documents']); $j++){
                $this->select(true, "nome", "type_document", "id = ".$body[$i]['documents'][$j]['id_document']);
                $body[$i][$this->results[0]['nome']] = null;
                if(isset($body[$i]['documents'][$j]['scadenza'])) $body[$i][$this->results[0]['nome']] .= "Scadenza: ".printField($body[$i]['documents'][$j]['scadenza'])."<br />";
                if(isset($body[$i]['documents'][$j]['numero'])) $body[$i][$this->results[0]['nome']] .= "Numero: ".printField($body[$i]['documents'][$j]['numero'])."<br />";
                if(isset($body[$i]['documents'][$j]['attach_front'])) $body[$i][$this->results[0]['nome']] .= "Fronte: ".printField($body[$i]['documents'][$j]['attach_front'], "file/document/")."<br />";
                if(isset($body[$i]['documents'][$j]['attach_back'])) $body[$i][$this->results[0]['nome']] .= "Retro: ".printField($body[$i]['documents'][$j]['attach_back'], "file/document/")."<br />";
                if(isset($body[$i]['documents'][$j]['attach_master'])) $body[$i][$this->results[0]['nome']] .= "Master: ".printField($body[$i]['documents'][$j]['attach_master'], "file/document/")."<br />";
                $body[$i][$this->results[0]['nome']] .= "<a href='crud_document.php?action=edit&id=".$body[$i]['documents'][$j]['id']."&o=anag'>Modifica</a>/<a href='crud_document.php?action=del&id=".$body[$i]['documents'][$j]['id']."&o=anag'>Cancella</a>";

                if(!in_array($this->results[0]['nome'], $ret['head'])) array_push($ret['head'], $this->results[0]['nome']);
                if(!in_array($this->results[0]['nome'], $ret['order'])) array_push($ret['order'], $this->results[0]['nome']);
                if(!in_array($this->results[0]['nome'], $documents)) array_push($documents, $this->results[0]['nome']);
                $this->reset();
            }
        }
        for($i = 0; $i<count($body); $i++){
            for($j = 0; $j<count($documents); $j++){
                if(!isset($body[$i][$documents[$j]])) $body[$i][$documents[$j]] = null;
            }
        }
        sort($ret['order']);
        array_unshift($ret['order'], "cognome", "nome");
        sort($ret['head']);
        array_unshift($ret['head'], "Cognome", "Nome");
        $ret['body'] = $body;
        $ret['subFields'] = $documents;
        return $ret;
    }
    public function checkPendingDocuments(){
        $this->reset();
        $this->funct("id", "link_document_users", "COUNT", "abilis = 0 and rejected = 0");
        if($this->results[0]['id']>0) {
            $this->reset();
            return true;
        }else{
            $this->reset();
            return false;
        }
    }
    public function printPendingDocuments(){
        echo '<div class="card bg-light" style="width: 18rem;">
        <div class="card-header"><h5>Documenti</h5></div>
        <div class="card-body bg-light">
            <h6 class="card-title">I seguenti documenti sono in attesa di approvazione.</h6>
            <div class="list-group">';
        $this->select(true, "id, id_user, id_document", "link_document_users", "abilis = 0 and rejected = 0", "scadenza");
        $this->subSelectOnField("document", "nome", "type_document", "id", "id_document");
        $this->subSelectOnField("user", "cognome, nome", "users", "id", "id_user");
        echo "<ul class='list-group bg-light'>";
        for($i = 0; $i<count($this->results); $i++){
            echo "<li class='list-group-item'>";
            echo "<strong>".$this->results[$i]['document'][0]['nome']." di ".$this->results[$i]['user'][0]['cognome']." ".$this->results[$i]['user'][0]['nome']."</strong>";
            echo "<br /><div class='btn-group' role='group'>";
            echo "<a href='check_document.php?id=".$this->results[$i]['id']."&action=eval'><button type='button' class='btn btn-info'>Valuta</button></a> ";
            echo "</div>";
            echo "</li>";
        }
        echo "</ul>";
        echo "</div></div></div>";
    }
    public function approveDocument($id){
        extract($_POST);
        extract($_FILES);

        if(strlen($attach_master['name'])==0) $attach_master['name'] == null;
        else{
            $this->reset();
            $this->select(true, "attach_master", "link_document_users", "id=$id");
            if($this->results[0]['attach_master'] == null) $edit = false;
            else $edit = true;
            $newName = $this->keyFileName($attach_front['name'], "link_document_users", "attach_master");
            $check = $this->uploadFile($edit, "file/document/", "attach_master", "link_document_users", "attach_master", $newName, $this->results[0]['attach_master']);
        }
        if (!isset($check['errori'])) {
            $buttons = [array("value" => "Torna Indietro", "url" => "check_document.php", "class"=>"info")];
            printAlert("success", "Complimenti il documento &egrave; stato aggiornato con successo!", $buttons);
            $c = ['abilis = 1'];
            if($attach_master['name'] != null) array_push($c, 'attach_master = "'.$attach_master['name'].'"');
            $this->update("link_document_users", $c, "id=$id");
            $this->addLog("approvato il documento con id = $id.", true);
        }else {
            $message = "<span class='left'>" . $check['text'];
            for ($i = 0; $i < count($check['errori']); $i++) {
                $message .= "<br /> - " . $check['errori'][$i];
            }
            $message .= "</span>";
            $buttons = [array("value" => "Torna Indietro", "class" => "danger", "url" => "check_document.php?id=$id&action=eval")];
            printAlert("danger", $message, $buttons, "left");
        }
    }
    public function refuseDocument($id, $cause){
        $cause = $this->setText($cause);
        $c = ['rejected = 1', "cause = '$cause'"];
        $this->update("link_document_users", $c, "id = $id");
        $this->addLog("rigettato il documento con causale: '$cause'.", true);
    }

    //UTENTI
    public function checkPendingUsers(){
        $this->reset();
        $this->funct("id", "users", "COUNT", "abilis = 0 and dimesso = 0");
        if($this->results[0]['id']>0) {
            $this->reset();
            return true;
        }else{
            $this->reset();
            return false;
        }
    }
    public function printPendingUsers(){
        echo '<div class="card text-white bg-dark" style="width: 18rem;">
        <div class="card-header border-success"><h5>Utenti</h5></div>
        <div class="card-body border-success">
            <h6 class="card-title">I seguenti utenti sono in attesa di approvazione.</h6>
            <div class="list-group">';
        $this->select(true, "id, cognome, nome, mail", "users", "abilis = 0 and dimesso = 0", "cognome, nome");
        echo "<ul class='list-group'>";
        for($i = 0; $i<count($this->results); $i++){
            echo "<li class='list-group-item text-white bg-dark border-success'>";
            echo "<strong>".$this->results[$i]['cognome']." ".$this->results[$i]['nome']."</strong>";
            echo "<br /><div class='btn-group' role='group'>";
            echo "<a href='check_users.php?id=".$this->results[$i]['id']."&action=y'><button type='button' class='btn btn-info'>Conferma</button></a> ";
            echo "<a href='check_users.php?id=".$this->results[$i]['id']."&action=n'><button type='button' class='btn btn-danger'>Rifiuta</button></a>";
            echo "</div>";
            echo "</li>";
        }
        echo "</ul>";
        echo "</div></div></div>";
    }
    public function checkEditedUsers(){
        $this->reset();
        $this->funct("id", "users", "COUNT", "abilis = 1 and updated = 1");
        if($this->results[0]['id']>0) {
            $this->reset();
            return true;
        }else{
            $this->reset();
            return false;
        }
    }
    public function printEditedUsers(){
        echo '<div class="card  bg-light" style="width: 18rem;">
        <div class="card-header bg-warning border-warning"><h5>Utenti Aggiornati</h5></div>
        <div class="card-body border-warning">
            <h6 class="card-title">I seguenti utenti hanno eseguito una modifica alla propria anagrafica.</h6>
            <div class="list-group">';
        $this->select(true, "id, cognome, nome, mail", "users", "abilis = 1 and updated = 1", "cognome, nome");
        echo "<ul class='list-group'>";
        for($i = 0; $i<count($this->results); $i++){
            echo "<li class='list-group-item bg-light border-warning'>";
            echo "<strong>".$this->results[$i]['cognome']." ".$this->results[$i]['nome']."</strong>";
            echo "<br /><div class='btn-group' role='group'>";
            echo "<a href='crud_user.php?id=".$this->results[$i]['id']."&action=check_edit'><button type='button' class='btn btn-info'>Visualizza</button></a> ";
            echo "</div>";
            echo "</li>";
        }
        echo "</ul>";
        echo "</div></div></div>";
    }
    public function checkUserData($id){
        $this->reset();
        $this->select(true, "photo, tel, CF, nascita, nascita_pr, nascita_citta, indirizzo, indirizzo_cap, indirizzo_citta, indirizzo_pr", "users", "id = $id");
        $check = true;
        foreach($this->results[0] as $value){
            if(is_null($value)) $check = false;
        }
        return $check;
    }
    public function printUserAlert(){
        echo '<div class="card bg-dark text-center" style="width: 18rem;">
        <div class="card-header bg-warning border-warning"><h5>Attenzione!</h5></div>
        <div class="card-body text-white border-warning">
        <br />
            <h6 class="card-title">Sembra manchino dei dati nella tua anagrafica. Per favore provvedi il prima possibile!</h6><br />
            <a href="crud_user.php"><button type="button" class="btn btn-warning">Aggiorna i tuoi dati</button></a>';
        echo "</div></div></div>";
    }
    public function getUserData($id = null, $operativo = null, $dimesso = null){
        if($id != null) $this->select(true, "*", "users", "id = $id");
        elseif($operativo == true) $this->select(true, "*", "users", "abilis = 1 and operativo = 1", "cognome, nome");
        elseif($operativo === false) $this->select(true, "*", "users", "abilis = 1 and operativo = 0", "cognome, nome");
        elseif($dimesso == true ) $this->select(true, "*", "users", "abilis = 0 and dimesso = 1", "cognome, nome");
        else $this->select(true, "*", "users", "abilis = 1 and dimesso = 0", "cognome, nome");
        for($i = 0; $i<count($this->results); $i++){
            $this->subSelectOnField("iscrizione", "MAX(giorno) AS giorno", "registro_iscrizioni", "id_user", "id", null, null, "and ingresso = 1");

            $this->subSelectOnField("dimissione", "MAX(giorno) AS giorno", "registro_iscrizioni", "id_user", "id", null, null, "and ingresso = 0");
        }
    }
    public function updateUserData($sec){
        if($sec == "anag"){
            global $CF, $nascita_citta, $nascita_pr, $nascita, $nome, $cognome;
            $CF = $this->setText(mb_strtoupper($CF));
            $nascita_citta = $this->setText(mb_strtoupper($nascita_citta));
            $nascita_pr = $this->setText(mb_strtoupper($nascita_pr));
            if(isset($nome)) $nome = $this->setText(ucfirst(mb_strtolower($nome)));
            if(isset($cognome)) $cognome = $this->setText(mb_strtoupper($cognome));

            if(strlen($CF) == 0) $CF = "NULL"; else $CF = "'".$CF."'";
            if(strlen($nascita_citta) == 0) $nascita_citta = "NULL"; else $nascita_citta = "'".$nascita_citta."'";
            if(strlen($nascita_pr) == 0) $nascita_pr = "NULL"; else $nascita_pr = "'".$nascita_pr."'";
            if(strlen($nascita) == 0) $nascita = "NULL"; else $nascita = "'".$nascita."'";
            if(isset($nome)) if(strlen($nome) == 0) $nome = "NULL"; else $nome = "'".$nome."'";
            if(isset($cognome)) if(strlen($cognome) == 0) $cognome = "NULL"; else $cognome = "'".$cognome."'";

            $c = [
                "CF = $CF",
                "nascita = $nascita",
                "nascita_citta = $nascita_citta",
                "nascita_pr = $nascita_pr"
            ];
            if(isset($nome)) array_push($c, "nome = $nome");
            if(isset($cognome)) array_push($c,"cognome = $cognome");

        }elseif ($sec == "resdom"){
            global $indirizzo, $indirizzo_cap, $indirizzo_citta, $indirizzo_pr;
            $indirizzo = $this->setText(mb_strtoupper($indirizzo));
            $indirizzo_citta = $this->setText(mb_strtoupper($indirizzo_citta));
            $indirizzo_pr = $this->setText(mb_strtoupper($indirizzo_pr));

            if(strlen($indirizzo) == 0) $indirizzo = "NULL"; else $indirizzo = "'".$indirizzo."'";
            if(strlen($indirizzo_citta) == 0) $indirizzo_citta = "NULL"; else $indirizzo_citta = "'".$indirizzo_citta."'";
            if(strlen($indirizzo_pr) == 0) $indirizzo_pr = "NULL"; else $indirizzo_pr = "'".$indirizzo_pr."'";
            if(strlen($indirizzo_cap) == 0) $indirizzo_cap = "NULL"; else $indirizzo_cap = "'".$indirizzo_cap."'";

            $c = [
                "indirizzo = $indirizzo",
                "indirizzo_cap = $indirizzo_cap",
                "indirizzo_citta = $indirizzo_citta",
                "indirizzo_pr = $indirizzo_pr"
            ];
        }elseif ($sec == "cont"){
            global $tel, $mail;
            //TODO: aggiungere splitter per telefono verificando lo 0 iniziale per i fissi, con verifica del +39
            $mail = $this->setText($mail);
            if(strlen($tel) == 0) $tel = "NULL"; else $tel = "'".$tel."'";
            if(strlen($mail) == 0) $mail = "NULL"; else $mail = "'".$mail."'";
            $c = [
                "mail = $mail",
                "tel = $tel"
            ];
        } //TODO: aggiungere le restanti sezioni


        if(strlen($pwd) == 0) $pwd = "NULL"; else $pwd = "'".$pwd."'";
        if(strlen($numero_socio) == 0) $numero_socio = "NULL"; else $numero_socio = "'".$numero_socio."'";



        $c = [
            "mail = $mail",
            "pwd = $pwd",
            "tel = $tel",
            "CF = $CF",
            "nascita = $nascita",
            "nascita_citta = $nascita_citta",
            "nascita_pr = $nascita_pr",
            "indirizzo = $indirizzo",
            "indirizzo_cap = $indirizzo_cap",
            "indirizzo_citta = $indirizzo_citta",
            "indirizzo_pr = $indirizzo_pr"
        ];
        if(isset($nome)) array_push($c, "nome = $nome");
        if(isset($cognome)) array_push($c,"cognome = $cognome");
        if(isset($operativo)) array_push($c,"operativo = $operativo");
        if(isset($numero_socio)) array_push($c,"numero_socio = $numero_socio");
        if(!$this->checkSuperUser()){
            array_push($c,"updated = 1");
        }else{
            array_push($c,"updated = 0");
        }
        if(strlen($photo['name'])>0) {
            $this->select(true, "photo", "users", "id = $id");
            if (isset($this->results[0]['photo'])) $edit = true;
            else $edit = false;
            $oldName = $this->results[0]['photo'];
            $photo['name'] = $this->keyFileName($photo['name'],"users", "photo");
            $this->reset();
            array_push($c,"photo = '".$photo['name']."'");
        }
        if(isset($master)) array_push($c,"master = $master");
        $this->update("users", $c, "id = $id");
        if(strlen($photo['name'])>0) {
            $this->uploadFile($edit, "file/personal_photos/", "photo", "users", "photo", $photo['name'], $oldName);
        }
        $this->addLog("aggiornato i dati dell'utente $cognome $nome [$id].", true);
    }

    public function getPendingUsers(){
        $this->select(true, "id, cognome, nome, mail", "users", "abilis = 0 and dimesso = 0");
    }
    public function approveUser($id){
        global $domain;
        $c = ["abilis = 1"];
        $this->update("users", $c, "id = $id");
        $this->addLog("approvato l'utente con id = $id.", true);
        $this->select(true, "cognome, nome, mail", "users", "id = $id");
        $message = "
            <p> Caro/a ".$this->results[0]['cognome']." ".$this->results[0]['nome']."<br />Ti inviamo la presente per confermarti che la tua registrazione &egrave; stata accettata!</p>
            <p>Per accedere vai all&apos;indirizzo <a href='http://intranet.$domain/' target='_blank'>http://intranet.$domain/</a> ed inserisci le credenziali precedentemente inserite alla registrazione.</p>
        ";
        $this->sendMail($this->results[0]['mail'], "Approvazione Utente", $message);
    }
    public function refuseUser($id, $cause){
        global $domain, $ref_mail;
        $this->select(true, "cognome, nome, mail", "users", "id = $id");
        $message = "
            <p> Caro/a ".$this->results[0]['cognome']." ".$this->results[0]['nome']."<br />Ti inviamo la presente per comunicarti che la tua registrazione NON &egrave; stata accettata!</p>
            <p>La motivazione &egrave; la seguente: $cause</p>
            <p>Ci scusiamo per l&apos;inconveniente. Per qualunque cosa contattaci a $ref_mail@$domain</p>
        ";
        $this->sendMail($this->results[0]['mail'], "Rigetto Richiesta iscrizione Utente", $message);
        $this->delete("users", "id = $id");
        $this->addLog("rigettato l'utente con id = $id, con causale: '$cause'.", true);

    }

    //registro
    public function getRegistro(){
        $this->select(true, "*", "registro_iscrizioni", null, "giorno DESC");
        $this->subSelectOnField("user", "cognome, nome", "users", "id", "id_user");
        return $this->retResults();
    }
    public function addRegistro($giorno, $iscrizione, $user){
        $v= ['giorno, ingresso, id_user', "'$giorno', $iscrizione, $user"];
        $this->insert("registro_iscrizioni", $v);
        $this->reset();
        $this->select(true, "ingresso", "registro_iscrizioni", "giorno = (SELECT MAX(giorno) AS giorno FROM registro_iscrizioni WHERE id_user = $user) and id_user = $user", null, "1");
        if($this->results[0]['ingresso']) $dim = 0;
        else $dim = 1;
        $c = ["dimesso = $dim", "abilis = ".$this->results[0]['ingresso']];
        if($dim == 1) array_push($c, "operativo = 0");
        $this->update("users", $c, "id = $user");
        $this->addLog("ha aggiunto una nota al registro sull'utente con id = $user", true);
    }
    public function updateRegistro($giorno, $iscrizione, $id){
        $c = ["giorno = '$giorno'", "ingresso = $iscrizione"];
        $this->update("registro_iscrizioni", $c, "id = $id");
        $this->reset();
        $this->select(true, "id_user", "registro_iscrizioni", "id = $id");
        $user = $this->results[0]['id_user'];
        $this->reset();
        $this->select(true, "ingresso", "registro_iscrizioni", "giorno = (SELECT MAX(giorno) AS giorno FROM registro_iscrizioni WHERE id_user = $user) and id_user = $user", null, "1");
        if($this->results[0]['ingresso']) $dim = 0;
        else $dim = 1;
        $c = ["dimesso = $dim", "abilis = ".$this->results[0]['ingresso']];
        if($dim == 1) array_push($c, "operativo = 0");
        $this->update("users", $c, "id = $user");
        $this->addLog("ha modificato la nota del registro con id = $id", true);

    }
    public function deleteRegistro($id){
        $this->select(true, "id_user", "registro_iscrizioni", "id = $id");
        $user = $this->results[0]['id_user'];
        $this->reset();
        $this->select(true, "ingresso", "registro_iscrizioni", "giorno = (SELECT MAX(giorno) AS giorno FROM registro_iscrizioni WHERE id_user = $user) and id_user = $user", null, "1");
        if($this->results[0]['ingresso']) $dim = 0;
        else $dim = 1;
        $this->update("users", ["dimesso = ".$this->results[0]['ingresso'], "abilis = $dim"], "id = $user");
        $this->reset();
        $this->delete("registro_iscrizioni", "id = $id");
        $this->addLog("ha cancellato la nota con id = $id", true);
    }

    //modelli di dichiarazione
    public function addDoc_model($nome, $t1, $t2, $t3, $particella, $fixed_date, $giorno, $auto_user_data, $data_field, $access_level, $pres_sign, $header_doc_model){
        $nome = $this->setText($nome);
        $t1 = $this->setText($t1);
        $t2 = $this->setText($t2);
        $t3 = $this->setText($t3);
        $particella = $this->setText($particella);
        $data_field = $this->setText($data_field);
        $v = [
            "titolo, t1, t2, t3, particella, auto_user_data, data_field, fixed_date, giorno, access_level, pres_sign, header_doc_model",
            "'$nome', '$t1', '$t2', '$t3', '$particella', $auto_user_data, '$data_field', $fixed_date, '$giorno', $access_level, $pres_sign, '$header_doc_model'"
        ];
        $this->insert("doc_models", $v);
        $this->addLog("aggiunto un modello dal titolo ".$nome, true);
    }
    public function getDoc_model($id = null, $access_level = null){
        if(isset($id)) {
            $this->select(true, "*", "doc_models", "id = $id");
        }elseif (isset($access_level)){
            $cond = "access_level = 2";
            if($access_level == 1)  $cond .= " or access_level = 1";
            $this->select(true, "id, titolo, fixed_date, auto_user_data, access_level, pres_sign", "doc_models", $cond, "titolo");
        }else{
            $this->select(true, "id, titolo, fixed_date, auto_user_data, access_level, pres_sign", "doc_models", null, "titolo");
        }
        for($i=0; $i<count($this->results); $i++){
            if($this->results[$i]['access_level'] == 0) $this->results[$i]['access_level'] = "<span class='null'>Solo Master</span>";
            if($this->results[$i]['access_level'] == 1) $this->results[$i]['access_level'] = "<span class='n'>Master e Operativi</span>";
            if($this->results[$i]['access_level'] == 2) $this->results[$i]['access_level'] = "<span class='y'>Tutti</span>";
        }
    }
    public function updateDoc_model($nome, $t1, $t2, $t3, $particella, $fixed_date, $giorno, $auto_user_data, $data_field, $access_level, $pres_sign, $header_doc_model){
        $id = $_GET['id'];
        $nome = $this->setText($nome);
        $t1 = $this->setText($t1);
        $t2 = $this->setText($t2);
        $t3 = $this->setText($t3);
        $particella = $this->setText($particella);
        $data_field = $this->setText($data_field);
        $c = [
            "titolo = '$nome'", "t1 = '$t1'", "t2 = '$t2'", "t3 = '$t3'", "particella = '$particella'", "auto_user_data = $auto_user_data", "data_field = '$data_field'", "fixed_date = $fixed_date", "giorno = '$giorno'", "access_level = $access_level", "pres_sign = $pres_sign", "header_doc_model = '$header_doc_model'"
        ];
        $this->update("doc_models", $c, "id = $id");
        $this->addLog("aggiornato il modello con id = ".$id, true);
    }
    public function mailDoc_model($key, $id_model, $id_user, $addToMail){
        global $asso_name;
        $this->reset();
        $this->getDoc_model($id_model);
        $this->getUserData($id_user);
        extract($this->results[0]);
        extract($this->results[1]);

        $message = "<p>Gentile ".$cognome." ".$nome."<br>La presente &egrave; per comunicarle che al seguente link potrà scaricare la Dichiarazione &quot;".$titolo."&quot; per lei redatta. (Scadenza tra 3 mesi)</p>";
        if(strlen($addToMail)>0) $message .= $addToMail;
        $message .= "<p><a href=http://intranet.gianninocaria.it/ext_key.php?key=$key>Scarica la Dichiarazione</a></p>";
        $message .= "<p>Ringraziandola per il lavoro che svolge ogni giorno per la nostra associazione<br>Lo Staff</p>";
        $ret = $this->sendMail($mail,"Invio Dichiarazione ".$titolo." da ".$asso_name, $message);
        if($ret){
            printAlert("success", "La mail &egrave stata inviata con successo!", [array("value"=>"Torna Indietro", "url"=>"print_doc_models.php?action=user&id_model=".$_GET['id_model'], "class"=>"info")]);
        }else{
            printAlert("danger", "Purtroppo qualcosa è andato storto, ti preghiamo di riprovare pi&ugrave; tardi", [array("value"=>"Torna Indietro", "url"=>"print_doc_models.php?action=user&id_model=".$_GET['id_model'], "class"=>"danger")]);
        }
    }
}