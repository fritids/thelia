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

	    header("Location: declinaison.php");

	}

	function suppdeclidisp($declidisp){
                $tdeclidisp = new Declidisp();	
		$tdeclidisp->charger($declidisp);
		$tdeclidisp->supprimer();

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
		
	}
	
	function majdeclidisp($id, $lang){
		$declidisp = new Declidisp();
		
		$query = "select * from $declidisp->table where declinaison='$id'";
		$resul = mysql_query($query, $declidisp->link);
		
		while($row = mysql_fetch_object($resul)){
			$declidispdesc = new Declidispdesc();

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
<?php include_once("title.php");?>
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
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p align="left"><span class="lien04"><a href="accueil.php" class="lien04">Accueil</a></span>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="declinaison.php" class="lien04"> Gestion des declinaisons </a><img src="gfx/suivant.gif" width="12" height="9" border="0" />  <?php if( !$id) { ?>Ajouter<?php } else { ?> Modifier <?php } ?></p>

<!-- bloc déclinaisons / colonne gauche -->  
<div id="bloc_description">
<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="form_modif" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" id="zoneaction" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" id="zoneid" name="id" value="<?php echo($id); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
<div class="entete_liste_config">
	<div class="titre">MODIFICATION DES DECLINAISONS</div>
	<div class="fonction_valider"><a href="#" onclick="document.getElementById('form_modif').submit()">VALIDER LES MODIFICATIONS</a></div>
</div>
    
    <!-- bloc descriptif de la déclinaison --> 			
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
        <td class="designation">Titre de la déclinaison</td>
        <td><input name="titre" id="titre" type="text" class="form_long" value="<?php echo($declinaisondesc->titre); ?>"/></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Chapo<br /><span class="note">(courte description d'introduction)</span></td>
        <td> <textarea name="chapo" id="chapo" cols="40" rows="2" class="form_long"><?php echo($declinaisondesc->chapo); ?></textarea></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Description<br /><span class="note">(description complète)</span></td>
        <td><textarea name="description" id="description" cols="53" rows="2" class="form"><?php echo($declinaisondesc->description); ?></textarea></td>
   	</tr>
</table>
<div class="patchplugin">
<?php 
	admin_inclure("declinaisonmodifier"); 
?>
</div>
<?php if($id != ""){ ?>
<div class="entete_liste_config">
	<div class="titre">INFORMATIONS SUR LA DECLINAISON</div>
</div>
<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
    	<th class="designation" style="width:134px;">ID</th>
        <th><?php echo($declinaison->id); ?></th>
   	</tr>
</table>
	<?php } ?>
</div>
<!-- fin du bloc de description / colonne de gauche -->
<?php if($id != ""){ ?>
  <!-- bloc de gestion des valeurs de la déclinaison / colonne de droite-->   
<div id="bloc_colonne_droite">
	<div class="entete_config">
		<div class="titre">AJOUTER UNE VALEUR</div>
	</div>
	<!-- bloc d'ajout des valeurs -->
			<ul class="ligne1">
				<li>
					<input type="hidden" name="id" value="<?php echo($id); ?>" /> 	
      				<input name="declidisp" type="text" class="form_inputtext" />
				</li>
				<li><a href="#" onclick="ajout()">AJOUTER</a></li>
			</ul>
	
	
	<div class="entete_config" style="margin:10px 0 0 0;">
		<div class="titre">VALEURS DISPONIBLES</div>
		
		<div class="maj">
      		<a href="#" onclick="maj()">METTRE A JOUR</a>
     	</div>
		
	</div>
	<!-- bloc des valeurs disponibles -->
	 		<?php
                if(!$lang) $lang=1;

                $query = "select * from $declidisp->table where declinaison='$id'";
                $resul = mysql_query($query);
                
                $declidispdesclang = new Declidispdesc();
                 	$i=0;  
                while($row = mysql_fetch_object($resul)){
                        $query2 = "select * from $declidispdesc->table where declidisp='$row->id' and lang='1'";
                        $resul2 = mysql_query($query2);
                        while($row2 = mysql_fetch_object($resul2)){
                                $declidispdesc->charger($row2->id, 1);
                                $declidispdesclang->charger_declidisp($row2->declidisp, $lang);
                                
                                if(!($i%2)) $fond="claire";
  								else $fond="fonce";
  								$i++;

            ?>

			<ul class="<?php echo($fond); ?>">
				<li style="width:50px;">ID : <?php echo($row->id); ?></li>
				<li><?php if($lang == "1") { ?>
				<input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($declidispdesc->titre); ?>" class="form_court" />
				<li style="text-align:right; width:170px;">
  			  	<a href="#" onclick="suppr('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a>
    
    			<?php } else { ?>
	   				<li><input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($declidispdesclang->titre); ?>" class="form_court" /></li>
	   				<li style="text-align:left; width:170px; overflow:hidden;"><?php echo($declidispdesc->titre); ?></li>
   				<?php } ?>
				</li>
			</ul>
			 <?php
              }
             }
             ?>

</div>
<!-- fin du bloc colonne de droite -->
<?php } ?> 
    </form>


</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
