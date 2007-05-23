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
?>
<?php
	include_once("../classes/Rubrique.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Paysdesc.class.php");
	include_once("../classes/Commande.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Statutdesc.class.php");

	if(!isset($action)) $action="";
	if(!isset($type)) $type="";
	
?>
<?php
	switch($action){
		case 'modifier' : modifier($raison, $entreprise, $nom, $prenom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email, $pourcentage, $ref, $type); break;

		case 'supprimer' : supprimer($ref);
		case 'supprcmd' : supprcmd($id);

	}
	

?>

<?php
	function modifier($raison, $entreprise, $nom, $prenom, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays, $telfixe, $telport, $email, $pourcentage, $ref, $type){

		$client = new Client();
		$client->charger_ref($ref);
		
		$client->pourcentage = $pourcentage;
		
		$client->raison = $raison;
		$client->entreprise = $entreprise;
		$client->nom = $nom;
		$client->prenom = $prenom; 				
		$client->adresse1 = $adresse1;
		$client->adresse2 = $adresse2;
		$client->adresse3 = $adresse3;
		$client->cpostal = $cpostal; 				
		$client->ville = $ville;	
		$client->pays = $pays; 		
		$client->telfixe = $telfixe; 
		$client->telport = $telport; 
		$client->email = $email;			
		$client->pourcentage = $pourcentage;	
		if($type != "") $client->type=1; else $client->type=0;
				
		$client->maj();

	
		header("Location: client_visualiser.php?ref=" . $ref);

	}

	
	function supprimer($ref){
	
		$client = new Client();		
		$client->charger_ref($ref);
		$client->delete();

		header("Location: client.php");

	}
	
?>

<?php
	function supprcmd($id){

		$tempcmd = new Commande();
		$tempcmd->charger($id);
		
		$tempcmd->supprimer();

	}
	
?>
<?php
	$client = new Client();
	$client->charger_ref($ref);
	
	if($client->raison == "1") $civilite = "Madame";
	else if($client->raison == "2") $civilite = "Mademoiselle";
	else if($client->raison == "3") $civilite = "Monsieur";


	if($client->parrain){
		$parrain = new Client();
		$parrain->charger_id($client->parrain);
	}
	
	$paysdesc = new Paysdesc();
	$paysdesc->charger($client->pays);
	
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />


<script language="JavaScript" type="text/JavaScript">

function supprimer(id){
	if(confirm("Voulez-vous vraiment supprimer cette commande ?")) location="client_visualiser.php?action=supprcmd&id=" + id + "&ref=<?php echo($ref); ?>";

}

</script>
</head>

<body>

<?php
	$menu="client";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des clients / D&eacute;tail du compte client n&deg;  <?php echo($ref); ?></p>
