<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../classes/Rubdeclinaison.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Declinaison.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Declinaisondesc.class.php");

switch($_GET["action"]){

	case "ajouter" : 
			declinaison_ajouter($_GET["declinaison"],$_GET["rubrique"]);
			break;
	case "liste" : 
		declinaison_liste($_GET["id"]);
		break;
	case "supprimer" : 
		declinaison_supprimer($_GET["declinaison"],$_GET["rubrique"]);
		break;

}

function declinaison_ajouter($declinaison,$rubrique){
	
	$rubdeclinaison = new Rubdeclinaison();
	$rubdeclinaison->rubrique = $rubrique;
	$rubdeclinaison->declinaison = $declinaison;
	$rubdeclinaison->add();
	
	liste($rubrique);
}

function declinaison_supprimer($declinaison,$rubrique){
		$rubdeclinaison = new Rubdeclinaison();
		$rubdeclinaison->charger($rubrique,$declinaison);
		$rubdeclinaison->delete("delete from $rubdeclinaison->table where id=$rubdeclinaison->id");
	
		liste($rubrique);
}


function declinaison_liste($id){
	$rubdeclinaison = new Rubdeclinaison();
	$query = "select * from $rubdeclinaison->table where rubrique=$id";
	$resul = mysql_query($query);
	$listeid = "";
	while($row = mysql_fetch_object($resul)){
		$listeid .= $row->declinaison.",";
	}
	if(strlen($listeid) > 0){
		$listeid = substr($listeid,0,strlen($listeid)-1);
	
		$declinaison = new Declinaison();
		$query = "select * from $declinaison->table where id NOT IN($listeid)";
		$resul = mysql_query($query);
	}
	else{
		$declinaison = new Declinaison();
		$query = "select * from $declinaison->table";
		$resul = mysql_query($query);
	}
	?>
	<select class="form_select" id="prod_caracteristique">
 	<option value="">&nbsp;</option>
	<?php
		while($row = mysql_fetch_object($resul)){
			$declinaisondesc = new Declinaisondesc();
			$declinaisondesc->charger($row->id);
			?>
			<option value="<?php echo $row->id; ?>"><?php echo $declinaisondesc->titre; ?></option>
		<?php
		}
	?>
	</select>
	<?php			
}


function liste($id){
	$rubdeclinaison = new Rubdeclinaison();
	$query = "select * from $rubdeclinaison->table where rubrique=$id";
	$resul = mysql_query($query);
	$i=0;
	while($row = mysql_fetch_object($resul)){
		if($i%2 == 0) $fond="claire";
		else $fond="fonce";
		$i++;
		$declinaisondesc = new Declinaisondesc();
		$declinaisondesc->charger($row->declinaison);
		?>
       	 <li class="<?php echo $fond; ?>">
		 <div class="cellule" style="width:260px;"><?php echo $declinaisondesc->titre; ?></div>
		 <div class="cellule" style="width:260px;">&nbsp;</div>
		 <div class="cellule_supp"><a href="javascript:caracteristique_supprimer(<?php echo $declinaisondesc->declinaison; ?>)"><img src="gfx/supprimer.gif" /></a></div>
		 </li>
	     <?php
	}
}

?>