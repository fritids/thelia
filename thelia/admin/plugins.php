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
<?php include_once("title.php");?>
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
   <p align="left"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="plugins.php" class="lien04">Gestion des plugins</a>            
    </p>
<div id="bloc_informations">
	<ul style="width:956px; margin-bottom:10px; background-color:red">
	<li class="entete_configuration" style="width:451px">AJOUTER UN PLUGIN</li>
	<li class="entete_configuration" style="padding:4px 0 5px 0; width:500px" ><div class="fonction_ajout" style="padding-top:-10px;">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" ENCTYPE="multipart/form-data">
				<input type="hidden" name="action" value="ajouter" />
                <input type="file" name="plugin" class="form" />
               	<input type="submit" value="Valider" />
		</form>
	</div></li>
	</ul>
	<ul>
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
<li class="entete_configuration">LISTE DES PLUGINS CLASSIQUES</li>

<?php	
	$query = "select * from $modules->table where type='3'";
	$resul = mysql_query($query, $modules->link);
	
	while($row = mysql_fetch_object($resul)){
			
		 $i++;
	
		if(!($i%2)) $fond="claire";
  		else $fond="fonce";

		$res = $contrib->chercher($row->nom, $tab);

		if($res)
			$titre = $res->titre;
		
		else
			$titre = $row->nom;
		
	
?>
<li class="<?php echo $fond; ?>" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;"><?php echo $titre; ?></li>
	<li class="<?php echo $fond; ?>" style="width:72px;"><?php 
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
</li>

<?php 
	}
?>
</ul>
<ul>
	<li class="entete_configuration">LISTE DES PLUGINS PAIEMENTS</li>
	
<?php	
	$query = "select * from $modules->table where type='1'";
	$resul = mysql_query($query, $modules->link);
	
	while($row = mysql_fetch_object($resul)){
			
		 $i++;
	
		if(!($i%2)) $fond="fonce";
  		else $fond="claire";

		$res = $contrib->chercher($row->nom, $tab);

		if($res)
			$titre = $res->titre;
		
		else
			$titre = $row->nom;
	
?>
<li class="<?php echo $fond; ?>" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;"><?php echo $titre; ?></li>
	<li class="<?php echo $fond; ?>" style="width:72px;">
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
</li>
<?php 
	}
?>
</ul>
<ul>
	<li class="entete_configuration">LISTE DES PLUGINS TRANSPORTS</li>
<?php	
	$query = "select * from $modules->table where type='2'";
	$resul = mysql_query($query, $modules->link);
	
	while($row = mysql_fetch_object($resul)){
			
		 $i++;
	
		if(!($i%2)) $fond="claire";
  		else $fond="fonce";
	
		$res = $contrib->chercher($row->nom, $tab);

		if($res)
			$titre = $res->titre;
		
		else
			$titre = $row->nom;
	
?>
<li class="<?php echo $fond; ?>" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;"><?php echo $titre; ?></li>
	<li class="<?php echo $fond; ?>" style="width:72px;">
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
</li>
<?php 
	}
?>
</ul>

</div>
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
