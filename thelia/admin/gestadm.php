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
	include_once("../classes/Administrateur.class.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	function valid(admin){

		if(document.getElementById('motdepasse1' + admin ).value == document.getElementById('motdepasse2' + admin ).value)
			document.getElementById('formadmin' + admin).submit();
		else{
			alert("Veuillez verifier votre mot de passe");
			return false;
		}
	}
</script>

</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des administrateurs</p>
   <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil</a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="variable.php" class="lien04">Gestion des administrateurs</a>    </p>
   <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100%" height="30" class="titre_cellule_tres_sombre">LISTE DES ADMINISTRATEURS</td>
     </tr>
   </table>
   <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="114" height="30" class="titre_cellule">IDENTIFIANT</td>
       <td width="114" height="30" class="titre_cellule">PRENOM</td>
       <td width="114" height="30" class="titre_cellule">NOM</td>
       <td width="114" class="titre_cellule">MOT DE PASSE</td>
       <td width="114" class="titre_cellule">CONFIRMATION DU MOT DE PASSE</td>
       <td width="70" class="titre_cellule">&nbsp;</td>
       <td width="70" class="titre_cellule">&nbsp;</td>
     </tr>
	 
  <?php
  	
	$administrateur = new Administrateur();

 	$query = "select * from $administrateur->table";
  	$resul = mysql_query($query, $administrateur->link);
  	
  	while($row = mysql_fetch_object($resul)){

  	
  ?>
     <form action="gestadm_modifier.php" id="formadmin<?php echo($row->id); ?>" method="post" onsubmit="valid('<?php echo $row->id; ?>');return false;">

     <tr>
       <td height="30" class="cellule_sombre"><input name="identifiant" type="text" class="form" value="<?php echo($row->identifiant); ?>" size="12" /></td>
   	   <td height="30" class="cellule_sombre"><input name="prenom" type="text" class="form" value="<?php echo($row->prenom); ?>" size="12" /></td>
   	   <td height="30" class="cellule_sombre"><input name="nom" type="text" class="form" value="<?php echo($row->nom); ?>" size="12" /></td>

       <td class="cellule_sombre"><input name="motdepasse1" id="motdepasse1<?php echo($row->id); ?>" type="password" value="<?php echo $pass; ?>" class="form" size="12" onclick="this.value='';" /></td>
	   <td class="cellule_sombre"><input name="motdepasse2" id="motdepasse2<?php echo($row->id); ?>" type="password" value="<?php echo $pass; ?>" class="form" size="12" onclick="this.value='';" /></td>

       <td class="cellule_sombre"><a href="#" class="txt_vert_11" onclick="valid('<?php echo $row->id; ?>');">Modifier</a> <a href="#"><img src="gfx/suivant.gif" onclick="valid('<?php echo $row->id; ?>');" width="12" height="9" border="0" /></a></span></span></td>
       <td align="center" valign="middle" class="cellule_sombre">&nbsp;</td>
     </tr>
   
   <input type="hidden" name="action" value="modifier" />
   <input type="hidden" name="id" value="<?php echo($row->id); ?>" />
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
