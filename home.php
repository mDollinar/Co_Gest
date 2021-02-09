<?php
require_once "inc/head.php";
?>
<script src="js/home.js" type="text/javascript"></script>
<div class="container">
    <div class="card-deck">
    <?php
        if($gest->checkSuperUser()) {
            if ($gest->checkPendingUsers()) $gest->printPendingUsers();
            if ($gest->checkPendingDocuments()) $gest->printPendingDocuments();
            if ($gest->checkEditedUsers()) $gest->printEditedUsers();
        }
            if (!$gest->checkUserData($_SESSION['user_id'])) $gest->printUserAlert();
    ?>
    </div>
</div>