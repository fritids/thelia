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
	$menu="plugins";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Liste des modules</p>
     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Liste des modules</a>              
    </p>
     <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES MODULES </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

       <?php

	include_once("../classes/Modules.class.php");
	$modules = new Modules();	
	$query = "select * from $modules->table where actif='1'";
	$resul = mysql_query($query, $modules->link);
	
	$i=0;
	
	while($row = mysql_fetch_object($resul)){
	
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
  		$i++;

		if(file_exists("../client/plugins/" .$row->nom . "/" . $row->nom. "_admin.php")){
				$nom_module = $row->nom;
				$nom_module[0] = strtoupper($nom_module[0]);
				

?>     
   


    
  <tr class="<?php echo($fond); ?>">
    <td width="21%" height="30"><?php echo($row->nom); ?></td>
    <td width="63%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left"><a href="module.php?nom=<?php echo $row->nom; ?>" class="txt_vert_11">Poursuivre </a><a href="module.php?nom=<?php echo $row->nom; ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
  </tr>

     
<?php
	}
 }
?>  
  </table>
</div>
</body>
</html>