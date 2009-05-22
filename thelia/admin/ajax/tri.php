<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once("../../classes/Produit.class.php");
include_once("../../classes/Produitdesc.class.php");
include_once("../../classes/Rubrique.class.php");
include_once("../../classes/Rubriquedesc.class.php");
include_once("../../classes/Image.class.php");

$critere = $_GET["critere"];
$order = $_GET["order"];
$ref = $_GET["ref"];
$alpha = $_GET["alpha"];

if($_GET["type"] == 1){
	$rubrique = new Rubrique();
	$rubriquedesc = new Rubriquedesc();
	
	
	if($alpha == "alpha"){
		$query = "select * from $rubriquedesc->table LEFT JOIN $rubrique->table ON $rubrique->table.id=$rubriquedesc->table.rubrique where  lang=\"1\" order by $rubriquedesc->table.$critere $order";
	}else{
		$query = "select * from $rubrique->table order by $critere $order";
	}
	
	
	$resul = mysql_query($query, $rubrique->link);		

	$i=0;

	while($row = mysql_fetch_object($resul)){
		$rubriquedesc->charger($row->id);
		
		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;
?>

<ul class="<?php echo($fond); ?>">
	<li style="width:112px;"></li>
	<li style="width:579px;"><span id="titrerub_<?php echo $row->id; ?>" class="texte_edit"><?php echo substr($rubriquedesc->titre,0,80); if(strlen($rubriquedesc->titre) > 80) echo " ..."; ?></span></li>
	<li style="width:54px;"><a href="parcourir.php?parent=<?php echo($rubriquedesc->rubrique); ?>" class="txt_vert_11">parcourir</a></li>
	<li style="width:34px;"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>" class="txt_vert_11">éditer</a></li>
	
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
else{
	$produit = new Produit();
	$produitdesc = new Produitdesc();
	
	if($alpha == "alpha"){
		$query = "select * from $produitdesc->table LEFT JOIN $produit->table ON $produit->table.id=$produitdesc->table.produit where $produit->table.rubrique=\"$ref\" and lang=\"1\" order by $produitdesc->table.$critere $order";
	}else{
		$query = "select * from $produit->table where rubrique=\"$ref\" order by $critere $order";
	}
	
	$resul = mysql_query($query);
	$i=0;
	while($row = mysql_fetch_object($resul)){
		$produit->charger($row->ref);
		$produitdesc->charger($row->id);

		if(!($i%2)) $fond="ligne_claire";
  		else $fond="ligne_fonce";
  		$i++;
?>

<?php

	$image = new Image();
	$query_image = "select * from $image->table where produit=\"" . $row->id . "\" order by classement limit 0,1";
	$resul_image = mysql_query($query_image, $image->link);
	$row_image = mysql_fetch_object($resul_image);
?>

<ul class="<?php echo($fond); ?>">
	<li><div class="vignette"><?php if($row_image) { ?> <img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/<?php echo $row_image->fichier;?>&width=51" title="<?php echo($produit->ref); ?>" /><?php }  ?></div></li>
	<li style="width:61px;"><span class="texte_noedit" title="<?php echo $row->ref; ?>"><?php echo(substr($row->ref,0,9)); if(strlen($row->ref)>9) echo " ..."; ?></span></li>
	<li style="width:225px;"><span id="titreprod_<?php echo $row->ref; ?>" class="texte_edit"><?php echo substr($produitdesc->titre,0,35); if(strlen($produitdesc->titre) > 35) echo " ..."; ?></span></li>
	<li style="width:39px;"><span id="stock_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->stock); ?></span></li>
	<li style="width:30px;"><span id="prix_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix); ?></span></li>
	<li style="width:68px;"><span id="prix2_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix2); ?></span></li>
	<li style="width:64px;"><input id="promo_<?php echo $row->ref; ?>" type="checkbox" name="promo[]" class="sytle_checkbox" onchange="checkvalues('promo',<?php echo $row->ref; ?>)" <?php if($row->promo) { ?> checked="checked" <?php } ?>/></li>
	<li style="width:64px;"><input type="checkbox" id="nouveaute_<?php echo $row->ref; ?>" name="nouveaute[]" class="sytle_checkbox" onchange="checkvalues('nouveaute',<?php echo $row->ref; ?>)" <?php if($row->nouveaute) { ?> checked="checked" <?php } ?>/></li>
	<li style="width:53px;"><input type="checkbox" id="ligne_<?php echo $row->ref; ?>" name="ligne[]" class="sytle_checkbox" onchange="checkvalues('ligne',<?php echo $row->ref; ?>)" <?php if($row->ligne) { ?> checked="checked" <?php } ?>/></li>
	<li style="width:41px;"><a href="produit_modifier.php?ref=<?php echo($produit->ref); ?>&rubrique=<?php echo($produit->rubrique); ?>"  class="txt_vert_11">détails</a></li>
	
	<li style="width:78px; text-align:center;"> 
	<div class="bloc_classement">
  <div class="classement"> 
		<a href="produit_modifier.php?ref=<?php echo($produit->ref ); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" border="0" /></a>
	</div>
	 <div class="classement"><span id="classementprod_<?php echo $produit->id; ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
	 <div class="classement">
		<a href="produit_modifier.php?ref=<?php echo($produit->ref ); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" border="0" /></a>
	</div>
	</div>
	</li>
	<li style="width:37px; text-align:center;"><a href="javascript:supprimer_produit('<?php echo $produit->ref ?>','<?php echo($parent); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
     
<?php
if(!($i%2)) $fond="ligne_claire";
	else $fond="ligne_fonce";
}
	
}


?>