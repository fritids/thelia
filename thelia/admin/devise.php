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
	include_once("../classes/Devise.class.php");
	
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
	$menu="paiement";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des devises</p>
   <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="paiement.php" class="lien04">Gestion du paiement</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="devise.php" class="lien04">Gestion des devises</a>    </p>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES DEVISES UTILIS&Eacute;ES SUR LE SITE </td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="400" height="30" class="titre_cellule">D&Eacute;SIGNATION</td>
       <td width="130" class="titre_cellule">TAUX ACTUEL EN &euro; </td>
       <td width="130" class="titre_cellule">&nbsp;</td>
       <td width="10" class="titre_cellule">&nbsp;</td>
     </tr>
	 
  <?php
  	
	$devise = new Devise();

 	$query = "select * from $devise->table";
  	$resul = mysql_query($query, $devise->link);
  	
  	while($row = mysql_fetch_object($resul)){
  	
  ?>
     <form action="devise_modifier.php" id="formdevise<?php echo($row->id); ?>" method="post">

     <tr>
       <td height="30" class="cellule_sombre"><input name="nom" type="text" class="form" value="<?php echo($row->nom); ?>" size="10" /></td>
       <td class="cellule_sombre">
         <input name="taux" type="text" class="form" value="<?php echo($row->taux); ?>" size="10" />
       </td>
       <td class="cellule_sombre"><a href="#" class="txt_vert_11" onclick="document.getElementById('formdevise<?php echo($row->id); ?>').submit();">Modifier</a> <a href="#"><img src="gfx/suivant.gif" onclick="document.getElementById('formdevise<?php echo($row->id); ?>').submit();" width="12" height="9" border="0" /></a></span></span></td>
       <td align="center" valign="middle" class="cellule_sombre"><a href="devise_modifier.php?action=supprimer&id=<?php echo $row->id ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></td>
     </tr>
   
   <input type="hidden" name="action" value="modifier" />
   <input type="hidden" name="id" value="<?php echo($row->id); ?>" />
   </form>   
	 <?php

	}
 ?>
	 </table> 
	</form>     
   <br />
   
     

      
   <table width="710" border="0" cellpadding="5" cellspacing="0">

      <form action="devise_modifier.php" id="formajouter" method="post">
      
    <tr>
      <td height="5"></td>
    </tr>

     <tr>
       <td height="30" width="400" class="cellule_sombre"><input name="nnom" type="text" class="form"  size="10" /></td>
       <td class="cellule_sombre">
         <input name="ntaux" type="text" class="form"" size="10" />
       </td>
   
     </tr>

</table>



   <table width="710" border="0" cellpadding="5" cellspacing="0">

  <tr>
      <td width="710" height="30" class="titre_cellule_tres_sombre2">
      <input type="hidden" name="action" value="ajouter" />
      <a href="#" onclick="document.getElementById('formajouter').submit()" class="lien_titre_cellule">AJOUTER UNE DEVISE </a></td>
    </tr>  
   
  
</table>

</form>

</div>
</body>
</html>
