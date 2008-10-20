<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/
?>
<?php
	include_once("pre.php");
	include_once("auth.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<script src="jquery/jquery.js" type="text/javascript"></script>
<script src="jquery/jeditable.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
        

  $(".texte_edit").editable("ajax/produit.php", { 
      indicator : "<img src='img/load.gif'>",
      select : true,
      onblur: "submit",
      cssclass : "ajaxedit"
  });

  
  
});

</script>

<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />

</head>

<script type="text/JavaScript">

function supprimer_produit(ref, parent){
	if(confirm("Voulez-vous vraiment supprimer ce produit ?")) location="produit_modifier.php?ref=" + ref + "&action=supprimer&parent=" + parent;

}

function supprimer_rubrique(id, parent){
	if(confirm("Voulez-vous vraiment supprimer cette rubrique ? Vous devez d'abord vider celle-ci")) location="rubrique_modifier.php?id=" + id + "&action=supprimer&parent=" + parent;

}

</script>

<body>

<div id="wrapper">
<div id="subwrapper">

<?php
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Produit.class.php");

	include_once("../fonctions/divers.php");
?>
<?php
	$menu="catalogue";
	include_once("entete.php");
	
	if(!isset($parent)) $parent="";
	if(!isset($lang)) $lang="";
	if(!isset($id)) $id="";
	if(!isset($classement)) $classement="";

?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion du catalogue</p>
     <p align="right"  class="geneva11Reg_3B4B5B"><a href="accueil.php"  class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="parcourir.php" class="lien04">Gestion du catalogue</a>
                          
            <?php
                    $parentdesc = new Rubriquedesc();

					$parentdesc->charger($parent, $lang);
					$parentnom = $parentdesc->titre;	
										
					$res = chemin($parent);
					$tot = count($res)-1;
	
