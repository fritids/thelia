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
<?php if(! est_autorise("acces_contenu")) exit; ?>
<?php
		if(!isset($parent)) $parent=0;
		if(!isset($lang)) $lang=0;
		if(!isset($i)) $i=0;

?>	

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
	$(".texte_edit").editable("ajax/listdos.php", { 
      select : true,
      onblur: "submit",
      cssclass : "ajaxedit"
  }); 

	$(".classement_edit").editable("ajax/classement.php", { 
	      select : true,
	      onblur: "submit",
	      cssclass : "ajaxedit",
		  callback : function(value, settings){
						$("#dossier").html(value);
						edit();	
					}
	  });
	
	  $(".contenudos_edit").editable("ajax/classement.php", { 
	      select : true,
	      onblur: "submit",
	      cssclass : "ajaxedit",
		  callback : function(value, settings){
						$("#contenudos").html(value);
						edit();
					}
	  });
	
}
</script>


<script type="text/JavaScript">
	function tri(order,ref,type,critere,alpha){
		$.ajax({
			type:"GET",
			url:"ajax/tricontenu.php",
			data : "ref="+ref+"&order="+order+"&type="+type+'&critere='+critere+"&alpha="+alpha,
			success : function(html){
				$("#"+type).html(html);
				$(".texte_edit").editable("ajax/contenu.php", { 
				      select : true,
				      onblur: "submit",
				      cssclass : "ajaxedit"
				  });
			}
		})
	}



function supprimer_contenu(id, parent){
	if(confirm("Voulez-vous vraiment supprimer ce contenu ?")) location="contenu_modifier.php?id=" + id + "&action=supprimer&parent=" + parent;

}

function supprimer_dossier(id, parent){
	if(confirm("Voulez-vous vraiment supprimer ce dossier ? Vous devez d'abord vider cellui-ci")) location="dossier_modifier.php?id=" + id + "&action=supprimer&parent=" + parent;

}

</script>
</head>
<body>
<div id="wrapper">
<div id="subwrapper">

<?php

	include_once("../classes/Dossier.class.php");
	include_once("../classes/Dossierdesc.class.php");

	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Contenu.class.php");

	include_once("../fonctions/divers.php");
?>

<?php
	$menu="contenu";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p align="left"><a href="accueil.php" class="lien04">Accueil </a><a href="#" onclick="document.getElementById('formulaire').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="listdos.php" class="lien04">Gestion du contenu</a>               

    <?php
                    $parentdesc = new Dossierdesc();
					$parentdesc->charger($parent, $lang);
					$parentnom = $parentdesc->titre;	
										
					$res = chemin_dos($parent);
					$tot = count($res)-1;
	
?>
                             
						
				
			<?php
				while($tot --){
			?>
			<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<a href="listdos.php?parent=<?php echo($res[$tot+1]->dossier); ?>" class="lien04"><?php echo($res[$tot+1]->titre); ?></a>  
			                         
            <?php
            	}
            
            ?>
             
			<?php
                    $parentdesc = new Dossierdesc();
					$parentdesc->charger($parent);
					$parentnom = $parentdesc->titre;	
					
			
			 if($parent != ""){
			 ?>
			 <img src="gfx/suivant.gif" width="12" height="9" border="0" />
			 <?php
			 }
			 ?>
			<a href="listdos.php?parent=<?php echo($parentdesc->dossier); ?>" class="lien04"><?php echo($parentdesc->titre); ?></a>
           </p>
<!-- début de la gestion des dossiers de contenu -->    
<div class="entete_liste">
	<div class="titre">LISTE DES DOSSIERS DE CONTENU </div><div class="fonction_ajout"><a href="dossier_modifier.php?parent=<?php echo($parent); ?>"><?php if($parent == "") { ?>AJOUTER UN DOSSIER<?php } else {?>AJOUTER UN SOUS-DOSSIER<?php } ?></a></div>
</div>
 <ul id="Nav">
		<li style="height:25px; width:119px; border-left:1px solid #96A8B5;"></li>
		
		<li style="height:25px; width:586px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Titre du dossier
			<ul class="Menu">
				<li style="width:591px;"><a href="javascript:tri('ASC','<?php echo($parent); ?>','dossier','titre','alpha')">Ordre alphabétique croissant</a></li>
				<li style="width:591px;"><a href="javascript:tri('DESC','<?php echo($parent); ?>','dossier','titre','alpha')">Ordre alphabétique d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:61px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:41px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:78px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Classement
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','dossier','classement','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','dossier','classement','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:44px; border-left:1px solid #96A8B5;">Suppr.</li>	

</ul>   
<div id="dossier">
<?php
	
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
	<li style="width:579px;"><span id="titredos_<?php echo $row->id; ?>" class="texte_edit"><?php echo substr($dossierdesc->titre,0,90); if(strlen($dossierdesc->titre) > 90) echo " ..."; ?></span></li>
	<li style="width:54px;"><a href="listdos.php?parent=<?php echo($dossierdesc->dossier); ?>">parcourir</a></li>
	<li style="width:34px;"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>">&eacute;diter</a></li>
	
	<li style="width:71px;">
	 <div class="bloc_classement">  
	    <div class="classement"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=M"><img src="gfx/up.gif" border="0" /></a></div>
	    <div class="classement"><span id="classementdossier_<?php echo $row->id; ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
	    <div class="classement"><a href="dossier_modifier.php?id=<?php echo($dossierdesc->dossier); ?>&action=modclassement&parent=<?php echo($parent); ?>&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
	 </div>
	</li>
	<li style="width:37px; text-align:center;"><a href="javascript:supprimer_dossier('<?php echo($dossierdesc->dossier); ?>', '<?php echo($parent); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
 

<!-- fin de la gestion des dossiers / début de la gestion des contenus -->
<?php
}

  		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
?>
</div> 
<div class="entete_liste" style="margin-top:20px">
	<div class="titre">LISTE DES CONTENUS</div>
	<div class="fonction_ajout"><a href="contenu_modifier.php?dossier=<?php echo($parent); ?>">AJOUTER UN CONTENU</a></div>
</div>  
<ul id="Nav2">
		<li style="height:25px; width:119px; border-left:1px solid #96A8B5;"></li>
		
		<li style="height:25px; width:586px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Titre du contenu
			<ul class="Menu">
				<li style="width:591px;"><a href="javascript:tri('ASC','<?php echo($parent); ?>','contenudos','titre','alpha')">Ordre alphabétique croissant</a></li>
				<li style="width:591px;"><a href="javascript:tri('DESC','<?php echo($parent); ?>','contenudos','titre','alpha')">Ordre alphabétique d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:61px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:41px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:78px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;cursor: pointer; ">
			Classement
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','<?php echo $parent; ?>','contenudos','classement','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','<?php echo $parent; ?>','contenudos','classement','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:44px; border-left:1px solid #96A8B5;">Suppr.</li>	

</ul>  
<div id="contenudos" class="bordure_bottom">
<?php
	
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
	<span id="titrecont_<?php echo $row->id; ?>" class="texte_edit"><?php echo substr($contenudesc->titre,0,90); if(strlen($contenudesc->titre) > 90) echo " ..."; ?></span></li>
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
		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
?>

<!-- fin du bloc de gestion des contenus -->
<?php
}
?>
	</div>
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
