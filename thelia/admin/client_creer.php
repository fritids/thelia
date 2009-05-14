<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/
?>
<?php
	include_once("pre.php");
	include_once("auth.php");
	include_once("../classes/Pays.class.php");
	include_once("../classes/Paysdesc.class.php");
	include_once("../classes/Client.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Message.class.php");
	include_once("../classes/Messagedesc.class.php");
	include_once("../classes/Variable.class.php");
	include_once("../lib/phpMailer/class.phpmailer.php");

	$erreurnom = 0;
	$erreurprenom = 0;
	$erreuradresse = 0;
	$erreurraison = 0;
	$erreurmail = 0;
	$erreurcpostal = 0;
	$erreurmailexiste = 0;
	$erreurville = 0;
	$erreurpays = 0;



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
				
				$messagedesc->description = str_replace("__NOM_SITE__",$nomsite->valeur,$messagedesc->description);
				$messagedesc->description = str_replace("__EMAIL__",$client->email,$messagedesc->description);
				$messagedesc->description = str_replace("__MOT_DE_PASSE__",$pass,$messagedesc->description);
				$messagedesc->description = str_replace("__URL_SITE__",$urlsite->valeur,$messagedesc->description);
				
				$messagedesc->descriptiontext = str_replace("__NOM_SITE__",$nomsite->valeur,$messagedesc->descriptiontext);
				$messagedesc->descriptiontext = str_replace("__EMAIL__",$client->email,$messagedesc->descriptiontext);
				$messagedesc->descriptiontext = str_replace("__MOT_DE_PASSE__",$pass,$messagedesc->descriptiontext);
				$messagedesc->descriptiontext = str_replace("__URL_SITE__",$urlsite->valeur,$messagedesc->descriptiontext);
				

				$mail = new PHPMailer();
				$mail->IsMail();
				$mail->FromName = $nomsite->valeur;
				$mail->From = $emailcontact->site;
				$mail->Subject = $messagedesc->titre;
				$mail->MsgHTML($messagedesc->description);
				$mail->AltBody = $messagedesc->descriptiontext;
				$mail->AddAddress($client->email,$client->nom." ".$client->prenom);

				$mail->send();

				header("location: client_visualiser.php?ref=".$client->ref);
		}
		else{
			//traitement des erreurs
			if($nom == "") $erreurnom = 1;
			if($prenom == "") $erreurprenom = 1;
			if($adresse1 == "") $erreuradresse = 1;
			if($raison == "") $erreurraison = 1;
			if($client->email == "") $erreurmail = 1;
			if($cpostal == "") $erreurcpostal = 1;
			if($client->existe($email1)) $erreurmailexiste = 1;
			if($ville == "") $erreurville = 1;
			if($pays == "") $erreurpays = 1;
			$paysform =$pays;

		}

	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>

</head>


<body>
	
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="client";
	include_once("entete.php");
?>

<div id="contenu_int"> 
      <p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="client.php" class="lien04">Gestion des clients</a>              
    </p>

<!-- Début de la colonne de gauche -->  
<div id="bloc_description">

<!-- bloc de création client --> 
<form action="client_creer.php" method="POST" id="formulaire">
<input type="hidden" name="action" value="ajouter"> 	
<div class="entete_liste_client">
			<div class="titre">CR&Eacute;ATION D'UN CLIENT</div>
			<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit()">VALIDER LES MODIFICATIONS</a></div>
