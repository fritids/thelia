<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../classes/Rubcaracteristique.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Caracteristique.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Caracteristiquedesc.class.php");

switch($_GET["action"]){

	case "ajouter" : 
			caracteristique_ajouter($_GET["caracteristique"],$_GET["rubrique"]);
			break;
	case "liste" : 
		caracteristique_liste($_GET["id"]);
		break;
	case "supprimer" : 
		caracteristique_supprimer($_GET["caracteristique"],$_GET["rubrique"]);
		break;

}

function caracteristique_ajouter($caracteristique,$rubrique){
	
	$rubcaracteristique = new Rubcaracteristique();
	$rubcaracteristique->rubrique = $rubrique;
	$rubcaracteristique->caracteristique = $caracteristique;
	$rubcaracteristique->add();
	
	liste($rubrique);
}

function caracteristique_supprimer($caracteristique,$rubrique){
		$rubcaracteristique = new Rubcaracteristique();
		$rubcaracteristique->charger($rubrique,$caracteristique);
		$rubcaracteristique->delete("delete from $rubcaracteristique->table where id=$rubcaracteristique->id");
	
		liste($rubrique);
}


function caracteristique_liste($id){
	$rubcaracteristique = new Rubcaracteristique();
	$query = "select * from $rubcaracteristique->table where rubrique=$id";
	$resul = mysql_query($query);
	$listeid = "";
	while($row = mysql_fetch_object($resul)){
		$listeid .= $row->caracteristique.",";
	}
	if(strlen($listeid) > 0){
		$listeid = substr($listeid,0,strlen($listeid)-1);
	
		$caracteristique = new Caracteristique();
		$query = "select * from $caracteristique->table where id NOT IN($listeid)";
		$resul = mysql_query($query);
	}
	else{
		$caracteristique = new Caracteristique();
		$query = "select * from $caracteristique->table";
		$resul = mysql_query($query);
	}
	?>
	<select class="form_select" id="prod_caracteristique">
 	<option value="">&nbsp;</option>
	<?php
		while($row = mysql_fetch_object($resul)){
			$caracteristiquedesc = new Caracteristiquedesc();
			$caracteristiquedesc->charger($row->id);
			?>
			<option value="<?php echo $row->id; ?>"><?php echo $caracteristiquedesc->titre; ?></option>
		<?php
		}
	?>
	</select>
	<?php			
}


function liste($id){
	$rubcaracteristique = new Rubcaracteristique();
	$query = "select * from $rubcaracteristique->table where rubrique=$id";
	$resul = mysql_query($query);
	$i=0;
	while($row = mysql_fetch_object($resul)){
		if($i%2 == 0) $fond="claire";
		else $fond="fonce";
		$i++;
		$caracteristiquedesc = new Caracteristiquedesc();
		$caracteristiquedesc->charger($row->caracteristique);
		?>
       	 <li class="<?php echo $fond; ?>">
		 <div class="cellule" style="width:260px;"><?php echo $caracteristiquedesc->titre; ?></div>
		 <div class="cellule" style="width:260px;">&nbsp;</div>
		 <div class="cellule_supp"><a href="javascript:caracteristique_supprimer(<?php echo $caracteristiquedesc->caracteristique; ?>)"><img src="gfx/supprimer.gif" /></a></div>
		 </li>
	     <?php
	}
}

?>