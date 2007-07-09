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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
	include_once("../classes/Boutique.class.php");
?>
<?php
	$menu="paiement";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion du paiement</p>
     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion du paiement</a>              
    </p>
     <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">VOTRE CHOIX</td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_sombre">
    <td width="31%" height="30">Gestion des codes promos</td>
    <td width="53%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="promo.php" class="txt_vert_11">Poursuivre </a><a href="promo.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>
 
  </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_claire">
    <td width="31%" height="30">Gestion des devises</td>
    <td width="53%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="devise.php" class="txt_vert_11">Poursuivre </a><a href="devise.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>

  <tr class="cellule_sombre">
    <td width="31%" height="30">Gestion des modes de paiement</td>
    <td width="53%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="modepay.php" class="txt_vert_11">Poursuivre </a><a href="modepay.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>
 
  </table>

</div>
</body>
</html>
