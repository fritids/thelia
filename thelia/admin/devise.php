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
<?php if(! est_autorise("acces_configuration")) exit; ?>
<?php
	include_once("../classes/Devise.class.php");
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>

<script type="text/javascript" src="../lib/jquery/jquery.js"></script>

</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p align="left"><a href="accueil.php" class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="devise.php" class="lien04">Gestion des devises</a></p>
   
<!-- Début de la colonne de gauche -->  
<div id="bloc_description">
		<!-- entete devises -->  	
		<div class="entete_liste_config">
			<div class="titre">LISTE DES DEVISES UTILIS&Eacute;ES SUR LE SITE </div>
			<div class="fonction_valider"><a href="#" onclick="$('#ajout_devise').show()">AJOUTER UNE DEVISE</a></div>
		</div>
<ul class="Nav_bloc_description">
		<li style="height:25px; width:89px;">D&eacute;signation</li>
		<li style="height:25px; width:88px; border-left:1px solid #96A8B5;">Symbole</li>
		<li style="height:25px; width:88px; border-left:1px solid #96A8B5; ">Code iso</li>
		<li style="height:25px; width:100px; border-left:1px solid #96A8B5;">Taux actuel en &euro;</li>
</ul>
<div class="bordure_bottom">
<?php
	$devise = new Devise();

 	$query = "select * from $devise->table";
  	$resul = mysql_query($query, $devise->link);
  	$i=0;
  	while($row = mysql_fetch_object($resul)){
  	 	if(!($i%2)) $fond="ligne_claire_BlocDescription";
  		else $fond="ligne_fonce_BlocDescription";
  		$i++;
?>

     <form action="devise_modifier.php" id="formdevise<?php echo($row->id); ?>" method="post">
		<ul class="<?php echo($fond); ?>">
			<li style="height:25px; width:90px;"><input name="nom" type="text" class="form" value="<?php echo($row->nom); ?>" size="10" /></li>
			<li style="height:25px; width:90px; border-left:1px solid #96A8B5;"><input name="symbole" type="text" class="form" value="<?php echo($row->symbole); ?>" size="10" /></li>
			<li style="height:25px; width:90px; border-left:1px solid #96A8B5; "><input name="code" type="text" class="form" value="<?php echo($row->code); ?>" size="10" /></li>
			<li style="height:25px; width:205px; border-left:1px solid #96A8B5;"><input name="taux" type="text" class="form" value="<?php echo($row->taux); ?>" size="10" /></li>
			<li style="height:25px; width:55px; border-left:1px solid #96A8B5;"><a href="#" onclick="document.getElementById('formdevise<?php echo($row->id); ?>').submit();">modifier</a></li>
			<li style="height:25px; width:13px; border-left:1px solid #96A8B5; text-align:right;"><a href="devise_modifier.php?action=supprimer&id=<?php echo $row->id ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
		</ul>
	<input type="hidden" name="action" value="modifier" />
   	<input type="hidden" name="id" value="<?php echo($row->id); ?>" />
   	</form>   
<?php } ?>
</div>

<div class="bordure_bottom" id="ajout_devise" style="display: none;">
<form action="devise_modifier.php" id="formajouter" method="post">
<input type="hidden" name="action" value="ajouter">
		<div class="entete_liste_config" style="margin-top:10px;">
			<div class="titre">AJOUT D'UNE DEVISE</div>
		</div>
		<ul class="Nav_bloc_description">
			<li style="height:25px; width:90px;">Désignation</li>
			<li style="height:25px; width:90px; border-left:1px solid #96A8B5;">Symbole</li>
			<li style="height:25px; width:90px; border-left:1px solid #96A8B5; ">Code iso</li>
			<li style="height:25px; width:100px; border-left:1px solid #96A8B5;">Taux actuel en &euro;</li>
		</ul>
		<ul class="claire">
				<li style="height:25px; width:90px;"><input name="nnom" type="text" class="form" size="10" /></li>
				<li style="height:25px; width:90px; border-left:1px solid #96A8B5;"><input name="nsymbole" type="text" class="form" size="10" /></li>
				<li style="height:25px; width:90px; border-left:1px solid #96A8B5;"><input name="ncode" type="text" class="form" size="10" /></li>
				<li style="height:25px; width:205px; border-left:1px solid #96A8B5;"><input name="ntaux" type="text" class="form" size="10" /></li>
				<li style="height:25px; width:50px; border-left:1px solid #96A8B5;"><a href="#" onclick="document.getElementById('formajouter').submit()">ajouter</a></li>
		</ul>
</form>
</div>


</div>
<!-- fin du bloc de description / colonne de gauche -->

<!-- bloc d'ajout des devises / colonne de droite-->   
<div id="bloc_colonne_droite">
	<div class="entete_config">
		<div class="titre">MISE A JOUR AUTOMATIQUE</div>
		<div class="maj">
      		<a href="devise_modifier.php?action=refresh">METTRE A JOUR</a>
     	</div>
	</div>
</div>
<!-- fin du bloc colonne de droite -->

</div>

<?php include_once("pied.php");?>
</div>	
</div>
</body>
</html>
