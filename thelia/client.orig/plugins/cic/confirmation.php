<?php

	include_once("../../../classes/Commande.class.php");	
	include_once("../../../fonctions/divers.php");

    $reference = $_POST['reference'];
    $etat = $_POST['code-retour'];
        
	$commande = new Commande();
	$commande->charger_trans($reference);
	if($etat == "paiement"){
	 $commande->statut = 2;
	 $commande->genfact();
	}
	
	$commande->maj();

	modules_fonction("confirmation", $commande);
	
@require_once("CMCIC_HMAC.inc.php");
if (!function_exists('CMCIC_hmac')) 
{
    die ('cant require hmac function.');
}

// ----------------------------------------------------------------------------
// function CMCIC_getMyTpe
//
// IN: Code soci�t� / Company code
//     Code langue / Language code
//
// OUT: Param�tres du Tpe / Tpe parameters
// Description: Get TPE Number, 2nd part of Key and other Merchant
//              Configuration Datas from merchant DataBase
//              Rechercher le num�ro de TPE, la 2nde partie crypt�e de clef
//              et autres infos de configuration Marchand
// ----------------------------------------------------------------------------
function CMCIC_getMyTpe($soc="mysoc",$lang="")
{
     @require("MyTpeCMCIC.inc.php");
     if ( !is_array($MyTpe) ) { die ('cant require Tpe config.'); }
     return $MyTpe;
}

// ----------------------------------------------------------------------------
// function TesterHmac
//
// IN: Param�tres du Tpe / Tpe parameters
//     Champs du formulaire / Form fields
// OUT: R�sultat v�rification / Verification result
// description: V�rifier le MAC et pr�parer la Reponse
//              Perform MAC verification and create Receipt
// ----------------------------------------------------------------------------
function TesterHmac($CMCIC_Tpe, $CMCIC_bruteVars )
{
   @$php2_fields = sprintf(CMCIC_PHP2_FIELDS, $CMCIC_bruteVars['retourPLUS'], 
                                              $CMCIC_Tpe["tpe"], 
                                              $CMCIC_bruteVars["date"],
                                              $CMCIC_bruteVars['montant'],
                                              $CMCIC_bruteVars['reference'],
                                              $CMCIC_bruteVars['texte-libre'],
                                               CMCIC_VERSION,
                                              $CMCIC_bruteVars['code-retour']);


    if ( strtolower($CMCIC_bruteVars['MAC'] ) == CMCIC_hmac($CMCIC_Tpe, $php2_fields) ):
        $result  = $CMCIC_bruteVars['code-retour'].$CMCIC_bruteVars['retourPLUS'];
        $receipt = CMCIC_PHP2_MACOK;
    else: 
        $result  = 'None';
        $receipt = CMCIC_PHP2_MACNOTOK.$php2_fields;
    endif;

    $mnt_lth = strlen($CMCIC_bruteVars['montant'] ) - 3;
    if ($mnt_lth > 0):
        $currency = substr($CMCIC_bruteVars['montant'], $mnt_lth, 3 );
        $amount   = substr($CMCIC_bruteVars['montant'], 0, $mnt_lth );
    else:
        $currency = "";
        $amount   = $CMCIC_bruteVars['montant'];
    endif;

    return array( "resultatVerifie" => $result ,
                  "accuseReception" => $receipt ,
                  "tpe"             => $CMCIC_bruteVars['TPE'],
                  "reference"       => $CMCIC_bruteVars['reference'],
                  "texteLibre"      => $CMCIC_bruteVars['texte-libre'],
                  "devise"          => $currency,
                  "montant"         => $amount);
}

// Begin Main : Retrieve Variables posted by CMCIC Payment Server 
//              Recevoir les variables post�es par le serveur bancaire

$CMCIC_reqMethod  = $_SERVER["REQUEST_METHOD"];
if (($CMCIC_reqMethod == "GET") or ($CMCIC_reqMethod == "POST")) {
    $CMCIC_bruteVars  = $_REQUEST;
}
else
    die ('Invalid REQUEST_METHOD (not GET, not POST).');

@$isVariableEmpty  = $CMCIC_bruteVars['TPE'];

// empty variables ?
if (!($isVariableEmpty > " "))
{
    // You should do your best to write your scripts so that they do not
    // require register_globals to be on. Using form variables as globals
    // can easily lead to possible security problems, if the code is not 
    // very well thought of.
    // Il est recommand� de ne pas �crire de scripts qui exige de param�trer
    // register_globals � on. Utiliser les variables du formulaire comme
    // globales peut amener des probl�mes de s�curit� si votre script n'est
    // pas tr�s bien con�u.

    // var_dump($CMCIC_bruteVars);
    echo "\r\nTrying PHP<=3 old style ! "."\r\n";

    settype($CMCIC_bruteVars , "array"); 

    @$CMCIC_bruteVars['MAC']         = $MAC;
    @$CMCIC_bruteVars['TPE']         = $TPE;
    @$CMCIC_bruteVars['date']        = $date;
    @$CMCIC_bruteVars['montant']     = $montant;
    @$CMCIC_bruteVars['reference']   = $reference;
    $URL_texte_libre                 = "texte-libre";
    @$CMCIC_bruteVars['texte-libre'] = $$URL_texte_libre;
    $URL_code_retour                 = "code-retour";
    @$CMCIC_bruteVars['code-retour'] = $$URL_code_retour;
    @$CMCIC_bruteVars['retourPLUS']   = $retourPLUS;

    // var_dump($CMCIC_bruteVars);
    echo "\r\n Is it Better ? "."\r\n";
}

// TPE init variables
// variables initiales TPE
@$CMCIC_Tpe = CMCIC_getMyTpe();

// Message Authentication
// Test d'authentification
@$CMCIC_authVars   = TesterHmac($CMCIC_Tpe, $CMCIC_bruteVars );

@$Verified_Result  = $CMCIC_authVars['resultatVerifie'];

// <<<--- code <<<--- 
// (Cas / Case : "None" , "Annulation" , "Payetest", "Paiement")
//-----------------------------------------------------------------------------
// Dump variables may give you an idea about what to do
//                           ********************
// Vider ces variables peut vous aider � voir ce qui est � coder
//-----------------------------------------------------------------------------
// var_dump($Verified_Result_Array);
// var_dump($CMCIC_bruteVars);
// var_dump($CMCIC_authVars);

//-----------------------------------------------------------------------------
// Send receipt to CMCIC server
// Envoyer un A/R au serveur bancaire
//-----------------------------------------------------------------------------
@printf (CMCIC_PHP2_RECEIPT, $CMCIC_authVars['accuseReception']);

// Copyright (c) 2003 Euro-Information ( mailto:centrecom@e-i.com )
// All rights reserved. ---

?>
