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
	if(!isset($parent)) $parent="0";
	if(!isset($lang)) $lang="1";
	if(!isset($id)) $id="";
	if(!isset($ligne)) $ligne="";
	
?>
<?php if(! est_autorise("acces_contenu")) exit; ?>
<?php

	include_once("../classes/Dossier.class.php");
	include_once("../classes/Dossierdesc.class.php");
	include_once("../classes/Lang.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Variable.class.php");
?>
<?php

	switch($action){
		case 'modclassement' : modclassement($id, $parent, $type); break;
		case 'modifier' : modifier($id, $lang, $titre, $chapo, $description, $postscriptum, $ligne, $urlsuiv); break;
		case 'ajouter' : ajouter($parent, $lang, $titre, $chapo, $description, $postscriptum, $ligne); break;
		case 'supprimer' : supprimer($id, $parent);
		case 'supprimg': supprimg($id);
		case 'ajouterphoto' : ajouterphoto($id,$lang); break;
		case 'modifierphoto' : modifierphoto($id_photo, $titre_photo, $chapo_photo, $description_photo,$lang); break;
		case 'supprimerphoto' : supprimerphoto($id_photo,$lang); break;
		case 'modclassementphoto' : modclassementphoto($id_photo,$type); break;
		case 'ajouterdoc' : ajouterdoc($id, $_FILES['doc']['tmp_name'], $_FILES['doc']['name'],$lang); break;
		case 'supprimer_document' : supprimer_document($id_document,$lang); break;
		case 'modifierdoc' : modifierdoc($id_document,$titredoc,$chapodoc,$descriptiondoc,$lang); break;
		case 'modclassementdoc' : modclassementdoc($id_document,$type); break;
	
	}
	

?>

