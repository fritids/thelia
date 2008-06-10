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
<?php
	$menu="paiement";
	include_once("entete.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des codes promos </p>
<p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="paiement.php" class="lien04">Gestion du paiement</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des promotions</a>              
    </p>
      <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES CODES PROMOS </td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td height="30" class="titre_cellule">CODE</td>
    <td height="30" class="titre_cellule">TYPE</td>
    <td height="30" class="titre_cellule">VALEUR</td>
    <td height="30" class="titre_cellule">MINI</td>
    <td height="30" class="titre_cellule">UTILISE</td>
    <td height="30" class="titre_cellule">ILLIMITE</td>
	<td height="30" class="titre_cellule">DATE EXP</td>
	<td height="30" class="titre_cellule">&nbsp;</td>
	<td height="30" class="titre_cellule">&nbsp;</td>
  </tr>
  
  <?php
  	$i=0;
  	
  	$promo = new Promo();
  	
 	$query = "select * from $promo->table $ordclassement limit $debut,20";
  	$resul = mysql_query($query, $promo->link);

  	while($row = mysql_fetch_object($resul)){
  		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
  		$i++;

		$jour = substr($row->datefin, 8, 2);
		$mois = substr($row->datefin, 5, 2);
		$annee = substr($row->datefin, 0, 4);

  ?>
    
  <tr class="<?php echo($fond); ?>">
    <td height="30">&nbsp;<?php echo($row->code); ?></td>
    <td height="30">&nbsp;<?php if($row->type == 1) { ?> S <?php } else { ?> P <?php } ?></td>
	<td height="30">&nbsp;<?php echo($row->valeur); ?></td>
	<td height="30">&nbsp;<?php echo($row->mini); ?></td>
	<td height="30">&nbsp;<?php if($row-> utilise == 1) { ?> OUI <?php } else { ?> NON <?php } ?></td>
    <td height="30">&nbsp;<?php if($row-> illimite == 1) { ?> OUI <?php } else { ?> NON <?php } ?></td>
	<td height="30">&nbsp;<?php if($row->datefin != "0000-00-00 00:00:00") echo $jour . "/" . $mois . "/" . $annee; else echo "//"; ?></td>
    <td height="30"><a href="promo_modifier.php?id=<?php echo($row->id); ?>" class="txt_vert_11">Modifier</a> <a href="promo_modifier.php?id=<?php echo($row->id); ?>" class="txt_vert_11"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
    <td height="30"><a href="promo.php?id=<?php echo($row->id); ?>&action=supprimer" class="txt_vert_11">Supprimer</a> <a href="promo.php?id=<?php echo($row->id); ?>&action=supprimer" class="txt_vert_11"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>

  </tr>
 
<?php } ?>  
   
  
  
</table>

     <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="5"></td>
    </tr>
    <tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre2">
	   <a href="promo_modifier.php" class="lien_titre_cellule">Ajouter un code</a></td>
    </tr>
  </table>
  
   <p align="center" class="geneva11Reg_3B4B5B"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pageprec); ?>" class="txt_vert_11">Page pr&eacute;c&eacute;dente</a> | 
   
     <?php for($i=0; $i<$nbpage; $i++){ ?>
    	 <?php if($page != $i+1){ ?>
  	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>" class="txt_vert_11"><?php echo($i+1); ?></a> |
    	 <?php } else {?>
    		 <?php echo($i+1); ?>
    		 <span class="txt_vert_11">|</span>
   		  <?php } ?>
     <?php } ?>
     
   
    <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pagesuiv); ?>" class="txt_vert_11">Page suivante</a></p>
</div> 

</body>
</html>
