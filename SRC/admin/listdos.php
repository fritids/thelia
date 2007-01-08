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
?>
<?php
		if(!isset($boutique)) $boutique="";
		if(!isset($parent)) $parent=0;
		if(!isset($lang)) $lang=0;
		if(!isset($i)) $i=0;

	if($boutique != "")
	 	$_SESSION["bout"] = $boutique;
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

	include_once("../classes/Dossier.class.php");
	include_once("../classes/Dossierdesc.class.php");

	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Contenu.class.php");

	include_once("../fonctions/divers.php");
?>

<?php
	$menu="contenu";
	include("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion du contenu </p>
   <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><a href="#" onclick="document.getElementById('formulaire').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="contenu.php" class="lien04">Gestion du contenu</a>               

    <?php
                    $parentdesc = new Dossierdesc();
					$parentdesc->charger($parent, $lang);
					$parentnom = $parentdesc->titre;	
					$parentdesc->destroy();
					
					$res = chemin_dos($parent);
					$tot = count($res)-1;
	
?>
                             
						
				
			<?php
				while($tot --){
			?>
			<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<a href="listdos.php?parent=<?php echo($res[$tot+1]->dossier); ?>" class="lien04"><?php echo($res[$tot+1]->titre); ?></a>                             
            <?php
            	}
            
            ?>
            
			<?php
                    $parentdesc = new Dossierdesc();
					$parentdesc->charger($parent);
					$parentnom = $parentdesc->titre;	
					$parentdesc->destroy();
			?>
			<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<a href="listdos.php?parent=<?php echo($parentdesc->dossier); ?>" class="lien04"><?php echo($parentdesc->titre); ?></a>
           </p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES RUBRIQUES DE CONTENU </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">




<?php
	
	$dossier = new Dossier();
	$dossierdesc = new Dossierdesc();
	
	$query = "select * from $dossier->table where parent=\"$parent\" and boutique=\"" . $_SESSION['bout'] ."\" order by classement";
	$resul = mysql_query($query, $dossier->link);		

	while($row = mysql_fetch_object($resul)){
		$dossierdesc->charger($row->id);
?>

    
  <tr class="<?php echo($fond); ?>">
    <td width="26%" height="30" class="geneva11bol_3B4B5B"><?php echo($dossierdesc->titre); ?></td>
    <td width="26%" height="30"><a href="listdos.php?parent=<?php echo($dossierdesc->dossier); ?>" class="txt_vert_11">Poursuivre </a><a href="listdos.php?parent=<?php echo($dossierdesc->dossier); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a> </td>
    <td width="21%" height="30">
      <div align="left"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>" class="txt_vert_11">Modifier  </a><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
	 <td width="15%" height="30"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=supprimer&parent=<?php echo($parent); ?>" class="txt_vert_11">Supprimer</a> <a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=supprimer&parent=<?php echo($parent); ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a> </td>
	  <td width="6%" height="30">   
	    <div align="center"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" width="12" height="9" border="0" /></a></div>
	  </td>
	   <td width="6%" height="30">  
	     <div align="center"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" width="12" height="9" border="0" /></a></div>
	   </td>
  </tr>

     
<?php
}
?>  

<?php
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
?>

  <tr class="<?php echo($fond); ?>">
    <td height="30" colspan="6" align="center">
      <div align="left"><a href="dossier_modifier.php?parent=<?php echo($parent); ?>" class="txt_vert_11">Ajouter un dossier de contenu</a> <a href="dossier_modifier.php?parent=<?php echo($parent); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
      </td>
    </tr>
 
  </table>
  
  <br /><br />
  
      
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES CONTENUS </td>
     </tr>
   </table>
  
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

<?php
	
	$contenu = new Contenu();
	$contenudesc = new ContenuDesc();
	
	$query = "select * from $contenu->table where dossier=\"$parent\" and boutique=\"" . $_SESSION['bout'] ."\" order by classement";
	$resul = mysql_query($query, $contenu->link);		

	while($row = mysql_fetch_object($resul)){
		$contenudesc->charger($row->id);
?>

    
  <tr class="<?php echo($fond); ?>">
    <td width="26%" height="30" class="geneva11bol_3B4B5B"><?php echo($contenudesc->titre); ?></td>
    <td width="26%" height="30">&nbsp;</td>
    <td width="21%" height="30">
      <div align="left"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>" class="txt_vert_11">Modifier  </a><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
	 <td width="15%" height="30"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=supprimer&parent=<?php echo($parent); ?>" class="txt_vert_11">Supprimer</a> <a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=supprimer&parent=<?php echo($parent); ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a> </td>
	  <td width="6%" height="30">   
	    <div align="center"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" width="12" height="9" border="0" /></a></div>
	  </td>
	   <td width="6%" height="30">  
	     <div align="center"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" width="12" height="9" border="0" /></a></div>
	   </td>
  </tr>

     
<?php
}
?>  <?php
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
?>
  <tr class="<?php echo($fond); ?>">
    <td height="30" colspan="6" align="center">
      <div align="left"><a href="contenu_modifier.php?dossier=<?php echo($parent); ?>" class="txt_vert_11">Ajouter un contenu</a> <a href="contenu_modifier.php?parent=<?php echo($parent); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
      </td>
    </tr>   
  </table>
</div>
</body>
</html>
