<?php
require_once "inc/head.php";
extract($_GET);
?>
<?php if($action == "edit"){
    $gest->getDocumentData($id);
?>
    <br />
    <h1>Aggiorna il Tipo di Documento</h1>
    <br />

    <form action="crud_document_type.php?action=update" method="post" class="form-group">
        <input type="hidden" value="<?php echo $gest->results[0]['id'] ?>" name="id">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Documento: </span>
            </div>
            <input type="text" class="form-control" placeholder="Nome Documento" aria-label="Username" aria-describedby="basic-addon1" required name="nome" value="<?php echo $gest->results[0]['nome']; ?>">
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="scadenza" value="true" name="scadenza" <?php if($gest->results[0]['scadenza'] == true) echo "checked"; ?>>
            <label class="form-check-label" for="scadenza">Ha una scadenza</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="numero" value="true" name="numero" <?php if($gest->results[0]['numero'] == true) echo "checked"; ?>>
            <label class="form-check-label" for="numero">Ha un numero identificativo</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="required" value="true" name="required" <?php if($gest->results[0]['required'] == true) echo "checked"; ?>>
            <label class="form-check-label" for="required">&Egrave; richiesto per tutti i soci</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="require_attach_front" value="true" name="require_attach_front" <?php if($gest->results[0]['require_attach_front'] == true) echo "checked"; ?>>
            <label class="form-check-label" for="require_attach_front">Richiede un&apos;allegato?</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="require_attach_back" value="true" name="require_attach_back" <?php if($gest->results[0]['require_attach_back'] == true) echo "checked"; ?>>
            <label class="form-check-label" for="require_attach_back">Richiede Fronte/Retro</label>
        </div>
        <br />
        <input type="submit" class="btn btn-info" value="SALVA">
    </form>

<?php }elseif($action == "add"){ ?>
    <br />
    <h1>Aggiungi un Tipo di Documento</h1>
    <br />
    <form action="crud_document_type.php?action=save" method="post" class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Documento: </span>
            </div>
            <input type="text" class="form-control" placeholder="Nome Documento" aria-label="Username" aria-describedby="basic-addon1" required name="nome">
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="scadenza" value="true" name="scadenza">
            <label class="form-check-label" for="scadenza">Ha una scadenza</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="numero" value="true" name="numero">
            <label class="form-check-label" for="numero">Ha un numero identificativo</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="required" value="true" name="required">
            <label class="form-check-label" for="required">&Egrave; richiesto per tutti i soci</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="require_attach_front" value="true" name="require_attach_front">
            <label class="form-check-label" for="require_attach_front">Richiede un&apos;allegato?</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" id="require_attach_back" name="require_attach_back" type="checkbox"
                   value="true">
            <label class="form-check-label" for="require_attach_back">Richiede Fronte/Retro</label>
        </div>
        <br />
        <input type="submit" class="btn btn-info" value="SALVA">
    </form>
<?php }elseif ($action=="save") {
extract($_POST);
$gest->editDocuments(true, $nome, $scadenza, $numero, $required, $require_attach_front, $require_attach_back);
?>
    <p class="alert alert-success">&quot;<?php echo $nome ?>&quot; &egrave; stato caricato con successo!<br />
        <a href="handle_document_type.php" class="btn btn-info">Torna Indietro</a>
    </p>
<?php }elseif ($action=="update") {
extract($_POST);
$gest->editDocuments(false, $nome, $scadenza, $numero, $required, $require_attach_front, $require_attach_back, $id);
?>
    <p class="alert alert-success">&quot;<?php echo $nome ?>&quot; &egrave; stato Aggiornato con successo!<br />
        <a href="handle_document_type.php" class="btn btn-info">Torna Indietro</a>
    </p>
<?php }elseif ($action=="del"){
$gest->delDocument($_GET['id']);
?>
    <p class="alert alert-danger">L&apos;elemento &egrave; stato Cancellato!<br />
        <a href="handle_document_type.php" class="btn btn-info">Torna Indietro</a>
    </p>
<?php } ?>