<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_configuration")) exit; ?>
<?php
        include_once("../../classes/Transzone.class.php");
        include_once("../../classes/Zone.class.php");
?>

<?php
	if($_GET['action'] == "supprimer" && $_GET['zone'] != ""){
		$transzone = new Transzone();
		$transzone->charger_id($_GET['zone']);
		$transzone->delete();
	
	} else if($_GET['action'] == "ajouter" && $_GET['id'] != "" && $_GET['zone'] != ""){
		$transzone = new Transzone();
		$transzone->zone = $_GET['zone'];
		$transzone->transport = $_GET['id'];
		$transzone->add();	
	
	}
	
	$zone = new Zone();
?>

		<div class="entete_liste_config" style="margin-top:15px;">
			<div class="titre">MODIFICATION DU TRANSPORT TITRE</div>
		</div>
		<ul class="ligne1">
				<li style="width:250px;">
					<select class="form_select" id="zone">
					<?php
						$query = "select * from $zone->table";
						$resul = mysql_query($query, $transzone->link);
						while($row = mysql_fetch_object($resul)){	
							$test = new Transzone();
							if(! $test->charger($_GET['id'], $row->id)){
					?>				
			     	<option value="<?php echo $row->id; ?>"><?php echo $row->nom; ?></option>
			     	<?php
			     			}
			     		}
			     	?>
					</select>
				</li>
				<li><a href="javascript:ajouter($('#zone').val())">AJOUTER UNE ZONE</a></li>
		</ul>
		
<?php 
			$query = "select * from $transzone->table where transport=\"" . $_GET['id']. "\"";
			$resul = mysql_query($query, $transzone->link);
			
			$i = 0;
			
			while($row = mysql_fetch_object($resul)){
				$zone = new Zone();
				$zone->charger($row->zone);
				if($i%2)
					$fond = "ligne_claire_transport";
				else
					$fond = "ligne_fonce_transport";
						
?>		
		<ul class="<?php echo $fond; ?>">
				<li style="width:492px;"><?php echo $zone->nom; ?></li>
				<li style="width:32px;"><a href="javascript:supprimer(<?php echo $row->id; ?>)">Supprimer</a></li>
		</ul>
<?php
				$i++;
			}
?>

	
	</div>	