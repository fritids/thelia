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
	if(!isset($parent)) $parent=0;
	if(!isset($lang)) $lang=1;
	if(!isset($tabdisp)) $tabdisp="";
?>
<?php
	include_once("../classes/Declinaison.class.php");
	include_once("../fonctions/divers.php");
	include_once("../lib/JSON.php");

    include_once("../classes/Lang.class.php");  
	include_once("../classes/Declidisp.class.php");
	include_once("../classes/Rubdeclinaison.class.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Cache.class.php");	
?>
<?php

	switch($action){
		case 'modclassement' : modclassement($id, $type); break;
		case 'modifier' : modifier($id, $lang, $titre, $chapo, $description, $tabdisp); break;
		case 'ajouter' : ajouter($lang, $id, $titre, $chapo, $description, $tabdisp); break;
		case 'ajdeclidisp' : ajdeclidisp($id, $declidisp, $lang); break;
		case 'majdeclidisp' : majdeclidisp($id, $lang); break;
		case 'supprimer' : supprimer($id, $parent); break;
		case 'suppdeclidisp' : suppdeclidisp($declidisp);
	}
	
?>
<?php

	function modclassement($id, $type){
      	$dec = new Declinaison();
        $dec->charger($id);
        $dec->changer_classement($id, $type);

		
	    header("Location: declinaison.php");

	}

	function modifier($id, $lang, $titre, $chapo, $description, $tabdisp){
	 		
		$json = new Services_JSON();

		if(!$lang) $lang=1;

		$declidisp = new Declidisp();
		$declidispdesc = new Declidispdesc();

		$declinaison = new Declinaison();
		$declinaisondesc = new Declinaisondesc();
		$declinaison->charger($id);
		$res = $declinaisondesc->charger($declinaison->id, $lang);	

		if(!$res){
			$temp = new Declinaisondesc();
			$temp->declinaison=$declinaison->id;
			$temp->lang=$lang;
			$temp->add();
			$declinaisondesc->charger($declinaison->id, $lang);
		
		}
			
			                 
	    $declinaisondesc->chapo = $chapo;
	    $declinaisondesc->description = $description;
	    $declinaisondesc->titre = $titre;
	 	 
        $declinaisondesc->chapo = ereg_replace("\n", "<br/>", $declinaisondesc->chapo);
   	    $declinaisondesc->description = ereg_replace("\n", "<br/>", $declinaisondesc->description);
										
		$declinaison->maj();
		$declinaisondesc->maj();

		$cache = new Cache();
		$cache->vider("DECLINAISON", "%");		
		$cache->vider("DECLIDISP", "%");
		$cache->vider("DECVAL", "%");
		$cache->vider("PRODUIT", "%");
			   
	    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $declinaison->id);
	}

	function ajouter($lang, $id, $titre, $chapo, $description, $tabdisp){

	 $json = new Services_JSON();
	 $tabdisp = stripslashes($tabdisp);
	 $tabdisp = $json->decode($tabdisp);

	 $declinaison = new Declinaison();
	 $declinaison->charger($id);
	 
   	 if($declinaison->id) return;

	 $declinaison = new Declinaison();

	 $declinaison = new Declinaison();

	 $query = "select max(classement) as maxClassement from $declinaison->table";

	 $resul = mysql_query($query, $declinaison->link);
     $maxClassement = mysql_result($resul, 0, "maxClassement");


	 $declinaison->id = $id;
	 $declinaison->classement =  $maxClassement + 1;

	 $lastid = $declinaison->add();

	 $declinaisondesc = new Declinaisondesc();	

	 $declinaisondesc->chapo = $chapo;
	 $declinaisondesc->description = $description;
	 $declinaisondesc->declinaison = $lastid;
	 $declinaisondesc->lang = 1;
	 $declinaisondesc->titre = $titre;

	 $declinaisondesc->chapo = ereg_replace("\n", "<br/>", $declinaisondesc->chapo);
     $declinaisondesc->description = ereg_replace("\n", "<br/>", $declinaisondesc->description);		
	 
	 $declinaisondesc->add();
	
     $declidisp = new Declidisp();
	 $declidispdesc = new Declidispdesc();

	for($i=0; $i<count($tabdisp); $i++){
		
		$declidisp->declinaison = $lastid;
		$lastidc = $declidisp->add();

		$declidispdesc->declidisp = $lastidc;
		$declidispdesc->lang = 1;
		$declidispdesc->titre = $tabdisp[$i]->texte;
		$declidispdesc->add();

	}

	 $rubrique = new Rubrique();
	 $query = "select * from $rubrique->table";
	 $resul = mysql_query($query, $rubrique->link);
	 
	 while($row = mysql_fetch_object($resul)){
		$rubdeclinaison = new Rubdeclinaison();
		$rubdeclinaison->rubrique = $row->id;
		$rubdeclinaison->declinaison = $lastid;
		$rubdeclinaison->add();
	 }
		
	$cache = new Cache();
	$cache->vider("DECLINAISON", "%");		
	$cache->vider("DECLIDISP", "%");
	$cache->vider("DECVAL", "%");
	$cache->vider("PRODUIT", "%");
		
	header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $lastid);

	}
	
	function supprimer($id, $parent){

		$declidisp = new Declidisp();
		$rubdeclinaison = new Rubdeclinaison();
		
		$query = "select * from $declidisp->table where declinaison='$id'";
		$resul = mysql_query($query, $declidisp->link);

		while($row = mysql_fetch_object($resul)){
			$declidisp->charger($row->id);
			$declidisp->supprimer();
		}
		
		$query = "delete from $rubdeclinaison->table where declinaison='$id'";
		$resul = mysql_query($query, $rubdeclinaison->link);
						
		$declinaison = new Declinaison();		
		$declinaison->charger($id);
		$declinaison->supprimer();

		$cache = new Cache();
		$cache->vider("DECLINAISON", "%");		
		$cache->vider("DECLIDISP", "%");
		$cache->vider("DECVAL", "%");
		$cache->vider("PRODUIT", "%");
		
	    header("Location: declinaison.php");

	}

	function suppdeclidisp($declidisp){
                $tdeclidisp = new Declidisp();	
		$tdeclidisp->charger($declidisp);
		$tdeclidisp->supprimer();
		
		$cache = new Cache();
		$cache->vider("DECLINAISON", "%");		
		$cache->vider("DECLIDISP", "%");
		$cache->vider("DECVAL", "%");
		$cache->vider("PRODUIT", "%");
	}

	function ajdeclidisp($id, $declidisp, $lang){
	
		if(!$lang) $lang=1;
		
		$tdeclidisp = new Declidisp();
		$tdeclidisp->declinaison = $id;
		$res = $tdeclidisp->add();

		$tdeclidispdesc = new Declidispdesc();
		$tdeclidispdesc->declidisp = $res;
		$tdeclidispdesc->lang = $lang;
		$tdeclidispdesc->titre = $declidisp;
		
		$tdeclidispdesc->add();

		$cache = new Cache();
		$cache->vider("DECLINAISON", "%");		
		$cache->vider("DECLIDISP", "%");
		$cache->vider("DECVAL", "%");
		$cache->vider("PRODUIT", "%");
		
	}
	
	function majdeclidisp($id, $lang){
		$declidisp = new Declidisp();
		$declidispdesc = new Declidispdesc();
		
		$query = "select * from $declidisp->table where declinaison='$id'";
		$resul = mysql_query($query, $declidisp->link);
		
		while($row = mysql_fetch_object($resul)){
			
			$var = $lang . "_" . $row->id;
			
			global $$var;
			
			$existe = $declidispdesc->charger_declidisp($row->id, $lang);
			
			$declidispdesc->declidisp = $row->id;
			$declidispdesc->lang = $lang;
			$declidispdesc->titre = $$var;

			if( ! $existe )
				$declidispdesc->add();	

			else $declidispdesc->maj();
			
			
		}	

		$cache = new Cache();
		$cache->vider("DECLINAISON", "%");		
		$cache->vider("DECLIDISP", "%");
		$cache->vider("DECVAL", "%");
		$cache->vider("PRODUIT", "%");		
		
	}	
