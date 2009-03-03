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
<?php
	include_once("../classes/Caracteristique.class.php");
	include_once("../fonctions/divers.php");
	include_once("../lib/JSON.php");
	
	include_once("../classes/Lang.class.php");  
	include_once("../classes/Caracdisp.class.php");
	include_once("../classes/Rubcaracteristique.class.php");
	include_once("../classes/Rubrique.class.php");

	if(!isset($action)) $action="";
	if(!isset($lang)) $lang="1";
	if(!isset($tabdisp)) $tabdisp="";
	if(!isset($affiche)) $affiche="";	
		
?>
<?php
	switch($action){
		case 'modclassement' : modclassement($id, $type); break;
		case 'modifier' : modifier($id, $lang, $titre, $chapo, $description, $tabdisp, $affiche); break;
		case 'maj' : maj($id, $lang, $titre, $chapo, $description, $tabdisp, $affiche); break;
		case 'ajouter' : ajouter($lang, $id, $titre, $chapo, $description, $tabdisp, $affiche); break;
		case 'ajcaracdisp' : ajcaracdisp($id, $caracdisp, $lang); break;
		case 'majcaracdisp' : majcaracdisp($id, $lang); break;
		case 'supprimer' : supprimer($id); break;
		case 'suppcaracdisp' : suppcaracdisp($caracdisp);
	}
	
?>
<?php
	function modclassement($id, $type){

      	$car = new Caracteristique();
        $car->charger($id);
        $car->changer_classement($id, $type);

	    header("Location: caracteristique.php");

	}
	
	function modifier($id, $lang, $titre, $chapo, $description, $tabdisp, $affiche){
	 	
		
		if(!$lang) $lang=1;
		
		$caracteristique = new Caracteristique();
		$caracteristiquedesc = new Caracteristiquedesc();
		$caracteristique->charger($id);
		$res = $caracteristiquedesc->charger($caracteristique->id, $lang);	

		if(!$res){
			$temp = new Caracteristiquedesc();
			$temp->caracteristique=$caracteristique->id;
			$temp->lang=$lang;
			$temp->add();
			$caracteristiquedesc->charger($caracteristique->id, $lang);
		
		}
			
		 if($affiche!="") $caracteristique->affiche = 1;
		 else $caracteristique->affiche = 0;
					                 
		 $caracteristiquedesc->chapo = $chapo;
		 $caracteristiquedesc->description = $description;
		 $caracteristiquedesc->titre = $titre;
	 	 
	 	 $caracteristiquedesc->chapo = ereg_replace("\n", "<br/>", $caracteristiquedesc->chapo);
		 $caracteristiquedesc->description = ereg_replace("\n", "<br/>", $caracteristiquedesc->description);
	
		 $caracteristique->maj();
		 $caracteristiquedesc->maj();
			
	     header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
	}

	function ajouter($lang, $id, $titre, $chapo, $description, $tabdisp, $affiche){

	 $caracteristique = new Caracteristique();
	 $caracteristique->charger($id);
	 
   	 if($caracteristique->id) return;

	 $caracteristique = new Caracteristique();

	 $query = "select max(classement) as maxClassement from $caracteristique->table";

	 $resul = mysql_query($query, $caracteristique->link);
     $maxClassement = mysql_result($resul, 0, "maxClassement");


	 $caracteristique->id = $id;
	 if($affiche!="") $caracteristique->affiche = 1;
	 else $caracteristique->affiche = 0;	 
	 
	 $caracteristique->classement =  $maxClassement + 1;
	 
	 $lastid = $caracteristique->add();

	 $caracteristiquedesc = new Caracteristiquedesc();	

	 $caracteristiquedesc->chapo = $chapo;
	 $caracteristiquedesc->description = $description;
	 $caracteristiquedesc->caracteristique = $lastid;
	 $caracteristiquedesc->lang = 1;
	 $caracteristiquedesc->titre = $titre;

	 $caracteristiquedesc->chapo = ereg_replace("\n", "<br/>", $caracteristiquedesc->chapo);
     $caracteristiquedesc->description = ereg_replace("\n", "<br/>", $caracteristiquedesc->description);		
	 
	 $caracteristiquedesc->add();

	 $rubrique = new Rubrique();
	 $query = "select * from $rubrique->table";
	 $resul = mysql_query($query, $rubrique->link);
	 
	 while($row = mysql_fetch_object($resul)){
		$rubcaracteristique = new Rubcaracteristique();
		$rubcaracteristique->rubrique = $row->id;
		$rubcaracteristique->caracteristique = $lastid;
		$rubcaracteristique->add();
	 }
		
	 header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $lastid);

	}
	
	function supprimer($id){

		$caracdisp = new Caracdisp();
		$rubcaracteristique = new Rubcaracteristique();
		
		$query = "select * from $caracdisp->table where caracteristique='$id'";
		$resul = mysql_query($query, $caracdisp->link);

		while($row = mysql_fetch_object($resul)){
			$caracdisp->charger($row->id);
			$caracdisp->supprimer();
		}
		
		$query = "delete from $rubcaracteristique->table where caracteristique='$id'";
		$resul = mysql_query($query, $rubcaracteristique->link);
						
		$caracteristique = new Caracteristique();		
		$caracteristique->charger($id);
		$caracteristique->supprimer();
		
	    header("Location: caracteristique.php");

	}

	function suppcaracdisp($caracdisp){
                $tcaracdisp = new Caracdisp();	
		$tcaracdisp->charger($caracdisp);
		$tcaracdisp->supprimer();

	}

	function ajcaracdisp($id, $caracdisp, $lang){
	
     	if(!$lang) $lang=1;

     	
		$tcaracdisp = new Caracdisp();
		$tcaracdisp->caracteristique = $id;
		$res = $tcaracdisp->add();

		$tcaracdispdesc = new Caracdispdesc();
		$tcaracdispdesc->caracdisp = $res;
		$tcaracdispdesc->lang = $lang;
		$tcaracdispdesc->titre = $caracdisp;
		
		$tcaracdispdesc->add();
	}
	
	
	function majcaracdisp($id, $lang){
		$caracdisp = new Caracdisp();
		
		$query = "select * from $caracdisp->table where caracteristique='$id'";
		$resul = mysql_query($query, $caracdisp->link);
		
		while($row = mysql_fetch_object($resul)){
			$caracdispdesc = new Caracdispdesc();
		
			$var = $lang . "_" . $row->id;
			
			global $$var;
			
			$existe = $caracdispdesc->charger_caracdisp($row->id, $lang);
			
			$caracdispdesc->caracdisp = $row->id;
			
			$caracdispdesc->lang = $lang;
			$caracdispdesc->titre = $$var;

			if( ! $existe )
				$caracdispdesc->add();	

			else $caracdispdesc->maj();
			

		
		}
	
	}
