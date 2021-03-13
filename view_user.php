<?php
require_once "inc/head.php";
require_once "crux/printFunctions.php";

if (isset($_GET['id'])) $id = $_GET['id'];
else $id = $_SESSION['user_id'];

$gest->getUserData($id);

extract($gest->results[0]);
if(strlen($photo)==0) $photo = "https://via.placeholder.com/150x150.png?text=Foto";
else $photo = "file/personal_photos/".$photo;
?>

<div id="maincontent">

<div class="container">
    <br>
    <?php if($gest->checkSuperUser()){
        echo "<h1>".restring("Dati Utente&nbsp;<a href='crud_user.php?sec=op&id=".$id."'>%admin%</a>")."</h1>";
    }else{
        echo "<h1>Dati Utente</h1>";
    }?>
    <br />
    <div class="anag"><h4><?php if($gest->checkSuperUser() && $photo_updated) echo restring("%alert_updated%&nbsp;"); echo restring("Foto Tessera&nbsp;&nbsp;<a href='crud_user.php?sec=photo&id=".$id."'>%edit%</a>")?></h4><p><img src="<?php echo $photo ?>" title="Foto Tessera" class="photo"/></p></div>
    <div class="anag"><h4><?php if($gest->checkSuperUser() && $anag_updated) echo restring("%alert_updated%&nbsp;"); echo restring("Dati Anagrafici&nbsp;&nbsp;<a href='crud_user.php?sec=anag&id=".$id."'>%edit%</a>")?></h4><p>Cognome: <?php echo $cognome?></p><p>Nome: <?php echo $nome?></p><p>Codice Fiscale:<?php echo $CF?></p><p>Nato/a a <?php echo $nascita_citta.", (".$nascita_pr.")"?> il <?php echo printField($nascita)?></p></div>
	<div class="res">
        <?php
            if(strlen($domicilio)>0){
        ?>
            <h4><?php if($gest->checkSuperUser() && $res_updated) echo restring("%alert_updated%&nbsp;"); echo restring("Residenza&nbsp;&nbsp;<a href='crud_user.php?sec=res&id=".$id."'>%edit%</a>")?></h4>
        <?php
            }else{
        ?>
            <h4><?php if($gest->checkSuperUser() && $res_updated) echo restring("%alert_updated%&nbsp;"); echo restring("Residenza e Domicilio&nbsp;&nbsp;<a href='crud_user.php?sec=resdom&id=".$id."'>%edit%</a>")?></h4>
        <?php
            }
        ?>
        <p><?php echo $indirizzo.", ".$indirizzo_citta.", ".$indirizzo_cap." (".$indirizzo_pr.")"?></p></div>
    <?php
        if(strlen($domicilio)>0){
    ?>
            <div class="dom"><h4><?php echo restring("Domicilio&nbsp;&nbsp;<a href='crud_user.php?sec=dom&id=".$id."'>%edit%</a>")?></h4><p><?php echo $domicilio.", ".$domicilio_citta.", ".$domicilio_cap." (".$domicilio_pr.")"?></p></div>
    <?php
        }
    ?>
    <div class="contacts"><h4><?php  if($gest->checkSuperUser() && $cont_updateda) echo restring("%alert_updated%&nbsp;"); echo restring("Contatti&nbsp;&nbsp;<a href='crud_user.php?sec=cont&id=".$id."'>%edit%</a>")?></h4><p>Tel: <?php echo $tel?></p><p>Mail: <?php echo $mail?></p></div>
    <div class="asso">
        <h4>Dati Associativi</h4>
        <p>Numero Socio: <?php echo printField($numero_socio) ?></p>
        <p>Data Iscrizione: <?php echo printField($iscrizione[0]['giorno']); ?></p>
        <p>Data Dimissione: <?php echo printField($dimissione[0]['giorno']); ?></p>
    </div>
    <?php if($gest->checkSuperUser()){
        echo"
        <div class='op'>
            <h4>Dati Gestionali</h4>
            <p>Utente Master: ".printField($master)."</p>
            <p>Operativo: ".printField($operativo)."</p>
        </div>
        ";
    }?>
</div>
</div>