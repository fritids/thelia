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
	include_once("../classes/Variable.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des variables</p>
   <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="variable.php" class="lien04">Gestion des variables</a>    </p>
   <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100%" height="30" class="titre_cellule_tres_sombre">LISTE DES VARIABLES</td>
     </tr>
   </table>
   <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="400" height="30" class="titre_cellule">NOM</td>
       <td width="130" class="titre_cellule">Valeur</td>
       <td width="130" class="titre_cellule">&nbsp;</td>
       <td width="10" class="titre_cellule">&nbsp;</td>
     </tr>
	 
  <?php
  	
	$variable = new Variable();

 	$query = "select * from $variable->table where cache='0'";
  	$resul = mysql_query($query, $variable->link);
  	
  	while($row = mysql_fetch_object($resul)){
  	
  ?>
     <form action="variable_modifier.php" id="formvariable<?php echo($row->id); ?>" method="post">

     <tr>
       <td height="30" class="cellule_sombre"><?php echo($row->nom); ?></td>
       <td class="cellule_sombre">
         <input name="valeur" type="text" class="form" value="<?php echo($row->valeur); ?>" size="50" />
       </td>
       <td class="cellule_sombre"><a href="#" class="txt_vert_11" onclick="document.getElementById('formvariable<?php echo($row->id); ?>').submit();">Modifier</a> <a href="#"><img src="gfx/suivant.gif" onclick="document.getElementById('formvariable<?php echo($row->id); ?>').submit();" width="12" height="9" border="0" /></a></span></span></td>
       <td align="center" valign="middle" class="cellule_sombre">&nbsp;</td>
     </tr>
   
   <input type="hidden" name="action" value="modifier" />
   <input type="hidden" name="nom" value="<?php echo($row->nom); ?>" />
   </form>   
	 <?php

	}
 ?>
	 </table> 
	</form>     

</div>
</div>
</div>
</body>
</html>
