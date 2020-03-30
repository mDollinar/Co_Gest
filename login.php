<?php
require_once "inc/head-nm.php";
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?php echo $asso_ext_name; ?>Gestionale Volontari</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<form action="crux/checklogin.php" method="post">
    <div class="form-group">
        <h1><?php echo $asso_ext_name; ?>Gestionale Volontari - Login</h1>
        <?php if($multiplatform == true){?>
        <input type="text" name="code" id="code" class="form-control input-index" placeholder="Codice Associazione" required>
        <?php }else{?>
            <input type="hidden" value="<?php echo $default_asso_code; ?>" name="code" id="code" />
        <?php } ?>
        <input type="text" name="myusername" id="myusername" class="form-control input-index" placeholder="Utente" required>
        <input type="password" name="mypassword" id="mypassword" class="form-control input-index" placeholder="Password" require>
        <input type="submit" value="Accedi" class="btn btn-info btn-index">
        <a href="register.php">Registrati!</a>
    </div>
</form>
</body>
</html>