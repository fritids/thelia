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
	include_once("../lib/pclzip.lib.php");
	
	include_once("../classes/Contrib.class.php");
	include_once("../classes/Racmodule.class.php");	
		
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
	include_once("../classes/Modules.class.php");
?>
<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des plugins</p>
   <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="plugins.php" class="lien04">Gestion des plugins</a>            
    </p>

<?php

	$contrib = new Contrib();
	$tab = $contrib->charger_tous();

	if(isset($_FILES['plugin'])){
		$plugin = $_FILES['plugin']['tmp_name']; 
		$plugin_name = $_FILES['plugin']['name'];
	}
	
	if(isset($action) && $action == "ajouter" && isset($plugin)){
		
		if($plugin) copy("$plugin", "../client/plugins/" . $plugin_name);
		$archive = new PclZip("../client/plugins/" . $plugin_name);
		$list = $archive->extract('../client/plugins/');
		if ($list == 0) {
		  die("ERROR : '".$archive->errorInfo(true)."'");
		}
		
		unlink("../client/plugins/" . $plugin_name);
	}
		
	$i=0;
	
	$d = dir("../client/plugins");

	while (false !== ($entry = $d->read())) {
	   
	if( substr($entry, 0, 1) == ".") continue;
		 $modules = new Modules();
		 $modules->charger($entry);

		$nomclass = $entry;
		$nomclass[0] = strtoupper($nomclass[0]);
		
		if(file_exists(realpath(dirname(__FILE__)) . "/../client/plugins/" . $entry . "/" . $nomclass . ".class.php")){
			
			include_once(realpath(dirname(__FILE__)) . "/../client/plugins/" . $entry . "/" . $nomclass . ".class.php");
			$tmpobj = new $nomclass();
	
			$type=0;
			if(strtolower(get_parent_class($tmpobj)) == "pluginsclassiques") $type="3";
			if(strtolower(get_parent_class($tmpobj)) == "pluginspaiements") $type="1";
			if(strtolower(get_parent_class($tmpobj)) == "pluginstransports") $type="2";
			
			if($type){
			
		 		if(! $modules->id){
			
					$modules = new Modules();
					$modules->nom = $entry;
					$modules->type=$type;
					$modules->actif=0;
					$modules->add();
			
		 		}
		
		
			}
		}
	
	}

  	$d->close();
?>

     <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100%" height="30" class="titre_cellule_tres_sombre">LISTE DES PLUGINS CLASSIQUES</td>
     </tr>
   </table>

<?php	
	$query = "select * from $modules->table where type='3'";
	$resul = mysql_query($query, $modules->link);
	
	while($row = mysql_fetch_object($resul)){
			
		 $i++;
	
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";

		$res = $contrib->chercher($row->nom, $tab);

		if($res)
			$titre = $res->titre;
		
		else
			$titre = $row->nom;
		
	
?>

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="<?php echo $fond; ?>">
    <td width="41%" height="30"><?php echo $titre; ?></td>
    <td width="48%" height="30">
      
    </td>
    <td width="17%" height="30">
      <div align="left">
	<?php 
		if($row->actif){

			if(file_exists("../client/plugins/" .$row->nom . "/" . $row->nom. "_admin.php")){
	?>
	
	<?php
			$rac = new Racmodule();
			if(	! $rac->charger($row->nom)){
	?>
				<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&rac=1" class="txt_vert_11">+</a>
		
	<?php
		} else {
	?>
		<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&rac=0" class="txt_vert_11">-</a>
	<?php	
		}	
			}
	?>	
		<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&actif=0" class="txt_vert_11">D&eacute;sactiver </a>

	<?php
		} else {
	?>

		<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&actif=1" class="txt_vert_11">Activer </a>
		
	<?php
			
		}
	?>
	   </div>
    </td>
  
</tr>

 
  </table>

<?php 

	}
	

 
?>

<br />

    <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100%" height="30" class="titre_cellule_tres_sombre">LISTE DES PLUGINS PAIEMENTS</td>
     </tr>
   </table>

<?php	
	$query = "select * from $modules->table where type='1'";
	$resul = mysql_query($query, $modules->link);
	
	while($row = mysql_fetch_object($resul)){
			
		 $i++;
	
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";

		$res = $contrib->chercher($row->nom, $tab);

		if($res)
			$titre = $res->titre;
		
		else
			$titre = $row->nom;
	
?>

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="<?php echo $fond; ?>">
    <td width="21%" height="30"><?php echo $titre; ?></td>
    <td width="69%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left">
	<?php 
		if($row->actif){
	?>
		<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&actif=0" class="txt_vert_11">D&eacute;sactiver </a>
	<?php
		} else {
	?>

		<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&actif=1" class="txt_vert_11">Activer </a>
		
	<?php
			
		}
	?>
	   </div>
    </td>
  
</tr>

 
  </table>

<?php 

	}
	

 
?>

<br />


    <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100%" height="30" class="titre_cellule_tres_sombre">LISTE DES PLUGINS TRANSPORTS</td>
     </tr>
   </table>

<?php	
	$query = "select * from $modules->table where type='2'";
	$resul = mysql_query($query, $modules->link);
	
	while($row = mysql_fetch_object($resul)){
			
		 $i++;
	
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
	
		$res = $contrib->chercher($row->nom, $tab);

		if($res)
			$titre = $res->titre;
		
		else
			$titre = $row->nom;
	
?>

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="<?php echo $fond; ?>">
    <td width="21%" height="30"><?php echo $titre; ?></td>
    <td width="69%" height="30">
      
    </td>
    <td width="16%" height="30">
      <div align="left">
	<?php 
		if($row->actif){
	?>
		<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&actif=0" class="txt_vert_11">D&eacute;sactiver </a>
	<?php
		} else {
	?>

		<a href="plugins_modifier.php?nom=<?php echo $row->nom ?>&actif=1" class="txt_vert_11">Activer </a>
		
	<?php
			
		}
	?>
	   </div>
    </td>
  
</tr>

 
  </table>

<?php 

	}
	

 
?>

<br />

<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="100%" height="30" class="titre_cellule_tres_sombre">AJOUTER UN PLUGIN </td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30" align="left" valign="middle" class="titre_cellule">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" ENCTYPE="multipart/form-data">
				<input type="hidden" name="action" value="ajouter" />
                <input type="file" name="plugin" />
               <input type="submit" value="Valider" />
               
               <br /><br />
                      </form></td>
  </tr>
</table>

</div>
</div>
</div>
</body>
</html>
