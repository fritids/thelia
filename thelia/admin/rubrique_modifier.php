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
//	var_dump($_POST); exit;
	include_once("pre.php");
	include_once("auth.php");
	
	if(!isset($action)) $action="";
	if(!isset($lang)) $lang="1";
	if(!isset($parent)) $parent="";
	if(!isset($page)) $page="";
	if(!isset($id)) $id="";
	if(!isset($ligne)) $ligne="";
	
?>
<?php
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Rubriquedesc.class.php");
	include_once("../classes/Lang.class.php");
	include_once("../fonctions/divers.php");
    include_once("../classes/Rubcaracteristique.class.php");
    include_once("../classes/Caracteristique.class.php");
    include_once("../classes/Image.class.php");
	include_once("../classes/Variable.class.php");
	include_once("../classes/Contenuassoc.class.php");
	include_once("../classes/Contenu.class.php");
	include_once("../classes/Contenudesc.class.php");
	include_once("../classes/Declinaison.class.php");
	include_once("../classes/Declinaisondesc.class.php");
	include_once("../classes/Rubdeclinaison.class.php");
?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $parent, $type); break;
		case 'modifier' : modifier($id, $parent, $lang, $titre, $chapo, $description, $postscriptum, $lien, $ligne, $urlsuiv); break;
		case 'ajouter' : ajouter($parent, $lang, $titre, $chapo, $description, $postscriptum, $lien, $ligne); break;
		case 'supprimer' : supprimer($id, $parent);
		case "ajouterphoto" : ajouterphoto($id,$lang); break;
		case "modifierphoto" : modifierphoto($id_photo,$titre_photo,$chapo_photo,$description_photo,$lang); break;
		case "supprimerphoto" : supprimerphoto($id_photo,$lang); break;
		case "modclassementphoto" : modclassementphoto($id_photo,$id,$type);
		case 'ajouterdoc' : ajouterdoc($id, $_FILES['doc']['tmp_name'], $_FILES['doc']['name'],$lang); break;
		case 'supprimer_document' : supprimer_document($id_document,$lang); break;
		case 'modclassementdoc' : modclassementdoc($id_document,$type); break;
		case 'modifierdoc' : modifierdoc($id_document,$titredoc,$chapodoc,$descriptiondoc,$lang); break;
	}
	

?>

