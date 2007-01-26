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
	include("auth.php");
	include_once("pre.php");
	
	if(!isset($action)) $action="";
	if(!isset($lang)) $lang="1";
	if(!isset($parent)) $parent="";
	if(!isset($page)) $page="";
	if(!isset($id)) $id="";
	if(!isset($_SESSION["bout"])) $_SESSION["bout"]="";	
?>
<?php
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Rubriquedesc.class.php");
	include_once("../classes/Lang.class.php");
	include_once("../fonctions/divers.php");
    include("../classes/Rubcaracteristique.class.php");
    include("../classes/Caracteristique.class.php");
    include("../classes/Image.class.php");
   
?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $parent, $type); break;
		case 'modifier' : modifier($id, $lang, $titre, $chapo, $description, $lien, $ligne); break;
		case 'ajouter' : ajouter($parent, $lang, $titre, $chapo, $description, $lien, $ligne); break;
		case 'supprimer' : supprimer($id, $parent);
		case 'supprimg': supprimg($id);
	
	}
	

?>

<?php



	function modclassement($id, $parent, $type){

		if($parent == 0) $parent=0;
		
		$rubrique = new Rubrique();
		$rubrique->charger($id);

	 	$query = "select max(classement) as maxClassement from $rubrique->table where parent='" . $parent . "'";

		$resul = mysql_query($query, $rubrique->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");

		if($type=="M"){
			if($rubrique->classement == 1) { header("Location: parcourir.php?parent=$parent"); return; }

			$query = "update $rubrique->table set classement=" . $rubrique->classement . " where classement=" . ($rubrique->classement-1) . " and parent='" . $parent . "'";

			$resul = mysql_query($query, $rubrique->link);
			
			 $rubrique->classement--;
		}
		
		else if($type=="D"){

			if($rubrique->classement == $maxClassement) { header("Location: parcourir.php?parent=$parent"); return; }

			
			$query = "update $rubrique->table set classement=" . $rubrique->classement . " where classement=" . ($rubrique->classement+1) . " and parent='" . $parent . "'";
			$resul = mysql_query($query, $rubrique->link);
			
			 $rubrique->classement++;
		}
		
		$rubrique->maj();

	    header("Location: parcourir.php?parent=$parent");

	}
	
	function modifier($id, $lang, $titre, $chapo, $description, $lien, $ligne){

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
		
		$rubrique->lien = $lien;
		$rubriquedesc->titre = $titre;
		$rubriquedesc->chapo = $chapo;
		$rubriquedesc->description = $description;
		
		if($ligne!="") $rubrique->ligne = 1;
		else $rubrique->ligne = 0;
    											
		$rubrique->maj();
		$rubriquedesc->maj();

	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $rubrique->id);


	}

	function ajouter($parent, $lang, $titre, $chapo, $description, $lien, $ligne){

     if(  $_SESSION["bout"] == "0") {
          header("Location: catalogue.php" );
          exit;
     }
         		
		$rubrique = new Rubrique();
		$rubrique->parent=$parent;
		$rubrique->lien = $lien;
		$rubrique->boutique = $_SESSION["bout"];

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

	}

	function supprimg($id){
		$rubrique = new Rubrique();		
		$rubrique->charger($id);
		$rubrique->image=0;
		if(file_exists("../client/gfx/photos/rubrique/" . $rubrique->id . ".jpg")) unlink("../client/gfx/photos/rubrique/" . $rubrique->id . ".jpg");
		$rubrique->maj();

		
	}	
?>

<?php
	$rubrique = new Rubrique();
	$rubriquedesc = new Rubriquedesc();

	if($id){
		
		$rubrique->charger($id);
		$rubriquedesc->charger($id, $lang);
		
		$_SESSION["bout"] = $rubrique->boutique;
	}
	
	$query = "select * from $rubrique->table where parent=\"$parent\"";
	$resul = mysql_query($query, $rubrique->link);
	$nbres = mysql_numrows($resul);
		
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<?php
	include("../classes/Client.class.php");
	
?>

<?php
	$client = new Client();
  	
  	
	if($page=="") $page=1;
  		 
	$query = "select * from $client->table";
  	$resul = mysql_query($query, $client->link);
  	$num = mysql_numrows($resul);
  	
  	$nbpage = ceil($num/20);
  	
  	$debut = ($page-1) * 20;
  	
  	if($page>1) $pageprec=$page-1;
  	else $pageprec=$page;

  	if($page<$nbpage) $pagesuiv=$page+1;
  	else $pagesuiv=$page;
  	 
  	$ordclassement = "order by nom";

?>

<body>

<?php
	$menu="catalogue";
	include("entete.php");
