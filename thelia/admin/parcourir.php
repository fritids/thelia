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
<?php if(! est_autorise("acces_catalogue")) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<script src="../lib/jquery/jeditable.js" type="text/javascript"></script>
<script src="../lib/jquery/menu.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function() {
 edit();
});

function edit(){
	$(".texte_edit").editable("ajax/produit.php", {
	  loadurl : "ajax/load.php", 
      select : true,
      onblur: "submit",
      cssclass : "ajaxedit"
  }); 

	$(".classement_edit").editable("ajax/classement.php", { 
	      select : true,
	      onblur: "submit",
	      cssclass : "ajaxedit",
		  callback : function(value, settings){
						var repere = value.split("|");
						if(repere[0] == "produit") $("#resulproduit").html(repere[1]);
						else if(repere[0] == "rubrique") $("#resulrubrique").html(repere[1]);
						edit();	
					}
	  });
	
}

function checkvalues(type,id){
	$.ajax({type:'POST', url:'ajax/produit.php', data:'id='+type+'_'+id })
}

</script>
</head>

<script type="text/JavaScript">

function supprimer_produit(ref, parent){
	if(confirm("Voulez-vous vraiment supprimer ce produit ?")) location="produit_modifier.php?ref=" + ref + "&action=supprimer&parent=" + parent;

}

function supprimer_rubrique(id, parent){
	if(confirm("Voulez-vous vraiment supprimer cette rubrique ? Vous devez d'abord vider celle-ci")) location="rubrique_modifier.php?id=" + id + "&action=supprimer&parent=" + parent;

}



