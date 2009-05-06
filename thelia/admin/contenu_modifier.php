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
	
	if(!isset($action)) $action="";
	if(!isset($lang)) $lang="1";
	if(!isset($ligne)) $ligne="";
?>
<?php
	 include_once("../classes/Variable.class.php");  
?>
<?php
	include_once("../classes/Dossier.class.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Image.class.php");
    include_once("../classes/Document.class.php");  
    include_once("../classes/Zone.class.php");  
    include_once("../classes/Pays.class.php");  
    include_once("../classes/Lang.class.php");  
	include_once("../classes/Variable.class.php");
?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $parent, $type); break;
		case 'modifier' : modifier($id, $lang, $dossier, $ligne, $titre, $chapo, $description, $postscriptum, $urlsuiv); break;
		case 'ajouter' : ajouter($lang, $dossier, $ligne, $titre, $chapo, $description, $postscriptum); break;
		case 'supprimer' : supprimer($id, $parent); break;
		case 'ajouterphoto' : ajouterphoto($id,$lang); break;
		case 'modifierphoto' : modifierphoto($id_photo,$titre_photo,$chapo_photo,$description_photo,$lang); break;
		case 'supprimerphoto' : supprimerphoto($id_photo,$lang); break;
		case 'modclassementphoto' : modclassementphoto($id_photo,$type); break;
		case 'ajouterdoc' : ajouterdoc($id, $_FILES['doc']['tmp_name'], $_FILES['doc']['name'],$lang); break;
		case 'modifierdoc' : modifierdoc($id_document,$titredoc,$chapodoc,$descriptiondoc,$lang); break;
		case 'supprimerdoc' : supprimerdoc($id_document,$lang); break;
		case 'modclassementdoc' : modclassementdoc($id_document,$type); break;

	}
	
