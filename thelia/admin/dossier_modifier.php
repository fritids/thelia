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
	include_once("../classes/Cache.class.php");
?>
<?php

	switch($action){
		case 'modclassement' : modclassement($id, $parent, $type); break;
		case 'modifier' : modifier($id, $lang, $titre, $chapo, $description, $ligne); break;
		case 'ajouter' : ajouter($parent, $lang, $titre, $chapo, $description, $ligne); break;
		case 'supprimer' : supprimer($id, $parent);
		case 'supprimg': supprimg($id);
	
	}
	

?>

<?php



	function modclassement($id, $parent, $type){

		if($parent == 0) $parent=0;
		
		$dossier = new Dossier();
		$dossier->charger($id);

	 	$query = "select max(classement) as maxClassement from $dossier->table where parent='" . $parent . "'";

		$resul = mysql_query($query, $dossier->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");

		if($type=="M"){
			if($dossier->classement == 1) { header("Location: listdos.php?parent=$parent"); return; }

			$query = "update $dossier->table set classement=" . $dossier->classement . " where classement=" . ($dossier->classement-1) . " and parent='" . $parent . "'";

			$resul = mysql_query($query, $dossier->link);
			
			 $dossier->classement--;
		}
		
		else if($type=="D"){

			if($dossier->classement == $maxClassement) { header("Location: listdos.php?parent=$parent"); return; }

			
			$query = "update $dossier->table set classement=" . $dossier->classement . " where classement=" . ($dossier->classement+1) . " and parent='" . $parent . "'";
			$resul = mysql_query($query, $dossier->link);
			
			 $dossier->classement++;
		}
		
		$dossier->maj();

		$cache = new Cache();
		$cache->vider("DOSSIER", "%");
		$cache->vider("CONTENU", "%");

		
	    header("Location: listdos.php?parent=$parent");
		exit;
	}
	
	function modifier($id, $lang, $titre, $chapo, $description, $ligne){
	
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

		if($ligne!="") $dossier->ligne = 1;
		else $dossier->ligne = 0;
   											
		$dossier->maj();
		$dossierdesc->maj();

		$cache = new Cache();
		$cache->vider("DOSSIER", "%");
		$cache->vider("CONTENU", "%");
		
	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $dossier->id);
		exit;

	}

	function ajouter($parent, $lang, $titre, $chapo, $description, $ligne){
  		
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
		
		$dossierdesc->add();
	
		$cache = new Cache();
		$cache->vider("DOSSIER", "%");
		$cache->vider("CONTENU", "%");
			 		
	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $lastid);
		exit;
	}
	
	function supprimer($id, $parent){
		
		$dossier = new Dossier();		
		$dossier->charger($id);
		$dossier->supprimer();

		$cache = new Cache();
		$cache->vider("DOSSIER", "%");
		$cache->vider("CONTENU", "%");
		
	    header("Location: listdos.php?parent=" . $parent);
		exit;

	}

	function supprimg($id){
		$dossier = new Dossier();		
		$dossier->charger($id);
		$dossier->image=0;
		if(file_exists("../client/gfx/photos/dossier/" . $dossier->id . ".jpg")) unlink("../client/gfx/photos/dossier/" . $dossier->id . ".jpg");
		$dossier->maj();

		$cache = new Cache();
		$cache->vider("DOSSIER", "%");
		$cache->vider("CONTENU", "%");	
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
	$nbres = mysql_numrows($resul);
		
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
	$menu="contenu";
	include_once("entete.php");
?>
<div id="contenu_int">
  <p class="titre_rubrique">Description g&eacute;n&eacute;rale de la rubrique de contenu </p>
  <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="listdos.php" class="lien04">Gestion</a><a href="listdos.php" class="lien04">du contenu </a>              

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
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre">Description g&eacute;n&eacute;rale du contenu &nbsp; 
							<?php
								$langl = new Lang();
								$query = "select * from $langl->table";
								$resul = mysql_query($query);
								while($row = mysql_fetch_object($resul)){
									$langl->charger($row->id);
						    ?>
						  
						  		 &nbsp; <a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&rubrique=<?php echo($rubrique); ?>&lang=<?php echo($langl->id); ?>"  class="lien06"><?php echo($langl->description); ?></a>
						  		&nbsp; 
						  <?php } ?> </td>
    </tr>
  </table>
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="titre_cellule">TITRE DU DOSSIER </td>
      <td class="cellule_claire"><input name="titre" type="text" class="form" value="<?php echo($dossierdesc->titre); ?>">
      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">CHAPO (resumé de la description)</td>
      <td class="cellule_sombre">
        <textarea name="chapo" cols="40" rows="2" class="form"><?php echo($dossierdesc->chapo); ?></textarea>
        
      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">DESCRIPTION DU DOSSIER</td>
      <td class="cellule_claire">
                 <textarea name="description" cols="40" rows="4" class="form"><?php echo($dossierdesc->description); ?></textarea>
       </span></td>
    </tr>
    
	 <tr>
      <td width="250" height="30" class="titre_cellule">En ligne :</td>
      <td width="440" class="cellule_claire">
         <input name="ligne" type="checkbox" class="form" <?php if($dossier->ligne || $id == "" ) { ?> checked="cheked" <?php } ?>/>
      </td>
    </tr>           
         
<?php
	if($id){
?>

         
	 <tr>
      <td height="30" class="titre_cellule">PHOTOS</td>
      <td class="cellule_sombre"><span class="arial11_bold_626262">
  
        <a href="#" class="lien04" onclick="window.open('photo_dossier.php?dosid=<?php echo($dossier->id); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=800');"> G&eacute;rer les photos</a></span></td>
    </tr>
		 <tr>
      <td height="30" class="titre_cellule">DOCUMENTS</td>
      <td class="cellule_sombre"><span class="arial11_bold_626262">

        <a href="#" class="lien04" onclick="window.open('document_dossier.php?dosid=<?php echo($dossier->id); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=300');"> G&eacute;rer les documents</a> </span></td>
    </tr>
    
    <?php } ?>
  </table>
   
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" onClick="document.getElementById('formulaire').submit()" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onClick="document.getElementById('formulaire').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
    </tr>
  </table>
   </form>

<?php if($id != ""){ ?>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" colspan="2" class="titre_cellule_tres_sombre">Informations sur le dossier							</td>
    </tr>
	<tr>
      <td width="246" height="30" class="titre_cellule">ID</td>
      <td width="444" class="titre_cellule"><?php echo($dossier->id); ?></td>
	</tr>   
	<tr>
      <td width="246" height="30" class="titre_cellule">URL réécrite : </td>
      <td width="444" class="titre_cellule"><?php echo(rewrite_dos("$dossier->id", $lang)); ?></td>
	</tr>
  </table>
<?php } ?>

</div>
</body>
</html>
