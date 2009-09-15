<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_configuration")) exit; ?>
<?php
        include_once("../../classes/Pays.class.php");
        include_once("../../classes/Paysdesc.class.php");
        include_once("../../classes/Zone.class.php");
?>

<?php
	if($_GET['action'] == "forfait" && $_GET['valeur'] != ""){
		$zone = new Zone();
		$zone->charger($_GET['id']);
		$zone->unite = $_GET['valeur'];
		$zone->maj();
	}

	else if($_GET['action'] == "ajouter" && $_GET['pays'] != ""){
		$pays = new Pays();
		$query = "update $pays->table set zone='" . $_GET['id'] . "' where id=\"" . $_GET['pays'] . "\"";
		$resul = mysql_query($query, $pays->link);
	}
		
	else if($_GET['action'] == "supprimer" && $_GET['pays'] != ""){
		$pays = new Pays();
		$query = "update $pays->table set zone='-1' where id=\"" . $_GET['pays'] . "\"";
		$resul = mysql_query($query, $pays->link);
	}
?>

<?php 

		$id = $_REQUEST['id'];
		
        $zone = new Zone();
		$zone->charger($id);
		
		$pays = new Pays();
		$query = "select * from $pays->table where zone=\"-1\"";
		$resul = mysql_query($query, $pays->link);
		
?>
	<div class="bordure_bottom" id="modifs_zone">
		<div class="entete_liste_config" style="margin-top:15px;">
			<div class="titre">MODIFICATION DE LA ZONE</div>
		</div>
		<ul class="ligne1">
				<li style="width:250px;">
					<select class="form_select" id="pays">
					<?php
						while($row = mysql_fetch_object($resul)){
							$paysdesc = new Paysdesc();
							$paysdesc->charger($row->id);
					?>
			     	<option value="<?php echo $paysdesc->id; ?>"><?php echo $paysdesc->titre; ?></option>
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
		<ul class="ligne_claire_transport">
				<li style="width:492px;"><?php echo $paysdesc->titre; ?></li>
				<li style="width:32px;"><a href="javascript:supprimer(<?php echo $row->id; ?>)">Supprimer</a></li>
		</ul>
<?php
	}
?>
		<ul class="ligne1">
				<li style="width:250px;"><input type="text" class="form_inputtext" id="forfait" onclick="this.value=''" value="<?php echo $zone->unite; ?>" /></li>
				<li><a href="javascript:forfait($('#forfait').val())">VALIDER</a></li>
		</ul>
	</div>