?>
<?php

	function modclassementdoc($id, $type){

      	$doc = new Document();
        $doc->charger($id);
        $doc->changer_classement($id, $type);
	}

	function supprimerdoc($id,$lang){
		
			$tmp = new Contenu();
			$tmp->charger($_REQUEST['id']);

			$document = new Document();
			$document->charger($id);
			
			if(file_exists("../client/document/$document->fichier")){
				 unlink("../client/document/$document->fichier");
			}
			
			$document->supprimer();
		    header("Location: contenu_modifier.php?id=" . $tmp->id."&dossier=".$tmp->dossier."&lang=".$lang);
	}

	function modifierdoc($id, $titre, $chapo, $description,$lang){

		$tmp = new Contenu();
		$tmp->charger($_REQUEST['id']);
	
		$documentdesc = new Documentdesc();
		$documentdesc->document = $id;
		$documentdesc->lang = $lang;
	
		$documentdesc->charger($id,$lang);
		
		$documentdesc->titre = $titre;
		$documentdesc->chapo = $chapo;
		$documentdesc->description = $description;
	
		if(!$documentdesc->id)
			$documentdesc->add();
		else 
			$documentdesc->maj();

	    header("Location: contenu_modifier.php?id=" . $tmp->id."&dossier=".$tmp->dossier."&lang=".$lang);

	}

	function ajouterdoc($contenu, $doc, $doc_name,$lang){

		$tmp = new Contenu();
		$tmp->charger($_REQUEST['id']);

		if($doc != ""){

			$fich = substr($doc_name, 0, strlen($doc_name)-4);
			$ext = substr($doc_name, strlen($doc_name)-3);
			
			$document = new Document();
			$documentdesc = new Documentdesc();

		 	$query = "select max(classement) as maxClassement from $document->table where contenu='" . $contenu . "'";

	 		$resul = mysql_query($query, $document->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
     					
			$document->contenu = $contenu;
			$document->classement = $maxClassement+1;
			
			$lastid = $document->add();
			$document->charger($lastid);
			$fich = eregfic($fich);
			$document->fichier = $fich . "_" . $contenu . "." . $ext;
			$document->maj();
					
			copy("$doc", "../client/document/" . $fich . "_" . $contenu . "." . $ext);
			
			header("location: contenu_modifier.php?id=".$tmp->id."&dossier=".$tmp->dossier."&lang=".$lang);
		}
	
	}

	function modclassementphoto($id, $type){
      	$img = new Image();
        $img->charger($id);
        $img->changer_classement($id, $type);
	}

	function supprimerphoto($id,$lang){
		
			$tmp = new Contenu();
			$tmp->charger($_REQUEST['id']);

			$image = new Image();
			$image->charger($id);

			$imagedesc = new Imagedesc();
			$imagedesc->charger($image->id);
						
			if(file_exists("../client/gfx/photos/contenu/$image->fichier"))
				 unlink("../client/gfx/photos/contenu/$image->fichier");
		
			
			$image->supprimer();
			$imagedesc->delete();
			
			header("location: contenu_modifier.php?id=".$tmp->id."&dossier=".$tmp->dossier."&lang=".$lang);
			
	}

	function modifierphoto($id, $titre, $chapo, $description,$lang){
		$tmp = new Contenu();
		$tmp->charger($_REQUEST['id']);

		$imagedesc = new Imagedesc();
		$imagedesc->image = $id;
		$imagedesc->lang = $lang;
	
		$imagedesc->charger($id,$lang);
		
		$imagedesc->titre = $titre;
		$imagedesc->chapo = $chapo;
		$imagedesc->description = $description;
	
		if(!$imagedesc->id)
			$imagedesc->add();
		else 
			$imagedesc->maj();
			
		header("location: contenu_modifier.php?id=".$tmp->id."&dossier=".$tmp->dossier."&lang=".$lang);

	}

	function ajouterphoto($id,$lang){

		$tmp = new Contenu();
		$tmp->charger($_REQUEST['id']);
		
		if(!isset($nomorig)) $nomorig="";
	
		for($i = 1; $i<6; $i++){
			$photo = $_FILES["photo" . $i]['tmp_name'];
			$photo_name = $_FILES["photo" . $i]['name'];
		
		if($photo != ""){

       	    $extension = substr($photo_name, strlen($nomorig)-3);
			$fich = substr($photo_name, 0, strlen($photo_name)-4);
			
			$photoprodw = new Variable();
			$photoprodw->charger("photoprodw");
			 
			$image = new Image();
			$imagedesc = new Imagedesc();


		 	$query = "select max(classement) as maxClassement from $image->table where contenu='" . $id . "'";

	 		$resul = mysql_query($query, $image->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");

			
			$image->contenu = $id;
			$image->classement = $maxClassement + 1;

			$lastid = $image->add();								

			$image->charger($lastid);
			$image->fichier = $fich . "_" . $lastid . "." . $extension;
			$image->maj();
			
			copy("$photo", "../client/gfx/photos/contenu/" . $fich . "_" . $lastid . "." . $extension);
			
			header("location: contenu_modifier.php?id=".$tmp->id."&dossier=".$tmp->dossier."&lang=".$lang);
		}
	 }
	}

	function modclassement($id, $parent, $type){

      	$cont = new Contenu();
        $cont->charger($id);
        $cont->changer_classement($id, $type);
		
	    header("Location: listdos.php?parent=" . $parent);
	}
	
	
	function modifier($id, $lang, $dossier, $ligne, $titre, $chapo, $description, $postscriptum, $urlsuiv){

	 if(!isset($id)) $id="";

		if(!$lang) $lang=1;
		
		$contenu = new Contenu();
		$contenudesc = new Contenudesc();
		$contenu->charger($id);
		$res = $contenudesc->charger($contenu->id, $lang);	


		if(!$res){
			$temp = new Contenudesc();
			$temp->contenu=$contenu->id;
			$temp->lang=$lang;
			$temp->add();
			$contenudesc->charger($contenu->id, $lang);
		}

		 $contenu->datemodif = date("Y-m-d H:i:s");		
		 $contenu->dossier = $dossier; 
	 	 if($ligne == "on") $contenu->ligne = 1; else $contenu->ligne = 0;
		 $contenudesc->chapo = $chapo;
		 $contenudesc->description = $description;
		 $contenudesc->postscriptum = $postscriptum;
		 $contenudesc->titre = $titre;
	 	 
	 	 $contenudesc->chapo = ereg_replace("\n", "<br/>", $contenudesc->chapo);
											
		$contenu->maj();
		$contenudesc->maj();
		
		if($urlsuiv) header("location: listdos.php?parent=".$contenu->dossier);
	    else header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $contenu->id . "&dossier=" . $contenu->dossier ."&lang=".$lang);
		exit;
	}

	function ajouter($lang, $dossier, $ligne, $titre, $chapo, $description, $postscriptum){

 	 if(!isset($id)) $id="";
	 
 	 $contenu = new Contenu();
	 $contenu->charger($id);
	 
   	 if($contenu->id) return;
   	 
	 $contenu = new Contenu();

	 $query = "select max(classement) as maxClassement from $contenu->table where dossier='" . $dossier . "'";

	 $resul = mysql_query($query, $contenu->link);
     $maxClassement = mysql_result($resul, 0, "maxClassement");

	 $contenu->datemodif = date("Y-m-d H:i:s");	
	 $contenu->dossier = $dossier; 
	 if($ligne == "on") $contenu->ligne = 1; else $contenu->ligne = 0;
	 $contenu->classement = $maxClassement + 1;
	 
	 $lastid = $contenu->add();
	
	 $contenudesc = new Contenudesc();	

	 $contenudesc->chapo = $chapo;
	 $contenudesc->description = $description;
	 $contenudesc->postscriptum = $postscriptum;
	 $contenudesc->contenu = $lastid;
	 $contenudesc->lang = 1;
	 $contenudesc->titre = $titre;

	 $contenudesc->chapo = ereg_replace("\n", "<br/>", $contenudesc->chapo);
     $contenudesc->postscriptum = ereg_replace("\n", "<br/>", $contenudesc->postscriptum);		
	 
	 $contenudesc->add();

	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $lastid . "&dossier=" . $contenu->dossier);
		exit;

	}
	
	function supprimer($id, $parent){
		
		$contenu = new Contenu();		
		$contenu->charger($id);
		$contenu->supprimer();

	    header("Location: listdos.php?parent=" . $parent);
		exit;
	}
	