?>
<?php
	$caracteristique = new Caracteristique();
	$caracteristiquedesc = new Caracteristiquedesc();
	
	$caracteristique->charger($id);
	$caracteristiquedesc->charger($caracteristique->id, $lang);

	
	$caracteristiquedesc->chapo = ereg_replace("<br/>", "\n", $caracteristiquedesc->chapo);
	$caracteristiquedesc->description = ereg_replace("<br/>", "\n", $caracteristiquedesc->description);
	
	$caracdisp = new Caracdisp();
	$caracdispdesc = new Caracdispdesc();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">


	  function ajout(){
	 
		  if(document.getElementById('zoneid').value != ""){

	  	 	document.getElementById('zoneaction').value='ajcaracdisp';	
	  	 	document.getElementById('form_modif').submit();
		 }
	 
		  else alert("Veuillez d'abord creer votre caracteristique"); 	 
	   
	  }

	  function maj(){
	 
		  if(document.getElementById('zoneid').value != ""){

	  	 	document.getElementById('zoneaction').value='majcaracdisp';	
	  	 	document.getElementById('form_modif').submit();
		 }
	 
		  else alert("Veuillez d'abord creer votre caracteristique"); 	 
	   
	  }

	  function suppr(caracdisp){
	  	if(confirm("Voulez-vous vraiment supprimer cette entree ?")) location="<?php echo($_SERVER['PHP_SELF'] ); ?>?id=<?php echo($id); ?>&action=suppcaracdisp&caracdisp=" + caracdisp;
	  
	  }	
	  
</script>
</head>

<body>

<?php
	$menu="catalogue";
	include_once("entete.php");
	
	if(!$lang) $lang=1;
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des caract&eacute;ristiques</p>
   <p align="right" class="geneva11Reg_3B4B5B"><span class="lien04"><a href="accueil.php" class="lien04">Accueil</a></span> <a href="#" onclick="document.getElementById('form_modif').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="catalogue.php" class="lien04"> Gestion du catalogue </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="caracteristique.php" class="lien04"> Gestion des caract&eacute;ristiques </a><img src="gfx/suivant.gif" width="12" height="9" border="0" />  <?php if( !$id) { ?>Ajouter<?php } else { ?> Modifier <?php } ?> 	                          </p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">MODIFICATION DES CARACTERISTIQUES 
	   <?php
								$langl = new Lang();
								$query = "select * from $langl->table";
								$resul = mysql_query($query);
								while($row = mysql_fetch_object($resul)){
									$langl->charger($row->id);
						    ?>
						  
						  		<a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($id); ?>&lang=<?php echo($langl->id); ?>" class="lien06"><?php echo($langl->description); ?></a>
						  		
						  <?php } ?>
						  
	   </td>
     </tr>
   </table>
      <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="form_modif" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" id="zoneaction" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" id="zoneid" name="id" value="<?php echo($id); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
	
		<table width="710" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                              <td width="250" height="30" class="titre_cellule">TITRE DE LA CARACTERISTIQUE:</td>
                              <td width="440" class="cellule_claire_vide">
                                <input name="titre" type="text" class="form" value="<?php echo($caracteristiquedesc->titre); ?>" />
                              </td>
                            </tr>
                            <tr>
                              <td height="30" class="titre_cellule">CHAPO (resum&eacute; de la description) : </td>
                              <td class="cellule_claire">
                                <textarea name="chapo" cols="40" rows="2" class="form"><?php echo($caracteristiquedesc->chapo); ?></textarea>              
                              </td>
                            </tr>
                            <tr>
                              <td height="30" class="titre_cellule">DESCRIPTION DE LA CARACTERISTIQUE: </td>
                              <td class="cellule_claire">
                                <textarea name="description" cols="40" rows="4" class="form"><?php echo($caracteristiquedesc->description); ?></textarea>
                                
                              </td>
                            </tr>
                            <tr>
                              <td height="30" class="titre_cellule">Affichée: </td>
                              <td class="cellule_claire">
        					 <input name="affiche" type="checkbox" class="form" <?php if($caracteristique->affiche || $id == "" ) { ?> checked="cheked" <?php } ?>/>
                                
                              </td>
                            </tr>
                            <tr>
                              <td height="30" class="cellule_sombre2">&nbsp;</td>
                              <td class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" onclick="document.getElementById('form_modif').submit()" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onclick="document.getElementById('form_modif').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
                            </tr>
        </table>
					
     
	  <table width="710" border="0" cellpadding="5" cellspacing="0">
	
