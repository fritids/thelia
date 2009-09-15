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
/*      GNU General Public Lifcense for more details.                                 */
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
	
	if(!isset($action)) $action="";
	if(!isset($page)) $page=0;
	
?>
<?php if(! est_autorise("acces_codespromos")) exit; ?>
<?php
	include_once("../classes/Promo.class.php");
	
?>
<?php
	$promo = new Promo();
  	
  	
	if($page=="") $page=1;
  		 
	$query = "select * from $promo->table";
  	$resul = mysql_query($query, $promo->link);
  	$num = mysql_num_rows($resul);
  	
  	$nbpage = ceil($num/20);
  	
  	$debut = ($page-1) * 20;
  	
  	if($page>1) $pageprec=$page-1;
  	else $pageprec=$page;

  	if($page<$nbpage) $pagesuiv=$page+1;
  	else $pagesuiv=$page;
  	 
  	$ordclassement = "order by code";

	
	switch($action){
		case 'ajouter' : ajouter($code, $type, $valeur, $mini, $utilise, $illimite, $jour, $mois, $annee); break;
		case 'modifier' : modifier($id, $code, $type, $valeur, $mini, $utilise, $illimite, $jour, $mois, $annee); break;
		case 'supprimer' : supprimer($id);

	}
	
?>
<?php
	function modifier($id, $code, $type, $valeur, $mini, $utilise, $illimite, $jour, $mois, $annee){

		$promo = new Promo();
		$promo->charger_id($id);

		$promo->code = $code;
		$promo->type = $type;
		$promo->utilise = $utilise;
		$promo->illimite = $illimite;
		$promo->valeur = $valeur;
		$promo->mini = $mini;
		$promo->datefin = $annee . "-" . $mois . "-" . $jour . " " . "00:00:00";
	
		$promo->maj();
			
	}
	

	function ajouter( $code, $type, $valeur, $mini, $utilise, $illimite, $jour, $mois, $annee){


		$promo = new Promo();

		$promo->code = $code;
		$promo->type = $type;
		$promo->utilise = $utilise;
		$promo->illimite = $illimite;		
		$promo->valeur = $valeur;
		$promo->mini = $mini;
		$promo->datefin = $annee . "-" . $mois . "-" . $jour . " " . "00:00:00";
		$promo->add();
		
	}
	
	function supprimer($id){

		$promo = new Promo();
		$promo->charger_id($id);

		$promo->delete();
		
	}
	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<script src="../lib/jquery/jeditable.js" type="text/javascript"></script>
<script src="../lib/jquery/menu.js" type="text/javascript"></script>
</head>
<body>
<div id="wrapper">
<div id="subwrapper">
<?php
	$menu="paiement";
	include_once("entete.php");
?>

<div id="contenu_int">

<p align="left"><a href="accueil.php" class="lien04">Accueil</a>&nbsp;<img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04">Configuration</a>&nbsp;<img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des codes promotions</a></p>


  <div class="entete_liste">
	<div class="titre">LISTE DES CODES PROMOS</div><div class="fonction_ajout"><a href="promo_modifier.php">AJOUTER UN CODE PROMO</a></div>
</div>
     <ul id="Nav">
		<li style="height:25px; width:207px; border-left:1px solid #96A8B5;">Code</li>
		<li style="height:25px; width:87px; border-left:1px solid #96A8B5;">Type</li>
		<li style="height:25px; width:87px; border-left:1px solid #96A8B5;">Montant</li>	
		<li style="height:25px; width:87px; border-left:1px solid #96A8B5;">Achat mini</li>	
		<li style="height:25px; width:87px; border-left:1px solid #96A8B5;">Code actif</li>
		<li style="height:25px; width:87px; border-left:1px solid #96A8B5;">Utilisation</li>
		<li style="height:25px; width:157px; border-left:1px solid #96A8B5;">Date d'expiration</li>
		<li style="height:25px; width:57px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:17px; border-left:1px solid #96A8B5;">Suppr.</li>
	</ul>
  <?php
  	$i=0;
  	
  	$promo = new Promo();
  	
 	$query = "select * from $promo->table $ordclassement limit $debut,20";
  	$resul = mysql_query($query, $promo->link);

  	while($row = mysql_fetch_object($resul)){
  		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;

		$jour = substr($row->datefin, 8, 2);
		$mois = substr($row->datefin, 5, 2);
		$annee = substr($row->datefin, 0, 4);

  ?>
  <ul class="<?php echo($fond); ?>">
	<li style="width:200px;"><span class="texte_noedit"><?php echo($row->code); ?></span></li>
	<li style="width:80px;"><?php if($row->type == 1) { ?> somme <?php } else { ?> pourcentage <?php } ?></li>
	<li style="width:80px;"><?php echo($row->valeur); ?><?php if($row->type == 1) { ?> &euro; <?php } else { ?> % <?php } ?></li>
	<li style="width:80px;"><?php echo($row->mini); ?> &euro;</li>
	<li style="width:80px;"><?php if($row->utilise == 0) { ?> oui <?php } else { ?> non <?php } ?></li>
	<li style="width:80px;"><?php if($row->illimite == 0) { ?> unique <?php } else { ?> illimit&eacute; <?php } ?></li>
	<li style="width:150px;"><?php if($row->datefin != "0000-00-00 00:00:00") echo $jour . "/" . $mois . "/" . $annee; else echo "//"; ?></li>
	<li style="width:50px;"><a href="promo_modifier.php?id=<?php echo($row->id); ?>">éditer</a></li>
	<li style="width:40px; text-align:center;"><a href="promo.php?id=<?php echo($row->id); ?>&action=supprimer"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
<?php } ?>  


  
   <p id="pages"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pageprec); ?>">Page pr&eacute;c&eacute;dente</a> | 
   
     <?php for($i=0; $i<$nbpage; $i++){ ?>
    	 <?php if($page != $i+1){ ?>
  	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>"><?php echo($i+1); ?></a> |
    	 <?php } else {?>
    		  <span class="selected"><?php echo($i+1); ?></span>
    		|
   		  <?php } ?>
     <?php } ?>
     
    <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pagesuiv); ?>">Page suivante</a>
    </p>
    
</div>
<?php include_once("pied.php"); ?>
</div>
</div>
</body>
</html>