?>
                             
			<?php
				if($parent){
			
			?>	
					<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<?php	
				}
				while($tot --){
			?>
				<a href="#" onclick="document.getElementById('formulaire').submit()"></a> <a href="parcourir.php?parent=<?php echo($res[$tot+1]->rubrique); ?>" class="lien04"> <?php echo($res[$tot+1]->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />                             
            <?php
            	}
            
            ?>
            
			<?php
                    $parentdesc = new Rubriquedesc();
					if($parent) $parentdesc->charger($parent);
					else $parentdesc->charger($id);
					$parentnom = $parentdesc->titre;	
					
			?>
			 <a href="parcourir.php?parent=<?php echo($parentdesc->rubrique); ?>" class="lien02"> <?php echo($parentdesc->titre); ?></a>                             


<?php
	$test = new Rubrique();
	$test->charger($parent);
	
	if(! $test->nbprod()){
?>
     <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100%" height="30" class="titre_cellule_tres_sombre">LISTE DES RUBRIQUES </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
<?php
	
	$rubrique = new Rubrique();
	$rubriquedesc = new Rubriquedesc();
	
	$query = "select * from $rubrique->table where parent=\"$parent\" order by classement";
	$resul = mysql_query($query, $rubrique->link);		

	$i=0;

	while($row = mysql_fetch_object($resul)){
		$rubriquedesc->charger($row->id);
		
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
  		$i++;
?>

    
  <tr class="<?php echo($fond); ?>">
    <td width="26%" height="30" align="left"><?php echo($rubriquedesc->titre); ?></td>
    <td width="26%" height="30"  align="left"><a href="parcourir.php?parent=<?php echo($rubriquedesc->rubrique); ?>" class="txt_vert_11">Poursuivre </a><a href="parcourir.php?parent=<?php echo($rubriquedesc->rubrique); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a> </td>
    <td width="21%" height="30">
      <div align="left"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>" class="txt_vert_11">Modifier  </a><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td> 
	 <td width="15%" height="30" align="left"><a href="javascript:supprimer_rubrique('<?php echo $rubriquedesc->rubrique ?>','<?php echo($parent); ?>')" class="txt_vert_11">Supprimer</a> <a href="javascript:supprimer_rubrique('<?php echo $rubriquedesc->rubrique ?>','<?php echo($parent); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a> </td>
	  <td width="6%" height="30">   
	    <div align="center"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" width="12" height="9" border="0" /></a></div>
	  </td>
	   <td width="6%" height="30">  
	     <div align="center"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" width="12" height="9" border="0" /></a></div>
	   </td>
  </tr>

     
<?php
}
?>  

<?php
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
?>

  </table>
     <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="5"></td>
    </tr>
    <tr>
      <td width="100%" height="30" class="titre_cellule_tres_sombre2">
	   <a href="rubrique_modifier.php?parent=<?php echo($parent); ?>" class="lien_titre_cellule"><?php if($parent == "") { ?>Ajouter une rubrique<?php } else {?>Ajouter une sous rubrique<?php } ?></a></td>
    </tr>
  </table>

<br /><br />

<?php } ?>

<?php

	$produit = new Produit();
	$produitdesc = new Produitdesc();
	
	if($classement == "alpha")
		$query = "select * from $produitdesc->table LEFT JOIN $produit->table ON $produit->table.id=$produitdesc->table.produit where $produit->table.rubrique=\"$parent\" and lang=\"1\" order by $produitdesc->table.titre";		

	else $query = "select * from $produit->table where rubrique=\"$parent\" order by classement";

$resul = mysql_query($query, $produit->link);		

$i = 0;

if(mysql_numrows($resul)){
?>
 
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100%" height="30" class="titre_cellule_tres_sombre">LISTE DES PRODUITS </td>
     </tr>
   </table>
   
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

<tr class="cellule_claire">
  <td>&nbsp;</td>
  <td>Titre</td>
  <td>Stock</td>
  <td>Prix</td>
  <td>Prix promo</td>
  <td>En promo</td>
  <td>Nouveaut&eacute;</td>
  <td>En ligne</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>

</tr>      

<?php
	


	while($row = mysql_fetch_object($resul)){
		$produit->charger($row->ref);
		$produitdesc->charger($row->id);

		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
  		$i++;

?>


<?php

	$image = new Image();
	$query_image = "select * from $image->table where produit=\"" . $row->id . "\" order by classement limit 0,1";
	$resul_image = mysql_query($query_image, $image->link);
	$row_image = mysql_fetch_object($resul_image);
?>

    
  <tr valign="middle" class="<?php echo($fond); ?>">
    <td width="5%" height="30"  align="left"><?php if($row_image) { ?> <img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/<?php echo $row_image->fichier;?>&width=60" class="imagelprod" alt="<?php echo($produit->ref); ?>" title="<?php echo($produit->ref); ?>" /><?php } else { ?><span class="imagelprod"><?php echo($row->ref); ?><?php } ?></span></td>
    <td width="12%" height="30"  align="left"><span id="titre_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($produitdesc->titre); ?></span></td>

    <td width="3%" height="30"><span id="stock_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->stock); ?></span></td>
    <td width="4%" height="30"><span id="prix_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix); ?></span></td>
    <td width="3%" height="30"><span id="prix2_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix2); ?></span></td>
    <td width="3%" height="30"><input type="checkbox" <?php if($row->promo) { ?> checked="checked" <?php } ?>/></td>
    <td width="3%" height="30"><input type="checkbox" <?php if($row->nouveaute) { ?> checked="checked" <?php } ?>/></td>
    <td width="3%" height="30"><input type="checkbox" <?php if($row->ligne) { ?> checked="checked" <?php } ?>/></td>


    <td width="2%" height="30">
      <div align="left"><a href="produit_modifier.php?ref=<?php echo($produit->ref); ?>&rubrique=<?php echo($produit->rubrique); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></div>
    </td>
	<td width="2%" height="30"  align="left"><a href="javascript:supprimer_produit('<?php echo $produit->ref ?>','<?php echo($parent); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a> </td>
		<td width="1%" height="30">
		  <a href="produit_modifier.php?ref=<?php echo($produit->ref ); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" width="12" height="9" border="0" /></a>
		</td>
			<td width="1%" height="30">
			  <a href="produit_modifier.php?ref=<?php echo($produit->ref ); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" width="12" height="9" border="0" /></a>
			</td>
  </tr>

     
<?php
}
?>  


<?php
		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
?>
  </table>

<?php
	}
?>
  
<?php
	$test = new Rubrique();
	$test->charger($parent);
	
	if(! $test->aenfant()){
?> 
     <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="5"></td>
    </tr>
    <tr>
      <td width="100%" height="30" class="titre_cellule_tres_sombre2">
	   <a href="produit_modifier.php?rubrique=<?php echo($parent); ?>" class="lien_titre_cellule">Ajouter un produit</a></td>
    </tr>
  </table>
<?php
	}
?>  
  
</div>
</div>
</div>
</body>
</html>
