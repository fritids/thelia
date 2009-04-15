<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
	session_start();
	if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}

	include_once(realpath(dirname(__FILE__)) . "/../../classes/Produit.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Produitdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Accessoire.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Rubrique.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Rubriquedesc.class.php");

?>
<?php

	switch($_GET['action']){
		case 'produit' : accessoire_produit(); break;
		case 'ajouter' : accessoire_ajouter(); break;	
		case 'supprimer' : accessoire_supprimer(); break;	
	}
?>
<?php
	function accessoire_produit(){
		$produit = new Produit();
		$produit->charger($_GET['ref']);
			
		$query = "select * from $produit->table where rubrique=\""  . $_GET['id_rubrique'] . "\"";
		$resul = mysql_query($query, $produit->link);
		
		while($row = mysql_fetch_object($resul)){
			
			$test = new Accessoire();
			if($test->charger_uni($produit->id, $row->id))
				continue;
					
			$produitdesc = new Produitdesc();
			$produitdesc->charger($row->id);
?>		
			<option value="<?php echo $row->id; ?>"><?php echo $produitdesc->titre; ?></option>
<?php
		}
	}
?>
<?php
	function accessoire_ajouter(){
		$produit = new Produit();
		$produit->charger($_GET['ref']);

		$accessoire = new Accessoire();
		
		$query = "select max(classement) as maxClassement from $accessoire->table where produit=\"" . $produit->id . "\"";
		$resul = mysql_query($query, $accessoire->link);
		$classement = mysql_result($resul, 0, "maxClassement") + 1;
			
		$accessoire = new Accessoire();
		$accessoire->produit = $produit->id;
		$accessoire->accessoire = $_GET['id'];
		$accessoire->classement = $classement;
		$accessoire->add();
		
		accessoire_liste();
	}
?>
<?php
	function accessoire_supprimer(){
		$accessoire = new Accessoire();
		$accessoire->charger($_GET['id']);
		$accessoire->delete();

		accessoire_liste();
	}
?>
<?php
		function accessoire_liste(){
		
				$produit = new Produit();
				$produit->charger($_GET['ref']);
                $accessoire = new Accessoire();
                $produita = new Produit();
                $produitdesca = new Produitdesc();

                $query = "select * from $accessoire->table where produit='$produit->id' order by classement";
                $resul = mysql_query($query, $accessoire->link);

                while($row = mysql_fetch_object($resul)){
                        $produita->charger_id($row->accessoire);
                        $produitdesca->charger($produita->id);
                        
                        $rubadesc = new Rubriquedesc();
                        $rubadesc->charger($produita->rubrique);
        ?>
        
        	 <li class="claire">
				<div class="cellule" style="width:260px;"><?php echo $rubadesc->titre; ?></div>
				<div class="cellule" style="width:260px;"><?php echo $produitdesca->titre; ?></div>
				<div class="cellule_supp"><a href="javascript:accessoire_supprimer(<?php echo $row->id; ?>)"><img src="gfx/supprimer.gif" /></a></div>
			</li>

<?php
                }
        }
?>
