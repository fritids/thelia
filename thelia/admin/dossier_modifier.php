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
		case 'modifier' : modifier($id, $lang, $titre, $chapo, $description, $postscriptum, $ligne); break;
		case 'ajouter' : ajouter($parent, $lang, $titre, $chapo, $description, $postscriptum, $ligne); break;
		case 'supprimer' : supprimer($id, $parent);
		case 'supprimg': supprimg($id);
		case 'ajouterphoto' : ajouterphoto($id); break;
		case 'modifierphoto' : modifierphoto($id_photo, $titre_photo, $chapo_photo, $description_photo,$lang); break;
		case 'supprimerphoto' : supprimerphoto($id_photo); break;
		case 'modclassementphoto' : modclassementphoto($id_photo,$type); break;
		case 'ajouterdoc' : ajouterdoc($id, $_FILES['doc']['tmp_name'], $_FILES['doc']['name']); break;
		case 'supprimer_document' : supprimer_document($id_document); break;
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
	
	}

	function supprimer_document($id){
		
			$document = new Document();
			$document->charger($id);
			
			if(file_exists("../client/document/$document->fichier")){
				 unlink("../client/document/$document->fichier");
			}
			
			$document->supprimer();		
		
	}

	function ajouterdoc($dosid, $doc, $doc_name){

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


	}
	

	function modclassementphoto($id, $type){
      	$img = new Image();
        $img->charger($id);
        $img->changer_classement($id, $type);
	}

	function supprimerphoto($id){
		
			$image = new Image();
			$image->charger($id);

			$imagedesc = new Imagedesc();
			$imagedesc->charger($image->id);
						
			if(file_exists("../client/gfx/photos/dossier/$image->fichier"))
				 unlink("../client/gfx/photos/dossier/$image->fichier");
		
			$image->supprimer();
			$imagedesc->delete();
			
	}

	function modifierphoto($id, $titre, $chapo, $description,$lang){
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

	}

	function ajouterphoto($id){

		if(!isset($nomorig)) $nomorig="";

		for($i = 1; $i<6; $i++){
			$photo = $_FILES["photo" . $i]['tmp_name'];
			$photo_name = $_FILES["photo" . $i]['name'];
		

		if($photo != ""){

       	    $extension = substr($photo_name, strlen($nomorig)-3);
			$fich = substr($photo_name, 0, strlen($photo_name)-4);
			
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
    		
		}
	 }

	}

	function modclassement($id, $parent, $type){

		if($parent == 0) $parent=0;
		
        $dos = new Dossier();
        $dos->charger($id);
        $dos->changer_classement($id, $type);

		
	    header("Location: listdos.php?parent=$parent");
	}
	
	function modifier($id, $lang, $titre, $chapo, $description, $postscriptum, $ligne){
	
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

	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $dossier->id."&lang=".$lang);
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

</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="contenu";
	include_once("entete.php");
?>
<div id="contenu_int">
  <p align="left"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="listdos.php" class="lien04">Gestion</a><a href="listdos.php" class="lien04">du contenu </a>              

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
			?> <a href="listdos.php?parent=<?php echo($res[$tot+1]->id); ?>" class="lien04"><?php echo($res[$tot+1]->titre); ?></a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" />                           
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
<!-- Bloc description -->
<div id="bloc_description">
	<!-- bloc entete de la rubrique -->   	
		<div class="entete">
			<div class="titre">DESCRIPTION G�N�RALE DU DOSSIER</div>
			<?php if($id){
				$site = new Variable();
				$site->charger("urlsite");
			?>
			<!-- pour visualiser la page dossier correspondante en ligne -->
			<!--
			<div class="voirenligne"><a title="Voir le document en ligne" href="<?php echo $site->valeur; ?>/rubrique.php?id_rubrique=<?php echo $id; ?>" target="_blank" ><img src="gfx/voir-produit-enligne.png" alt="Voir la rubrique en ligne" title="Voir la rubrique en ligne" /></a></div> -->
			<?php
			}
			?>
			<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit()">VALIDER LES MODIFICATIONS</a></div>
		</div>