?>
<div id="contenu_int">
  <p class="titre_rubrique">Modiifer les caract&eacute;ristiques de la rubrique </p>
  <p  class="geneva11Reg_3B4B5B"><a href="index.php"  class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="catalogue.php" class="lien04">Gestion du catalogue </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="parcourir.php" class="lien04">Par rubrique</a>
                          
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
			 <a href="parcourir.php?parent=<?php echo($parentdesc->rubrique); ?>" class="lien02"> <?php echo($parentdesc->titre); ?></a>                             
            
              
                          
                          
                          <span class="arial11_bold_626262"><img src="gfx/suivant.gif" width="12" height="9" border="0" />
                          <?php if( !$id) { ?>Ajouter<?php } else { ?> Modifier <?php } ?>                         </p>
					
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre">Description g&eacute;n&eacute;rale de la rubrique
							<?php
								$langl = new Lang();
								$query = "select * from $langl->table";
								$resul = mysql_query($query);
								while($row = mysql_fetch_object($resul)){
									$langl->charger($row->id);
						    ?>
						  &nbsp; 
						  		<a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&lang=<?php echo($langl->id); ?>" class="lien06"><?php echo($langl->description); ?></a>&nbsp; 
						  		
						  <?php } ?> </td>
    </tr>
	<tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre2"><a href="rubcaracteristique.php?id=<?php echo($id); ?>" class="lien04">G&eacute;rer les caract&eacute;ristiques</a></td>
    </tr>
	<tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre2"><a href="rubdeclinaison.php?id=<?php echo($id); ?>" class="lien04">G&eacute;rer les d&eacute;clinaisons</a></td>
    </tr>    
  </table>
  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" id="formulaire" method="post" ENCTYPE="multipart/form-data">
  <input type="hidden" name="action" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" name="parent" value="<?php echo($parent); ?>" />
	<input type="hidden" name="id" value="<?php echo($id); ?>" />
	<input type="hidden" name="lang" value="<?php echo($lang); ?>" />
	<table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="titre_cellule">TITRE :</td>
      <td class="cellule_sombre">
        <input name="titre" type="texte" class="form" value="<?php echo($rubriquedesc->titre); ?>"/>
      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">CHAPO (resum&eacute; de la description) : </td>
      <td class="cellule_claire">
        <textarea name="chapo" cols="40" rows="2" class="form"><?php echo($rubriquedesc->chapo); ?></textarea>
      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">DESCRIPTION : </td>
      <td class="cellule_claire">
        <textarea name="description" cols="40" rows="4" class="form"><?php echo($rubriquedesc->description); ?></textarea>
      </td>
    </tr>
	 <tr>
      <td width="250" height="30" class="titre_cellule">LIEN :</td>
      <td width="440" class="cellule_claire">
        <input name="lien" type="texte" class="form" value="<?php echo($rubrique->lien); ?>"/>
      </td>
    </tr>
	 <tr>
      <td width="250" height="30" class="titre_cellule">CONTENUS ASSOCIES :</td>
      <td width="440" class="cellule_claire">
  		   <a href="#" class="lien04" onclick="window.open('contenu_assoc.php?objet=<?php echo($id); ?>&type=0', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=800');"> G&eacute;rer les contenus associ&eacute;s</a>
        
      </td>
    </tr>
	 <tr>
      <td width="250" height="30" class="titre_cellule">En ligne :</td>
      <td width="440" class="cellule_claire">
         <input name="ligne" type="checkbox" class="form" <?php if($rubrique->ligne || $id == "" ) { ?> checked="cheked" <?php } ?>/>
      </td>
    </tr>    
    
    
 <?php
 	if($id != ""){
 ?>   
    
    <tr>
      <td width="250" height="30" class="titre_cellule">IMAGE : </td>
      <td width="440" class="cellule_claire"> <a href="#" class="lien04" onClick="window.open('photo_rubrique.php?rubid=<?php echo($rubrique->id); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=300');"> G&eacute;rer les photos</a>
      
        <br/>
        
        <?php 
		$image = new Image();
		$imagedesc = new Imagedesc();

		$query = "select * from $image->table where rubrique='$rubrique->id'";
		$resul = mysql_query($query, $image->link);
		
		while($row = mysql_fetch_object($resul)){
			$imagedesc->charger($row->id);
	?>
        &nbsp;<img src="../client/gfx/photos/rubrique/petite/<?php echo($row->fichier); ?>" border="0" />       
         <br />
    <?php
		}
	?>           
      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">DOCUMENT : </td>
      <td class="cellule_claire"> <a href="#" class="lien04" onClick="window.open('document_rubrique.php?rubid=<?php echo($rubrique->id); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=300');"> G&eacute;rer les documents</a></td>
    </tr>
    
   <?php
    	}
   ?> 

  </table>
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" onClick="document.getElementById('formulaire').submit()" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onClick="document.getElementById('formulaire').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
    </tr>
  </table>
  </form>
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" colspan="2" class="titre_cellule_tres_sombre">Informations sur la rubrique</td>
    </tr>
	<tr>
      <td width="246" height="30" class="titre_cellule">ID</td>
      <td width="444" class="titre_cellule"><?php echo($rubrique->id); ?></td>
	</tr>  
	<tr>
      <td width="246" height="30" class="titre_cellule">URL réécrite : </td>
      <td width="444" class="titre_cellule"><?php echo(rewrite_rub("$rubrique->id", $lang)); ?></td>
	</tr>
   </table>
  
 
</div>
</body>
</html>