?>
<?php
	$declinaison = new Declinaison();
	$declinaisondesc = new Declinaisondesc();
	
	$declinaison->charger($id);
	$declinaisondesc->charger($declinaison->id, $lang);
	
	$declinaisondesc->chapo = ereg_replace("<br/>", "\n", $declinaisondesc->chapo);
	$declinaisondesc->description = ereg_replace("<br/>", "\n", $declinaisondesc->description);
	
	$declidisp = new Declidisp();
	$declidispdesc = new Declidispdesc();

	if(!$lang) $lang=1;
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

	  	 document.getElementById('zoneaction').value='ajdeclidisp';	
	  	 document.getElementById('form_modif').submit();
	 }
	 
	 else alert("Veuillez d'abord creer votre declinaison"); 	 
	   
		  }

	  function maj(){
	 
		  if(document.getElementById('zoneid').value != ""){

	  	 	document.getElementById('zoneaction').value='majdeclidisp';	
	  	 	document.getElementById('form_modif').submit();
		 }
	 
		  else alert("Veuillez d'abord creer votre declinaisons"); 	 
	   
	  }
	  
	  function suppr(declidisp){
	  	if(confirm("Voulez-vous vraiment supprimer cette entree ?")) location="<?php echo($_SERVER['PHP_SELF'] ); ?>?id=<?php echo($id); ?>&action=suppdeclidisp&declidisp=" + declidisp;
	  
	  }	
	  