<p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="client.php" class="lien04">Gestion des clients</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Visualiser</a>        
    </p>     
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">INFORMATIONS SUR LE CLIENT</td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="titre_cellule">SOCI&Eacute;T&Eacute;</td>
       <td class="cellule_sombre"><?php echo($client->entreprise); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">CIVILIT&Eacute;</td>
       <td class="cellule_claire"><?php echo($civilite); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">NOM </td>
       <td class="cellule_sombre"><?php echo($client->nom); ?> </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">PR&Eacute;NOM</td>
       <td class="cellule_claire"><?php echo($client->prenom); ?></td>
     </tr>
     <tr>
       <td width="250" height="30" class="titre_cellule">ADRESSE</td>
       <td width="440" class="cellule_sombre"><?php echo($client->adresse1); ?></td>
     </tr>
     <tr>
       <td width="250" height="30" class="titre_cellule">ADRESSE SUITE</td>
       <td width="440" class="cellule_sombre"><?php echo($client->adresse2); ?></td>
     </tr>
     <tr>
       <td width="250" height="30" class="titre_cellule">ADRESSE SUITE 2</td>
       <td width="440" class="cellule_sombre"><?php echo($client->adresse3); ?></td>
     </tr>     
     <tr>
       <td height="30" class="titre_cellule">CODE POSTAL </td>
       <td class="cellule_claire"><?php echo($client->cpostal); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">VILLE</td>
       <td class="cellule_sombre"><?php echo($client->ville); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">PAYS</td>
       <td class="cellule_claire"><?php echo($paysdesc->titre); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">T&Eacute;L&Eacute;PHONE FIXE</td>
       <td class="cellule_sombre"><?php echo($client->telfixe); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">T&Eacute;L&Eacute;PHONE PORTABLE </td>
       <td class="cellule_claire"><?php echo($client->telport); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">E-MAIL</td>
       <td class="cellule_sombre"><a href="mailto:<?php echo($client->email); ?>" class="txt_vert_11"><?php echo($client->email); ?> </a> </td>
     </tr>

	<?php if(isset($parrain)) { ?>
     <tr>
       <td height="30" class="titre_cellule">PARRAIN</td>
       <td class="cellule_sombre"><a href="client_visualiser.php?ref=<?php echo $parrain->ref ?>" class="txt_vert_11"><?php echo $parrain->prenom . " " . $parrain->nom; ?> </a> </td>
     </tr>
	<?php } ?>
	
     <tr>
       <td height="30" class="titre_cellule">Remise </td>
       <td class="cellule_claire"><?php echo($client->pourcentage); ?> %</td>
     </tr>     
   </table>
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="client_modifier.php?ref=<?php echo($client->ref); ?>" class="txt_vert_11">Modifier les coordonn&eacute;es du client </a></span> <a href="client_modifier.php?ref=<?php echo($client->ref); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
     </tr>
   </table>
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES COMMANDES DE CE CLIENT / Toutes les sommes sont en &euro; </td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="140" height="30" class="titre_cellule">N&deg; DE COMMANDE </td>
       <td width="130" class="titre_cellule">DATE &amp; HEURE </td>
       <td width="130" class="titre_cellule">MONTANT</td>
       <td width="130" class="titre_cellule">STATUT</td>
       <td width="110" class="titre_cellule">&nbsp;</td>
       <td width="10" class="titre_cellule">&nbsp;</td>
     </tr>




 <?php
  	$i=0;

	$commande = new Commande();
  	$client = new Client();
  	$client->charger_ref($ref);
  		  	
    $query = "select * from $commande->table where client='" . $client->id . "' order by date desc";
  	$resul = mysql_query($query, $commande->link);
  	$venteprod = new Venteprod();
  	
  	while($row = mysql_fetch_object($resul)){
  	

  		
  		$statutdesc = new Statutdesc();
  		$statutdesc->charger($row->statut);
  		
  		$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$row->id'"; 
  		$resul2 = mysql_query($query2, $venteprod->link);
  		$total = round(mysql_result($resul2, 0, "total"), 2);

		$port = $row->port;
		$total -= $row->remise;
		$total += $port;
		if($total<0) $total = 0;
		
  		$jour = substr($row->date, 8, 2);
  		$mois = substr($row->date, 5, 2);
  		$annee = substr($row->date, 2, 2);
  		
  		$heure = substr($row->date, 11, 2);
  		$minute = substr($row->date, 14, 2);
  		$seconde = substr($row->date, 17, 2);
  		  	
  		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";

  		$i++;
  ?>




     <tr>
       <td height="30" class="<?php echo($fond); ?>"><?php echo($row->ref); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo($jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute . ":" . $seconde); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo(round($total, 2)); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo($statutdesc->titre); ?></td>
       <td class="<?php echo($fond); ?>"><a href="commande_details.php?ref=<?php echo($row->ref); ?>" class="txt_vert_11">En savoir plus </a> <a href="commande_details.php?ref=<?php echo($row->ref); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
       <td class="<?php echo($fond); ?>_vide"> <a href="#" onClick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></td>
     </tr>



<?php } ?>




   </table>

   <br /><br />
  
 <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES FILLEULS DE CE CLIENT</td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="140" height="30" class="titre_cellule">NOM</td>
       <td width="130" class="titre_cellule">PRENOM</td>
       <td width="130" class="titre_cellule">EMAIL</td>
     </tr>




 <?php
  	
	$listepar = new Client();
	
	$query = "select * from $listepar->table where parrain=" . $client->id;
	$resul = mysql_query($query);
	
	$i=0;
	
	while($row = mysql_fetch_object($resul)){
		$listepar->charger_id($row->id);
  		
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";

  		$i++;
  ?>


     <tr>
       <td class="<?php echo($fond); ?>"><a href="client_visualiser.php?ref=<?php echo $listepar->ref ?>" class="txt_vert_11"><?php echo $listepar->nom; ?></a></td>
       <td class="<?php echo($fond); ?>"><?php echo $listepar->prenom; ?></td>
       <td class="<?php echo($fond); ?>"><a href="mailto:<?php echo $listepar->email ?>" class="txt_vert_11"><?php echo $listepar->email; ?></a></td>
     
     </tr>


<?php } ?>




   </table>


   <!--
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES DEVIS DE CE CLIENT / Toutes les sommes sont en &euro; </td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="150" height="30" class="titre_cellule">N&deg; DU DEVIS </td>
       <td width="125" class="titre_cellule">DATE &amp; HEURE </td>
       <td width="125" class="titre_cellule">MONTANT</td>
       <td width="130" class="titre_cellule">STATUT</td>
       <td width="110" class="titre_cellule">&nbsp;</td>
       <td width="10" class="titre_cellule">&nbsp;</td>
     </tr>
     <tr>
       <td height="30" class="cellule_sombre"><a href="gestion_des_clients02.htm" class="txt_vert_11">DE60216141409JEA</a></td>
       <td class="cellule_sombre">16/02/06 14:14:09</td>
       <td class="cellule_sombre">17.80</td>
       <td class="cellule_sombre">En attente </td>
       <td class="cellule_sombre"><a href="gestion_des_commandes02.htm" class="txt_vert_11">En savoir plus </a> <a href="gestion_des_commandes02.htm"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
       <td align="center" valign="middle" class="cellule_sombre"><a href="#"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></td>
     </tr>
      </table>
      
      -->
      
   </div>
</body>
</html>
