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
	include("auth.php");
	include_once("pre.php");
	
	if(!isset($action)) $action="";
	
?>
<?php
	include("../classes/Devise.class.php");

	if($action == "modifier"){
	
		$devise = new Devise();

 		$devise->charger($id);
 		$devise->taux = $taux;	
 		$devise->maj();
 			
		header("Location: devise.php");
	}		
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
	$menu="devises";
	include("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des devises / Modifier une devise </p>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">AJOUT / MODIFICATION D'UNE DEVISE</td>
     </tr>
   </table>
   <form action="<?php echo($_SERVER['PHP_SELF']); ?>" name="formdevise" method="post">
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="400" height="30" class="titre_cellule">D&Eacute;SIGNATION</td>
       <td width="130" class="titre_cellule">TAUX ACTUEL EN &euro; </td>
       <td width="130" class="titre_cellule">NOUVEAU TAUX  EN &euro; </td>
       <td width="10" class="titre_cellule">&nbsp;</td>
     </tr>
	 
  <?php
  	
	$devise = new Devise();

 	$query = "select * from $devise->table";
  	$resul = mysql_query($query, $devise->link);
  	
  	while($row = mysql_fetch_object($resul)){
  	
  ?>
     <tr>
       <td height="30" class="cellule_sombre"><input name="textfield" type="text" class="form" value="<?php echo($row->nom); ?>" size="10" /></td>
       <td class="cellule_sombre">
         <input name="textfield2" type="text" class="form" value="<?php echo($row->id); ?>" size="10" />
       </td>
       <td class="cellule_sombre"><input name="textfield" type="text" class="form" size="10" /></td>
       <td align="center" valign="middle" class="cellule_sombre"></td>
     </tr>
	 <?php
	 }
	 ?>
   </table>
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="cellule_sombre2">
         <div align="right"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="gestion_des_devises01.htm" class="txt_vert_11">valider </a></span> <a href="#"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></div>
       </td>
     </tr>
	 <input type="hidden" name="action" value="modifier" />



   </table> </form>
</div>
</body>
</html>
