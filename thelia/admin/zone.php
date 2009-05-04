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
    include_once("../fonctions/divers.php");
    include_once("../classes/Zone.class.php");
    include_once("../classes/Pays.class.php");
    include_once("../classes/Paysdesc.class.php");
?>
<?php

	if($_POST['action'] == "ajouter" && $_POST['nomzone'] != ""){
        $zone = new Zone();
		$zone->nom = $_POST['nomzone'];
		$id = $zone->add();	
	}

	else if($_GET['action'] == "supprimer" && $_GET['id'] != ""){
	
        $zone = new Zone();
		$pays = new Pays();
		
        $query = "update $pays->table set zone=\"-1\" where zone=\"" . $_GET['id'] . "\"";   
        $resul = mysql_query($query, $pays->link);
        $zone->charger($_GET['id']);
        $zone->delete();
	}
	
	if($_REQUEST['id'] != "")
		$id = $_REQUEST['id'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<?php include_once("js/zone.php"); ?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>
<div id="contenu_int"> 
    <p align="left"><a href="index.php" class="lien04">Accueil<img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04"> Configuration</a> </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des zones </a></p>
        
<!-- Début de la colonne de gauche -->  
<div id="bloc_description">
<div class="bordure_bottom">
<!-- liste des zones -->  	
		<div class="entete_liste_config">
			<div class="titre">LISTE DES ZONES</div>
		</div>
<?php

		$zone = new Zone();
		$query = "select * from $zone->table";
		$resul = mysql_query($query, $zone->link);
		
		$i = 0;
		
		while($row = mysql_fetch_object($resul)){
		if($i%2)
			$fond = "ligne_fonce_BlocDescription";
		else
			$fond = "ligne_claire_BlocDescription";

?>		
		<ul class="<?php echo $fond; ?>">
			<li style="width:460px;"><?php echo $row->nom; ?></li>
			<li style="width:40px;"><a href="zone.php?action=editer&id=<?php echo $row->id; ?>#zone">&eacute;diter</a></li>
			<li><a href="zone.php?action=supprimer&id=<?php echo $row->id; ?>">supprimer</a></li>
		</ul>
<?php
		$i++;
	}
?>
</div>
<!-- fin lites zones -->
<!-- bloc modification d'une zone -->
<a name="zone">&nbsp;</a>
<?php 
	if($id != ""){ 
        $zone = new Zone();
		$zone->charger($id);
		
		$pays = new Pays();
		$query = "select * from $pays->table where zone=\"-1\"";
		$resul = mysql_query($query, $pays->link);
		
?>
	<div class="bordure_bottom" id="listepays">
		<div class="entete_liste_config" style="margin-top:15px;">
			<div class="titre">MODIFICATION DE LA ZONE <?php echo strtoupper($zone->nom); ?></div>
		</div>
		<ul class="ligne1">
				<li style="width:250px;">
					<select class="form_select" id="pays">
					<?php
						while($row = mysql_fetch_object($resul)){
							$paysdesc = new Paysdesc();
							$paysdesc->charger($row->id);
					?>
			     	<option value="<?php echo $paysdesc->pays; ?>"><?php echo $paysdesc->titre; ?></option>
					<?php
						}
					?>
					</select>
				</li>
				<li><a href="javascript:ajouter($('#pays').val())">AJOUTER UN PAYS</a></li>
		</ul>

<?php
		$pays = new Pays();
		$query = "select * from $pays->table where zone=\"" . $id . "\"";
		$resul = mysql_query($query, $pays->link);

?>
<?php
		while($row = mysql_fetch_object($resul)){
			$paysdesc = new Paysdesc();
			$paysdesc->charger($row->id);
?>
		<ul class="ligne_claire_BlocDescription">
				<li style="width:505px;"><?php echo $paysdesc->titre; ?></li>
				<li style="width:32px;"><a href="javascript:supprimer(<?php echo $row->id; ?>)">supprimer</a></li>
		</ul>
<?php
	}
?>


		<ul class="ligne1">
				<li style="width:250px;"><input type="text" class="form_inputtext" id="forfait" onclick="this.value=''" value="<?php echo $zone->unite; ?>" /></li>
				<li><a href="javascript:forfait($('#forfait').val())">VALIDER</a></li>
		</ul>
	</div>
	
<?php
	}
?>
	
<!-- fin du bloc modification d'une zone -->
</div>
<!-- fin du bloc description -->

<!-- bloc colonne de droite -->   
<div id="bloc_colonne_droite">

<!-- bloc d'ajout d'une zone -->
<form action="zone.php" method="post" id="formaj">
<input type="hidden" name="action" value="ajouter" />
	<div class="bordure_bottom" id="ajout_zone">
		<div class="entete_config">
			<div class="titre">AJOUT D'UNE ZONE</div>
		</div>
		<ul class="ligne1">
				<li style="width:260px;">
					<input type="text" name="nomzone" class="form_inputtext" onclick="this.value=''" value="Nom de la zone">
				</li>
				<li><a href="javascript:document.getElementById('formaj').submit()">AJOUTER</a></li>
		</ul>
	</div>
</form>
<!-- fin du bloc d'ajout d'une zone -->
</div>
<!-- fin du bloc colonne de droite -->		
</div>
<?php include_once("pied.php"); ?>
</div>
</div>
</body>
</html>
