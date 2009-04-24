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

<?php
	include_once("../classes/Rubrique.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Promo.class.php");
	
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
	
	if(! $promo->utilise) $utiliseo = "checked=\"checked\"";
	else $utilisen = "checked=\"checked\""; 

	if($promo->illimite) $illimiteo = "checked=\"checked\"";
	else $illimiten = "checked=\"checked\""; 

	if($promo->type == "1") $types = "checked=\"checked\"";
	else $typep = "checked=\"checked\""; 

	$jour = substr($promo->datefin, 8, 2);
	$mois = substr($promo->datefin, 5, 2);
	$annee = substr($promo->datefin, 0, 4);	

?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="paiement";
	include_once("entete.php");
?>

<div id="contenu_int">
<p><a href="accueil.php" class="lien04">Accueil </a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="paiement.php" class="lien04">Gestion du paiement</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="promo.php" class="lien04">Gestion des codes promos</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04"><?php if($id) { ?>Modifier <?php } else { ?> Ajouter <?php } ?></a>                   
    </p>

<!-- DŽbut de la colonne de gauche -->  
<div id="bloc_description">
<div class="bordure_bottom">
<form action="promo.php" id="formulaire" method="post">
<div class="entete">
			<div class="titre">MODIFICATION DU CODE PROMOTION</div>
			<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit()">VALIDER LES MODIFICATIONS</a></div>
</div>
<input type="hidden" name="action" value="<?php if($id != "") { ?>modifier<?php } else { ?>ajouter<?php } ?>" />
<input type="hidden" name="id" value="<?php echo($id); ?>" />
  	<ul class="ligne_claire_BlocDescription" style="background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">
		<li class="designation" style="width:280px; background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">Code</li>
		<li><input name="code" type="text" class="form" value="<?php echo($promo->code); ?>" size="40" /></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">Type</li>
		<li><input name="type" type="radio" class="form" value="1" <?php echo($types); ?> />
somme
<input name="type" type="radio" class="form" value="2" <?php echo($typep); ?> />
pourcentage</li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">Montant du code promotion</li>
		<li><input name="valeur" type="text" class="form" value="<?php echo($promo->valeur); ?>" size="10" /></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">Montant d'achat minimum</li>
		<li><input name="mini" type="text" class="form" value="<?php echo($promo->mini); ?>" size="10" /></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">Code actif</li>
		<li>Oui <input name="utilise" type="radio" class="form" value="0" <?php echo($utiliseo); ?> /> &nbsp; Non <input name="utilise" type="radio" class="form" value="1" <?php echo($utilisen); ?> /></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">Utilisation unique ou illimit&eacute;</li>
		<li>Unique<input name="illimite" type="radio" class="form" value="1" <?php echo($illimiteo); ?> /> &nbsp; Illimit&eacute; <input name="illimite" type="radio" class="form" value="0" <?php echo($illimiten); ?> /></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">Date d'expiration</li>
		<li><input name="jour" type="text" class="form" value="<?php echo($jour); ?>" size="2" /> 
       <input name="mois" type="text" class="form" value="<?php echo($mois); ?>" size="2" />
	   <input name="annee" type="text" class="form" value="<?php echo($annee); ?>" size="4" /></li>
	</ul>
</form>
</div>   

</div>
<!-- fin du bloc description -->
   
   </div>
   <?php include_once("pied.php");?>
   </div>
   </div>
</body>
</html>