<?php

	function modclassementdoc($id, $type){
      	$doc = new Document();
        $doc->charger($id);
        $doc->changer_classement($id, $type);
	
	}


	function modifierdoc($id, $titre, $chapo, $description,$lang){
		$tmp = new Dossier();
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

	    header("Location: dossier_modifier.php?id=" . $tmp->id."&lang=".$lang);
	
	}

	function supprimer_document($id,$lang){
			$tmp = new Dossier();
			$tmp->charger($_REQUEST['id']);
		
			$document = new Document();
			$document->charger($id);
			
			if(file_exists("../client/document/$document->fichier")){
				 unlink("../client/document/$document->fichier");
			}
			
			$document->supprimer();		
	
		    header("Location: dossier_modifier.php?id=" . $tmp->id."&lang=".$lang);
	}

	function ajouterdoc($dosid, $doc, $doc_name,$lang){

		$tmp = new Dossier();
		$tmp->charger($_REQUEST['id']);

		if($doc != ""){

			$fich = substr($doc_name, 0, strlen($doc_name)-4);
			$ext = substr($doc_name, strlen($doc_name)-3);
			
			$document = new Document();
			$documentdesc = new Documentdesc();

		 	$query = "select max(classement) as maxClassement from $document->table where dossier='" . $dosid . "'";

	 		$resul = mysql_query($query, $document->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
     					
			$document->dossier = $dosid;
			$document->classement = $maxClassement+1;

			
			$lastid = $document->add();
			$document->charger($lastid);
			$fich = eregfic($fich);
			$document->fichier = $fich . "_" . $dosid . "." . $ext;
			$document->maj();
					
			copy("$doc", "../client/document/" . $fich . "_" . $dosid . "." . $ext);
		}

 	    header("Location: dossier_modifier.php?id=" . $tmp->id."&lang=".$lang);

	}
	

	function modclassementphoto($id, $type){
      	$img = new Image();
        $img->charger($id);
        $img->changer_classement($id, $type);
	}

	function supprimerphoto($id,$lang){

			$tmp = new Dossier();
			$tmp->charger($_REQUEST['id']);
		
			$image = new Image();
			$image->charger($id);

			$imagedesc = new Imagedesc();
			$imagedesc->charger($image->id);
						
			if(file_exists("../client/gfx/photos/dossier/$image->fichier"))
				 unlink("../client/gfx/photos/dossier/$image->fichier");
		
			$image->supprimer();
			$imagedesc->delete();

		    header("Location: dossier_modifier.php?id=" . $tmp->id."&lang=".$lang);
			
	}

	function modifierphoto($id, $titre, $chapo, $description,$lang){
		$tmp = new Dossier();
		$tmp->charger($_REQUEST['id']);


		$imagedesc = new Imagedesc();
		$imagedesc->image = $id;
		$imagedesc->lang = $lang;
	
		$imagedesc->charger($id,$lang);
		
		$imagedesc->titre = $titre;
		$imagedesc->chapo = $chapo;
		$imagedesc->description = $description;
		$imagedesc->lang = $lang;
	
		if(!$imagedesc->id)
			$imagedesc->add();
		else 
			$imagedesc->maj();

	    header("Location: dossier_modifier.php?id=" . $tmp->id."&lang=".$lang);

	}

	function ajouterphoto($id,$lang){

		$tmp = new Dossier();
		$tmp->charger($_REQUEST['id']);

		if(!isset($nomorig)) $nomorig="";

		for($i = 1; $i<6; $i++){
			$photo = $_FILES["photo" . $i]['tmp_name'];
			$photo_name = $_FILES["photo" . $i]['name'];
		

		if($photo != ""){

  	        preg_match("/([^\/]*).((jpg|gif|png|jpeg))/i", $photo_name, $decoupe);
			
			$fich = $decoupe[1];
	   	    $extension = $decoupe[2];
			
			$photoprodw = new Variable();
			$photoprodw->charger("photorubw");

			$image = new Image();
			$imagedesc = new Imagedesc();
			
			$image->dossier = $id;

		 	$query = "select max(classement) as maxClassement from $image->table where dossier='" . $id . "'";

	 		$resul = mysql_query($query, $image->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
    					
			$image->classement = $maxClassement+1;

			
			
			$lastid = $image->add();
			
			$image->charger($lastid);
			$image->fichier = $fich . "_" . $lastid . "." . $extension;
			$image->maj();
			
			copy("$photo", "../client/gfx/photos/dossier/" . $fich . "_" . $lastid . "." . $extension);
			
    		modules_fonction("uploadimage", $lastid);
			
		}
	  }
	    header("Location: dossier_modifier.php?id=" . $tmp->id."&lang=".$lang);

	}

	function modclassement($id, $parent, $type){

		if($parent == 0) $parent=0;
		
        $dos = new Dossier();
        $dos->charger($id);
        $dos->changer_classement($id, $type);

		
	    header("Location: listdos.php?parent=$parent");
	}
	
	function modifier($id, $lang, $titre, $chapo, $description, $postscriptum, $ligne, $urlsuiv){
	
		$dossier = new Dossier();
		$dossierdesc = new Dossierdesc();
		$dossier->charger($id);
		$res = $dossierdesc->charger($id, $lang);	

		if(!$res){
			$temp = new Dossierdesc();
			$temp->dossier=$dossier->id;
			$temp->lang=$lang;
			$temp->add();
			$dossierdesc->charger($id, $lang);
		
		}
		
		$dossierdesc->titre = $titre;
		$dossierdesc->chapo = $chapo;
		$dossierdesc->description = $description;
		$dossierdesc->postscriptum = $postscriptum;

		if($ligne!="") $dossier->ligne = 1;
		else $dossier->ligne = 0;
   											
		$dossier->maj();
		$dossierdesc->maj();
		if($urlsuiv){
			header("location: listdos.php?parent=".$dossier->parent);
		}
		else{
	    	header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $dossier->id."&lang=".$lang);
		}
		exit;

	}

	function ajouter($parent, $lang, $titre, $chapo, $description, $postscriptum, $ligne){
  		
		$dossier = new Dossier();
		$dossier->parent=$parent;

		if($ligne!="") $dossier->ligne = 1;
		else $dossier->ligne = 0;	

		if($parent == "") $parent=0;
		$query = "select max(classement) as maxClassement from $dossier->table where parent='$parent'";

		$resul = mysql_query($query, $dossier->link);
   		$maxClassement = mysql_result($resul, 0, "maxClassement");

     	$dossier->classement = $maxClassement+1;
     	
		$lastid = $dossier->add();

		$dossier->charger($lastid);

		$dossier->maj();


		$dossierdesc = new Dossierdesc();	
	
		$dossierdesc->dossier = $lastid;
		$dossierdesc->lang = 1;
		$dossierdesc->titre = $titre;
		$dossierdesc->chapo = $chapo;
		$dossierdesc->description = $description;
		$dossierdesc->postscriptum = $postscriptum;
		
		$dossierdesc->add();
	
	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $lastid."&lang=".$lang);
		exit;
	}
	
	function supprimer($id, $parent){
		
		$dossier = new Dossier();		
		$dossier->charger($id);
		$dossier->supprimer();

	    header("Location: listdos.php?parent=" . $parent);
		exit;

	}	
?>

