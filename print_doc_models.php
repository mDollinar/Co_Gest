<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";
extract($_GET);
if(!isset($id_model)) {
    ?>
    <br><h1>Scegli il modello da stampare</h1><br>
    <?php
    $gest->reset();
    $gest->getDoc_model();
    $addFields = ['<a href=print_doc_models.php?action=user&id_model=%id%>%select%</a>'];
    printTable("print_doc_models_choose_model", ['Titolo', 'Scegli il Documento'], $gest->results, "file", ['titolo'], null, $addFields);
}elseif(isset($id_model) && !isset($id_user)){
    ?>
    <br><h1>Scegli l'utente per cui stampare </h1><br>
    <?php
    $gest->reset();
    $gest->getUserData();
    $addFields = ["<a href=print_doc_models.php?action=user&id_model=$id_model&id_user=%id%>%print%</a>",
        "<a href=print_doc_models.php?action=mail&id_model=$id_model&id_user=%id%>%mail%</a>"];
    printTable("print_doc_models_choose_user", ['Cognome', 'Nome', 'Stampa'], $gest->results, "file", ['cognome', 'nome'], null, $addFields);
}elseif(isset($id_model) && isset($id_user)){
    if($action == "mail") {
        $gest->reset();
        $gest->getUserData($id_user);
        $gest->getDoc_model($id_model);
        extract($gest->results[0]);
        extract($gest->results[1]);
        ?>
        <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/ckeditor.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/translations/it.js"></script>`
        <br><h1>Invia &quot;<?php echo $titolo ?>&quot; per mail a <?php echo $cognome . " " . $nome ?> </h1><br>
        <form
            action='print_doc_models.php?action=sendMail&id_user=<?php echo $id_user; ?>&id_model=<?php echo $id_model; ?>' method='post' class='form-group'>
            <div class="container">
                <br>
                <h2>Vuoi Aggiungere qualcosa?</h2>
                <h4>In caso contrario lasciare il campo in bianco</h4>
                <textarea id="addToMail" name="addToMail"></textarea><br/>
                <input type="submit" value="Invia" class="btn btn-info">
            </div>
            <script>
                ClassicEditor
                    .create(document.querySelector('#addToMail'), {
                        toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
                        language: 'it'
                    })
                    .then(editor => {
                        console.log(editor);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            </script>
        <?php
    }elseif ($action == "sendMail"){
        extract($_POST);
        $key = $gest->keyGenerator("key_strings", "key_string");
        $v = array("id_model"=>$id_model, "id_user"=>$id_user);
        $gest->addKey($key, "doc_models", $v);
        $gest->reset();
        $gest->mailDoc_model($key, $id_model, $id_user, $addToMail);
    }else{
        $gest->reset();
        $gest->getDoc_model($id_model);
        extract($gest->results[0]);
        printDoc_model(0, $titolo, $fixed_date, $giorno, $t1, $t2, $t3, $particella, $auto_user_data, $data_field, null, $id_user, $pres_sign, $header_doc_model);
        $gest->addLog("visualizzato il modello con titolo ".$titolo." per l'utente ".$id_user, true);
    }
}