<?php
include_once("pre.php");
include_once("auth.php");

include_once("../classes/Commande.class.php");
include_once("../classes/Venteprod.class.php");
include_once("../classes/Produit.class.php");
include_once("../classes/Produitdesc.class.php");

$produit = new Produit();
if($produit->charger($ref)){
	$produitdesc = new Produitdesc();
	$produitdesc->charger($produit->id);
	$_SESSION["commande"]->nbart++;
	$nbart = $_SESSION["commande"]->nbart;
	$_SESSION["commande"]->venteprod[$nbart-1] = new Venteprod();
	$_SESSION["commande"]->venteprod[$nbart-1]->ref = $produit->ref;
	$_SESSION["commande"]->venteprod[$nbart-1]->titre = $produitdesc->titre;
	$_SESSION["commande"]->venteprod[$nbart-1]->quantite = $_REQUEST["qtite"];
	$_SESSION["commande"]->venteprod[$nbart-1]->tva = $tva;
	$_SESSION["commande"]->venteprod[$nbart-1]->prixu = $prixu;
	
}
else{
	$_SESSION["commande"]->nbart++;
	$nbart = $_SESSION["commande"]->nbart;
	$_SESSION["commande"]->venteprod[$nbart-1] = new Venteprod();
	$_SESSION["commande"]->venteprod[$nbart-1]->ref = $ref;
	$_SESSION["commande"]->venteprod[$nbart-1]->titre = $titre;
	$_SESSION["commande"]->venteprod[$nbart-1]->quantite = $qtite;
	$_SESSION["commande"]->venteprod[$nbart-1]->tva = $tva;
	$_SESSION["commande"]->venteprod[$nbart-1]->prixu = $prixu;
}
?>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="titre_cellule" height="30" width="200">R&eacute;f&eacute;rence</td>
		<td class="titre_cellule" height="30" width="200">Titre</td>
		<td class="titre_cellule" height="30" width="200">Prix Unitaire</td>
		<td class="titre_cellule" height="30" width="200">Quantit&eacute;</td>
		<td class="titre_cellule" height="30" width="200">TVA</td>
	</tr>
<?php
$j=0;
for($i=0;$i<$_SESSION["commande"]->nbart;$i++){
	if($i%2 == 0) $fond="claire";
	else $fond="sombre";
	$j++;
	?>
	<tr>
		<td class="cellule_<?php echo $fond; ?>" height="30" width="200"><?php echo $_SESSION["commande"]->venteprod[$i]->ref; ?></td>
		<td class="cellule_<?php echo $fond; ?>" height="30" width="200"><?php echo $_SESSION["commande"]->venteprod[$i]->titre; ?></td>
		<td class="cellule_<?php echo $fond; ?>" height="30" width="200"><?php echo $_SESSION["commande"]->venteprod[$i]->prixu; ?></td>
		<td class="cellule_<?php echo $fond; ?>" height="30" width="200"><?php echo $_SESSION["commande"]->venteprod[$i]->quantite; ?></td>
		<td class="cellule_<?php echo $fond; ?>" height="30" width="200"><?php echo $_SESSION["commande"]->venteprod[$i]->tva; ?></td>
	</tr>
	<?php
}
?>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="titre_cellule" height="30" width="800"> </td>
		<td class="titre_cellule" height="30"><a href="#" onclick="valid()">PASSER COMMANDE</a></td>
	</tr>
</table>