</div>
<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
        	<th class="designation" width="290">Soci&eacute;t&eacute;</th>
		    <th><input name="entreprise" type="text" class="form" size="40" <?php if(isset($entreprise)) echo "value=\"$entreprise\""; ?> /></th>
	</tr>
	<tr class="fonce">
			<td class="designation">Siret</td>
		    <td><input name="siret" type="text" class="form" size="40" <?php if(isset($siret)) echo "value=\"$siret\""; ?> /></td>
	</tr>
	<tr class="claire">
		    <td class="designation">N° Intracommunautaire</td>
		    <td><input name="intracom" type="text" class="form" size="40" <?php if(isset($intracom)) echo "value=\"$intracom\""; ?> /></td></tr>
	<tr class="fonce">
			<td class="designation">Civilit&eacute; <?php if($erreurraison) echo "obligatoire"; ?></td>
			<td><input name="raison" type="radio" class="form" value="1" <?php if(isset($raison) && $raison == 1) echo "checked"; ?>/>
		Madame
		<input name="raison" type="radio" class="form" value="2" <?php if(isset($raison) && $raison == 2) echo "checked"; ?>/>
		Mademoiselle
		<input name="raison" type="radio" class="form" value="3" <?php if(isset($raison) && $raison == 3) echo "checked"; ?>/>
		Monsieur</td>
	</tr>
	<tr class="claire">
		   	<td class="designation">Nom <?php if($erreurnom) echo "obligatoire";  ?></td>
		    <td><input name="nom" type="text" class="form" size="40" <?php if(isset($nom)) echo "value=\"$nom\""; ?> /></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Pr&eacute;nom <?php if($erreurprenom) echo "obligatoire"; ?></td>
		    <td><input name="prenom" type="text" class="form" size="40" <?php if(isset($prenom)) echo "value=\"$prenom\""; ?> /></td>
	</tr>
	<tr class="claire">
		    <td class="designation">Adresse <?php if($erreuradresse) echo "obligatoire"; ?></td>
		    <td><input name="adresse1" type="text" class="form" size="40" <?php if(isset($adresse1)) echo "value=\"$adresse1\""; ?>/></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Adresse suite</td>
		    <td><input name="adresse2" type="text" class="form" size="40" <?php if(isset($adresse2)) echo "value=\"$adresse2\""; ?>/></td>
	</tr>
	<tr class="claire">
		    <td class="designation">Adresse suite 2</td>
		    <td><input name="adresse3" type="text" class="form" size="40" <?php if(isset($adresse3)) echo "value=\"$adresse3\""; ?>/></td>
	</tr>     
	<tr class="fonce">
		    <td class="designation">Code postal <?php if($erreurcpostal) echo "obligatoire"; ?></td>
		    <td><input name="cpostal" type="text" class="form" size="40" <?php if(isset($cpostal)) echo "value=\"$cpostal\""; ?>/></td>
	</tr>
	<tr class="claire">	
			<td class="designation">Ville <?php if($erreurville) echo "obligatoire"; ?></td>
			<td><input name="ville" type="text" class="form" size="40" <?php if(isset($ville)) echo "value=\"$ville\""; ?>/></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Pays <?php if($erreurpays) echo "obligatoire"; ?></td>
		    <td><select name="pays" class="form_client">
		     <?php
		      	$pays = new Pays();
		      	$query ="select * from $pays->table";

		      	$resul = mysql_query($query, $pays->link);
		      	while($row = mysql_fetch_object($resul)){
					$paysdesc = new Paysdesc();
					$paysdesc->charger($row->id);

		      ?>
		      <option value="<?php echo $row->id; ?>" <?php if($paysform == $row->id){ echo "selected"; } ?> ><?php echo($paysdesc->titre); ?></option>
		      <?php } ?>
		      </select>
			</td>
	</tr>
	<tr class="claire">
		    <td class="designation">T&eacute;l&eacute;phone fixe</td>
		    <td><input name="telfixe" type="text" class="form" size="40" <?php if(isset($telfixe)) echo "value=\"$telfixe\""; ?>/></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">T&eacute;l&eacute;phone portable</td>
		    <td><input name="telport" type="text" class="form" size="40" <?php if(isset($telport)) echo "value=\"$telport\""; ?>/></td>
	</tr>
	<tr class="claire">
		    <td class="designation">E-mail <?php if($erreurmail) echo "obligatoire"; else if($erreurmailexiste) echo "existe déjà"; ?></td>
		    <td><input name="email1" type="text" class="form" size="40" /></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Confirmation e-mail</td>
		    <td><input name="email2" type="text" class="form" size="40" /></td>
	</tr>
	<tr class="claire">
		    <td class="designation">Remise </td>
		    <td><input name="pourcentage" type="text" class="form" size="40" <?php if(isset($remise)) echo "value=\"$remise\""; ?>/></td>
	</tr>     
	<tr class="foncebottom">
		    <td class="designation">Revendeur </td>
		    <td><input type="checkbox" name="type" class="form" <?php if(isset($type)) echo "checked"; ?>/></td>
	</tr> 
</table>
			
</div>
<!-- fin du bloc description -->
	</form>
</div> 
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>