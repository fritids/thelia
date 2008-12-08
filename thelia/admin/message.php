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
	include_once("../classes/Message.class.php");
	include_once("../classes/Messagedesc.class.php");
?>
<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des messages </p>
     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="#" class="lien04">Gestion des messages</a>           
    </p>
     <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES MESSAGES</td>
     </tr>
   </table>


<?php
	$i=0;
	
	$message = new Message();
	$query = "select * from $message->table";
	$resul = mysql_query($query, $message->link);
	
	while($row = mysql_fetch_object($resul)){
		
		 $i++;
		
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";

		$messagedesc = new Messagedesc();
		$messagedesc->charger($row->id);
?>

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="<?php echo $fond; ?>">
    <td width="41%" height="30"><?php if($messagedesc->intitule != "") echo $messagedesc->intitule; else echo $row->nom; ?></td>
    <td width="43%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="message_modifier.php?nom=<?php echo $row->nom ?>" class="txt_vert_11">Poursuivre </a><a href="message.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>

 
  </table>

<?php 

	}
?>

</div>
</body>
</html>
