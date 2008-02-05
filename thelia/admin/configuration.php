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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
	include_once("../classes/Cache.class.php");
	
?>
<?php
	$menu="configuration";
	include_once("entete.php");
?>
<?php
	if(isset($action) && $action == "videcache"){
		$cache = new Cache();
		$query = "delete from $cache->table";
		$resul = mysql_query($query, $cache->link);
	}
?>
<div id="contenu_int"> 
   <p class="titre_rubrique">Configuration </p>
     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Configuration</a>              
    </p>
     <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">VOTRE CHOIX</td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_sombre">
    <td width="21%" height="30">Gestion des variables</td>
    <td width="63%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="variable.php" class="txt_vert_11">Poursuivre </a><a href="variable.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>
 
  </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_claire">
    <td width="21%" height="30">Gestion des messages</td>
    <td width="63%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="message.php" class="txt_vert_11">Poursuivre </a><a href="message.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>

  </table>

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_sombre">
    <td width="21%" height="30">Gestion des administrateurs</td>
    <td width="63%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="gestadm.php" class="txt_vert_11">Poursuivre </a><a href="gestadm.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>

  </table>

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_claire">
    <td width="21%" height="30">Gestion des plugins</td>
    <td width="63%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="plugins.php" class="txt_vert_11">Poursuivre </a><a href="plugins.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>
 
  </table>

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_sombre">
    <td width="21%" height="30">Vider le cache</td>
    <td width="63%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="configuration.php?action=videcache" class="txt_vert_11">Poursuivre </a><a href="configuration.php?action=videcache"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>

 
  </table>
</div>
</body>
</html>