<?php

	function modifierdoc($id, $titre, $chapo, $description,$lang){

		$tmp = new Rubrique();
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

	   header("Location: rubrique_modifier.php?id=" . $tmp->id."&lang=".$lang);
	
	}

	function modclassementdoc($id, $type){

      	$doc = new Document();
        $doc->charger($id);
        $doc->changer_classement($id, $type);

	}

	function supprimer_document($id,$lang){

		    $tmp = new Rubrique();
		    $tmp->charger($_REQUEST['id']);
		
			$document = new Document();
			$document->charger($id);
			
			if(file_exists("../client/document/$document->fichier")){
				 unlink("../client/document/$document->fichier");
			}
			
			$document->supprimer();
		
	  	    header("Location: rubrique_modifier.php?id=" . $tmp->id."&lang=".$lang);
					
	}

	function ajouterdoc($rubrique, $doc, $doc_name,$lang){

		$tmp = new Rubrique();
		$tmp->charger($_REQUEST['id']);

		if($doc != ""){
			$fich = substr($doc_name, 0, strlen($doc_name)-4);
			$ext = substr($doc_name, strlen($doc_name)-3);
			
			$document = new Document();
			$documentdesc = new Documentdesc();

		 	$query = "select max(classement) as maxClassement from $document->table where rubrique='" . $rubrique . "'";

	 		$resul = mysql_query($query, $document->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
     					
			$document->rubrique = $rubrique;
			$document->classement = $maxClassement+1;

			$lastid = $document->add();
			$document->charger($lastid);
			$fich = eregfic($fich);
			
			$document->fichier = $fich . "_" . $rubrique . "." . $ext;
			$document->maj();
					
			copy("$doc", "../client/document/" . $fich . "_" . $rubrique . "." . $ext);	
		}
	   
	     header("Location: rubrique_modifier.php?id=" . $tmp->id."&lang=".$lang);

	}

	function modclassementphoto($id, $rubid, $type){
      	$img = new Image();
        $img->charger($id);
        $img->changer_classement($id, $type);
	}

	function supprimerphoto($id,$lang){

			$tmp = new Rubrique();
			$tmp->charger($_REQUEST['id']);
		
			$image = new Image();
			$image->charger($id);

			$imagedesc = new Imagedesc();
			$imagedesc->charger($image->id);
						
			if(file_exists("../client/gfx/photos/rubrique/$image->fichier"))
				 unlink("../client/gfx/photos/rubrique/$image->fichier");
		
			$image->supprimer();
			$imagedesc->delete();
	
		    header("Location: rubrique_modifier.php?id=" . $tmp->id."&lang=".$lang);
	}


	function modifierphoto($id, $titre, $chapo, $description,$lang){
		$tmp = new Rubrique();
		$tmp->charger($_REQUEST['id']);

		$imagedesc = new Imagedesc();
		$imagedesc->image = $id;
		$imagedesc->lang = "1";
	
		$imagedesc->charger($id);
		
		$imagedesc->titre = $titre;
		$imagedesc->chapo = $chapo;
		$imagedesc->description = $description;
		$imagedesc->lang = $lang;
	
		if(!$imagedesc->id)
			$imagedesc->add();
		else 
			$imagedesc->maj();

	    header("Location: rubrique_modifier.php?id=" . $tmp->id."&lang=".$lang);

	}


	function ajouterphoto($id,$lang){

		$tmp = new Rubrique();
		$tmp->charger($_REQUEST['id']);
		
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
			
			$image->rubrique = $id;

		 	$query = "select max(classement) as maxClassement from $image->table where rubrique='" . $id . "'";

	 		$resul = mysql_query($query, $image->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
    					
			$image->classement = $maxClassement+1;

			
			
			$lastid = $image->add();
			
			$image->charger($lastid);
			$image->fichier = $fich . "_" . $lastid . "." . $extension;
			$image->maj();
			
			copy("$photo", "../client/gfx/photos/rubrique/" . $fich . "_" . $lastid . "." . $extension);
    		
		}
	   }
		
	   header("Location: rubrique_modifier.php?id=" . $tmp->id."&lang=".$lang);
	   
	}


	function modclassement($id, $parent, $type){

        if($parent == 0) $parent=0;

        $rub = new Rubrique();
        $rub->charger($id);
        $rub->changer_classement($id, $type);

		
	    header("Location: parcourir.php?parent=$parent");

	}
	
	function modifier($id, $parent, $lang, $titre, $chapo, $description, $postscriptum, $lien, $ligne, $urlsuiv){

		$rubrique = new Rubrique();
		$rubriquedesc = new Rubriquedesc();
		$rubrique->charger($id);
		$res = $rubriquedesc->charger($id, $lang);	
		
		if(!$res){
			$temp = new Rubriquedesc();
			$temp->rubrique=$rubrique->id;
			$temp->lang=$lang;
			$temp->add();
			$rubriquedesc->charger($id, $lang);
		
		}
		
		if($parent != $rubrique->parent){
			$query = "select max(classement) as maxClassement from $rubrique->table where parent='$parent'";
			$resul = mysql_query($query, $rubrique->link);
			$max = mysql_result($resul, 0, "maxClassement");
			$rubrique->classement = $max+1;
		}
		
		$rubrique->lien = $lien;
		$rubrique->parent = $parent;
		$rubriquedesc->titre = $titre;
		$rubriquedesc->chapo = $chapo;
		$rubriquedesc->description = $description;
		$rubriquedesc->postscriptum = $postscriptum;
		
		if($ligne!="") $rubrique->ligne = 1;
		else $rubrique->ligne = 0;
    											
		$rubrique->maj();
		$rubriquedesc->maj();
		
		if($urlsuiv) header("location: parcourir.php?parent=".$rubrique->parent);
	    else header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $rubrique->id."&lang=".$lang);
		exit;
	}

	function ajouter($parent, $lang, $titre, $chapo, $description, $postscriptum, $lien, $ligne){
	
		$rubrique = new Rubrique();
		$rubrique->parent=$parent;
		$rubrique->lien = $lien;

		if($ligne!="") $rubrique->ligne = 1;
		else $rubrique->ligne = 0;	

		if($parent == "") $parent=0;
		$query = "select max(classement) as maxClassement from $rubrique->table where parent='$parent'";

		$resul = mysql_query($query, $rubrique->link);
   		$maxClassement = mysql_result($resul, 0, "maxClassement");

     	$rubrique->classement = $maxClassement+1;
     	
		$lastid = $rubrique->add();

		$rubrique->charger($lastid);
		
		$rubrique->maj();


		$rubriquedesc = new Rubriquedesc();	
	
		$rubriquedesc->rubrique = $lastid;
		$rubriquedesc->lang = 1;
		$rubriquedesc->titre = $titre;
		$rubriquedesc->chapo = $chapo;
		$rubriquedesc->description = $description;
		$rubriquedesc->postscriptum = $postscriptum;
		
		$rubriquedesc->add();

		$caracteristique = new Caracteristique();
		$query = "select * from $caracteristique->table";
		$resul = mysql_query($query, $caracteristique->link);
			
		$rubcaracteristique = new Rubcaracteristique();
			
		while($row = mysql_fetch_object($resul)){	

			$rubcaracteristique->rubrique = $lastid;
	 		$rubcaracteristique->caracteristique = $row->id;
	 		$rubcaracteristique->add();
	 	}


	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $lastid);

	}
	
	function supprimer($id, $parent){
		$rubcaracteristique = new Rubcaracteristique();
		$query = "delete from $rubcaracteristique->table where rubrique=\"$id\"";
		$resul = mysql_query($query, $rubcaracteristique->link);
		
		$rubrique = new Rubrique();		
		$rubrique->charger($id);
		$rubrique->supprimer();
		
	    header("Location: parcourir.php?parent=" . $parent);
		exit;
	}

