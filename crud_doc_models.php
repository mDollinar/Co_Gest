<?php
require_once "inc/head.php";
require_once "core/printFunctions.php";
extract($_GET);
if($action == "new"){
    ?>
    <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/translations/it.js"></script>`
    <br><h1>Aggiungi un nuovo Modello di Dichiarazione</h1><br>
    <div class="container">
        <form action='crud_doc_models.php?action=preview&save=1' method='post' class='form-group' target="_blank">
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">Chi può stampare la  Dichiarazione?</span></div>
                <select name="access_level" id="access_level" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="0">Solo Master</option>
                    <option value="1">Master & Operativi</option>
                    <option value="2">Tutti i Soci non dimessi</option>
                </select>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">La firma è di: </span></div>
                <select name="pres_sign" id="pres_sign" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="1">Presidente </option>
                    <option value="0">Utente </option></select>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">Quale Intestazione visualizzer&agrave;?</span></div>
                <select name="header_doc_model" id="header_doc_model" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <?php
                    for($i = 0; $i<count($doc_model_headings); $i++){
                        echo "<option value='".$doc_model_headings[$i]['file']."'>".$doc_model_headings[$i]['name']."</option>";
                    }
                    ?>>
                </select>
            </div>
            <div class='input-group mb-3'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'>Titolo del Modello</span>
                </div>
                <input type='text' class='form-control' name='titolo' id='titolo' placeholder="Modello fuffa da firmare" required>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">La data è fissa? </span></div>
                <select name="fixed_date" id="fixed_date" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="1">Sì </option>
                    <option value="0">No </option></select>
            </div>
            <div class='input-group mb-3'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'>Seleziona la data</span>
                </div>
                <input type='date' class='form-control' name='giorno' id='giorno' disabled>
            </div>
            <br><h2>Testo anteposto</h2><br>
            <textarea id="t1" name="t1"></textarea><br />
            <div class='input-group mb-3'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'>Particella Centrale</span>
                </div>
                <input type='text' class='form-control' name='particella' id='particella' placeholder="DICHIARA">
            </div>
            <br><h2>Testo posposto</h2><br>
            <textarea id="t2" name="t2"></textarea><br>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">Genera automaticamente i dati del Socio? </span></div>
                <select name="auto_user_data"  id="auto_user_data" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="1">Sì </option>
                    <option value="0">No </option></select>
            </div>
            <div id="data_field">
                <br><h2>Composizione Dati anagrafici</h2>
                <h6>%NOME% = Nome del Socio || %COGNOME% = Cognome del Socio || %CF% = Codice Fiscale || %NASCITA% = Data di nascita || %NASCITA_CITTA% = Citt&agrave; di nascita || %NASCITA_PR% = Provincia di nascita || %INDIRIZZO% = Indirizzo di residenza || %INDIRIZZO_CITTA% = Citt&agrave; di residenza || %INDIRIZZO_PR% = Provincia di residenza || %INDIRIZZO_CAP% = Codice avviamento postale di residenza || %N_SOCIO% = Numero di iscrizione || %TEL% = Numero di telefono || %MAIL% = Indirizzo e-mail</h6>
                <textarea id="data_field" name="data_field" ></textarea>
            </div>
            <br><h2>Testo conclusivo</h2><br>
            <textarea id="t3" name="t3"></textarea><br>
            <input type="submit" value="Salva" class="btn btn-info">
        </form>
    </div>
    <script>
        ClassicEditor
            .create( document.querySelector( '#t1' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector( '#t2' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector( '#t3' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector( 'textarea#data_field' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        $("div#data_field").hide();
        $("select#auto_user_data").change(function(){
            if(this.value == 0){
                $("div#data_field").show();
            }else{
                $("div#data_field").hide();
            }
        });
        $("select#fixed_date").change(function(){
            if(this.value == 1){
                $("input#giorno").removeAttr("disabled");
            }else{
                $("input#giorno").attr("disabled", true);
            }
        })
    </script>
<?php
}elseif($action == "preview"||$action == "previewUpdate"){
    extract($_POST);
    extract($_GET);
    if($save == 0){
        $gest->getDoc_model($id);
        extract($gest->results[0]);
    }
    if($auto_user_data == 1) $data_field = null;
    if($fixed_date == 0) $date = null;
    printDoc_model($save, $titolo, $fixed_date, $giorno, $t1, $t2, $t3, $particella, $auto_user_data, $data_field, $access_level, $_SESSION['user_id'], $pres_sign, $header_doc_model);
}elseif($action == "save"){
    extract($_POST);
    $gest->addDoc_model($titolo, $t1, $t2, $t3, $particella, $fixed_date, $date, $auto_user_data, $data_field, $access_level, $pres_sign, $header_doc_model);
    $buttons = [array("url"=>"handle_doc_models.php", "class"=>"info", "value"=>"Torna Indietro")];
    printAlert("success", "Complimenti, il Modello di Dichiarazione &egrave; stato caricato con successo!", $buttons);
}elseif($action == "edit"){
    $gest->getDoc_model($id);
    extract($gest->results[0]);
    ?>
    <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/translations/it.js"></script>`
    <br><h1>Aggiorna il modello di Dichiarazione</h1><br>
    <div class="container">
        <form action='crud_doc_models.php?action=previewUpdate&save=1&id=<?php echo $id; ?>' method='post' class='form-group' target="_blank">
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">Chi può stampare la Dichiarazione?</span></div>
                <select name="access_level" id="access_level" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="0" <?php if($access_level == 0) echo "selected" ?>>Solo Master</option>
                    <option value="1" <?php if($access_level == 1) echo "selected" ?>>Master & Operativi</option>
                    <option value="2" <?php if($access_level == 2) echo "selected" ?>>Tutti i Soci non dimessi</option>
                </select>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">La firma è di: </span></div>
                <select name="pres_sign" id="pres_sign" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="1" <?php if($pres_sign == 1) echo "selected" ?>>Presidente </option>
                    <option value="0" <?php if($pres_sign == 0) echo "selected" ?>>Utente </option></select>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">Quale Intestazione visualizzer&agrave;?</span></div>
                <select name="header_doc_model" id="header_doc_model" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <?php
                    for($i = 0; $i<count($doc_model_headings); $i++){
                        echo "<option value='".$doc_model_headings[$i]['file']."'";
                        if($header_doc_model == $doc_model_headings[$i]['file']) echo " selected";
                        echo ">".$doc_model_headings[$i]['name']."</option>";
                    }
                    ?>>
                </select>
            </div>
            <div class='input-group mb-3'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'>Titolo del Modello</span>
                </div>
                <input type='text' class='form-control' name='titolo' id='titolo' placeholder="Modello fuffa da firmare" required value="<?php echo $titolo; ?>">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">La data è fissa? </span></div>
                <select name="fixed_date" id="fixed_date" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="1" <?php if($fixed_date == 1) echo "selected" ?>>Sì </option>
                    <option value="0" <?php if($fixed_date == 0) echo "selected" ?>>No </option></select>
            </div>
            <div class='input-group mb-3'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'>Seleziona la data</span>
                </div>
                <input type='date' class='form-control' name='giorno' id='giorno'  <?php if($fixed_date == 0) echo "disabled" ?> value="<?php echo $giorno; ?>">
            </div>
            <br><h2>Testo anteposto</h2><br>
            <textarea id="t1" name="t1"><?php echo $t1; ?></textarea><br />
            <div class='input-group mb-3'>
                <div class='input-group-prepend'>
                    <span class='input-group-text'>Particella Centrale</span>
                </div>
                <input type='text' class='form-control' name='particella' id='particella' placeholder="DICHIARA" value="<?php echo $particella; ?>">
            </div>
            <br><h2>Testo posposto</h2><br>
            <textarea id="t2" name="t2"><?php echo $t2; ?></textarea><br>
            <div class="input-group mb-3">
                <div class="input-group-prepend"><span class="input-group-text">Genera automaticamente i dati del Socio? </span></div>
                <select name="auto_user_data"  id="auto_user_data" class="custom-select" required>
                    <option value="">Seleziona...</option>
                    <option value="1" <?php if($auto_user_data == 1) echo "selected" ?>>Sì </option>
                    <option value="0" <?php if($auto_user_data == 0) echo "selected" ?>>No </option></select>
            </div>
            <div id="data_field">
                <br><h2>Composizione Dati anagrafici</h2>
                <h6>%NOME% = Nome del Socio || %COGNOME% = Cognome del Socio || %CF% = Codice Fiscale || %NASCITA% = Data di nascita || %NASCITA_CITTA% = Citt&agrave; di nascita || %NASCITA_PR% = Provincia di nascita || %INDIRIZZO% = Indirizzo di residenza || %INDIRIZZO_CITTA% = Citt&agrave; di residenza || %INDIRIZZO_PR% = Provincia di residenza || %INDIRIZZO_CAP% = Codice avviamento postale di residenza || %N_SOCIO% = Numero di iscrizione || %TEL% = Numero di telefono || %MAIL% = Indirizzo e-mail</h6>
                <textarea id="data_field" name="data_field" ><?php echo $data_field; ?></textarea>
            </div>
            <br><h2>Testo conclusivo</h2><br>
            <textarea id="t3" name="t3"><?php echo $t3; ?></textarea><br>
            <input type="submit" value="Salva" class="btn btn-info">
        </form>
    </div>
    <script>
        ClassicEditor
            .create( document.querySelector( '#t1' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector( '#t2' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector( '#t3' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector( 'textarea#data_field' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                language: 'it'
            } )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
        $("select#auto_user_data").change(function(){
            if(this.value == 0){
                $("div#data_field").show();
            }else{
                $("div#data_field").hide();
            }
        });
        $("select#fixed_date").change(function(){
            if(this.value == 1){
                $("input#giorno").removeAttr("disabled");
            }else{
                $("input#giorno").attr("disabled", true);
            }
        })
    </script>
<?php
    if($auto_user_data == 1){
        echo '<script>$("div#data_field").hide();</script>';
    }
}elseif($action == "update") {
    extract($_POST);
    $gest->updateDoc_model($titolo, $t1, $t2, $t3, $particella, $fixed_date, $date, $auto_user_data, $data_field, $access_level, $pres_sign, $header_doc_model);
    $buttons = [array("url" => "handle_doc_models.php", "class" => "info", "value" => "Torna Indietro")];
    printAlert("success", "Complimenti, il Modello di Dichiarazione &egrave; stato aggiornato con successo!", $buttons);
}