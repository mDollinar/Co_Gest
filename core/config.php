<?php
//VARIABILI DI CONFIGURAZIONE
$asso_name = "Asso_Gest";             //string - nome dell'associazione da visualizzare
$asso_ext_name;                 //string - valore gestito dal sistema
$default_asso_code = "AAG-001"; //string - codice identificativo univoco dell'associazione, valido per tutti gli utenti.
$multiplatform = false;         //bool(true|false) - se false valorizzare $default_asso_code e $asso_name
$citta_sede = "Roma";
$president_sign = "Marco Rossi";
$president_sign_pic = "inc/sign.png";
$header_doc_model = "inc/head_doc_model_full_data.php";
$asso_logo = "inc/assologo.png";
$assoName = "Associazione di Protezione Civile
        <span class=\"nomeAssociazione_evidenziato\">&quot;Friuli&quot;</span>
        Comuncale - <span class=\"onlus\">onlus</span>";
$logo_pc = "inc/logoPCLazio.png";
$sede_legale = "sede legale: Via milano, 11 - 00100 Roma";
$sede_operativa = "sede operativa: Corso Italia, 1 - 00100 Roma";
$CF_asso = "C.F. 00660066";
$pec = "E-mail PEC: pec@pec.it";
$fax = "Fax 52415156";
$tel_asso = "tel. (H24) 3552015485 - 3492515685";
$domain = "domain.it";
$ref_mail = "info";

$doc_model_headings = [
    array("name"=>"Intestazione Completa", "file"=>"head_doc_model_full_data"),
];

//PROCESSI ESEGUITI DA VARIABILI
if(strlen($asso_name)>0 && $multiplatform == false) $asso_ext_name = $asso_name." - ";
?>