function check(nom,objet,rubrique,modif){

		var checkall=document.getElementsByName(nom);
	 	for (i=0;i<checkall.length;i++){ 
			if(checkall[i].type == "checkbox")
			  if(modif == 0) 
				checkall[i].checked=false;
			  else
				checkall[i].checked=true;
	 	}
	

	$.ajax({type:'GET', url:'ajax/checkall.php', data:'id='+objet+'_'+rubrique+"&modif="+modif })
	
}
	
	
function tri(order,ref,type,critere,alpha){
	$.ajax({
		type:"GET",
		url:"ajax/tri.php",
		data : "ref="+ref+"&order="+order+"&type="+type+'&critere='+critere+"&alpha="+alpha,
		success : function(html){
			if(type == "1") $("#resulrubrique").html(html);
			else $("#resulproduit").html(html);
			$(".texte_edit").editable("ajax/produit.php", { 
     		indicator : "<img src='img/load.gif'>",
     		select : true,
     		onblur: "submit",
      		cssclass : "ajaxedit"
  			});

			$(".classement_edit").editable("ajax/classement.php", { 
			      indicator : "<img src='img/load.gif'>",
			      select : true,
			      onblur: "submit",
			      cssclass : "ajaxedit",
				  callback : function(value, settings){
								$("#resul").html(value);
							}
			  });
			
  
		}
	})
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
<p align="left"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="parcourir.php" class="lien04">Gestion du catalogue</a>
                          
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
			 <a href="parcourir.php?parent=<?php echo($parentdesc->rubrique); ?>" class="lien04"> <?php echo($parentdesc->titre); ?></a>                             

<?php
	$test = new Rubrique();
	$test->charger($parent);
	
?>
<div class="entete_liste">
	<div class="titre">LISTE DES RUBRIQUES</div><div class="fonction_ajout"><a href="rubrique_modifier.php?parent=<?php echo($parent); ?>"><?php if($parent == "") { ?>AJOUTER UNE RUBRIQUE<?php } else {?>AJOUTER UNE SOUS-RUBRIQUE<?php } ?></a></div>
</div>
<ul id="Nav">
		<li style="height:25px; width:119px; border-left:1px solid #96A8B5;">
		
		</li>
		
		<li style="height:25px; width:586px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;  cursor: pointer;">
			Titre de la rubrique
			<ul class="Menu">
				<li style="width:591px;"><a href="javascript:tri('ASC','<?php echo $parent; ?>','1','titre','alpha')">Tri croissant</a></li>
				<li style="width:591px;"><a href="javascript:tri('DESC','<?php echo $parent; ?>','1','titre','alpha')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>

		<li style="height:25px; width:61px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:41px; border-left:1px solid #96A8B5;"></li>	
		<li style="height:25px; width:78px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer;">
			Classement
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','1','classement','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','1','classement','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:44px; border-left:1px solid #96A8B5;">Suppr.</li>	
	</ul>
<div id="resulrubrique">

<?php
	
	$rubrique = new Rubrique();
	$rubriquedesc = new Rubriquedesc();
	
	$query = "select * from $rubrique->table where parent=\"$parent\" order by classement";
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
	<li style="width:54px;"><a href="parcourir.php?parent=<?php echo($rubriquedesc->rubrique); ?>" >parcourir</a></li>
	<li style="width:34px;"><a href="rubrique_modifier.php?id=<?php echo($rubriquedesc->rubrique); ?>" alt="id : <?php echo $row->id; ?>" title="id : <?php echo $row->id; ?>">éditer</a></li>
	
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
?>  
</div>
<?php
		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";

	$produit = new Produit();
	$produitdesc = new Produitdesc();
	
	if($classement == "alpha")
		$query = "select * from $produitdesc->table LEFT JOIN $produit->table ON $produit->table.id=$produitdesc->table.produit where $produit->table.rubrique=\"$parent\" and lang=\"1\" order by $produitdesc->table.titre";		

	else $query = "select * from $produit->table where rubrique=\"$parent\" order by classement";

$resul = mysql_query($query, $produit->link);		

$i = 0;


?>
<?php
	$test = new Rubrique();
	$test->charger($parent);
	
	
?> 


<div class="entete_liste" style="margin-top:20px">
	<div class="titre">LISTE DES PRODUITS</div>
	<div class="fonction_ajout"><a href="produit_modifier.php?rubrique=<?php echo($parent); ?>">AJOUTER UN PRODUIT</a></div>
</div>

 
<ul id="Nav2">
		<li style="height:25px; width:44px; border-left:1px solid #96A8B5;"> </li>
		<li style="height:25px; width:68px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">Réf.
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','2','ref','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','2','ref','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:232px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Titre du produit
			<ul class="Menu">
				<li style="width:232px;"><a href="javascript:tri('ASC','<?php echo $parent; ?>','2','titre','alpha')">Tri croissant</a></li>
				<li style="width:232px;"><a href="javascript:tri('DESC','<?php echo $parent; ?>','2','titre','alpha')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>

		<li style="height:25px; width:46px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">Stock
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','2','stock','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','2','stock','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:37px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">Prix
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','2','prix','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','2','prix','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>	
		<li style="height:25px; width:75px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">Prix promo
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','2','prix2','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','2','prix2','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>	
		<li style="height:25px; width:71px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Promotion
			<ul class="Menu">
				<li><a href="javascript:check('promo[]','promo',<?php echo $parent; ?>,1)">Tout cocher</a></li>
				<li><a href="javascript:check('promo[]','promo',<?php echo $parent; ?>,0)">Tout décocher</a></li>
			</ul>
		</li>
		<li style="height:25px; width:71px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Nouveauté
			<ul class="Menu">
				<li><a href="javascript:check('nouveaute[]','nouveaute',<?php echo $parent; ?>,1)">Tout cocher</a></li>
				<li><a href="javascript:check('nouveaute[]','nouveaute',<?php echo $parent; ?>,0)">Tout décocher</a></li>
			</ul>
		</li>
		<li style="height:25px; width:60px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			En ligne
			<ul class="Menu">
				<li><a href="javascript:check('ligne[]','ligne',<?php echo $parent; ?>,1)">Tout cocher</a></li>
				<li><a href="javascript:check('ligne[]','ligne',<?php echo $parent; ?>,0)">Tout décocher</a></li>
			</ul>
		</li>
		<li style="height:25px; width:48px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:85px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Classement
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','2','classement','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','2','classement','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:44px; border-left:1px solid #96A8B5;">Suppr.</li>
	</ul>

<div id="resulproduit" class="bordure_bottom">
<?php
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
	<li><div class="vignette"><?php if($row_image) { ?><a href="produit_modifier.php?ref=<?php echo($produit->ref); ?>&rubrique=<?php echo($produit->rubrique); ?>"> <img src="../fonctions/redimlive.php?type=produit&nomorig=<?php echo $row_image->fichier;?>&width=51" title="id : <?php echo($produit->id); ?>" alt="id : <?php echo($produit->id); ?>" /></a><?php }  ?></div></li>
	<li style="width:61px;"><span class="texte_noedit" title="<?php echo($row->ref); ?>"><?php echo(substr($row->ref,0,9)); if(strlen($row->ref)>9) echo " ..."; ?></span></li>
	<li style="width:225px;"><span id="titreprod_<?php echo $row->ref; ?>" class="texte_edit"><?php echo substr($produitdesc->titre,0,35); if(strlen($produitdesc->titre) > 35) echo " ..."; ?></span></li>
	<li style="width:39px;"><span id="stock_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->stock); ?></span></li>
	<li style="width:30px;"><span id="prix_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix); ?></span></li>
	<li style="width:68px;"><span id="prix2_<?php echo $row->ref; ?>" class="texte_edit"><?php echo($row->prix2); ?></span></li>
	<li style="width:64px;"><input id="promo_<?php echo $row->ref; ?>" type="checkbox" name="promo[]" class="sytle_checkbox" onchange="checkvalues('promo','<?php echo $row->ref; ?>')" <?php if($row->promo) { ?> checked="checked" <?php } ?>/></li>
	<li style="width:64px;"><input type="checkbox" id="nouveaute_<?php echo $row->ref; ?>" name="nouveaute[]" class="sytle_checkbox" onchange="checkvalues('nouveaute','<?php echo $row->ref; ?>')" <?php if($row->nouveaute) { ?> checked="checked" <?php } ?>/></li>
	<li style="width:53px;"><input type="checkbox" id="ligne_<?php echo $row->ref; ?>" name="ligne[]" class="sytle_checkbox" onchange="checkvalues('ligne','<?php echo $row->ref; ?>')" <?php if($row->ligne) { ?> checked="checked" <?php } ?>/></li>
	<li style="width:41px;"><a href="produit_modifier.php?ref=<?php echo($produit->ref); ?>&rubrique=<?php echo($produit->rubrique); ?>">éditer</a></li>
	
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

?>  
</div>
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