?>

<?php
	$rubrique = new Rubrique();
	$rubriquedesc = new Rubriquedesc();

	if($id){
		
		$rubrique->charger($id);
		$rubriquedesc->charger($id, $lang);
	}
	
	$query = "select * from $rubrique->table where parent=\"$parent\"";
	$resul = mysql_query($query, $rubrique->link);
	$nbres = mysql_num_rows($resul);
		
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");
include_once('js/contenu_associe.php'); 
include_once('js/caracteristique.php');
include_once('js/declinaison.php');
?>
</head>

<?php
	include_once("../classes/Client.class.php");
?>

<?php
	$client = new Client();
  	
  	
	if($page=="") $page=1;
  		 
	$query = "select * from $client->table";
  	$resul = mysql_query($query, $client->link);
  	$num = mysql_num_rows($resul);
  	
  	$nbpage = ceil($num/20);
  	
  	$debut = ($page-1) * 20;
  	
  	if($page>1) $pageprec=$page-1;
  	else $pageprec=$page;

  	if($page<$nbpage) $pagesuiv=$page+1;
  	else $pagesuiv=$page;
  	 
  	$ordclassement = "order by nom";
	 if($id){
		$site = new Variable();
		$site->charger("urlsite");
	}

	
?>


<body>

<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="catalogue";
	include_once("entete.php");
?>

<div id="contenu_int">
  <p align="left"><a href="index.php"  class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="catalogue.php" class="lien04">Gestion du catalogue </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="parcourir.php" class="lien04">Par rubrique</a>
                          
            <?php
                    $parentdesc = new Rubriquedesc();
					
					$parentdesc->charger($id, $lang);
					
					$parentnom = $parentdesc->titre;	
					
					$res = chemin($id);
					$tot = count($res)-1;
	
