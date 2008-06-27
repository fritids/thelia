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
	
	if(!isset($liste)) $liste="";
	if(!isset($i)) $i=0;
	if(!isset($id)) $id="";
	
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
	include_once("../classes/Caracteristique.class.php");
	include_once("../fonctions/divers.php");?>
<?php
	$menu="catalogue";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des caract&eacute;ristiques</p>
   <p align="right" class="geneva11Reg_3B4B5B"><span class="lien04"><a href="accueil.php" class="lien04">Accueil</a></span> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="catalogue.php" class="lien04"> Gestion du catalogue</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="caracteristique.php" class="lien04"> Gestion des caract&eacute;ristiques</a></p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES CARACTERISTIQUES </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

       <?php
	
	$caracteristique = new Caracteristique();
	$caracteristiquedesc = new Caracteristiquedesc();
	
	$query = "select * from $caracteristique->table";
	$resul = mysql_query($query, $caracteristique->link);		


	while($row = mysql_fetch_object($resul)){
		$liste .= $row->id . ",";
	
	}

	
	$liste = substr($liste, 0, strlen($liste)-1);
	
	if($liste != "") {

		$query = "select * from $caracteristiquedesc->table,$caracteristique->table where $caracteristiquedesc->table.caracteristique=$caracteristique->table.id and $caracteristiquedesc->table.caracteristique in ($liste) and $caracteristiquedesc->table.lang='1' order by classement";
		$resul = mysql_query($query, $caracteristiquedesc->link);
	
		while($row = mysql_fetch_object($resul)){	
			$caracteristiquedesc->charger($row->caracteristique);
			
			if(!($i%2)) $fond="cellule_sombre";
  			else $fond="cellule_claire";
  			$i++;
		

?>     
   
  <tr class="<?php echo($fond); ?>">
    <td width="26%" height="30"><a href="caracteristique_modifier.php?id=<?php echo($caracteristiquedesc->caracteristique); ?>" class="txt_vert_11"><?php echo($caracteristiquedesc->titre); ?></a></td>
    <td width="26%" height="30"><a href="<?php echo "caracteristique_modifier.php?id=$caracteristiquedesc->caracteristique"; ?>" class="txt_vert_11">Modifier <img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
    <td width="21%" height="30">
     <a href="<?php echo "caracteristique_modifier.php?id=$caracteristiquedesc->caracteristique&action=supprimer"; ?>" class="txt_vert_11">Supprimer <img src="gfx/suivant.gif" width="12" height="9" border="0" /></a>   </td>

	  <td width="6%" height="30">   
	    <div align="center"><a href="caracteristique_modifier.php?id=<?php echo($caracteristiquedesc->caracteristique); ?>&action=modclassement&type=M"><img src="gfx/up.gif" width="12" height="9" border="0" /></a></div>
	  </td>
	   <td width="6%" height="30">  
	     <div align="center"><a href="caracteristique_modifier.php?id=<?php echo($caracteristiquedesc->caracteristique); ?>&action=modclassement&type=D"><img src="gfx/dn.gif" width="12" height="9" border="0" /></a></div>
	   </td>
     
  </tr>
       
<?php
		}

		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
  		$i++;
}
?> 
 
  </table>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="5"></td>
    </tr>
    <tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre2">
	    <form action="caracteristique_modifier.php" id="form_ajout" method="post">
	 				      <input type="hidden" name="parent" value="<?php echo($parent); ?>" />
						  <input type="hidden" name="id" value="<?php echo($id); ?>" />
	  <a href="#" onClick="document.getElementById('form_ajout').submit()" class="lien_titre_cellule">AJOUTER UNE NOUVELLE CARACTERISTIQUE</a>
	  </form>
	  </td>
    </tr>
  </table>
</div>
</body>
</html>
