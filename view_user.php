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
        <div class="row row-utente justify-content-center">
            <div class="col-3 border border-secondary photo"><h5><?php echo restring("Foto Tessera&nbsp;&nbsp;<a href='crud_user.php?sec=photo&id=".$id."'>%edit%</a>")?></h5><p><img src="<?php echo $photo ?>" title="Foto Tessera" class="photo fototessera"/></p>
            </div>
            <div class="col-6 border border-secondary asso">
                <div class="row row-utente"> 
                    <div class="col-5 asso">
        <h5>Dati Associativi</h5>
        <p>Numero Socio: <strong><?php echo printField($numero_socio) ?></strong></p>
        <p>Data Iscrizione: <strong><?php echo printField($iscrizione[0]['giorno']); ?></strong></p>
        <p>Data Dimissione: <?php echo printField($dimissione[0]['giorno']); ?></p>
                    </div>
        
                    <div class="col-4 asso">

    <?php if($gest->checkSuperUser()){
        echo"
        
            <h5>Dati Gestionali</h5>
            <p>Utente Master: ".printField($master)."</p>
            <p>Operativo: ".printField($operativo)."</p>
        
        ";
    }?>  
                         
                    </div>
                </div>
            </div>
        </div>
    <br>
      
        <div class="row row-utente">
            <div class="col-9 border border-secondary" style="margin-left:20px">
                <div class="row-row-utente">  
                    <div class="col-4 anag"><h5><?php echo restring("Dati Anagrafici&nbsp;&nbsp;<a href='crud_user.php?sec=anag&id=".$id."'>%edit%</a>")?></h5><p>Cognome: <strong><?php echo $cognome?></strong></p><p>Nome: <strong><?php echo $nome?></strong></p><p>Codice Fiscale: <strong><?php echo $CF?></strong></p><p>Nato/a a <strong><?php echo $nascita_citta.", (".$nascita_pr.")"?></strong></p><p>il  <strong><?php echo printField($nascita)?></strong></p>
                    </div>
	                <div class="col-4 res">
           
        <?php
            if(strlen($domicilio)>0){
        ?>
            <h5><?php echo restring("Residenza&nbsp;&nbsp;<a href='crud_user.php?sec=res&id=".$id."'>%edit%</a>")?></h5>
        <?php
            }else{
        ?>
            <h5><?php echo restring("Residenza e Domicilio&nbsp;&nbsp;<a href='crud_user.php?sec=resdom&id=".$id."'>%edit%</a>")?></h5>
        <?php
            }
        ?>
        <p><strong><?php echo $indirizzo.", ".$indirizzo_citta.", ".$indirizzo_cap." (".$indirizzo_pr.")"?></strong></p>
                    </div>
    <?php
        if(strlen($domicilio)>0){
    ?>
    
                    <div class="col-4 res"><h5><?php echo restring("Domicilio&nbsp;&nbsp;<a href='crud_user.php?sec=dom&id=".$id."'>%edit%</a>")?></h5><p><strong><?php echo $domicilio.", ".$domicilio_citta.", ".$domicilio_cap." (".$domicilio_pr.")"?></strong></p>
                    </div>
    <?php
        }
    ?>
        
                    <div class="col-4 contacts">
                <h5><?php echo restring("Contatti&nbsp;&nbsp;<a href='crud_user.php?sec=cont&id=".$id."'>%edit%</a>")?></h5><p>Tel: <strong><?php echo $tel?></strong></p><p>Mail: <strong><?php echo $mail?></strong></p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>