</script>
</head>

<body>

<?php
	$menu="catalogue";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des declinaisons</p>
   <p align="right" class="geneva11Reg_3B4B5B"><span class="lien04"><a href="accueil.php" class="lien04">Accueil</a></span>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="catalogue.php" class="lien04">Gestion du catalogue</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="declinaison.php" class="lien04"> Gestion des declinaisons </a><img src="gfx/suivant.gif" width="12" height="9" border="0" />  <?php if( !$id) { ?>Ajouter<?php } else { ?> Modifier <?php } ?> 	                          </p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">MODIFICATION DES DECLINAISONS 
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
                              <td width="250" height="30" class="titre_cellule">TITRE DE LA DECLINAISON:</td>
                              <td width="440" class="cellule_claire_vide">
                                <input name="titre" type="text" class="form" value="<?php echo($declinaisondesc->titre); ?>" />
                              </td>
                            </tr>
                            <tr>
                              <td height="30" class="titre_cellule">CHAPO (resum&eacute; de la description) : </td>
                              <td class="cellule_claire">
                                <textarea name="chapo" cols="40" rows="2" class="form"><?php echo($declinaisondesc->chapo); ?></textarea>              
                              </td>
                            </tr>
                            <tr>
                              <td height="30" class="titre_cellule">DESCRIPTION DE LA DECLINAISON: </td>
                              <td class="cellule_claire">
                                <textarea name="description" cols="40" rows="4" class="form"><?php echo($declinaisondesc->description); ?></textarea>
                                
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
      	<input name="declidisp" type="text" class="form" size="20" />
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
</td>                            </tr>
					
							<?php
                if(!$lang) $lang=1;

                $query = "select * from $declidisp->table where declinaison='$id'";
                $resul = mysql_query($query);
                
                $declidispdesclang = new Declidispdesc();
                
                while($row = mysql_fetch_object($resul)){
                        $query2 = "select * from $declidispdesc->table where declidisp='$row->id' and lang='1'";
                        $resul2 = mysql_query($query2);
                        while($row2 = mysql_fetch_object($resul2)){
                                $declidispdesc->charger($row2->id, 1);
                                $declidispdesclang->charger_declidisp($row2->declidisp, $lang);

             ?>
                            <tr class="titre_cellule">
                              <td height="30" align="right">ID : <?php echo($row->id); ?></td>
                              <td height="30" class="cellule_claire_vide" >
							  <table width="100%" border="0">
  <tr>
    
    <?php if($lang == "1") { ?>
				<td width="30%"><span class="geneva11bol_3B4B5B"><input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($declidispdesc->titre); ?>" class="form" /></span></td>
			    <td width="31%">
  			  <a href="#" onclick="suppr('<?php echo($row->id); ?>')" class="txt_vert_11">Supprimer <img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a><a href="#" onclick="suppr('<?php echo($row->id); ?>')"></a>
    
    <?php } else { ?>
    	<td width="30%"><span class="geneva11bol_3B4B5B"><?php echo($declidispdesc->titre); ?></span></td>
	    <td width="31%">
    <input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($declidispdesclang->titre); ?>" />
    
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
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" colspan="2" class="titre_cellule_tres_sombre">Informations sur la d&eacute;clinaison </td>
    </tr>
	<tr>
      <td width="246" height="30" class="titre_cellule">ID : </td>
      <td width="444" class="titre_cellule"><?php echo($declinaison->id); ?></td>
	</tr>
  </table>
</div>
</body>
</html>