<?php
	$dossier = new Dossier();
	$dossierdesc = new Dossierdesc();

	if($id){
		
		$dossier->charger($id);
		$dossierdesc->charger($id, $lang);
		
	}

	$query = "select * from $dossier->table where parent=\"$parent\"";
	$resul = mysql_query($query, $dossier->link);
	$nbres = mysql_num_rows($resul);
		
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>

<script type="text/javascript" src="../lib/jquery/jquery.js"></script>	

</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="contenu";
	include_once("entete.php");
?>
<div id="contenu_int">
  <p><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="listdos.php" class="lien04">Gestion du contenu </a>              

    <?php
                    $parentdesc = new Dossierdesc();
					$parentdesc->charger($id, $lang);
					$parentnom = $parentdesc->titre;	
					
					$res = chemin_dos($id);
					$tot = count($res)-1;
	
?>
                             
				
			<?php
				if($parent || $id){
			
			?>	
					<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<?php	
				}
				while($tot --){
			?> <a href="listdos.php?parent=<?php echo($res[$tot+1]->dossier); ?>" class="lien04"><?php echo($res[$tot+1]->titre); ?></a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" />                           
            <?php
            	}
            
            ?>
            
			<?php
                    $parentdesc = new Dossierdesc();
					if($parent) $parentdesc->charger($parent);
					else $parentdesc->charger($id);
					$parentnom = $parentdesc->titre;	
				
			?>
			 <a href="listdos.php?parent=<?php echo($parentdesc->dossier); ?>" class="lien04"><?php echo($parentdesc->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> 
            <?php if( !$id) { ?>Ajouter<?php } else { ?> Modifier <?php } ?> </p>	
                                     
   <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="formulaire" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" name="id" value="<?php echo($id); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
 	<input type="hidden" name="parent" value="<?php echo($parent); ?>" /> 
	<input type="hidden" name="urlsuiv" id="url" value="0">
<!-- Bloc description -->
<div id="bloc_description">
	<!-- bloc entete de la rubrique -->   	
		<div class="entete">
			<div class="titre">DESCRIPTION GÉNÉRALE DU DOSSIER</div>
			<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit()">VALIDER LES MODIFICATIONS</a></div>
		</div>
<!-- bloc descriptif de la rubrique --> 			
<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
        <th class="designation">Changer la langue</th>
        <th>
        <?php
			$langl = new Lang();
			$query = "select * from $langl->table";
			$resul = mysql_query($query);
			while($row = mysql_fetch_object($resul)){
			$langl->charger($row->id);
		?> 
		<div class="flag<?php if($lang ==  $langl->id) { ?>Selected<?php } ?>">
			<a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&lang=<?php echo($langl->id); ?>">
				<img src="gfx/lang<?php echo($langl->id); ?>.gif" />
			</a>
		</div> 
		<?php } ?>
		</th>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Titre</td>
        <td><input name="titre" id="titre" type="text" class="form_long" value="<?php echo($dossierdesc->titre); ?>"/></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Chapo<br /><span class="note">(courte description d'introduction)</span></td>
        <td> <textarea name="chapo" id="chapo" cols="40" rows="2" class="form_long"><?php echo($dossierdesc->chapo); ?></textarea></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Description<br /><span class="note">(description complète)</span></td>
        <td><textarea name="description" id="description" cols="40" rows="20" class="form_long"><?php echo($dossierdesc->description); ?></textarea></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Postscriptum<br /> <span class="note">(champs d'information complémentaire)</span></td>
        <td>
        <textarea name="postscriptum" id="postscriptum" cols="40" rows="2" class="form_long"><?php echo($dossierdesc->postscriptum); ?></textarea></td>
   	</tr>
  	 <tr class="fonce">
      <td class="designation">En ligne :</td>
      <td>
         <input name="ligne" id="ligne" type="checkbox" class="form" <?php if($dossier->ligne || $id == "" ) { ?> checked="cheked" <?php } ?>/>
      </td>
    </tr>  
    </table>
<?php if($id != ""){ ?>
<!-- bloc d'informations sur le dossier-->
		<div class="entete">
			<div class="titre" style="cursor:pointer" onclick="$('#pliantsinfos').show('slow');">INFORMATIONS SUR LE DOSSIER</div>
		</div>

<div class="blocs_pliants_prod" id="pliantsinfos">
		
				<ul class="lignesimple">
					<li class="cellule_designation" style="width:140px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">ID</li>
					<li class="cellule" style="width:438px; padding: 5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;"><?php echo($dossier->id); ?></li>
				</ul>
			
			<ul class="lignesimple">
				<li class="cellule_designation" style="width:140px;">URL réécrite</li>
				<li class="cellule"><?php echo(rewrite_dos("$dossier->id", $lang)); ?></li>
			</ul>
		
		<div class="bloc_fleche" style="cursor:pointer" onclick="$('#pliantsinfos').hide();"><img src="gfx/fleche_accordeon_up.gif" /></div>
				
</div>
 <?php } ?>

<!-- Fin information dossier -->
<div class="patchplugin">
<?php 
	admin_inclure("dossiermodifier"); 
?>
</div>

</div><!-- fin du bloc_description -->	
</form>

<?php
if($id != ""){
?>
<!-- bloc de gestion des photos et documents / colonne de droite -->   
<div id="bloc_photos">
<!-- début du bloc Boite à outils du dossier -->   
<div class="entete">
	<div class="titre">BOITE A OUTILS</div>
</div>
<div class="bloc_transfert">
	<div class="claire">
		<div class="champs" style="padding-top:10px; width:375px;">
			<?php
			$query = "select max(classement) as maxcount from $dossier->table where parent=$dossier->parent";
			$resul = mysql_query($query);
			$maxclassement = mysql_result($resul,0,"maxcount");
			
			$query = "select min(classement) as mincount from $dossier->table where parent=$dossier->parent";
			$resul = mysql_query($query);
			$minclassement = mysql_result($resul,0,"mincount");
			
			$classement = $dossier->classement;
			if($classement > $minclassement){
				$prec = $classement;
				do{
					$prec--;
					$query = "select id from $dossier->table where parent=$dossier->parent and classement=$prec";
					$resul = mysql_query($query);
				}while(!mysql_num_rows($resul) && $prec > $minclassement);
				
				if(mysql_num_rows($resul) != 0){
					 $idprec = mysql_result($resul,0,"id");
			?>
			<a href="dossier_modifier.php?id=<?php echo $idprec; ?>"><img src="gfx/precedent.png" alt="Dossier pr&eacute;c&eacute;dent" title="Dossier pr&eacute;c&eacute;dent" style="padding:0 5px 0 0;margin-top:-5px;height:38px;"/></a>
			<?php
				}
			}
			?>	
			<!-- pour visualiser la page rubrique correspondante en ligne -->
			<a title="Voir le dossier en ligne" href="<?php echo $site->valeur; ?>/dossier.php?id_dossier=<?php echo $dossier->id; ?>" target="_blank" ><img src="gfx/site.png" alt="Voir le dossier en ligne" title="Voir le dossier en ligne" /></a>
			<a href="#" onclick="document.getElementById('formulaire').submit();"><img src="gfx/valider.png" alt="Enregistrer les modifications" title="Enregistrer les modifications" style="padding:0 5px 0 0;"/></a>
			<a href="#" onclick="document.getElementById('url').value='1'; document.getElementById('formulaire').submit(); "><img src="gfx/validerfermer.png" alt="Enregistrer les modifications et fermer la fiche" title="Enregistrer les modifications et fermer la fiche" style="padding:0 5px 0 0;"/></a>
			<?php
				if($classement<$maxclassement){
					$suivant = $dossier->classement;
					do{
						$suivant++;
						$query = "select id from $dossier->table where parent=$dossier->parent and classement=$suivant";
						$resul = mysql_query($query);
					}while(!mysql_num_rows($resul) && $suivant<$maxclassement);
					
					if(mysql_num_rows($resul) != 0){
						$idsuiv = mysql_result($resul,0,"id");
					
			?>
			<a href="dossier_modifier.php?id=<?php echo $idsuiv; ?>" ><img src="gfx/suivant.png" alt="Dossier suivant" title="Dossier suivant" style="padding:0 5px 0 0;"/></a>	
			<?php
					}
				}
			?>
		</div>
   	</div>
</div>
<!-- fin du bloc Boite à outils du dossier--> 

<!-- début du bloc de transfert des images du dossier-->
<div class="entete" style="margin-top:10px;">
	<div class="titre">GESTION DES PHOTOS</div>
</div>
<!-- bloc transfert des images -->
<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:160px; padding-top:10px;">Transférer des images</div>
		<div class="champs" style="padding-top:10px;">
			<form action="dossier_modifier.php" method="post" ENCTYPE="multipart/form-data">
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
<!-- fin du bloc de transfert des images du dossier-->

<!-- début du bloc de gestion des photos du dossier -->
<div class="bloc_fleche" style="cursor:pointer" onclick="$('#pliantsphotos').show('slow');"><img src="gfx/fleche_accordeon_img_dn.gif" /></div>
<div class="blocs_pliants_photo" id="pliantsphotos">
	<ul>
   <?php
			$image = new Image();
			
			
			$query = "select * from $image->table where dossier='$id' order by classement";
			$resul = mysql_query($query, $image->link);

			while($row = mysql_fetch_object($resul)){
				$imagedesc = new Imagedesc();
				$imagedesc->charger($row->id,$lang);
   ?>
		<form action="dossier_modifier.php" method="POST">
		<input type="hidden" name="action" value="modifierphoto" />
		<input type="hidden" name="id_photo" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="lang" value="<?php echo $lang; ?>">


			<li class="lignesimple">
				<div class="cellule_designation" style="height:208px;">&nbsp;</div>
				<div class="cellule_photos" style="height:200px; overflow:hidden;"><img src="../fonctions/redimlive.php?type=dossier&nomorig=<?php echo($row->fichier); ?>&width=&height=200&opacite=&nb=" border="0" / ></div>
				<div class="cellule_supp"><a href="dossier_modifier.php?id_photo=<?php echo($row->id); ?>&id=<?php echo($id); ?>&action=supprimerphoto&lang=<?php echo($lang); ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
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
				<div class="cellule"><textarea name="description_photo" class="form" rows="3"><?php echo $imagedesc->description ?></textarea></div>
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
   		
	</ul>
<div class="bloc_fleche" style="cursor:pointer" onclick="$('#pliantsphotos').hide();"><img src="gfx/fleche_accordeon_img_up.gif" /></div>
</div>
<!-- fin du bloc de gestion des photos du dossier -->

<!-- début du bloc de transfert des documents du dossier -->
<div class="entete"  style="margin-top:10px;">
	<div class="titre">GESTION DES DOCUMENTS</div>
</div>
<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:70px; padding-top:10px;">Transférer des documents</div>
		<div class="champs" style="padding-top:10px;">
			<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
				<input type="hidden" name="action" value="ajouterdoc" />
 				<input type="hidden" name="id" value="<?php echo($id); ?>" /> 
				<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
    			<input type="file" name="doc" class="form" /><br/>
    			<input type="submit" value="Ajouter" />
    		</form>
		</div>
	</div>
</div>
<!-- fin du bloc transfert des documents du dossier -->
<!-- début du bloc de gestion des documents du dossier -->
<div class="bloc_fleche" style="cursor:pointer" onclick="$('#pliantsfichier').show('slow');"><img src="gfx/fleche_accordeon_img_dn.gif" /></div>
<div class="blocs_pliants_fichier" id="pliantsfichier">
	<ul>
   	   <?php
			$document = new Document();
			$documentdesc = new Documentdesc();

			$query = "select * from $document->table where dossier='$id' order by classement";
			$resul = mysql_query($query, $document->link);

			while($row = mysql_fetch_object($resul)){
				$documentdesc = new Documentdesc();
				$documentdesc->charger($row->id,$lang);
        ?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<input type="hidden" name="action" value="modifierdoc" />
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<input type="hidden" name="id_document" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="lang" value="<?php echo($lang); ?>" />
			
			<li class="lignesimple">
				<div class="cellule_designation">Fichier</div>
				<div class="cellule_document"><a href="../client/document/<?php echo($row->fichier); ?>" target="_blank"><?php if(strlen($row->fichier) > 26) echo(substr($row->fichier,0,26)." ... ".substr($row->fichier,strlen($row->fichier)-3,strlen($row->fichier)));
				else echo $row->fichier; ?></a></div>
			<div class="cellule_supp_fichier">
			<a href="dossier_modifier.php?id=<?php echo($id); ?>&id_document=<?php echo($row->id); ?>&action=supprimer_document&lang=<?php echo $lang; ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
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
					<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$id."&id_document=".$row->id."&action=modclassementdoc&type=M&lang=".$lang; ?>"><img src="gfx/up.gif" border="0" /></a></div>
				<div class="classement">
					<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$id."&id_document=".$row->id."&action=modclassementdoc&type=D&lang=".$lang; ?>"><img src="gfx/dn.gif" border="0" /></a></div>
			</div>
			
		</li>
		<li class="lignesimple">
			<div class="cellule_designation" style="height:30px;">&nbsp;</div>
			<div class="cellule" style="height:30px; border-bottom: 1px dotted #9DACB6"><input type="submit" value="Enregistrer" /></div>
		</li> 
		</form> 	
    	 <?php
                }
        ?>
		 </ul>
       <div class="bloc_fleche" style="cursor:pointer" onclick="$('#pliantsfichier').hide();"><img src="gfx/fleche_accordeon_img_up.gif" /></div>
</div>

</div> 
<!-- fin bloc photos colonne de droite -->
<?php } ?>
</div>
<?php include_once("pied.php");?>
</div>
</div>

</body>
</html>
           
         