?>
                             
			<?php
				if($parent || $id ){
			
			?>	
					<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<?php	
				}
				while($tot --){
			?>
			 <a href="parcourir.php?parent=<?php echo($res[$tot+1]->id); ?>" class="lien04"> <?php echo($res[$tot+1]->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />                             
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
            <?php include("tinymce.php"); ?>       
                   <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <?php if( !$id) { ?>Ajouter<?php } else { ?> Modifier <?php } ?>
 </p>
	
<!-- Début de la colonne de gauche / bloc de la fiche rubrique -->  
 <form action="<?php echo($_SERVER['PHP_SELF']); ?>" id="formulaire" method="post" ENCTYPE="multipart/form-data">
  <input type="hidden" name="action" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" name="id" value="<?php echo($id); ?>" />
	<input type="hidden" name="lang" value="<?php echo($lang); ?>" />
	<input type="hidden" name="urlsuiv" id="url" value="0">
	
	
<div id="bloc_description">
	<!-- bloc entete de la rubrique -->   	
		<div class="entete">
			<div class="titre">DESCRIPTION GÉNÉRALE DE LA RUBRIQUE</div>
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
		<div class="flag">
			<a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&lang=<?php echo($langl->id); ?>">
				<img src="gfx/lang<?php echo($langl->id); ?>.gif" />
			</a>
		</div> 
		<?php } ?>
		</th>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Titre</td>
        <td><input name="titre" id="titre" type="text" class="form_long" value="<?php echo($rubriquedesc->titre); ?>"/></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Chapo<br /><span class="note">(courte description d'introduction)</span></td>
        <td> <textarea name="chapo" id="chapo" cols="40" rows="2" class="form_long"><?php echo($rubriquedesc->chapo); ?></textarea></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Description<br /><span class="note">(description complète)</span></td>
        <td><textarea name="description" id="description" cols="40" rows="2" class="form"><?php echo($rubriquedesc->description); ?></textarea></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Postscriptum<br /> <span class="note">(champs d'information complémentaire)</span></td>
        <td>
        <textarea name="postscriptum" id="postscriptum" cols="40" rows="2" class="form_long"><?php echo($rubriquedesc->postscriptum); ?></textarea></td>
   	</tr>
   	<tr class="fonce">
      <td class="designation">Champs libre</td>
      <td>
        <input name="lien" id="lien" type="texte" class="form_long" value="<?php echo($rubrique->lien); ?>"/>
      </td>
    </tr>
   <?php
	if($id != ""){
   ?>
   <tr class="claire">
      <td class="designation">Appartenance<br /> <span class="note">(déplacer dans une autre rubrique)</span></td>
      <td style="vertical-align:top;">
        <select name="parent" id="parent" class="form_long">    
    	 <option value="0">-- Racine --</option>
         <?php 
          echo arbreOptionRub(0, 1, $id); 
		 ?>
          </select>
        </span></td>
    </tr>
  <?php
	} else {
		
   ?>
	<input type="hidden" name="parent" id="parent" value="<?php echo($parent); ?>" />

  <?php
	}
  ?>
  	 <tr class="fonce">
      <td class="designation">En ligne :</td>
      <td>
         <input name="ligne" id="ligne" type="checkbox" class="form" <?php if($rubrique->ligne || $id == "" ) { ?> checked="cheked" <?php } ?>/>
      </td>
    </tr>  
    </table>
 <?php
 if($id != ""){
 ?>
    <!-- bloc des contenus associés à la rubrique -->  
<ul id="blocs_pliants_prod">
	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">GESTION DES CONTENUS ASSOCIÉS</a></h3>
			<ul>
				<li class="ligne1">
					<div class="cellule">
					<select class="form_select" id="contenuassoc_dossier" onChange="charger_listcont(this.value, 0,'<?php echo $rubrique->id; ?>');">
			     	<option value="">&nbsp;</option>
			     	 <?php 
	 					echo arbreOption_dos(0, 1, 0);
	 				?>
					</select></div>
					<div class="cellule">
					<select class="form_select" id="select_prodcont">
					</select>
					</div>
					<div class="cellule"><a href="javascript:contenu_ajouter(document.getElementById('select_prodcont').value, 0,'<?php echo $rubrique->id; ?>')">AJOUTER</a></div>
				</li>
			

	<div id="contenuassoc_liste">		
		<?php	
		        $contenuassoc = new Contenuassoc();
		        $contenua = new Contenu();
		        $contenuadesc = new Contenudesc();

		        $query = "select * from $contenuassoc->table where type='0' and objet='$produit->id' order by classement";
		        $resul = mysql_query($query, $contenuassoc->link);

				$i = 0;

		        while($row = mysql_fetch_object($resul)){

		        		if($i%2)
		        			$fond = "fonce";
		        		else
		        			$fond = "claire";

		        		$i++;

		                $contenua->charger($row->contenu);
		        		$contenuadesc->charger($contenua->id);

		                $dossierdesc = new Dossierdesc();
		                $dossierdesc->charger($contenua->dossier);
		?>
        
        	 <li class="<?php echo $fond; ?>">
				<div class="cellule" style="width:260px;"><?php echo $dossierdesc->titre; ?></div>
				<div class="cellule" style="width:260px;"><?php echo $contenuadesc->titre; ?></div>
				<div class="cellule_supp"><a href="javascript:contenuassoc_supprimer(<?php echo $row->id; ?>, 0,'<?php echo $rubrique->id; ?>')"><img src="gfx/supprimer.gif" /></a></div>
			</li>
		<?php
		}
		?>		
	</div>
			<h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" /></a></h3>

		</ul>		
		
	</li>
	<?php
	$rubcaracteristique = new Rubcaracteristique();
	$query = "select * from $rubcaracteristique->table where rubrique=$rubrique->id";
	$resul = mysql_query($query);
	$listeid = "";
	while($row = mysql_fetch_object($resul)){
		$listeid .= $row->caracteristique.",";
	}
	if(strlen($listeid) > 0){
		$listeid = substr($listeid,0,strlen($listeid)-1);
	
		$caracteristique = new Caracteristique();
		$query = "select * from $caracteristique->table where id NOT IN($listeid)";
		$resul = mysql_query($query);
	}
	else{
		$caracteristique = new Caracteristique();
		$query = "select * from $caracteristique->table";
		$resul = mysql_query($query);
	}
	
	

	
	?>

	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">GESTION DES CARACTERISTIQUES ASSOCI&Eacute;ES</a></h3>
			<ul>
				<li class="ligne1">
					<div class="cellule" id="liste_prod_caracteristique">
					<select class="form_select" id="prod_caracteristique">
			     	<option value="">&nbsp;</option>
					<?php
						while($row = mysql_fetch_object($resul)){
							$caracteristiquedesc = new Caracteristiquedesc();
							$caracteristiquedesc->charger($row->id);
							?>
							<option value="<?php echo $row->id; ?>"><?php echo $caracteristiquedesc->titre; ?></option>
						<?php
						}
					?>
					</select>
					</div>
					<div class="cellule"><a href="javascript:caracteristique_ajouter(document.getElementById('prod_caracteristique').value)">AJOUTER</a></div>
				</li>
			
				
	<div id="caracteristique_liste">		
			<?php
				$rubcaracteristique = new Rubcaracteristique();
				$query = "select * from $rubcaracteristique->table where rubrique=$rubrique->id";
				$resul = mysql_query($query);
				$i=0;
				while($row = mysql_fetch_object($resul)){
					if($i%2 == 0) $fond="claire";
					else $fond="fonce";
					$i++;
					$caracteristiquedesc = new Caracteristiquedesc();
					$caracteristiquedesc->charger($row->caracteristique);
			?>
        
        	 <li class="<?php echo $fond; ?>">
				<div class="cellule" style="width:520px;"><?php echo $caracteristiquedesc->titre; ?></div>
				<div class="cellule_supp"><a href="javascript:caracteristique_supprimer(<?php echo $caracteristiquedesc->caracteristique; ?>)"><img src="gfx/supprimer.gif" /></a></div>
			</li>
			<?php
			}
			?>		
	</div>
			<h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" /></a></h3>

		</ul>		
		
	</li>
	<?php
	$rubdeclinaison = new Rubdeclinaison();
	$query = "select * from $rubdeclinaison->table where rubrique=$rubrique->id";
	$resul = mysql_query($query);
	$listeid = "";
	while($row = mysql_fetch_object($resul)){
		$listeid .= $row->declinaison.",";
	}
	if(strlen($listeid) > 0){
		$listeid = substr($listeid,0,strlen($listeid)-1);

		$declinaison = new Declinaison();
		$query = "select * from $declinaison->table where id NOT IN($listeid)";
		$resul = mysql_query($query);
	}
	else{
		$declinaison = new Declinaison();
		$query = "select * from $declinaison->table";
		$resul = mysql_query($query);
	}
?>
	

	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">GESTION DES D&Eacute;CLINAISONS ASSOCI&Eacute;ES</a></h3>
			<ul>
				<li class="ligne1">
					<div class="cellule" id="liste_prod_decli">
					<select class="form_select" id="prod_decli">
			     	<option value="">&nbsp;</option>
					<?php
						while($row = mysql_fetch_object($resul)){
							$declinaisondesc = new Declinaisondesc();
							$declinaisondesc->charger($row->id);
							?>
							<option value="<?php echo $row->id; ?>"><?php echo $declinaisondesc->titre; ?></option>
						<?php
						}
					?>
					</select>
					</div>
					<div class="cellule"><a href="javascript:declinaison_ajouter(document.getElementById('prod_decli').value)">AJOUTER</a></div>
				</li>
			

	<div id="declinaison_liste">		
			<?php
				$rubdeclinaison = new Rubdeclinaison();
				$query = "select * from $rubdeclinaison->table where rubrique=$rubrique->id";
				$resul = mysql_query($query);
				$i=0;
				while($row = mysql_fetch_object($resul)){
					if($i%2 == 0) $fond="claire";
					else $fond="fonce";
					$i++;
					$declinaisondesc = new Declinaisondesc();
					$declinaisondesc->charger($row->declinaison);
			?>
        
        	 <li class="<?php echo $fond; ?>">
				<div class="cellule" style="width:520px;"><?php echo $declinaisondesc->titre; ?></div>
				<div class="cellule_supp"><a href="javascript:declinaison_supprimer(<?php echo $declinaisondesc->declinaison; ?>)"><img src="gfx/supprimer.gif" /></a></div>
			</li>
			<?php
			}
			?>		
	</div>
			<h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" /></a></h3>

		</ul>		
		
	</li>
	
<div class="patchplugin">
<?php 
	admin_inclure("rubriquemodifier"); 
?>
</div>
	
<?php if($id != ""){ ?>
	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">INFORMATIONS SUR LA RUBRIQUE</a></h3>
			<ul>
				<li class="lignesimple">
					<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">ID</div>
					<div class="cellule" style="width:450px; padding: 5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;"><?php echo($rubrique->id); ?></div>
				</li>
			
			<li class="lignesimple">
				<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px;">URL réécrite</div>
				<div class="cellule" style="padding: 5px 0 0 5px;"><?php echo(rewrite_rub("$rubrique->id", $lang)); ?></div>
			</li>
		<h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" /></a></h3>

		</ul>		
		
	</li>
 <?php } ?> 
</ul>
<?php } ?>
</div><!-- fin du bloc_description -->	
</form>
<?php
if($id != ""){
?>
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
			$query = "select max(classement) as maxclassement from $rubrique->table where parent=$rubrique->parent";
			$resul = mysql_query($query);
			$maxclassement = mysql_result($resul,0,"maxclassement");
			if($rubrique->classement >1){
				$prec = $rubrique->classement-1;
				$queryclassement = "select id from $rubrique->table where parent=$rubrique->parent and classement=$prec";
				$resulclassement = mysql_query($queryclassement);
				$idprec = mysql_result($resulclassement,0,"id");
			?>
			<a href="rubrique_modifier.php?id=<?php echo $idprec; ?>" ><img src="gfx/precedent.png" alt="Rubrique pr&eacute;c&eacute;dente" title="Rubrique pr&eacute;c&eacute;dente" style="padding:0 5px 0 0;margin-top:-5px;height:38px;"/></a>	
			<?php
			}
			?>
			
			<!-- pour visualiser la page rubrique correspondante en ligne -->
			<a title="Voir la rubrique en ligne" href="<?php echo $site->valeur; ?>/rubrique.php?id_rubrique=<?php echo $id; ?>" target="_blank" ><img src="gfx/site.png" alt="Voir la rubrique en ligne" title="Voir la rubrique en ligne" /></a>
			<a href="#" onclick="document.getElementById('formulaire').submit();"><img src="gfx/valider.png" alt="Enregistrer les modifications" title="Enregistrer les modifications" style="padding:0 5px 0 0;"/></a>
			<a href="#" onclick="document.getElementById('url').value='1'; document.getElementById('formulaire').submit(); "><img src="gfx/validerfermer.png" alt="Enregistrer les modifications et fermer la fiche" title="Enregistrer les modifications et fermer la fiche" style="padding:0 5px 0 0;"/></a>
			<?php
			if($rubrique->classement < $maxclassement){
				$suivant = $rubrique->classement+1;
				$query = "select id from $rubrique->table where parent=$rubrique->parent and classement=$suivant";
				$resul = mysql_query($query);
				$idsuiv = mysql_result($resul,0,"id");
			
			?>
			<a href="rubrique_modifier.php?id=<?php echo $idsuiv; ?>" ><img src="gfx/suivant.png" alt="Rubrique suivante" title="Rubrique suivante" style="padding:0 5px 0 0;"/></a>	
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
			<form action="rubrique_modifier.php" method="post" ENCTYPE="multipart/form-data">
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
			
			
			$query = "select * from $image->table where rubrique='$id' order by classement";
			$resul = mysql_query($query, $image->link);

			while($row = mysql_fetch_object($resul)){
				$imagedesc = new Imagedesc();
				$imagedesc->charger($row->id,$lang);
   ?>
		<form action="rubrique_modifier.php" method="POST">
		<input type="hidden" name="action" value="modifierphoto" />
		<input type="hidden" name="id_photo" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="lang" value="<?php echo $lang; ?>">


			<li class="lignesimple">
				<div class="cellule_designation" style="height:208px;">&nbsp;</div>
				<div class="cellule_photos" style="height:200px; overflow:hidden;"><img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/rubrique/<?php echo($row->fichier); ?>&width=&height=200&opacite=&nb=" border="0" / ></div>
				<div class="cellule_supp"><a href="rubrique_modifier.php?id_photo=<?php echo($row->id); ?>&id=<?php echo($id); ?>&action=supprimerphoto"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
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
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id_photo=".$row->id."&action=modclassementphoto&type=M&id=".$id; ?>"><img src="gfx/up.gif" border="0" /></a></div>
					<div class="classement">
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id_photo=".$row->id."&action=modclassementphoto&type=D&id=".$id; ?>"><img src="gfx/dn.gif" border="0" /></a></div>
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
<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:43px; padding-top:10px;">Transférer des documents</div>
		<div class="champs" style="padding-top:10px;">
			<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
				<input type="hidden" name="action" value="ajouterdoc" />
 				<input type="hidden" name="id" value="<?php echo($id); ?>" /> 
    			<input type="hidden" name="lang" value="<?php echo($lang); ?>"
    			<input type="file" name="doc" class="form"><br/>
    			<input type="submit" value="Ajouter">
    		</form>
		</div>
	</div>

   	   <?php
			$document = new Document();
			

			$query = "select * from $document->table where rubrique='$rubrique->id' order by classement";
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
					<a href="rubrique_modifier.php?id=<?php echo($id); ?>&id_document=<?php echo($row->id); ?>&action=supprimer_document&lang=<?php echo $lang; ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
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

</div> <!-- fin bloc-photos colonne de droite -->
<?php } ?>
</div> <!-- fin bloc-photos colonne contenu-int -->
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
