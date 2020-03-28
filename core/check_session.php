<?php
session_start();

$reservedPages=[
    "check_document.php",
    "crud_document_type.php",
    "handle_document_type.php",
    "view_users.php",
    "view_document.php",
    "check_users.php",
    "crud_registro.php",
    "handle_registro.php",
    "crud_doc_models.php",
    "handle_doc_models.php",
    "print_doc_models.php",
    "session_simulator.php"
];

if(in_array(basename($_SERVER['PHP_SELF']), $reservedPages) && ($_SESSION['master'] == 0 && $_SESSION['adm']==0) && $_SESSION['simulation'] != 1) header("location: home.php");

if($_SESSION["myusername"] != "k" || $_SESSION["mypassword"] != "k" || isset($_SESSION['user_id']) == false || isset($_SESSION['user']) == false){
    header("location: login.php");
}