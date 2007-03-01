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
	include("auth.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<?php
	include("../classes/Rubrique.class.php");
	include("../fonctions/divers.php");
	include("../classes/Promo.class.php");
	
	if(!isset($id)) $id="";
	if(!isset($types)) $types="";
	if(!isset($utiliseo)) $utiliseo="";
	if(!isset($illimiteo)) $illimiteo="";

	$utilisen = "";
	$illimiten = "";
	$typep = "";
		
?>


<?php
	$promo = new Promo();
	$promo->charger_id($id);
	
	if($promo->utilise) $utiliseo = "checked=\"checked\"";
	else $utilisen = "checked=\"checked\""; 

	if($promo->illimite) $illimiteo = "checked=\"checked\"";
	else $illimiten = "checked=\"checked\""; 

	if($promo->type == "1") $types = "checked=\"checked\"";
	else $typep = "checked=\"checked\""; 

	

?>
</head>

<body>

<?php
	$menu="paiement";
	include("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des codes promos</p>
<p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="paiement.php" class="lien04">Gestion du paiement</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="promo.php" class="lien04">Gestion des codes promos</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04"><?php if($id) { ?>Modifier <?php } else { ?> Ajouter <?php } ?></a>                   
    </p>
   
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">INFORMATIONS SUR LES CODES PROMOS</td>
     </tr>
   </table>
   
 <form action="promo.php" id="formulaire" method="post">

<input type="hidden" name="action" value="<?php if($id != "") { ?>modifier<?php } else { ?>ajouter<?php } ?>" />
<input type="hidden" name="id" value="<?php echo($id); ?>" />
    
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="titre_cellule">CODE</td>
       <td class="cellule_sombre">
         <input name="code" type="text" class="form" value="<?php echo($promo->code); ?>" size="30" />
       </span></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">TYPE</td>
       <td class="cellule_claire">
         <input name="type" type="radio" class="form" value="1" <?php echo($types); ?> />
somme
<input name="type" type="radio" class="form" value="2" <?php echo($typep); ?> />
pourcentage
</td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">VALEUR </td>
       <td class="cellule_sombre">
       <input name="valeur" type="text" class="form" value="<?php echo($promo->valeur); ?>" size="10" />       </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">UTILISE</td>
       <td class="cellule_claire">
         Oui <input name="utilise" type="radio" class="form" value="1" <?php echo($utiliseo); ?> /> &nbsp; Non <input name="utilise" type="radio" class="form" value="0" <?php echo($utilisen); ?> />
       </span></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">ILLIMITE</td>
       <td class="cellule_claire">
         Oui <input name="illimite" type="radio" class="form" value="1" <?php echo($illimiteo); ?> /> &nbsp; Non <input name="illimite" type="radio" class="form" value="0" <?php echo($illimiten); ?> />
       </span></td>
     </tr>
   </table>
</form>
   
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" class="txt_vert_11" onClick="document.getElementById('formulaire').submit()">Valider les modifications </a></span> <a href="#" onClick="document.getElementById('formulaire').submit()><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
     </tr>
   </table>
   </div>
</body>
</html>
