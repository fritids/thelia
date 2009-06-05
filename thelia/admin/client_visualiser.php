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

		case 'supprimer' : supprimer($ref);break;
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
		
		$tempcmd->statut = "5";
		$tempcmd->maj();

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

<script type="text/javascript" src="../lib/jquery/jquery.js"></script>
<script type="text/javascript">

function supprimer(id){
	if(confirm("Voulez-vous vraiment supprimer cette commande ?")) location="client_visualiser.php?action=supprcmd&id=" + id + "&ref=<?php echo($ref); ?>";

}

</script>
</head>

<body>

<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="client";
	include_once("entete.php");
?>

<div id="contenu_int">
<p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="client.php" class="lien04">Gestion des clients</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Visualiser</a></p>
    
<!-- Début de la colonne de gauche -->  
<div id="bloc_description">
  
<div class="entete_liste_client">
	<div class="titre">INFORMATIONS SUR LE CLIENT </div>
	<div class="fonction_valider"><a href="client_modifier.php?ref=<?php echo($client->ref); ?>">MODIFIER LES COORDONN&Eacute;ES DU CLIENT</a></div>
</div>
   <table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
        	<th class="designation" width="290">Soci&eacute;t&eacute;</td>
       		<th><?php echo($client->entreprise); ?></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Siret</td>
       <td><?php echo($client->siret); ?></td>
     </tr>
     <tr class="claire">
       <td class="designation">N° Intracommunautaire</td>
       <td><?php echo($client->intracom); ?></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Civilit&eacute;</td>
       <td><?php echo($civilite); ?></td>
     </tr>
     <tr class="claire">
       <td class="designation">Nom</td>
       <td><?php echo($client->nom); ?> </td>
     </tr>
     <tr class="fonce">
       <td class="designation">Pr&eacute;nom</td>
       <td><?php echo($client->prenom); ?></td>
     </tr>
     <tr class="claire">
       <td class="designation">Adresse</td>
       <td><?php echo($client->adresse1); ?></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Adresse suite</td>
       <td><?php echo($client->adresse2); ?></td>
     </tr>
     <tr class="claire">
       <td class="designation">Adresse suite 2</td>
       <td><?php echo($client->adresse3); ?></td>
     </tr>     
     <tr class="fonce">
       <td class="designation">Code postal</td>
       <td><?php echo($client->cpostal); ?></td>
     </tr>
     <tr class="claire">
       <td class="designation">Ville</td>
       <td><?php echo($client->ville); ?></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Pays</td>
       <td><?php echo($paysdesc->titre); ?></td>
     </tr>
     <tr class="claire">
       <td class="designation">T&eacute;l&eacute;phone fixe</td>
       <td><?php echo($client->telfixe); ?></td>
     </tr>
     <tr class="fonce">
       <td class="designation">T&eacute;l&eacute;phone portable </td>
       <td><?php echo($client->telport); ?></td>
     </tr>
     <tr class="claire">
       <td class="designation">E-mail</td>
       <td><a href="mailto:<?php echo($client->email); ?>" class="txt_vert_11"><?php echo($client->email); ?> </a> </td>
     </tr>
     <tr class="foncebottom">
       <td class="designation">Remise </td>
       <td><?php echo($client->pourcentage); ?> %</td>
     </tr> 
     <?php if(isset($parrain)) { ?>
     <tr class="clairebottom">
       <td class="designation">Parrain</td>
       <td><a href="client_visualiser.php?ref=<?php echo $parrain->ref ?>" class="txt_vert_11"><?php echo $parrain->prenom . " " . $parrain->nom; ?> </a> </td>
     </tr>
	<?php } ?>    
   </table>

<?php
	admin_inclure("clientvisualiser");		
?>

<!-- -->

		<div class="entete_liste_client">
			<div class="titre" style="cursor:pointer" onclick="$('#pliantlistecommandes').show('slow');">LISTE DES COMMANDES DE CE CLIENT</div>
		</div>
		<div class="blocs_pliants_prod" id="pliantlistecommandes">
				
	<ul class="ligne1">
		<li class="cellule" style="width:130px;">N&deg; DE COMMANDE</li>
		<li class="cellule" style="width:130px;">DATE &amp; HEURE</li>
		<li class="cellule" style="width:120px;">MONTANT EN &euro;</li>
		<li class="cellule" style="width:90px;">STATUT</li>
		<li class="cellule" style="width:60px;"></li>
		<li class="cellule" style="width:20px;"></li>

	</ul>
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
  		  	
  		if(!($i%2)) $fond="fonce";
  		else $fond="claire";

  		$i++;
  ?>

	<ul class="lignesimple">
		<li class="cellule" style="width:130px;"><?php echo($row->ref); ?></li>
		<li class="cellule" style="width:130px;"><?php echo($jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute . ":" . $seconde); ?></li>
		<li class="cellule" style="width:120px;"><?php echo(round($total, 2)); ?></li>
		<li class="cellule" style="width:90px;"><?php echo($statutdesc->titre); ?></li>
		<li class="cellule" style="width:60px;"><a href="commande_details.php?ref=<?php echo($row->ref); ?>">&eacute;diter</a></li>
		<li class="cellule" style="width:20px;"><a href="#" onclick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>

	</ul>

<?php } ?>

<div class="bloc_fleche" style="cursor:pointer" onclick="$('#pliantlistecommandes').hide();"><img src="gfx/fleche_accordeon_up.gif" /></div>
</div>

<!-- -->

<!-- -->

		<div class="entete_liste_client">
			<div class="titre" style="cursor:pointer" onclick="$('#pliantfilleul').show('slow');">LISTE DES FILLEULS DE CE CLIENT</div>
		</div>
		<div class="blocs_pliants_prod" id="pliantfilleul">
				
	<ul class="ligne1">
		<li class="cellule" style="width:160px;">NOM</li>
		<li class="cellule" style="width:160px;">PRENOM</li>
		<li class="cellule" style="width:155px;">E-MAIL</li>
		<li class="cellule" style="width:90px;"></li>


	</ul>

<?php
  	
	$listepar = new Client();
	
	$query = "select * from $listepar->table where parrain=" . $client->id;
	$resul = mysql_query($query);
	
	$i=0;
	
	while($row = mysql_fetch_object($resul)){
		$listepar->charger_id($row->id);
  		
		if(!($i%2)) $fond="fonce";
  		else $fond="claire";

  		$i++;
  ?>
	<ul class="lignesimple">
		<li class="cellule" style="width:160px;"><?php echo $listepar->nom; ?></li>
		<li class="cellule" style="width:160px;"><?php echo $listepar->prenom; ?></li>
		<li class="cellule" style="width:155px;"><a href="mailto:<?php echo $listepar->email ?>"><?php echo $listepar->email; ?></a></li>
		<li class="cellule" style="width:90px;"><a href="client_visualiser.php?ref=<?php echo $listepar->ref ?>">&eacute;diter</a></li>

	</ul>
<?php } ?>


<div class="bloc_fleche" style="cursor:pointer" onclick="$('#pliantfilleul').hide();"><img src="gfx/fleche_accordeon_up.gif" /></div>
</div>

<!-- -->

	
</div>

</div> 
<?php include_once("pied.php");?>
</div>
</div>

</body>
</html>
