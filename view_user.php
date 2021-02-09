<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";

$gest->getUserData($_SESSION['user_id']);

extract($gest->results[0]);
if(strlen($photo)==0) $photo = "https://via.placeholder.com/150x150.png?text=Foto";
else $photo = "file/personal_photos/".$photo;
?>

<div class="container">
    <br><h1>Dati Utente</h1><br>
    <div class="anag"><h4><?php echo restring("Foto Tessera&nbsp;&nbsp;<a href='crud_user.php?sec=photo'>%edit%</a>")?></h4><p><img src="<?php echo $photo ?>" title="Foto Tessera" class="photo"/></p></div>
    <div class="anag"><h4><?php echo restring("Dati Anagrafici&nbsp;&nbsp;<a href='crud_user.php?sec=anag'>%edit%</a>")?></h4><p>Cognome: <?php echo $cognome?></p><p>Nome: <?php echo $nome?></p><p>Codice Fiscale:<?php echo $CF?></p><p>Nato/a a <?php echo $nascita_citta.", (".$nascita_pr.")"?> il <?php echo printField($nascita)?></p></div>
	<div class="res">
        <?php
            if(strlen($domicilio)>0){
        ?>
            <h4><?php echo restring("Residenza&nbsp;&nbsp;<a href='crud_user.php?sec=res'>%edit%</a>")?></h4>
        <?php
            }else{
        ?>
            <h4><?php echo restring("Residenza e Domicilio&nbsp;&nbsp;<a href='crud_user.php?sec=resdom'>%edit%</a>")?></h4>
        <?php
            }
        ?>
        <p><?php echo $indirizzo.", ".$indirizzo_citta.", ".$indirizzo_cap." (".$indirizzo_pr.")"?></p></div>
    <?php
        if(strlen($domicilio)>0){
    ?>
            <div class="dom"><h4><?php echo restring("Domicilio&nbsp;&nbsp;<a href='crud_user.php?sec=dom'>%edit%</a>")?></h4><p><?php echo $domicilio.", ".$domicilio_citta.", ".$domicilio_cap." (".$domicilio_pr.")"?></p></div>
    <?php
        }
    ?>
    <div class="contacts"><h4><?php echo restring("Contatti&nbsp;&nbsp;<a href='crud_user.php?sec=cont'>%edit%</a>")?></h4><p>Tel: <?php echo $tel?></p><p>Mail: <?php echo $mail?></p></div>
</div>
