<?php


include_once("config.php");
include_once("../../../classes/Commande.class.php");	
include_once("../../../fonctions/divers.php");

$chaine = ''; 
$reponse = '';
$donnees = '';
  
$url = parse_url($serveur);        

foreach ($_POST as $champs=>$valeur) { 
   $donnes["$champs"] = $valeur;
   $chaine .= $champs.'='.urlencode(stripslashes($valeur)).'&'; 
}
$chaine.="cmd=_notify-validate";

// open the connection to paypal
$fp = fsockopen($url['host'],"80",$err_num,$err_str,30); 
if(!$fp) {
     return false;
 } else { 

   fputs($fp, "POST $url[path] HTTP/1.1\r\n"); 
   fputs($fp, "Host: $url[host]\r\n"); 
   fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
   fputs($fp, "Content-length: ".strlen($chaine)."\r\n"); 
   fputs($fp, "Connection: close\r\n\r\n"); 
   fputs($fp, $chaine . "\r\n\r\n"); 

   while(!feof($fp))  
      $reponse .= fgets($fp, 1024); 
  
   fclose($fp); 

}

if(strstr($reponse, "VERIFIED")){
	$reference = $_POST['invoice'];

	$commande = new Commande();
	$commande->charger_trans("$reference");
    $commande->statut = 2;
    $commande->genfact();
	$commande->maj();

	modules_fonction("confirmation", $commande);

}
?>