<?php

	if($id != ""){

?>	  


                            <tr>
                              <td width="250" height="30" class="titre_cellule">VALEURS DISPONIBLES :</td>
                              <td width="440" class="cellule_claire_vide">

	<input type="hidden" name="id" value="<?php echo($id); ?>" /> 	
      	<input name="caracdisp" type="text" class="form" size="20" />
                              </td>
                            </tr>
                            <tr class="cellule_sombre2">
                              <td height="30" >&nbsp;</td>
                              <td height="30" >
                              
                      <?php if($lang == "1") { ?>
                              <a href="#" onclick="ajout()" class="txt_vert_11">Ajouter une valeur <img src="gfx/suivant.gif" width="12" height="9" border="0" /></a>
                      <?php } else { ?>
                      		  <a href="#" onclick="maj()" class="txt_vert_11">Mettre à jour <img src="gfx/suivant.gif" width="12" height="9" border="0" /></a>
                      
                      <?php } ?>        
                              
                              <br />
</td>
                            </tr>

							
							<?php
                if(!$lang) $lang=1;

                $query = "select * from $caracdisp->table where caracteristique='$id'";
                $resul = mysql_query($query);
                
                $caracdispdesclang = new Caracdispdesc();
                                
                while($row = mysql_fetch_object($resul)){
                        $query2 = "select * from $caracdispdesc->table where caracdisp='$row->id' and lang='1'";
                        $resul2 = mysql_query($query2);
                        while($row2 = mysql_fetch_object($resul2)){
                                $caracdispdesc->charger($row2->id,1);
                                $caracdispdesclang->charger_caracdisp($row2->caracdisp, $lang);

             ?>
                            <tr class="titre_cellule">
                              <td height="30" align="right">ID : <?php echo($row->id); ?></td>
                              <td height="30" class="cellule_claire_vide" >
							  <table width="100%" border="0">
  <tr>
    <?php if($lang == "1") { ?>
			<td width="30%"><span class="geneva11bol_3B4B5B"><input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($caracdispdesc->titre); ?>" class="form" /></span></td>
		    <td width="31%">
  			  <a href="#" onclick="suppr('<?php echo($row->id); ?>')" class="txt_vert_11">Supprimer <img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a><a href="#" onclick="suppr('<?php echo($row->id); ?>')"></a>
    
    <?php } else { ?>
    	<td width="30%"><span class="geneva11bol_3B4B5B"><?php echo($caracdispdesc->titre); ?></span></td>
	    <td width="31%">
    <input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($caracdispdesclang->titre); ?>" />
    
    <?php
    	}
    ?>
    	</td>
    	
    	
  </tr>
	
</table>


	  						  </td>
                            </tr>
							 <?php
                                        }
                                }
                        ?>
<?php
}
?>
	<tr class="cellule_sombre2">
      <td height="30" >&nbsp;</td>
      <td height="30" >
      
<?php if($lang == "1") { ?>
      <a href="#" onclick="maj()" class="txt_vert_11">Mettre à jour <img src="gfx/suivant.gif" width="12" height="9" border="0" /></a>
<?php } ?>      
      
      <br />
</td>
    </tr>
        </table>
  </form>
 <?php
        admin_inclure("caracteristiquemodifier");
 ?>
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" colspan="2" class="titre_cellule_tres_sombre">Informations sur la carat&eacute;ristique </td>
    </tr>
	<tr>
      <td width="246" height="30" class="titre_cellule">ID : </td>
      <td width="444" class="titre_cellule"><?php echo($caracteristique->id); ?></td>
	</tr>
  </table>
</div>
</body>
</html>

            
