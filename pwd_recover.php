<?php
extract($_GET);
extract($_POST);
require_once "inc/head-nm.php";
$gest = new Gest();?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $asso_ext_name; ?>Gestionale Volontari</title>
        <link rel="stylesheet" href="css/style.0.1.css">
    </head>
<body>
<?php
if(!isset($action)){
    ?>
    <form action="pwd_recover.php?action=check" method="post">
        <div class="form-group">
            <h1><?php echo $asso_ext_name; ?>Gestionale Volontari - Recupero Password</h1>
            <input type="email" name="mail" id="mail" class="form-control input-index" placeholder="mail: m.rossi@gmail.com" required>
            <input type="submit" value="Recupera" class="btn btn-info btn-index">
            <a class="btn btn-info" href="login.php">Accedi!</a>
        </div>
    </form>

    <?php
}elseif ($action == "check") {
    $check = $gest->checkRecover($mail);
    if ($check==0) {
        echo "<div class='alert alert-danger' role='alert'>L'&apos;indirizzo email indicato non risulta nella banca dati!  <br>Per favore verifica nuovamente o contatta l&apos;associazione. <br> <a href='pwd_recover.php'>Indietro</a></div>";
    } else {
        $gest->recoverPwd($mail);
        echo "<div class='alert alert-success' role='alert'>Congratulazioni!<br />La tua password e nome utente sono stati inviati alla mail indicata! <br />
<a href='index.php'>Indietro</a></div>";
    }
}