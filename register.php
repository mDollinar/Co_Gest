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
        <form action="register.php?action=check" method="post">
        <div class="form-group">
            <h1><?php echo $asso_ext_name; ?>Gestionale Volontari - Registrazione</h1>
            <?php if($multiplatform == true){?>
                <input type="text" name="code" id="code" class="form-control input-index" placeholder="Codice Associazione: AGOV-001" required>
            <?php }else{?>
                <input type="hidden" value="<?php echo $default_asso_code; ?>" name="code" id="code" />
            <?php } ?>
            <input type="text" name="nome" id="nome" class="form-control input-index" placeholder="Nome: Marco" required>
            <input type="text" name="cognome" id="nome" class="form-control input-index" placeholder="Cognome: Rossi" required>
            <input type="text" name="myusername" id="myusername" class="form-control input-index" placeholder="UserName: m.rossi" required>
            <input type="password" name="mypassword" id="mypassword" class="form-control input-index" placeholder="Password" required>
            <input type="email" name="mail" id="mail" class="form-control input-index" placeholder="mail: m.rossi@gmail.com" required>
            <input type="submit" value="Registrati" class="btn btn-info btn-index">
            <a class="btn btn-info" href="login.php">Accedi!</a>
        </div>
        </form>

        <?php
    }elseif ($action == "check") {
        $check = $gest->checkRegister($mail, $myusername);
        if ($check>0) {
            echo "<div class='alert alert-danger' role='alert'>L'&apos;indirizzo email o il nome utente sono stati gi&agrave; utilizzati!  <br>Per favore usane di nuovi. <br> <a href='register.php'>Indietro</a></div>";
        } else {
            $gest->RegisterUser($nome, $cognome, $myusername, $mypassword, $mail, $code);
            echo "<div class='alert alert-success' role='alert'>Congratulazioni!<br />La tua registrazione &egrave; stata eseguita!<br />Il prima possibile riceverai la conferma dell'abilitazione via mail.<br />
<a href='index.php'>Indietro</a></div>";
        }
    }