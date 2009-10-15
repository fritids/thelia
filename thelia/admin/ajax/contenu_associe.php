<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
	session_start();
	if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
	include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_catalogue")) exit; ?>
<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Produit.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Rubrique.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Produitdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Contenuassoc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Dossier.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Dossierdesc.class.php");

?>
<?php

	switch($_GET['action']){
		case 'contenu_assoc' : contenuassoc_contenu(); break;
		case 'ajouter' : contenuassoc_ajouter(); break;	
		case 'supprimer' : contenuassoc_supprimer(); break;	
	}
?>
<?php
	function contenuassoc_contenu(){
		if($_GET['type'] == 1){
			$objet = new Produit();
			$objet->charger($_GET['objet']);
		}
		else{
			$objet = new Rubrique();
			$objet->charger($_GET['objet']);
		}
		
		$contenu = new Contenu();
		
		$query = "select * from $contenu->table where dossier=\""  . $_GET['id_dossier'] . "\"";
		$resul = mysql_query($query, $contenu->link);

		while($row = mysql_fetch_object($resul)){
			
			$contenuassoc = new Contenuassoc();
			if($contenuassoc->existe($objet->id, $_GET['type'], $row->id)) continue;
			
			$contenudesc = new Contenudesc();
			$contenudesc->charger($row->id);
?>		
			<option value="<?php echo $row->id; ?>"><?php echo $contenudesc->titre; ?></option>
<?php
		}
	}
?>
<?php
	function contenuassoc_ajouter(){
		if($_GET['type'] == 1){
			$objet = new Produit();
			$objet->charger($_GET['objet']);
		}
		else{
			$objet = new Rubrique();
			$objet->charger($_GET['objet']);
		}

		$contenuassoc = new Contenuassoc();
		
		$query = "select max(classement) as maxClassement from $contenuassoc->table where objet=\"" . $objet->id . "\" and type=\"" . $_GET['type'] . "\"";
		$resul = mysql_query($query, $contenuassoc->link);
		$classement = mysql_result($resul, 0, "maxClassement") + 1;
			
		$contenuassoc = new Contenuassoc();
		$contenuassoc->objet = $objet->id;
		$contenuassoc->type = $_GET['type'];
		$contenuassoc->contenu = $_GET['id'];
		$contenuassoc->classement = $classement;
		$contenuassoc->add();

		contenuassoc_liste($_GET['type'],$_GET['objet']);
	}
?>
<?php
	function contenuassoc_supprimer(){
		$contenuassoc = new Contenuassoc();
		$contenuassoc->charger($_GET['id']);
		$contenuassoc->delete();

		contenuassoc_liste($_GET['type'],$_GET['objet']);
	}
?>
<?php
		function contenuassoc_liste($type,$objet){
			if($type == 1){
				$obj = new Produit();
				$obj->charger($objet);
			}
			else{
				$obj = new Rubrique();
				$obj->charger($objet);
			}
		
                $contenuassoc = new Contenuassoc();
                $contenua = new Contenu();
                $contenuadesc = new Contenudesc();

                $query = "select * from $contenuassoc->table where type='$type' and objet='$obj->id' order by classement";
                $resul = mysql_query($query, $contenuassoc->link);

				$i = 0;
				
                while($row = mysql_fetch_object($resul)){
                		
                		if($i%2)
                			$fond = "fonce";
                		else
                			$fond = "claire";
                		
                		$i++;

                        $contenua->charger($row->contenu);
                		$contenuadesc->charger($contenua->id);

                        $dossierdesc = new Dossierdesc();
                        $dossierdesc->charger($contenua->dossier);
        ?>
        
        	 <li class="<?php echo $fond; ?>">
				<div class="cellule" style="width:260px;"><?php echo $dossierdesc->titre; ?></div>
				<div class="cellule" style="width:260px;"><?php echo $contenuadesc->titre; ?></div>
				<div class="cellule_supp"><a href="javascript:contenuassoc_supprimer(<?php echo $row->id; ?>, 1,'<?php echo $objet; ?>')"><img src="gfx/supprimer.gif" /></a></div>
			</li>

        			

<?php
                }
        }
?>