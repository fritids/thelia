<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once("../../classes/Produit.class.php");
include_once("../../classes/Produitdesc.class.php");
include_once("../../classes/Rubrique.class.php");
include_once("../../classes/Rubriquedesc.class.php");
include_once("../../classes/Contenu.class.php");
include_once("../../classes/Contenudesc.class.php");
include_once("../../classes/Dossier.class.php");
include_once("../../classes/Dossierdesc.class.php");
include_once("../../classes/Caracteristique.class.php");
include_once("../../classes/Caracteristiquedesc.class.php");
include_once("../../classes/Declinaison.class.php");
include_once("../../classes/Declinaisondesc.class.php");
$sep = explode( "_", $_POST['id']);


$pos = strpos($_POST['id'], "_");

$modif = substr($_POST['id'], 0, $pos);
$classement = $_POST["value"];
if($modif == "classementrub"){
	
	$rubrique = new Rubrique();
	$rubrique->charger(substr($_POST['id'], $pos+1));
	if($classement>$rubrique->classement){
		$query = "select * from $rubrique->table where parent=$rubrique->parent and classement BETWEEN $rubrique->classement and $classement";
		$resul = mysql_query($query,$rubrique->link);
		while($row = mysql_fetch_object($resul)){
			$rub = new Rubrique();
			$rub->charger($row->id);
			$rub->classement--;
			$rub->maj();
		}
		$rubrique->classement = $classement;
		$rubrique->maj();
	}
	else{
		$query = "select * from $rubrique->table where parent = $rubrique->parent and classement BETWEEN $classement and $rubrique->classement";
		$resul = mysql_query($query,$rubrique->link);
		while($row = mysql_fetch_object($resul)){
			$rub = new Rubrique();
			$rub->charger($row->id);
			$rub->classement++;
			$rub->maj();
		}
		
		$rubrique->classement = $classement;
		$rubrique->maj();
	}
	
		$parent = $rubrique->parent;
		$rubrique = new Rubrique();
		$rubriquedesc = new Rubriquedesc();

		$query = "select * from $rubrique->table where parent=\"$parent\" order by classement";
		$resul = mysql_query($query, $rubrique->link);		

		$i=0;
		echo("rub|");
		while($row = mysql_fetch_object($resul)){
			$rubriquedesc->charger($row->id);

			if(!($i%2)) $fond="ligne_claire_rub";
	  		else $fond="ligne_fonce_rub";
	  		$i++;
	?>

	<ul class="<?php echo($fond); ?>" id="<?php echo $row->id; ?>">
		<li style="width:112px;"></li>
		<li style="width:579px;"><span id="titrerub_<?php echo $row->id; ?>" class="texte_edit"><?php echo($rubriquedesc->titre); ?></span></li>
		<li style="width:54px;"><a href="parcourir.php?parent=<?php echo($rubriquedesc->rubrique); ?>">parcourir</a></li>
		<li style="width:34px;"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>">éditer</a></li>

		<li style="width:71px;">
		 <div class="bloc_classement">  
		    <div class="classement"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" border="0" /></a></div>
		    <div class="classement"><span id="classementrub_<?php echo $row->id ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
		    <div class="classement"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
		 </div>
		</li>
		<li style="width:37px; text-align:center;"><a href="javascript:supprimer_rubrique('<?php echo $rubriquedesc->rubrique ?>','<?php echo($parent); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
	</ul>

	<?php
	}	
}
else if($modif == "classementprod"){
	$produit = new Produit();
	$produit->charger_id(substr($_POST['id'], $pos+1));
	if($classement>$produit->classement){
		$query = "select * from $produit->table where rubrique=$produit->rubrique and classement BETWEEN $produit->classement and $classement";
		$resul = mysql_query($query,$produit->link);
		while($row = mysql_fetch_object($resul)){
			$prod = new Produit();
			$prod->charger_id($row->id);
			$prod->classement--;
			$prod->maj();
		}
		$produit->classement = $classement;
		$produit->maj();
	}
	else{
		$query = "select * from $produit->table where rubrique=$produit->rubrique and classement BETWEEN $classement and $produit->classement";
		$resul = mysql_query($query,$produit->link);
		while($row = mysql_fetch_object($resul)){
			$prod = new Produit();
			$prod->charger_id($row->id);
			$prod->classement++;
			$prod->maj();
		}
		
		$produit->classement = $classement;
		$produit->maj();
	}
	
		$parent = $produit->rubrique;
		$produit = new Produit();
		$produitdesc = new Produitdesc();

		

	$query = "select * from $produit->table where rubrique=\"$parent\" order by classement";

	$resul = mysql_query($query, $produit->link);
		$i=0;
		echo("prod|");
		while($row = mysql_fetch_object($resul)){
			$produit->charger($row->ref);
			$produitdesc->charger($row->id);

			if(!($i%2)) $fond="ligne_claire";
	  		else $fond="ligne_fonce";
	  		$i++;
		$image = new Image();
		$query_image = "select * from $image->table where produit=\"" . $row->id . "\" order by classement limit 0,1";
		$resul_image = mysql_query($query_image, $image->link);
		$row_image = mysql_fetch_object($resul_image);
	?>

	<ul class="<?php echo($fond); ?>">
		<li><div class="vignette"><?php if($row_image) { ?> <img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/<?php echo $row_image->fichier;?>&width=51" title="<?php echo($produit->ref); ?>" /><?php }  ?></div></li>
		<li style="width:61px;"><span class="texte_noedit"><?php echo($row->ref); ?></span></li>
		<li style="width:225px;"><span id="titreprod_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($produitdesc->titre); ?></span></li>
		<li style="width:39px;"><span id="stock_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->stock); ?></span></li>
		<li style="width:30px;"><span id="prix_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix); ?></span></li>
		<li style="width:68px;"><span id="prix2_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix2); ?></span></li>
		<li style="width:64px;"><input id="promo_<?php echo $row->ref; ?>" type="checkbox" name="promo[]" class="sytle_checkbox" onchange="checkvalues('promo','<?php echo $row->ref; ?>')" <?php if($row->promo) { ?> checked="checked" <?php } ?>/></li>
		<li style="width:64px;"><input type="checkbox" id="nouveaute_<?php echo $row->ref; ?>" name="nouveaute[]" class="sytle_checkbox" onchange="checkvalues('nouveaute','<?php echo $row->ref; ?>')" <?php if($row->nouveaute) { ?> checked="checked" <?php } ?>/></li>
		<li style="width:53px;"><input type="checkbox" id="ligne_<?php echo $row->ref; ?>" name="ligne[]" class="sytle_checkbox" onchange="checkvalues('ligne','<?php echo $row->ref; ?>')" <?php if($row->ligne) { ?> checked="checked" <?php } ?>/></li>
		<li style="width:41px;"><a href="produit_modifier.php?ref=<?php echo($produit->ref); ?>&rubrique=<?php echo($produit->rubrique); ?>"  class="txt_vert_11">détails</a></li>

		<li style="width:78px; text-align:center;"> 
		<div class="bloc_classement">
	  <div class="classement"> 
			<a href="produit_modifier.php?ref=<?php echo($produit->ref ); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" border="0" /></a>
		</div>
		 <div class="classement"><span id="classementprod_<?php echo $row->id; ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
		 <div class="classement">
			<a href="produit_modifier.php?ref=<?php echo($produit->ref ); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" border="0" /></a>
		</div>
		</div>
		</li>
		<li style="width:37px; text-align:center;"><a href="javascript:supprimer_produit('<?php echo $produit->ref ?>','<?php echo($parent); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
	</ul>
	<?php
	}
}
else if($modif == "classementdossier"){
	$dossier = new Dossier();
	$dossier->charger(substr($_POST['id'], $pos+1));
	if($classement>$dossier->classement){
		$query = "select * from $dossier->table where parent=$dossier->parent and classement BETWEEN $dossier->classement and $classement";
		$resul = mysql_query($query,$dossier->link);
		while($row = mysql_fetch_object($resul)){
			$dos = new Dossier();
			$dos->charger($row->id);
			$dos->classement--;
			$dos->maj();
		}
		$dossier->classement = $classement;
		$dossier->maj();
	}
	else{
		$query = "select * from $dossier->table where parent=$dossier->parent and classement BETWEEN $classement and $dossier->classement";
		$resul = mysql_query($query,$dossier->link);
		while($row = mysql_fetch_object($resul)){
			$dos = new Dossier();
			$dos->charger($row->id);
			$dos->classement++;
			$dos->maj();
		}
		
		$dossier->classement = $classement;
		$dossier->maj();
	}
	$parent = $dossier->parent;
	$dossier = new Dossier();
	$dossierdesc = new Dossierdesc();
	
	$query = "select * from $dossier->table where parent=\"$parent\" order by classement";
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
	<li style="width:579px;"><span id="titredos_<?php echo $row->id; ?>" class="texte_edit"><?php echo($dossierdesc->titre); ?></span></li>
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
else if($modif == "classementcontenu"){
	$contenu = new Contenu();
	$contenu->charger(substr($_POST['id'], $pos+1));
	if($classement>$contenu->classement){
		$query = "select * from $contenu->table where dossier=$contenu->dossier and classement BETWEEN $contenu->classement and $classement";
		$resul = mysql_query($query,$contenu->link);
		while($row = mysql_fetch_object($resul)){
			$cont = new Contenu();
			$cont->charger($row->id);
			$cont->classement--;
			$cont->maj();
		}
		$contenu->classement = $classement;
		$contenu->maj();
	}
	else{
		$query = "select * from $contenu->table where dossier=$contenu->dossier and classement BETWEEN $classement and $contenu->classement";
		$resul = mysql_query($query,$contenu->link);
		while($row = mysql_fetch_object($resul)){
			$cont = new Contenu();
			$cont->charger($row->id);
			$cont->classement++;
			$cont->maj();
		}
		
		$contenu->classement = $classement;
		$contenu->maj();
	}
		$parent = $contenu->dossier;
		$contenu = new Contenu();
		$contenudesc = new ContenuDesc();

		$query = "select * from $contenu->table where dossier=\"$parent\" order by classement";
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
		<span id="titrecont_<?php echo $row->id; ?>" class="texte_edit"><?php echo($contenudesc->titre); ?></span></li>
		<li style="width:54px;"></li>
		<li style="width:34px;"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&dossier=<?php echo $parent; ?>" class="txt_vert_11">&eacute;diter</a></li>
		<li style="width:71px;">
		 <div class="bloc_classement">  
		    <div class="classement"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" border="0" /></a></div>
		    <div class="classement"><span id="classementcontenu_<?php echo $row->id; ?>" class="contenudos_edit"><?php echo $row->classement; ?></span></div>
		    <div class="classement"><a href="contenu_modifier.php?id=<?php echo($contenudesc->contenu); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
		 </div>
		</li>
		<li style="width:37px; text-align:center;"><a href="javascript:supprimer_contenu('<?php echo($contenudesc->contenu); ?>', '<?php echo($parent); ?>');"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
	</ul>

	<?php
	}
}
if($modif == "classementcarac"){
	$caracteristique = new Caracteristique();
	$caracteristique->charger(substr($_POST['id'], $pos+1));
	if($classement>$caracteristique->classement){
		$query = "select * from $caracteristique->table where classement BETWEEN $caracteristique->classement and $classement";
		$resul = mysql_query($query,$caracteristique->link);
		while($row = mysql_fetch_object($resul)){
			$carac = new Caracteristique();
			$carac->charger($row->id);
			$carac->classement--;
			$carac->maj();
		}
		$caracteristique->classement = $classement;
		$caracteristique->maj();
	}
	else{
		$query = "select * from $caracteristique->table where classement BETWEEN $classement and $caracteristique->classement";
		$resul = mysql_query($query,$caracteristique->link);
		while($row = mysql_fetch_object($resul)){
			$carac = new Caracteristique();
			$carac->charger($row->id);
			$carac->classement++;
			$carac->maj();
		}
		
		$caracteristique->classement = $classement;
		$caracteristique->maj();
	}
	
	$caracteristiquedesc = new Caracteristiquedesc();
	$query = "select * from $caracteristique->table order by classement";
	$resul = mysql_query($query,$caracteristique->link);
	
	$liste = "";
		while($row = mysql_fetch_object($resul)){
			$liste .= $row->id . ",";

		}


		$liste = substr($liste, 0, strlen($liste)-1);

		if($liste != "") {

			$query = "select * from $caracteristiquedesc->table,$caracteristique->table where $caracteristiquedesc->table.caracteristique=$caracteristique->table.id and $caracteristiquedesc->table.caracteristique in ($liste) and $caracteristiquedesc->table.lang='1' order by classement";
			$resul = mysql_query($query, $caracteristiquedesc->link);
			$i=0;
			while($row = mysql_fetch_object($resul)){	
				$caracteristiquedesc->charger($row->caracteristique);

				if(!($i%2)) $fond="ligne_claire_rub";
	  			else $fond="ligne_fonce_rub";
	  			$i++;


	?>     
	<ul class="<?php echo($fond); ?>">
		<li style="width:112px;"></li>
		<li style="width:648px;"><span id="titrerub_<?php echo $row->id; ?>" class="texte_edit"><?php echo($caracteristiquedesc->titre); ?></span></li>
		<li style="width:32px;"><a href="<?php echo "caracteristique_modifier.php?id=$caracteristiquedesc->caracteristique"; ?>">&eacute;diter</a></li>
		<li style="width:71px;">
		 <div class="bloc_classement">  
		    <div class="classement"><a href="caracteristique_modifier.php?id=<?php echo($caracteristiquedesc->caracteristique); ?>&action=modclassement&type=M"><img src="gfx/up.gif" border="0" /></a></div>
		    <div class="classement"><span id="classementcarac_<?php echo $row->id ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
		    <div class="classement"><a href="caracteristique_modifier.php?id=<?php echo($caracteristiquedesc->caracteristique); ?>&action=modclassement&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
		 </div>
		</li>
		<li style="width:37px; text-align:center;"><a href="<?php echo "caracteristique_modifier.php?id=$caracteristiquedesc->caracteristique&action=supprimer"; ?>" ><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
	</ul>


	<?php
			}

			if(!($i%2)) $fond="ligne_claire_rub";
	  		else $fond="ligne_fonce_rub";
	  		$i++;
		}	
	}
	
if($modif == "classementdecli"){
	
	$declinaison = new Declinaison();
	$declinaison->charger(substr($_POST['id'], $pos+1));
	if($classement>$declinaison->classement){
		$query = "select * from $declinaison->table where classement BETWEEN $declinaison->classement and $classement";
		$resul = mysql_query($query,$declinaison->link);
		while($row = mysql_fetch_object($resul)){
			$decli = new Declinaison();
			$decli->charger($row->id);
			$decli->classement--;
			$decli->maj();
		}
		$declinaison->classement = $classement;
		$declinaison->maj();
	}
	else{
		$query = "select * from $declinaison->table where classement BETWEEN $classement and $declinaison->classement";
		$resul = mysql_query($query,$declinaison->link);
		while($row = mysql_fetch_object($resul)){
			$decli = new Declinaison();
			$decli->charger($row->id);
			$decli->classement++;
			$decli->maj();
		}
		
		$declinaison->classement = $classement;
		$declinaison->maj();
	}
	
	$declinaisondesc = new Declinaisondesc();
	
	$query = "select * from $declinaison->table order by classement";
	$resul = mysql_query($query, $declinaison->link);	
	$i=0;	

	while($row = mysql_fetch_object($resul)){
		$declinaisondesc->charger($row->id);
		
		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;
		

?>     
<ul class="<?php echo($fond); ?>">
	<li style="width:112px;"></li>
	<li style="width:648px;"><span id="titrerub_<?php echo $row->id; ?>" class="texte_edit"><?php echo($declinaisondesc->titre); ?></span></li>
	<li style="width:32px;"><a href="<?php echo "declinaison_modifier.php?id=$declinaisondesc->declinaison"; ?>">&eacute;diter</a></li>
	<li style="width:71px;">
	 <div class="bloc_classement">  
	    <div class="classement"><a href="declinaison_modifier.php?id=<?php echo($declinaisondesc->declinaison); ?>&action=modclassement&type=M"><img src="gfx/up.gif" border="0" /></a></div>
	    <div class="classement"><span id="classementdecli_<?php echo $row->id; ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
	    <div class="classement"><a href="declinaison_modifier.php?id=<?php echo($declinaisondesc->declinaison); ?>&action=modclassement&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
	 </div>
	</li>
	<li style="width:37px; text-align:center;"><a href="<?php echo "declinaison_modifier.php?id=$declinaisondesc->declinaison&action=supprimer"; ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
<?php
}

		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;
	
	
}

?>