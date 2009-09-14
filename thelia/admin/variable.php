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
	include_once("../classes/Variable.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p align="left"><a href="accueil.php" class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="variable.php" class="lien04">Gestion des variables</a></p>
   
<!-- bloc dŽclinaisons / colonne gauche -->  
<div id="bloc_description">
<div class="entete_liste_config">
	<div class="titre">LISTE DES VARIABLES</div>
</div>
<ul class="Nav_bloc_description">
		<li style="height:25px; width:150px;">Nom</li>
		<li style="height:25px; width:360px; border-left:1px solid #96A8B5;">Valeur</li>
</ul>
<div class="bordure_bottom">
	<?php
	$variable = new Variable();

 	$query = "select * from $variable->table where cache='0'";
  	$resul = mysql_query($query, $variable->link);
  	$i=0;
  	while($row = mysql_fetch_object($resul)){
  	
  	 	if(!($i%2)) $fond="ligne_claire_BlocDescription";
  		else $fond="ligne_fonce_BlocDescription";
  		$i++;

  ?>
     <form action="variable_modifier.php" id="formvariable<?php echo($row->id); ?>" method="post">

		<ul class="<?php echo $fond; ?>">
			<li style="width:151px;"><?php echo($row->nom); ?></li>
			<li style="width:360px; border-left:1px solid #96A8B5;"><input name="valeur" type="text" class="form" value="<?php echo($row->valeur); ?>" size="50" /></li>
			<li style="width:50px; border-left:1px solid #96A8B5;"><a href="#" onclick="document.getElementById('formvariable<?php echo($row->id); ?>').submit();">modifier</a></li>
		</ul>
     
   
   <input type="hidden" name="action" value="modifier" />
   <input type="hidden" name="nom" value="<?php echo($row->nom); ?>" />
   </form>   
	 <?php

	}
 ?>
</div>    
</div>
<!-- fin du bloc de description / colonne de gauche -->
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
