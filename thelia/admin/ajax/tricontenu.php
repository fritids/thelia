<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_catalogue")) exit; ?>
<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Dossier.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Dossierdesc.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Contenu.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Contenudesc.class.php");




$critere = $_GET["critere"];
$order = $_GET["order"];
$parent = $_GET["ref"];
$type = $_GET["type"];
$alpha = $_GET["alpha"];

if($type == "dossier"){

		$dossier = new Dossier();
		$dossierdesc = new Dossierdesc();
		
		if($alpha == "alpha"){
			$query = "select * from $dossierdesc->table LEFT JOIN $dossier->table ON $dossier->table.id=$dossierdesc->table.dossier where parent=\"$parent\" and lang=\"1\" order by $dossierdesc->table.$critere $order";
		}else{
			$query = "select * from $dossier->table where parent=\"$parent\" order by $critere $order";
		}


		$resul = mysql_query($query, $dossier->link);		
		$i=0;
		while($row = mysql_fetch_object($resul)){
			$dossierdesc->charger($row->id);

			if(!($i%2)) $fond="ligne_claire_rub";
	  		else $fond="ligne_fonce_rub";
	  		$i++;

	?>
	
	<ul class="<?php echo($fond); ?>">
		<li style="width:112px;"></li>
		<li style="width:579px;"><span id="titredos_<?php echo $row->id; ?>" class="texte_edit"><?php echo substr($dossierdesc->titre,0,90); if(strlen($dossierdesc->titre) > 90) echo " ..."; ?></span></li>
		<li style="width:54px;"><a href="listdos.php?parent=<?php echo($dossierdesc->dossier); ?>" class="txt_vert_11">parcourir</a></li>
		<li style="width:34px;"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>" class="txt_vert_11">éditer</a></li>

		<li style="width:71px;">
		 <div class="bloc_classement">  
		    <div class="classement"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" border="0" /></a></div>
		    <div class="classement"><span id="classementdossier_<?php echo $row->id; ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
		    <div class="classement"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
		 </div>
		</li>
		<li style="width:37px; text-align:center;"><a href="javascript:supprimer_dossier('<?php echo($dossierdesc->dossier); ?>', '<?php echo($parent); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
	</ul>
	 
	<?php
	}
}
else if($type == "contenudos"){

		$contenu = new Contenu();
		$contenudesc = new ContenuDesc();
		
		if($alpha == "alpha"){
			$query = "select * from $contenudesc->table LEFT JOIN $contenu->table ON $contenu->table.id=$contenudesc->table.contenu where dossier=\"$parent\" and lang=\"1\" order by $contenudesc->table.$critere $order";
		}else{
			$query = "select * from $contenu->table where dossier=\"$parent\" order by $critere $order";
		}

		$resul = mysql_query($query, $contenu->link);		
		$i=0;
		while($row = mysql_fetch_object($resul)){
			$contenudesc->charger($row->id);

			if(!($i%2)) $fond="ligne_claire_rub";
	  		else $fond="ligne_fonce_rub";
	  		$i++;
	?>

	<ul class="<?php echo($fond); ?>">
		<li style="width:112px;"></li>
		<li style="width:579px;">
		<span id="titrecont_<?php echo $row->id; ?>" class="texte_edit"><?php echo substr($contenudesc->titre,0,90); if(strlen($contenudesc->titre) > 90) echo " ..."; ?></span></li>
		<li style="width:54px;"></li>
		<li style="width:34px;"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&dossier=<?php echo $parent; ?>" class="txt_vert_11">éditer</a></li>
		<li style="width:71px;">
		 <div class="bloc_classement">  
		    <div class="classement"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" border="0" /></a></div>
		    <div class="classement"><span id="classement_<?php echo $row->ref; ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
		    <div class="classement"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
		 </div>
		</li>
		<li style="width:37px; text-align:center;"><a href="javascript:supprimer_contenu('<?php echo($contenudesc->contenu); ?>', '<?php echo($parent); ?>');"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
	</ul>

	<?php
	}
	
}


?>