?>
<?php

	if(!isset($id)) $id="";
	
	$contenu = new Contenu();
	$contenudesc = new Contenudesc();
	
	$contenu->charger($id);
	$contenudesc->charger($contenu->id, $lang);
	
	$contenudesc->chapo = ereg_replace("<br/>", "\n", $contenudesc->chapo);
	$contenudesc->postscriptum = ereg_replace("<br/>", "\n", $contenudesc->postscriptum);
	
	$site = new Variable();
	$site->charger("urlsite");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>
<?php include_once("tinymce.php"); ?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="contenu";
	include_once("entete.php");
?>
<div id="contenu_int">
  <p align="left"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="listdos.php" class="lien04">Gestion du contenu </a>
  
    <?php
    				$cont = new Contenu();
    				$cont->charger($id);
   				
    				$contdesc = new Contenudesc();
    				$contdesc->charger($cont->id);
    														
					$parentnom = $contdesc->titre;	

					if($cont->dossier) $res = chemin_dos($cont->dossier);
					else $res = chemin_dos($dossier);

					$tot = count($res)-1;
	
?>
                             
						
				
			<?php
				if($cont->dossier || $dossier){
			
			?>	
				<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<?php	
				}
			
				while($tot --){
			?><a href="listdos.php?parent=<?php echo($res[$tot+1]->id); ?>" class="lien04"><?php echo($res[$tot+1]->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<?php
            	}
            
            ?>
            
			<?php
                    $parentdesc = new Dossierdesc();
                    if($cont->dossier)
						$parentdesc->charger($cont->dossier);
					else $parentdesc->charger($dossier);
					
					$parentnom = $parentdesc->titre;	
		
			
			?>
			<a href="listdos.php?parent=<?php echo($parentdesc->dossier); ?>" class="lien04"><?php echo($parentdesc->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />
			
			 <?php if( $id) { ?>
			 
			<a href="#" class="lien04"><?php echo($contdesc->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />
           Modifier<?php } else { ?> Ajouter <?php } ?> </p>	
           
<div id="bloc_description">
 <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="formulaire" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" name="id" value="<?php echo($id); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
 	<input type="hidden" name="dossier" value="<?php echo($dossier); ?>" /> 
	<input type="hidden" name="urlsuiv" id="url" value="0">

<!-- bloc descriptif du produit -->   	
		<div class="entete">
			<div class="titre">DESCRIPTION GÉNÉRALE DU CONTENU</div>
			<?php if($ref){
				$site = new Variable();
				$site->charger("urlsite");
				?>
				
			<div class="voirenligne"><a title="Voir le contenu en ligne" href="<?php echo $site->valeur; ?>/contenu.php?ref=<?php echo $ref; ?>&id_rubrique=<?php echo $rubrique; ?>" target="_blank" ><img src="gfx/voir-produit-enligne.png" alt="Voir le contenu en ligne" title="Voir le contenu en ligne" /></a></div>
			
			<?php
			}
			?>
			<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit()">VALIDER LES MODIFICATIONS</a></div>
		</div>
	<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
        <td class="designation">Changer la langue</td>
        <td>
        <?php
			$langl = new Lang();
			$query = "select * from $langl->table";
			$resul = mysql_query($query);
			while($row = mysql_fetch_object($resul)){
			$langl->charger($row->id);
		?>
		<a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&dossier=<?php echo($dossier); ?>&lang=<?php echo($langl->id); ?>"><img src="gfx/lang<?php echo($langl->id); ?>.gif" /></a>
		<?php } ?>
</td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Titre</td>
        <td><input name="titre" id="titre" type="text" class="form_long" value="<?php echo($contenudesc->titre); ?>"></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Chapo<br /> <span class="note">(courte description d'introduction)</span></td>
        <td> <textarea name="chapo" id="chapo" cols="40" rows="2" class="form_long"><?php echo($contenudesc->chapo); ?></textarea></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Description<br /> <span class="note">(description complète)</span></td>
        <td>
        <textarea name="description" id="description" cols="40" rows="2"><?php echo($contenudesc->description); ?></textarea></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Postscriptum<br /> <span class="note">(champs d'information complémentaire)</span></td>
        <td><textarea name="postscriptum" id="postscriptum" cols="40" rows="2" class="form_long"><?php echo($contenudesc->postscriptum); ?></textarea></td>
   	</tr>
   <?php
	if($id != ""){
   ?>
   <tr class="fonce">
      <td class="designation">Appartenance<br /> <span class="note">(déplacer dans un autre dossier)</span></td>
      <td style="vertical-align:top;">
        <select name="dossier" id="dossier"  class="form_long">
      		<?php if($id) echo arbreOption_dos(0, 1, $contenu->dossier); else echo arbreOption_dos(0, 1, $dossier); ?>
      	</select>
        </span></td>
    </tr>
  <?php
	} 
   ?>
	<tr class="claire">
      <td class="designation">En ligne :</td>
      <td>    <input type="checkbox" name="ligne" id="ligne" class="form" <?php if($contenu->ligne) echo "checked"; ?>></td>
    </tr>  
    </table>
<?php if($id != ""){ ?>
<!-- bloc d'informations sur le contenu-->  
<ul id="blocs_pliants_prod">

	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">INFORMATIONS SUR LE CONTENU</a></h3>
			<ul>
				<li class="lignesimple">
					<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">ID</div>
					<div class="cellule" style="width:450px; padding: 5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;"><?php echo($contenu->id); ?></div>
				</li>
			
			<li class="lignesimple">
				<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px;">URL réécrite</div>
				<div class="cellule" style="padding: 5px 0 0 5px;"><?php echo(rewrite_cont("$contenu->id", $lang)); ?></div>
			</li>
		<h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" /></a></h3>

		</ul>		
		
	</li>

</ul>
 <?php } ?> 

   </form>
 <?php 
	admin_inclure("contenumodifier"); 
 ?>
</div>    
 <?php if($id != ""){ ?>  
<!-- bloc de gestion des photos et documents / colonne de droite -->   
<div id="bloc_photos">
<!-- Boite à outils -->   
<div class="entete">
	<div class="titre">BOITE A OUTILS</div>
</div>
<div class="bloc_transfert">
	<div class="claire">
		<div class="champs" style="padding-top:10px; width:375px;">
			<?php
			$query = "select count(id) as maxcount from $contenu->table where dossier=$contenu->dossier";
			$resul = mysql_query($query);
			$maxclassement = mysql_result($resul,0,"maxcount");
			if($contenu->classement>1){
				$prec = $contenu->classement-1;
				$query = "select id from $contenu->table where dossier=$contenu->dossier and classement=$prec";
				$resul = mysql_query($query);
				$idprec = mysql_result($resul,0,"id");
			?>
			<a href="contenu_modifier.php?id=<?php echo $idprec; ?>&dossier=<?php echo $contenu->dossier; ?>"><img src="gfx/precedent.png" alt="Contenu pr&eacute;c&eacute;dent" title="Contenu pr&eacute;c&eacute;dent" style="padding:0 5px 0 0;margin-top:-5px;height:38px;"/></a>
			<?php
			}
			?>	
			<!-- pour visualiser la page rubrique correspondante en ligne -->
			<a title="Voir le contenu en ligne" href="<?php echo $site->valeur; ?>/contenu.php?id_contenu=<?php echo $contenu->id; ?>" target="_blank" ><img src="gfx/site.png" alt="Voir le contenu en ligne" title="Voir le contenu en ligne" /></a>
			<a href="#" onclick="document.getElementById('formulaire').submit();"><img src="gfx/valider.png" alt="Enregistrer les modifications" title="Enregistrer les modifications" style="padding:0 5px 0 0;"/></a>
			<a href="#" onclick="document.getElementById('url').value='1'; document.getElementById('formulaire').submit();"><img src="gfx/validerfermer.png" alt="Enregistrer les modifications et fermer la fiche" title="Enregistrer les modifications et fermer la fiche" style="padding:0 5px 0 0;"/></a>
			<?php
			if($contenu->classement<$maxclassement){
				$suivant = $contenu->classement+1;
				$query = "select id from $contenu->table where dossier=$contenu->dossier and classement=$suivant";
				$resul = mysql_query($query);
				$idsuiv = mysql_result($resul,0,"id");
			?>
			<a href="contenu_modifier.php?id=<?php echo $idsuiv; ?>&dossier=<?php echo $contenu->dossier; ?>" ><img src="gfx/suivant.png" alt="Contenu suivant" title="Contenu suivant" style="padding:0 5px 0 0;"/></a>	
			<?php
			}
			?>
		</div>
   	</div>
</div>
<div class="entete" style="margin-top:10px;">
	<div class="titre">GESTION DES PHOTOS</div>
</div>
<!-- bloc transfert des images -->
<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:140px; padding-top:10px;">Transférer des images</div>
		<div class="champs" style="padding-top:10px;">
			<form action="contenu_modifier.php" method="post" ENCTYPE="multipart/form-data">
				<input type="hidden" name="action" value="ajouterphoto">
      			<input type="hidden" name="id" value="<?php echo($id); ?>">
      			<?php for($i=1; $i<6; $i++) { ?>
	      			<input type="file" name="photo<?php echo($i); ?>" class="form"><br/>
	  			<?php } ?>
	        	<input type="submit" value="Ajouter">
   			</form>
   		</div>
   	</div>
</div>


<ul id="blocs_pliants_photo">
<li>
	<h3 class="head"><a href="#"><img src="gfx/fleche_accordeon_img_dn.gif" /></a><h3>
	<ul>
   <?php
			$image = new Image();

			$query = "select * from $image->table where contenu='$id' order by classement";
			$resul = mysql_query($query, $image->link);

			while($row = mysql_fetch_object($resul)){
				$imagedesc = new Imagedesc();
				$imagedesc->charger($row->id,$lang);
   ?>
		<form action="contenu_modifier.php" method="POST">
		<input type="hidden" name="action" value="modifierphoto" />
		<input type="hidden" name="id_photo" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="lang" value="<?php echo $lang; ?>">
		<input type="hidden" name="dossier" value="<?php echo $contenu->dossier; ?>">


			<li class="lignesimple">
				<div class="cellule_designation" style="height:208px;">&nbsp;</div>
				<div class="cellule_photos" style="height:200px; overflow:hidden;"><img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/contenu/<?php echo($row->fichier); ?>&width=&height=200&opacite=&nb=" border="0" / ></div>
				<div class="cellule_supp"><a href="contenu_modifier.php?id_photo=<?php echo($row->id); ?>&id=<?php echo($id); ?>&action=supprimerphoto&lang=<?php echo $lang; ?>&dossier=<?php echo $dossier; ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">Titre</div>
				<div class="cellule">
				<input type="text" name="titre_photo" style="width:219px;" class="form" value="<?php echo $imagedesc->titre ?>" />
				</div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:50px;">Chapo</div>
				<div class="cellule"><textarea name="chapo_photo" rows="2"class="form" style="width:219px;"><?php echo $imagedesc->chapo ?></textarea></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:65px;">Description</div>
				<div class="cellule"><textarea name="description_photo" class="form" rows="3" style="width:219px;"><?php echo $imagedesc->description ?></textarea></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">Classement</div>
				<div class="cellule">
					<div class="classement">
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id_photo=".$row->id."&action=modclassementphoto&type=M&id=".$id."&lang=".$lang; ?>"><img src="gfx/up.gif" border="0" /></a></div>
					<div class="classement">
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id_photo=".$row->id."&action=modclassementphoto&type=D&id=".$id."&lang=".$lang; ?>"><img src="gfx/dn.gif" border="0" /></a></div>
				</div>
				
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">&nbsp;</div>
				<div class="cellule" style="height:30px; border-bottom: 1px dotted #9DACB6"><input type="submit" value="Enregistrer" /></div>
			</li>

		</form>
   		<?php } ?>
   		<h3 class="head" style="margin:0 0 5px 0"><a href="javascript:;"><img src="gfx/fleche_accordeon_img_up.gif" /></a><h3>
	</ul>
</li>



<!-- bloc de gestion des documents -->
<div class="entete" style="margin-top:10px;">
	<div class="titre">GESTION DES DOCUMENTS</div>
</div>
<!-- bloc transfert des documents -->
<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:43px; padding-top:10px;">Transférer des documents</div>
		<div class="champs" style="padding-top:10px;">
			<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
				<input type="hidden" name="action" value="ajouterdoc" />
      			<input type="hidden" name="id" value="<?php echo($id); ?>">
				<input type="hidden" name="dossier" value="<?php echo($dossier); ?>">
				<input type="hidden" name="lang" value="<?php echo($lang); ?>">
      			<input type="file" name="doc" class="form"><br/>
      			<input type="submit" value="Ajouter">
    		</form>
		</div>
	</div>

   	  <?php
			$document = new Document();
			$documentdesc = new Documentdesc();

			$query = "select * from $document->table where contenu='$id' order by classement";
			$resul = mysql_query($query, $document->link);

			while($row = mysql_fetch_object($resul)){
				$document = new Document();
				$documentdesc = new Documentdesc();
				
				$documentdesc->charger($row->id,$lang);
        ?>
             <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<input type="hidden" name="action" value="modifierdoc" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="id_document" value="<?php echo $row->id; ?>" />
				<input type="hidden" name="dossier" value="<?php echo($dossier); ?>">
				<input type="hidden" name="lang" value="<?php echo($lang); ?>">
				   
   <li class="lignesimple">
				<div class="cellule_designation" style="height:208px;">&nbsp;</div>
				<div class="cellule_photos" style="height:200px; overflow:hidden;"><a href="../client/document/<?php echo($row->fichier); ?>" target="_blank"><?php echo($row->fichier); ?></a></div>
				<div class="cellule_supp">
				<a href="contenu_modifier.php?id=<?php echo($id); ?>&id_document=<?php echo($row->id); ?>&action=supprimerdoc&lang=<?php echo $lang; ?>&dossier=<?php echo $dossier; ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">Titre</div>
				<div class="cellule">
				<input type="text" name="titredoc" style="width:219px;" class="form" value="<?php echo $documentdesc->titre ?>" />
				</div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:50px;">Chapo</div>
				<div class="cellule"><textarea name="chapodoc" rows="2" class="form" style="width:219px;"><?php echo $documentdesc->chapo ?></textarea>
				</div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:65px;">Description</div>
				<div class="cellule"><textarea name="descriptiondoc" class="form" rows="3" style="width:219px;"><?php echo $documentdesc->description ?></textarea></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">Classement</div>
				<div class="cellule">
					<div class="classement">
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$id."&id_document=".$row->id."&action=modclassementdoc&type=M&dossier=".$dossier."&lang=".$lang; ?>"><img src="gfx/up.gif" border="0" /></a></div>
					<div class="classement">
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$id."&id_document=".$row->id."&action=modclassementdoc&type=D&dossier=".$dossier."&lang=".$lang; ?>"><img src="gfx/dn.gif" border="0" /></a></div>
				</div>
				
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">&nbsp;</div>
				<div class="cellule" style="height:30px; border-bottom: 1px dotted #9DACB6"><input type="submit" value="Enregistrer" /></div>
			</li>    	 <?php
                }
        ?>
</div> <!-- fin bloc transfert des documents -->

</div> <!-- fin bloc-photos colonne de droite -->
   <?php } ?>      
</div>  
<?php include_once("pied.php"); ?>
</div>
</div>
<!-- -->
<script type="text/javascript" src="../lib/jquery/jquery.js"></script>
<script type="text/javascript" src="../lib/jquery/accordion.js"></script>
<script type="text/javascript">
jQuery().ready(function(){	
	// applying the settings
	jQuery('#blocs_pliants_prod').Accordion({
		active: 'h3.selected',
		header: 'h3.head',
		alwaysOpen: false,
		animated: true,
		showSpeed: 400,
		hideSpeed: 400
	});
	jQuery('#blocs_pliants_photo').Accordion({
		active: 'h3.selected',
		header: 'h3.head',
		alwaysOpen: false,
		animated: false,
		showSpeed: 400,
		hideSpeed: 100
	});


});	
</script>
<!-- -->
</body>
</html>
