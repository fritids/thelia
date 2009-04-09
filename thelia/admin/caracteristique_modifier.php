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
<?php include_once("title.php"); ?>
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
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
	
	if(!$lang) $lang=1;
?>

<div id="contenu_int"> 
   <p align="left"><span class="lien04"><a href="accueil.php" class="lien04">Accueil</a></span> <a href="#" onclick="document.getElementById('form_modif').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="configuration.php" class="lien04"> Configuration </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="caracteristique.php" class="lien04"> Gestion des caract&eacute;ristiques </a><img src="gfx/suivant.gif" width="12" height="9" border="0" />  <?php if( !$id) { ?>Ajouter<?php } else { ?> Modifier <?php } ?></p>

<!-- bloc caractéristiques /colonne gauche -->   
<div id="bloc_description">
 <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="form_modif" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" id="zoneaction" value="<?php if(!$id) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" id="zoneid" name="id" value="<?php echo($id); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
	<!-- bloc entete des caractéristiques -->
<div class="entete_liste_config">
	<div class="titre">MODIFICATION DES CARACTERISTIQUES</div>
	<div class="fonction_valider"><a href="#" onclick="document.getElementById('form_modif').submit()">VALIDER LES MODIFICATIONS</a></div>
</div>
     
<!-- bloc descriptif de la caractéristique --> 			
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
        <td class="designation">Titre de la caractéristique</td>
        <td><input name="titre" id="titre" type="text" class="form_long" value="<?php echo($caracteristiquedesc->titre); ?>"/></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Chapo<br /><span class="note">(courte description d'introduction)</span></td>
        <td> <textarea name="chapo" id="chapo" cols="40" rows="2" class="form_long"><?php echo($caracteristiquedesc->chapo); ?></textarea></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Description<br /><span class="note">(description complète)</span></td>
        <td><textarea name="description" id="description" cols="53" rows="2" class="form"><?php echo($caracteristiquedesc->description); ?></textarea></td>
   	</tr>
	<tr class="claire">
        <td class="designation">Visible</td>
        <td>
        <input name="affiche" type="checkbox" class="form" <?php if($caracteristique->affiche || $id == "" ) { ?> checked="cheked" <?php } ?>/><span class="note">(permet de rendre visible ou non cette caractéristique à l'affichage dans une boucle)</span></td>
    </tr>
</table>
	<?php if($id != ""){ ?>
<div class="entete_liste_config">
	<div class="titre">INFORMATIONS SUR LA CARACTERISTIQUE</div>
</div>
<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
    	<th class="designation" style="width:134px;">ID</th>
        <th><?php echo($caracteristique->id); ?></th>
   	</tr>
</table>
	<?php } ?>
  </div>
<!-- fin du bloc de description / colonne de gauche -->
<?php if($id != ""){ ?>
  <!-- bloc de gestion des valeurs de la caractéristique / colonne de droite-->   
<div id="bloc_colonne_droite">
	<div class="entete_config">
		<div class="titre">AJOUTER UNE VALEUR</div>
	</div>
	<!-- bloc d'ajout des valeurs -->
			<ul class="ligne1">
				<li>
					<input type="hidden" name="id" value="<?php echo($id); ?>" /> 	
      				<input name="caracdisp" type="text" class="form_inputtext" />
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

                $query = "select * from $caracdisp->table where caracteristique='$id'";
                $resul = mysql_query($query);
                
                $caracdispdesclang = new Caracdispdesc();
                  $i=0;              
                while($row = mysql_fetch_object($resul)){
                        $query2 = "select * from $caracdispdesc->table where caracdisp='$row->id' and lang='1'";
                        $resul2 = mysql_query($query2);
                 		
                        while($row2 = mysql_fetch_object($resul2)){
                                $caracdispdesc->charger($row2->id,1);
                                $caracdispdesclang->charger_caracdisp($row2->caracdisp, $lang);
				
								if(!($i%2)) $fond="claire";
  								else $fond="fonce";
  								$i++;

             ?>

			<ul class="<?php echo($fond); ?>">
				<li style="width:50px;">ID : <?php echo($row->id); ?></li>
				<li><?php if($lang == "1") { ?>
				<input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($caracdispdesc->titre); ?>" class="form_court" /></li>
				<li style="text-align:right; width:170px;">
  			  	<a href="#" onclick="suppr('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a>
    
    			<?php } else { ?>
    				
	   				<li><input type="text" name="<?php echo($lang); ?>_<?php echo($row->id); ?>" value="<?php echo($caracdispdesclang->titre); ?>" class="form_court" /></li>
	   				<li style="text-align:left; width:170px; overflow:hidden;"><?php echo($caracdispdesc->titre); ?></li>
   				<?php
    				}
    			?>
				</li>
			</ul>
			 <?php
              }
             }
             ?>

 <?php 
	admin_inclure("caracteristiquemodifier"); 
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

            
