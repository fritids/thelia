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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<script src="../lib/jquery/jeditable.js" type="text/javascript"></script>
<script src="../lib/jquery/menu.js" type="text/javascript"></script>

<?php
	include_once("../classes/Client.class.php");
	include_once("../classes/Commande.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Statutdesc.class.php");
	include_once("../classes/Produit.class.php");
	
	$menu="";
?>
</head>

<script language="JavaScript" type="text/JavaScript">

function supprimer(id){
	if(confirm("Voulez-vous vraiment supprimer cette commande ?")) location="commande.php?action=supprimer&id=" + id;

}

function supprimer_produit(ref, parent){
	if(confirm("Voulez-vous vraiment supprimer ce produit ?")) location="produit_modifier.php?ref=" + ref + "&action=supprimer&parent=" + parent;

}

function supprimer_rubrique(id, parent){
	if(confirm("Voulez-vous vraiment supprimer cette rubrique ? Vous devez d'abord vider celle-ci")) location="rubrique_modifier.php?id=" + id + "&action=supprimer&parent=" + parent;

}

</script>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	include_once("entete.php");
?>

<div id="contenu_int"> 
    <p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">R&eacute;sultats de la recherche </a>              
    </p>

<?php if(est_autorise("acces_clients")){ ?>

<div class="entete_general">
	<div class="titre">RESULTATS CLIENT</div>
</div>
<ul id="Nav">
		<li style="width:160px;">N&deg; de client</li>
		<li style="width:200px; border-left:1px solid #96A8B5;">Nom</li>
		<li style="width:200px; border-left:1px solid #96A8B5;">Pr&eacute;nom</li>
		<li style="width:305px; border-left:1px solid #96A8B5;">E-mail</li>	
		<li style="width:50px; border-left:1px solid #96A8B5;">&nbsp;</li>
</ul>
    <?php
    	$i=0;
  	$client = new Client();
 	$search="and nom like '%$motcle%' or prenom like '%$motcle%' or ville like '%$motcle%' or email like '%$motcle%'";
  	
 	$query = "select * from $client->table where 1 $search";
  	$cliresul = mysql_query($query, $client->link);
  	$clilist="";
   	while($row = mysql_fetch_object($cliresul)){
  		$clilist .= "'$row->id', ";
  		
		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;
  		
  	?>
  	<ul class="<?php echo($fond); ?>">
	<li style="width:152px;"><a href="commande.php?client=<?php echo($row->id); ?>"><?php echo($row->ref); ?></a></li>
	<li style="width:193px;"><?php echo($row->nom); ?></li>
	<li style="width:193px;"><?php echo($row->prenom); ?></li>
	<li style="width:298px;"><a href="mailto:<?php echo($row->email); ?>"><?php echo($row->email); ?></a></li>
	<li><a href="client_visualiser.php?ref=<?php echo($row->ref); ?>">&eacute;diter</a></li>
	
	</ul>

 <?php
	}
	
	$clilist = substr($clilist, 0, strlen($clilist)-2);
 ?>

<?php } ?>
<?php if(est_autorise("acces_commandes")){ ?>
 <div class="entete_general" style="margin:10px 0 0 0">
	<div class="titre">RESULTATS COMMANDE</div>
</div>
<ul id="Nav">
		<li style="width:160px;">N&deg; de commande</li>
		<li style="width:200px; border-left:1px solid #96A8B5;">Date</li>
		<li style="width:200px; border-left:1px solid #96A8B5;">Nom</li>
		<li style="width:200px; border-left:1px solid #96A8B5;">Montant</li>	
		<li style="width:100px; border-left:1px solid #96A8B5;">Statut</li>
		<li style="width:20px; border-left:1px solid #96A8B5;">Suppr.</li>
</ul>
    <?php
  
  	$search="";
  	
  	if($clilist!="") $search .= "where client in ($clilist) or ";
  	else $search .= "where client and  ";
  	$commande = new Commande();
  	
  	
  	$i=0;
  	$jour = substr($motcle, 0, 2);
  	$mois = substr($motcle, 3,2);
  	$annee = substr($motcle, 6);

	$ladate = "$annee-$mois-$jour";
	$ladate = str_replace("??", "%", $ladate);
	
  	$search .= " ref like '%$motcle%' or date like '$ladate'";
  		
   	$query = "select * from $commande->table $search";
  	$resul = mysql_query($query, $commande->link);

  	$venteprod = new Venteprod();
  	
  	while($row = mysql_fetch_object($resul)){
  	
  		$client = new Client();
  		$client->charger_id($row->client);
  		
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
  		$annee = substr($row->date, 0, 4);
  		
  		$heure = substr($row->date, 11, 2);
  		$minute = substr($row->date, 14, 2);
  		$seconde = substr($row->date, 17, 2);
  		
  		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;
  		
  ?>
  		<ul class="<?php echo($fond); ?>">
			<li style="width:152px;"><a href="commande_details.php?ref=<?php echo($row->ref); ?>"><?php echo($row->ref); ?></a></li>
			<li style="width:193px;"><?php echo($jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute . ":" . $seconde); ?></li>
			<li style="width:193px;"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>"><?php echo($client->nom . " " . $client->prenom); ?></a></li>
			<li style="width:193px;"><?php echo($total); ?></li>
			<li style="width:93px;"><?php echo($statutdesc->titre); ?></li>
			<li><a href="#" onclick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
		</ul>


 <?php

	}
 ?>
<?php } ?>
<?php if(est_autorise("acces_catalogue")){ ?>
 <div class="entete_general" style="margin:10px 0 0 0">
	<div class="titre">RESULTATS PRODUITS</div>
</div>
<ul id="Nav">
		<li style="width:160px;">R&eacute;f&eacute;rence</li>
		<li style="width:408px; border-left:1px solid #96A8B5;">Titre</li>
		<li style="width:308px; border-left:1px solid #96A8B5;">Prix</li>	
		<li style="width:20px; border-left:1px solid #96A8B5;">Suppr.</li>
</ul>

  <?php
  	$i=0;
  	$search="";

  	$search .= "and ref like '%$motcle%'";
  
  
  	$produit = new Produit();
  	
  		
   	$query = "select * from $produit->table where 1 $search";
  	$resul = mysql_query($query, $produit->link);

  	$produitdesc = new Produitdesc();
 
 	$prodlist="";
 	
   	while($row = mysql_fetch_object($resul)){
		 $prodlist .= "'$row->id', ";	  	
 	 } 	
  	
  	$prodlist = substr($prodlist, 0, strlen($prodlist)-2);

  	$search="";

  	$search .= "and titre like '%$motcle%' or description like '%$motcle%'";
  
  
  	$produit = new Produit();
  	
  		
   	$query = "select * from $produitdesc->table where 1 $search";
  	$resul = mysql_query($query, $produitdesc->link);
	if(mysql_num_rows($resul) && $prodlist!="") $prodlist .= ",";
	
  	$produitdesc = new Produitdesc();
 
 	$num = 0;
 
   	while($row = mysql_fetch_object($resul)){
   		$num++;
		 $prodlist .= "'$row->produit', ";	  	
 	 } 	

	if( substr($prodlist, strlen($prodlist)-2, 1) == ",")
 		$prodlist = substr($prodlist, 0, strlen($prodlist)-2);

	if($num == 1) $prodlist .= "'";
	
	if($prodlist == "") $search = "where 0";
	else $search = " where id in ($prodlist)";
	
   	$query = "select * from $produit->table $search";
  	$query = str_replace("'')", "')", $query);
	$resul = mysql_query($query, $produitdesc->link);	

  	while($row = mysql_fetch_object($resul)){
  	
  		$produitdesc->charger($row->id);
  	if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;

  ?>
  <ul class="<?php echo($fond); ?>">
	<li style="width:152px;"><a href="produit_modifier.php?ref=<?php echo($row->ref); ?>&rubrique=<?php echo($row->rubrique); ?>"><?php echo($row->ref); ?></a></li>
	<li style="width:400px;"><?php echo($produitdesc->titre); ?></li>
	<li style="width:303px;"><?php echo($row->prix); ?></li>
	<li style="width:20px;"><a href="javascript:supprimer_produit('<?php echo $row->ref ?>','<?php echo($row->rubrique); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
<?php } ?>

 <?php
	}
 ?>

<?php 
	admin_inclure("recherche"); 
?>

</div> 
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
