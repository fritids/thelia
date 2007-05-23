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
	if(!isset($_SESSION["bout"])) $_SESSION["bout"]="";	
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
?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $parent, $type); break;
		case 'modifier' : modifier($id, $lang, $dossier, $ligne, $titre, $chapo, $description); break;
		case 'ajouter' : ajouter($lang, $dossier, $ligne, $titre, $chapo, $description); break;
		case 'supprimer' : supprimer($id, $parent);

	}
	
?>
<?php

	function modclassement($id, $parent, $type){

		$contenu = new Contenu();
		$contenu->charger($id);

	 	$query = "select max(classement) as maxClassement from $contenu->table where dossier='" . $parent . "'";

		$resul = mysql_query($query, $contenu->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");

	 
		if($type=="M"){
			if($contenu->classement == 1) { header("Location: listedos.php?parent=" . $parent); exit; }

			$query = "update $contenu->table set classement=" . $contenu->classement . " where classement=" . ($contenu->classement-1) . " and dossier='" . $parent . "'";

			$resul = mysql_query($query, $contenu->link);
			
			 $contenu->classement--;
		}
		
		else if($type=="D"){

			if($contenu->classement == $maxClassement) { header("Location: listdos.php?parent=" . $parent); exit; }

			
			$query = "update $contenu->table set classement=" . $contenu->classement . " where classement=" . ($contenu->classement+1) . " and dossier='" . $parent . "'";
			$resul = mysql_query($query, $contenu->link);
			
			 $contenu->classement++;
		}
		
		$contenu->maj();

	    header("Location: listdos.php?parent=" . $parent);
		exit;
	}
	
	
	function modifier($id, $lang, $dossier, $ligne, $titre, $chapo, $description){

	 if(!isset($id)) $id="";

     if(  $_SESSION["bout"] == "") {
          header("Location: listdos.php" );
          exit;
     }
     
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

		 $contenu->boutique = $_SESSION["bout"];
		 $contenu->datemodif = date("Y-m-d H:i:s");		
		 $contenu->dossier = $dossier; 
	 	 if($ligne == "on") $contenu->ligne = 1; else $contenu->ligne = 0;
		 $contenudesc->chapo = $chapo;
		 $contenudesc->description = $description;
		 $contenudesc->titre = $titre;
	 	 
	 	 $contenudesc->chapo = ereg_replace("\n", "<br/>", $contenudesc->chapo);
	//	 $contenudesc->description = ereg_replace("\n", "<br/>", $contenudesc->description);
											
		$contenu->maj();
		$contenudesc->maj();

	   
	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $contenu->id . "&dossier=" . $contenu->dossier);
		exit;
	}

	function ajouter($lang, $dossier, $ligne, $titre, $chapo, $description){

 	 if(!isset($id)) $id="";
	 
     if(  $_SESSION["bout"] == "") {
          header("Location: listdos.php" );
          exit;
     }
             

	 $contenu = new Contenu();
	 $contenu->charger($id);
	 
   	 if($contenu->id) return;
   	 
	 $contenu = new Contenu();

	 $query = "select max(classement) as maxClassement from $contenu->table where dossier='" . $dossier . "'";

	 $resul = mysql_query($query, $contenu->link);
     $maxClassement = mysql_result($resul, 0, "maxClassement");

	 $contenu->boutique = $_SESSION["bout"];
	 $contenu->datemodif = date("Y-m-d H:i:s");	
	 $contenu->dossier = $dossier; 
	 if($ligne == "on") $contenu->ligne = 1; else $contenu->ligne = 0;
	 $contenu->classement = $maxClassement + 1;
	 
	 $lastid = $contenu->add();
	
	 $contenudesc = new Contenudesc();	

	 $contenudesc->chapo = $chapo;
	 $contenudesc->description = $description;
	 $contenudesc->contenu = $lastid;
	 $contenudesc->lang = 1;
	 $contenudesc->titre = $titre;

	 $contenudesc->chapo = ereg_replace("\n", "<br/>", $contenudesc->chapo);
     $contenudesc->description = ereg_replace("\n", "<br/>", $contenudesc->description);		
	 
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
	$contenudesc->description = ereg_replace("<br/>", "\n", $contenudesc->description);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<?php include_once("tinymce.php"); ?>
</head>

<body>

<?php
	$menu="contenu";
	include_once("entete.php");
?>
<div id="contenu_int">
  <p class="titre_rubrique">Description g&eacute;n&eacute;rale du contenu </p>
  <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="contenu.php" class="lien04">Gestion du contenu </a> 
  
    <?php
    				$cont = new Contenu();
    				$cont->charger($id);
    				
    				if($id) $_SESSION["bout"] = $cont->boutique;
    				
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
			<a href="listdos.php?parent=<?php echo($parentdesc->dossier); ?>" class="lien04"><?php echo($parentdesc->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />&nbsp;
			
			 <?php if( $id) { ?>
			 
			<a href="#" class="lien04"><?php echo($contdesc->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />&nbsp;
           Modifier<?php } else { ?> Ajouter <?php } ?> </p>	
                                    
   <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="formulaire" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" name="id" value="<?php echo($id); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
 	<input type="hidden" name="dossier" value="<?php echo($dossier); ?>" /> 
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
						  
						  		 &nbsp; <a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&dossier=<?php echo($dossier); ?>&lang=<?php echo($langl->id); ?>"  class="lien06"><?php echo($langl->description); ?></a>
						  		&nbsp; 
						  <?php } ?> </td>
    </tr>
  </table>
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr><input type="submit" id="boutoncache" style="display: none">
      <td height="30" class="cellule_sombre2"><span class="sous_titre_dossier"><span class="geneva11Reg_3B4B5B"><a href="#" onClick="document.getElementById('boutoncache').click()" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onClick="document.getElementById('boutoncache').click()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
    </tr>
  </table>  
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="titre_cellule">TITRE DU CONTENU </td>
      <td class="cellule_claire"><input name="titre" type="text" class="form" value="<?php echo($contenudesc->titre); ?>">
      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">CHAPO (resumé de la description)</td>
      <td class="cellule_sombre">
        <textarea name="chapo" cols="40" rows="2" class="form"><?php echo($contenudesc->chapo); ?></textarea>
      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">DESCRIPTION DU CONTENU </td>
      <td class="cellule_claire">

			<textarea name="description" rows="18" cols="50" style="width: 100%"><?php echo($contenudesc->description); ?></textarea>
		

        </span></td>
    </tr>
    
        
    <tr>
      <td height="30" class="titre_cellule">En ligne </td>
      <td class="cellule_claire">
    <input type="checkbox" name="ligne" <?php if($contenu->ligne) echo "checked"; ?>>
        </span></td>
    </tr>   
         
    <tr>
      <td height="30" class="titre_cellule">Appartenance ( dossier père )</td>
      <td class="cellule_claire">
    <select name="dossier">
      <?php if($id) echo arbreOption_dos(0, 1, $contenu->dossier); else echo arbreOption_dos(0, 1, $dossier); ?>
      </select>
        </span></td>
    </tr> 
             
<?php
	if($id){
?>

         
	 <tr>
      <td height="30" class="titre_cellule">PHOTOS</td>
      <td class="cellule_sombre"><span class="arial11_bold_626262">
        <?php 
		$image = new Image();
		$imagedesc = new Imagedesc();

		$query = "select * from $image->table where contenu='$contenu->id' and contenu>0";
		$resul = mysql_query($query, $image->link);
		
		while($row = mysql_fetch_object($resul)){
			$imagedesc->charger($row->id);
	?>
        <?php echo($row->fichier); ?>
        <br />
        <?php
		}
	?>
        <a href="#" class="lien04" onclick="window.open('photo_contenu.php?contid=<?php echo($contenu->id); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=800');"> G&eacute;rer les photos</a></span></td>
    </tr>
		 <tr>
      <td height="30" class="titre_cellule">DOCUMENTS</td>
      <td class="cellule_sombre"><span class="arial11_bold_626262">
               <a href="#" class="lien04" onclick="window.open('document_contenu.php?contid=<?php echo($contenu->id); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=300');"> G&eacute;rer les documents</a> </span></td>
    </tr>
    
    <?php } ?>
  </table>
   
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr><input type="submit" id="boutoncache" style="display: none">
      <td height="30" class="cellule_sombre2"><span class="sous_titre_dossier"><span class="geneva11Reg_3B4B5B"><a href="#" onClick="document.getElementById('boutoncache').click()" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onClick="document.getElementById('boutoncache').click()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
    </tr>
  </table>
   </form>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" colspan="2" class="titre_cellule_tres_sombre">Informations sur le contenu </td>
    </tr>
	<tr>
      <td width="246" height="30" class="titre_cellule">ID</td>
      <td width="444" class="titre_cellule"><?php echo($contenu->id); ?></td>
	</tr>   
	<tr>
      <td width="246" height="30" class="titre_cellule">URL réécrite : </td>
      <td width="444" class="titre_cellule"><?php echo(rewrite_cont("$contenu->id", $lang)); ?></td>
	</tr>
  </table>
</div>
</body>
</html>