<!-- bloc descriptif de la rubrique --> 			
<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
        <th class="designation">Changer la langue</td>
        <th>
        <?php
			$langl = new Lang();
			$query = "select * from $langl->table";
			$resul = mysql_query($query);
			while($row = mysql_fetch_object($resul)){
			$langl->charger($row->id);
		?> 
		<div class="flag">
			<a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&lang=<?php echo($langl->id); ?>">
				<img src="gfx/lang<?php echo($langl->id); ?>.gif" />
			</a>
		</div> 
		<?php } ?>
		</td>
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
        <td class="designation">Description<br /><span class="note">(description compl�te)</span></td>
        <td><textarea name="description" id="description" cols="40" rows="2" class="form_long"><?php echo($dossierdesc->description); ?></textarea></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Postscriptum<br /> <span class="note">(champs d'information compl�mentaire)</span></td>
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



<!-- Information sur le dossier -->
<?php if($id != ""){ ?>
	<ul id="blocs_pliants_prod">
	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">INFORMATIONS SUR LE DOSSIER</a></h3>
			<ul>
				<li class="lignesimple">
					<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">ID</div>
					<div class="cellule" style="width:450px; padding: 5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;"><?php echo($dossier->id); ?></div>
				</li>
			
			<li class="lignesimple">
				<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px;">URL r��crite</div>
				<div class="cellule" style="padding: 5px 0 0 5px;"><?php echo(rewrite_dos("$dossier->id", $lang)); ?></div>
			</li>
		<h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" /></a></h3>

		</ul>		
		
	</li>
	</ul>
 <?php } ?>

<!-- Fin information dossier -->


</div><!-- fin du bloc_description -->	
</form>

<?php
if($id != ""){
?>
<!-- bloc photos /colonne de droite -->
<div id="bloc_photos">
<div class="entete">
	<div class="titre">GESTION DES PHOTOS</div>
</div>
<!-- bloc transfert des images -->
<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:140px; padding-top:10px;">Transf�rer des images</div>
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


<ul id="blocs_pliants_photo">
<li>
	<h3 class="head"><a href="#"><img src="gfx/fleche_accordeon_img_dn.gif" /></a><h3>
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
				<div class="cellule_photos" style="height:200px; overflow:hidden;"><img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/dossier/<?php echo($row->fichier); ?>&width=&height=200&opacite=&nb=" border="0" / ></div>
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
   		<h3 class="head" style="margin:0 0 5px 0"><a href="javascript:;"><img src="gfx/fleche_accordeon_img_up.gif" /></a><h3>
	</ul>
</li>



<!-- bloc de gestion des documents -->
<div class="entete">
	<div class="titre">GESTION DES DOCUMENTS</div>
</div>
<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:43px; padding-top:10px;">Transf�rer des documents</div>
		<div class="champs" style="padding-top:10px;">
			<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
				<input type="hidden" name="action" value="ajouterdoc" />
 				<input type="hidden" name="id" value="<?php echo($id); ?>" /> 
				<input type="hidden" name="lang" value="<?php echo $lang; ?>">
    			<input type="file" name="doc" class="form"><br/>
    			<input type="submit" value="Ajouter">
    		</form>
		</div>
	</div>
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
			<input type="hidden" name="lang" value="<?php echo($lang); ?>">
			   
<li class="lignesimple">
			<div class="cellule_designation" style="height:208px;">&nbsp;</div>
			<div class="cellule_photos" style="height:200px; overflow:hidden;"><a href="../client/document/<?php echo($row->fichier); ?>" target="_blank"><?php echo($row->fichier); ?></a></div>
			<div class="cellule_supp">
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

</div> <!-- fin bloc transfert des documents -->

</div> <!-- fin bloc photos colonne de droite -->
<?php } ?>
</div>
<?php include_once("pied.php");?>
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
           
         

