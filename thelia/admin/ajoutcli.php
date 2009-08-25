<?php
include_once("pre.php");
include_once("auth.php");

include_once("../classes/Client.class.php");
include_once("../classes/Variable.class.php");
include_once("../classes/Message.class.php");
include_once("../classes/Messagedesc.class.php");
include_once("../lib/phpMailer/class.phpmailer.php");
include_once("../fonctions/divers.php");
if($action == "ajouter"){
	$client = new Client();

	$client = New Client();
	$client->raison = strip_tags($raison);
	$client->nom = strip_tags($nom);
	$client->entreprise = strip_tags($entreprise);
	$client->ref = date("ymdHis") . strtoupper(substr(strip_tags($prenom),0, 3));
	$client->prenom = strip_tags($prenom);
	$client->telfixe = strip_tags($telfixe);
	$client->telport =strip_tags($telport); 
	if( preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z.]+$/","$email1") 
		&& $email1==$email2) $client->email = strip_tags($email1);
	$client->adresse1 = strip_tags($adresse1);
	$client->adresse2 = strip_tags($adresse2);
	$client->adresse3 = strip_tags($adresse3);
	$client->cpostal = strip_tags($cpostal);
	$client->ville = strip_tags($ville);
	$client->siret = strip_tags($siret);
	$client->intracom = strip_tags($intracom);
	$client->pays = strip_tags($pays);
	if($revendeur == "on") $client->revendeur = 1;
	else $client->revendeur = 0;
	$client->type = "0";

	$testcli = new Client();
	if($parrain != "") 
		if($testcli->charger_mail($parrain)) $parrain=$testcli->id;
		else $parrain=-1;
	else $parrain=0;

	$client->motdepasse = genpass(8);
	$pass = $client->motdepasse;
	if($client->raison!="" && $client->prenom!="" && $client->nom!="" && $client->email!="" && $client->motdepasse!="" 
		&& $client->email && ! $client->existe($email1) && $client->adresse1 !="" && $client->cpostal!="" && $client->ville !="" && $client->pays !=""){
			$client->crypter();
			$client->add();

			$rec = $client->charger_mail($client->email);

			$message = new Message();
			$message->charger("creation_client");

			$messagedesc = new Messagedesc();
			$messagedesc->charger($message->id);

			$nomsite = new Variable();
			$nomsite->charger("nomsite");

			$urlsite = new Variable();
			$urlsite->charger("urlsite");

			$emailcontact = new Variable();
			$emailcontact->charger("emailcontact");
			
			$messagedesc->description = str_replace("__NOMSITE__",$nomsite->valeur,$messagedesc->description);
			$messagedesc->description = str_replace("__EMAIL__",$client->email,$messagedesc->description);
			$messagedesc->description = str_replace("__MOTDEPASSE__",$pass,$messagedesc->description);
			$messagedesc->description = str_replace("__URLSITE__",$urlsite->valeur,$messagedesc->description);
			$messagedesc->description = str_replace("__NOM__",$client->nom,$messagedesc->description);
			$messagedesc->description = str_replace("__PRENOM__",$client->prenom,$messagedesc->description);
			$messagedesc->description = str_replace("__ADRESSE1__",$client->adresse1,$messagedesc->description);
			$messagedesc->description = str_replace("__ADRESSE2__",$client->adresse2,$messagedesc->description);
			$messagedesc->description = str_replace("__ADRESSE3__",$client->adresse3,$messagedesc->description);
			$messagedesc->description = str_replace("__VILLE__",$client->ville,$messagedesc->description);
			$messagedesc->description = str_replace("__CPOSTAL__",$client->cpostal,$messagedesc->description);
			$messagedesc->description = str_replace("__TELEPHONE__",$client->telfixe,$messagedesc->description);
			$messagedesc->description = str_replace("__CIVILITE__",$raison[$client->raison],$messagedesc->description);
			$messagedesc->description = str_replace("__PAYS__",$paysdesc->titre,$messagedesc->description);

			$messagedesc->descriptiontext = str_replace("__NOMSITE__",$nomsite->valeur,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__EMAIL__",$client->email,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__MOTDEPASSE__",$pass,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__URLSITE__",$urlsite->valeur,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__NOM__",$client->nom,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__PRENOM__",$client->prenom,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__ADRESSE1__",$client->adresse1,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__ADRESSE2__",$client->adresse2,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__ADRESSE3__",$client->adresse3,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__VILLE__",$client->ville,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__CPOSTAL__",$client->cpostal,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__TELEPHONE__",$client->telfixe,$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__CIVILITE__",$raison[$client->raison],$messagedesc->descriptiontext);
			$messagedesc->descriptiontext = str_replace("__PAYS__",$paysdesc->titre,$messagedesc->descriptiontext);
			

			$mail = new PHPMailer();
			$mail->IsMail();
			$mail->FromName = $nomsite->valeur;
			$mail->From = $emailcontact->site;
			$mail->Subject = $messagedesc->titre;
			$mail->MsgHTML($messagedesc->description);
			$mail->AltBody = $messagedesc->descriptiontext;
			$mail->AddAddress($client->email,$client->nom." ".$client->prenom);

			$mail->send();
			?>
				<input type="text"name="client" class="form" value="<?php echo $client->nom." ".$client->prenom; ?>">
				<input type="hidden" name="id_client" value="<?php echo $client->ref; ?>">
			<?php
	}
}
?>