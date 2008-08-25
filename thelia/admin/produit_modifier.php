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
	if(!isset($page)) $page="";
	if(!isset($id)) $id="";
	if(!isset($promo)) $promo="";
	if(!isset($reappro)) $reappro="";
	if(!isset($nouveaute)) $nouveaute="";
	if(!isset($perso)) $perso="";
	if(!isset($appro)) $appro="";
	if(!isset($ref)) $ref="";
	if(!isset($ligne)) $ligne="";
	
?>
<?php
	 include_once("../classes/Variable.class.php");  
?>
<?php
	include_once("../classes/Rubrique.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Image.class.php");
    include_once("../classes/Document.class.php");  
    include_once("../classes/Accessoire.class.php");  
    include_once("../classes/Transzone.class.php");  
    include_once("../classes/Zone.class.php");  
    include_once("../classes/Pays.class.php");  
    include_once("../classes/Lang.class.php");  
    include_once("../classes/Rubcaracteristique.class.php");  
    include_once("../classes/Caracteristique.class.php");  
    include_once("../classes/Caracval.class.php"); 
    include_once("../classes/Caracdisp.class.php"); 
    include_once("../classes/Declidisp.class.php");
    include_once("../classes/Rubdeclinaison.class.php");  
    include_once("../classes/Declinaison.class.php");  
    include_once("../classes/Exdecprod.class.php");  
    include_once("../classes/Stock.class.php");  
?>
<?php

	switch($action){
		case 'modclassement' : modclassement($ref, $parent, $type); break;
		case 'modifier' : modifier($id, $lang, $ref, $prix, $ecotaxe, $promo, $reappro, $prix2, $rubrique, $nouveaute, $perso, $appro, $poids, $stock, $tva, $ligne, $garantie, $titre, $chapo, $description, $postscriptum); break;
		case 'ajouter' : ajouter($lang, $ref, $prix, $ecotaxe, $promo, $reappro, $prix2, $rubrique, $nouveaute, $perso, $appro, $poids, $stock, $tva, $ligne, $garantie, $titre, $chapo, $description, $postscriptum); break;
		case 'acdec' : moddecli($produit, $id, 1); break;
		case 'desdec' : moddecli($produit, $id, 0); break;
		case 'supprimer' : supprimer($ref, $parent);

	}
	

?>
<?php
	function modclassement($ref, $parent, $type){

		$produit = new Produit();
		$produit->charger($ref);

	 	$query = "select max(classement) as maxClassement from $produit->table where rubrique='" . $parent . "'";

		$resul = mysql_query($query, $produit->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");


		if($type=="M"){
			if($produit->classement == 1) { header("Location: parcourir.php?parent=" . $parent); return; }

			$query = "update $produit->table set classement=" . $produit->classement . " where classement=" . ($produit->classement-1) . " and rubrique='" . $parent . "'";

			$resul = mysql_query($query, $produit->link);
			
			 $produit->classement--;
		}
		
		else if($type=="D"){

			if($produit->classement == $maxClassement) { header("Location: parcourir.php?parent=" . $parent); return; }

			
			$query = "update $produit->table set classement=" . $produit->classement . " where classement=" . ($produit->classement+1) . " and rubrique='" . $parent . "'";
			$resul = mysql_query($query, $produit->link);
			
			 $produit->classement++;
		}
		
		$produit->maj();

	    header("Location: parcourir.php?parent=" . $parent);
	}
	
	function modifier($id, $lang, $ref, $prix, $ecotaxe, $promo, $reappro, $prix2, $rubrique, $nouveaute, $perso, $appro, $poids, $stock, $tva, $ligne, $garantie, $titre, $chapo, $description, $postscriptum){

     
		if(!$lang) $lang=1;
		
		$produit = new Produit();
		$produitdesc = new Produitdesc();
		$produit->charger($ref);
		$res = $produitdesc->charger($produit->id, $lang);	


		if(!$res){
			$temp = new Produitdesc();
			$temp->produit=$produit->id;
			$temp->lang=$lang;
			$temp->add();
			$produitdesc->charger($produit->id, $lang);
		
		}

		 $produit->datemodif = date("Y-m-d H:i:s");		
		 $produit->prix = $prix; 
		 $produit->prix2 = $prix2;
		 $produit->ecotaxe = $ecotaxe;
			
		if($produit->rubrique != $rubrique){
			$query = "select max(classement) as maxClassement from $produit->table where rubrique='" . $rubrique . "'";
			$resul = mysql_query($query, $produit->link);
			$produit->classement =  mysql_result($resul, 0, "maxClassement") + 1;
		}
		
		 $produit->rubrique = $rubrique; 
	 	 if($promo == "on") $produit->promo = 1; else $produit->promo = 0;
	 	 if($reappro == "on") $produit->reappro = 1; else $produit->reappro = 0;	 	 
	 	 if($nouveaute == "on") $produit->nouveaute = 1; else $produit->nouveaute = 0;
	 	 if($ligne == "on") $produit->ligne = 1; else $produit->ligne = 0;
		 $produit->garantie = $garantie;  
		 $produit->perso = $perso;  
		 $produit->appro = $appro;  
		 $produit->poids = $poids;
		 $produit->stock = $stock;
		 $produit->tva = ereg_replace(",", ".", $tva);
		 $produitdesc->chapo = $chapo;
		 $produitdesc->description = $description;
		 $produitdesc->postscriptum = $postscriptum;
		 $produitdesc->titre = $titre;
	 	 
	 	 $produitdesc->chapo = ereg_replace("\n", "<br />", $produitdesc->chapo);
			
	
		$rubcaracteristique = new Rubcaracteristique();
 	  	$caracteristiquedesc = new Caracteristiquedesc();
  	 	$caracval = new Caracval();
   	
   	
   		$query = "select * from $rubcaracteristique->table where rubrique='" . $produit->rubrique . "'";
   		$resul = mysql_query($query);			
		
		
		
		while($row = mysql_fetch_object($resul)){
			$caracval = new Caracval();
			$deb="caract";
			$deb2="typecaract";
			
			$val=$row->caracteristique;
			$var = $deb.$val;
			$var2 = $deb2.$val;
			
			global $$var;
			global $$var2;
			
			$query2 = "delete from $caracval->table where produit='" . $produit->id . "' and caracteristique='" . $row->caracteristique . "'";
			$resul2 = mysql_query($query2);
			
	
		
			if($$var2 == "c" && $$var != "")
	  			foreach($$var as $selectval) {
   					if($selectval != ""){
						$caracval->produit = $produit->id;
						$caracval->caracteristique = $row->caracteristique;
						$caracval->caracdisp = $selectval;
						$caracval->add();
					}
			}
			
			else if($$var != "") {
				$caracval->produit = $produit->id;
				$caracval->caracteristique = $row->caracteristique;
				$caracval->valeur = $$var;
				$caracval->add();
			}
		}	
															
		$produit->maj();
		$produitdesc->maj();



  	$rubdeclinaison = new Rubdeclinaison();
   	$declinaisondesc = new Declinaisondesc();
   	$declidisp = new Declidisp();
    $declidispdesc = new Declidispdesc();

   	$query = "select * from $rubdeclinaison->table where rubrique='" . $rubrique . "'";
   	$resul = mysql_query($query);

	$nb = 0;
  	
   	while($row = mysql_fetch_object($resul)){

   		$declinaisondesc->charger($row->declinaison);

   		
   		$query2 = "select * from $declidisp->table where declinaison='$row->declinaison'";
   		$resul2 = mysql_query($query2);
   		$nbres = mysql_num_rows($resul2);
		
          while($row2 = mysql_fetch_object($resul2)){
               	$var="stock" . $row2->id;
				$var2="surplus" . $row2->id;
          		global $$var, $$var2;
          		
                   $stock = new Stock();
                   
                   if ($stock->charger($row2->id,$produit->id) == 0) {
                     $stock->declidisp=$row2->id;
             		 $stock->produit=$produit->id;
             		 $stock->valeur=$$var; 
             		 $stock->surplus=$$var2; 
               		 $stock->add();
					$nb += $stock->valeur;
                   } 
                   
                   else {
                   		$stock->valeur=$$var; 
						$stock->surplus=$$var2; 
                   		$stock->maj();	
						$nb += $stock->valeur;
                   	}
                
                }
		

	}

	if($nb)
		$produit->stock = $nb;
			
	$produit->maj();
	
	modules_fonction("modprod", $produit->ref);
	
	    header("Location: " . $_SERVER['PHP_SELF'] . "?ref=" . $produit->ref . "&rubrique=" . $produit->rubrique);
	}

	function ajouter($lang, $ref, $prix, $ecotaxe, $promo, $reappro, $prix2, $rubrique, $nouveaute, $perso, $appro, $poids, $stock, $tva, $ligne, $garantie, $titre, $chapo, $description, $postscriptum){
  

	 $ref = ereg_replace(" ", "", $ref);
	 $ref = ereg_replace("/", "", $ref);
	 $ref = ereg_replace("\+", "", $ref);
	 $ref = ereg_replace("\.", "-", $ref);
	 $ref = ereg_replace(",", "-", $ref);
	 $ref = ereg_replace(";", "-", $ref); 
	 $ref = ereg_replace("'", "", $ref); 
	 $ref = ereg_replace("\n", "", $ref); 
	 $ref = ereg_replace("\"", "", $ref); 	 	 
	 
	 $produit = new Produit();
	 $produit->charger($ref);
	 
   	 if($produit->id){
		header("Location: produit_modifier.php?rubrique=$rubrique&existe=1");
  	 	exit;
     }
   	 
	 $produit = new Produit();

	 $query = "select max(classement) as maxClassement from $produit->table where rubrique='" . $rubrique . "'";

	 $resul = mysql_query($query, $produit->link);
     $maxClassement = mysql_result($resul, 0, "maxClassement");

	 $produit->ref = $ref;
	 $produit->datemodif = date("Y-m-d H:i:s"); 
	 $produit->prix = $prix; 
	 $produit->prix2 = $prix2;
	 if($produit->prix2 == "") $produit->prix2 = $prix;
	 $produit->ecotaxe = $ecotaxe;
	 $produit->rubrique = $rubrique; 
	 if($promo == "on") $produit->promo = 1; else $produit->promo = 0;
	 if($reappro == "on") $produit->reappro = 1; else $produit->reappro = 0;
	 if($nouveaute == "on") $produit->nouveaute = 1; else $produit->nouveaute = 0;
	 if($ligne == "on") $produit->ligne = 1; else $produit->ligne = 0;
	 $produit->garantie = $garantie;  
	 $produit->perso = $perso;  
	 $produit->appro = $appro;  
	 $produit->poids = $poids;
	 $produit->stock = $stock;
	 $produit->tva = ereg_replace(",", ".", $tva);
	 $produit->classement = $maxClassement + 1;
	 
	 $lastid = $produit->add();
	
	 $produitdesc = new Produitdesc();	

	 $produitdesc->chapo = $chapo;
	 $produitdesc->description = $description;
	 $produitdesc->postscriptum = $postscriptum;	
	 $produitdesc->produit = $lastid;
	 $produitdesc->lang = 1;
	 $produitdesc->titre = $titre;

	 $produitdesc->chapo = ereg_replace("\n", "<br />", $produitdesc->chapo);
	 
	 $produitdesc->add();
	
	
		$rubcaracteristique = new Rubcaracteristique();
 	  	$caracteristiquedesc = new Caracteristiquedesc();
  	 	$caracval = new Caracval();
   	
   	
   		$query = "select * from $rubcaracteristique->table where rubrique='" . $produit->rubrique . "'";
   		$resul = mysql_query($query);			
		
		while($row = mysql_fetch_object($resul)){
			$caracval = new Caracval();
			$deb="caract";
			$deb2="typecaract";
			
			$val=$row->caracteristique;
			$var = $deb.$val;
			$var2 = $deb2.$val;
			
			global $$var;
			global $$var2;
			
			$query2 = "delete from $caracval->table where produit='" . $produit->id . "' and caracteristique='" . $row->caracteristique . "'";
			$resul2 = mysql_query($query2);
	
			if($$var != "")

			if($$var2 == "c")
	  			foreach($$var as $selectval) {
   				
					$caracval->produit = $lastid;
					$caracval->caracteristique = $row->caracteristique;
					$caracval->caracdisp = $selectval;
					$caracval->add();
			}
			
			else {
				$caracval->produit = $lastid;
				$caracval->caracteristique = $row->caracteristique;
				$caracval->valeur = $$var;
				$caracval->add();
			}
		}			
	
	
	$rubdeclinaison = new Rubdeclinaison();
   	$declinaisondesc = new Declinaisondesc();
   	$declidisp = new Declidisp();
    $declidispdesc = new Declidispdesc();
 	
   	$query = "select * from $rubdeclinaison->table where rubrique='" . $rubrique . "'";
   	$resul = mysql_query($query);

  	
   	while($row = mysql_fetch_object($resul)){

   		$declinaisondesc->charger($row->declinaison);

   		
   		$query2 = "select * from $declidisp->table where declinaison='$row->declinaison'";
   		$resul2 = mysql_query($query2);
   		$nbres = mysql_num_rows($resul2);
  
          while($row2 = mysql_fetch_object($resul2)){
     		 	$stock = new Stock();
     		 	$stock->declidisp=$row2->id;
				$stock ->produit=$lastid;
				$stock->valeur=0;
				$stock->surplus=0;
    			$stock->add();
			}
		

	}

  header("Location: " . $_SERVER['PHP_SELF'] . "?ref=" . $produit->ref . "&rubrique=" . $produit->rubrique);

	}


	function moddecli($produit, $declidisp, $type){

		$exdecprod = new Exdecprod();
		if(! $type) {

			$exdecprod->produit = $produit;
			$exdecprod->declidisp = $declidisp;
			$exdecprod->add();
		}
		
		else {
			$exdecprod->charger($produit, $declidisp);
			$exdecprod->delete();
			
		}

				
	}
	
	function supprimer($ref, $parent){
		
		$produit = new Produit();		
		$produit->charger($ref);
		$produit->supprimer();
	
		$stock = new Stock();
		$query = "delete from $stock->table where produit='" . $produit->id . "'"; 
		$resul = mysql_query($query, $stock->link);
				
	    header("Location: parcourir.php?parent=" . $parent);

	}
	
?>
<?php
	$produit = new Produit();
	$produitdesc = new Produitdesc();
	
	$produit->charger($ref);
	$produitdesc->charger($produit->id, $lang);

	$produitdesc->chapo = ereg_replace("<br />", "\n", $produitdesc->chapo);
	
	if($produit->tva == ""){
		$tvar = new Variable();
		$tvar->charger("tva");
		$tva = $tvar->valeur;
	
	}
	else $tva=$produit->tva;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
 
<script type="text/javascript">
	function envoyer(){
		if(document.getElementById('ref_c').value == "") alert("Veuillez entrer une reference");
		else document.getElementById('formulaire').submit();
	}
</script>

<?php include_once("tinymce.php"); ?>

<?php
	if(isset($existe) && $existe == "1"){
?>
	<script type="text/javascript">
		alert("La reference est deja utilisee");
	</script>
<?php
		
	}
?>

</head>

<body>

<?php
	$menu="catalogue";
	include_once("entete.php");
?>
<div id="contenu_int">
  <p class="titre_rubrique">Description g&eacute;n&eacute;rale du produit</p>
  <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="catalogue.php" class="lien04">Gestion du catalogue</a>               
   
    <?php
                    $parentdesc = new Rubriquedesc();
					$parentdesc->charger($rubrique);
					
					$parentnom = $parentdesc->titre;	
					
					$res = chemin($rubrique);
					$tot = count($res)-1;
	
?>
                             
			<?php
				if($rubrique){
			
			?>	
					<img src="gfx/suivant.gif" width="12" height="9" border="0" />
			<?php	
				}
				while($tot --){
			?><a href="parcourir.php?parent=<?php echo($res[$tot+1]->rubrique); ?>" class="lien04"><?php echo($res[$tot+1]->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />                             
            <?php
            	}
            
            ?>
            
			<?php
                    $parentdesc = new Rubriquedesc();
					$parentdesc->charger($rubrique);
					$parentnom = $parentdesc->titre;	
					
			?>
			<a href="parcourir.php?parent=<?php echo($parentdesc->rubrique); ?>" class="lien04"><?php echo($parentdesc->titre); ?></a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /> 

			 <?php if( $ref) { ?>
			 
			<a href="#" class="lien04"><?php echo($produitdesc->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" />&nbsp;
           Modifier<?php } else { ?> Ajouter <?php } ?> </p>	                         
   <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="formulaire" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" value="<?php if(!$ref) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" name="ref" value="<?php echo($ref); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
 	<input type="hidden" name="rubrique" value="<?php echo($produit->rubrique); ?>" /> 
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre">Description g&eacute;n&eacute;rale du produit &nbsp; 
							<?php
								$langl = new Lang();
								$query = "select * from $langl->table";
								$resul = mysql_query($query);
								while($row = mysql_fetch_object($resul)){
									$langl->charger($row->id);
						    ?>
						  
						  		 &nbsp; <a href="<?php echo($_SERVER['PHP_SELF']); ?>?ref=<?php echo($ref); ?>&rubrique=<?php echo($rubrique); ?>&lang=<?php echo($langl->id); ?>"  class="lien06"><?php echo($langl->description); ?></a>
						  		&nbsp; 
						  <?php } ?> </td>
    </tr>
  </table>
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" onClick="envoyer()" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onclick="envoyer()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
    </tr>
  </table>  
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="titre_cellule">REFERENCE :</td>
      <td class="cellule_sombre">
        <input type="text" name="ref" id="ref_c" class="form" value="<?php echo($produit->ref); ?>" <?php if($ref) echo "disabled";?>>      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">TITRE</td>
      <td class="cellule_claire"><input name="titre" id="titre" type="text" class="form" value="<?php echo($produitdesc->titre); ?>">      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">CHAPO</td>
      <td class="cellule_sombre">
        <textarea name="chapo" id="chapo" cols="40" rows="2" class="form"><?php echo($produitdesc->chapo); ?></textarea>      </td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">DESCRIPTION</td>
      <td class="cellule_claire">

			<textarea name="description" id="description" rows="18" cols="50" style="width: 100%"><?php echo($produitdesc->description); ?></textarea>

        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">POSTSCRIPTUM</td>
      <td class="cellule_claire">
        <textarea name="postscriptum" id="postscriptum" cols="40" rows="2" class="form"><?php echo($produitdesc->postscriptum); ?></textarea>
      </td>
    </tr>
	 <tr>
      <td height="30" colspan="2" class="titre_cellule_tres_sombre">Caractéristiques du produit </span></td>
      </tr>
    <tr>
      <td width="250" height="30" class="titre_cellule">PRIX</td>
      <td width="440" class="cellule_sombre">
        <input name="prix" id="prix" type="text" class="form" value="<?php echo($produit->prix); ?>" />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">PRIX PROMOTIONNE </td>
      <td class="cellule_claire">
        <input name="prix2" id="prix2" type="text" class="form" value="<?php echo($produit->prix2); ?>" />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">TVA </td>
      <td class="cellule_claire">
        <input name="tva" id="tva" type="text" class="form" value="<?php echo($tva); ?>" />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">Eco-taxe </td>
      <td class="cellule_claire">
        <input name="ecotaxe" id="ecotaxe" type="text" class="form" value="<?php echo($produit->ecotaxe); ?>" />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">NOUVEAUTE</td>
      <td class="cellule_sombre">
        <input name="nouveaute" id="nouveaute" type="checkbox" class="form" <?php if($produit->nouveaute) echo "checked"; ?> />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">GARANTIE</td>
      <td class="cellule_claire">
        <input name="garantie" id="garantie" type="text" class="form" value="<?php echo($produit->garantie); ?>" />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">EN PROMO </td>
      <td class="cellule_sombre">
        <input name="promo" id="promo" type="checkbox" class="form" <?php if($produit->promo) echo "checked"; ?> />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">EN REAPROVISIONNEMENT </td>
      <td class="cellule_claire">
        <input name="reappro" id="reappro" type="checkbox" class="form" <?php if($produit->reappro) echo "checked"; ?> />
        </span></td>
    </tr>
    <tr>
      <td height="30" class="titre_cellule">EN LIGNE </td>
      <td class="cellule_sombre">
        <input name="ligne" id="ligne" type="checkbox" class="form" <?php if($produit->ligne) echo "checked"; ?> />
        </span></td>
    </tr>
	 <tr>
      <td height="30" class="titre_cellule">APPARTENANCE (rubrique p&egrave;re) </td>
      <td class="cellule_sombre">
        <select name="rubrique" id="rubrique" class="form">
          <?php if($ref) echo arbreOption(0, 1, $produit->rubrique); else {  ?>
          	<option value="">&nbsp;</option>
          
         <?php 
          echo arbreOption(0, 1, $rubrique); } ?>
          </select>
        </span></td>
    </tr>
	 <tr>
      <td height="30" class="titre_cellule">POIDS</td>
      <td class="cellule_sombre">
        <input type="text" name="poids" id="poids" value="<?php echo($produit->poids); ?>" />
        </span></td>
    </tr>
    
	 <tr>
      <td height="30" class="titre_cellule">STOCK </td>
      <td class="cellule_sombre">
        <input type="text" name="stock" id="stock" value="<?php echo($produit->stock); ?>" />
        </span></td>
    </tr>        
   
   <?php
   	
   	$rubcaracteristique = new Rubcaracteristique();
   	$caracteristiquedesc = new Caracteristiquedesc();
   	$caracdisp = new Caracdisp();
    $caracdispdesc = new Caracdispdesc();
 	$caracteristique = new Caracteristique();

   	$query = "select * from $rubcaracteristique->table,$caracteristique->table  where $rubcaracteristique->table.caracteristique=$caracteristique->table.id and $rubcaracteristique->table.rubrique='" . $rubrique . "' order by $caracteristique->table.classement";
   	$resul = mysql_query($query);

   	$caracval = new Caracval();
   	
   	while($row = mysql_fetch_object($resul)){
		$caracval = new Caracval();
   		$caracteristiquedesc->charger($row->caracteristique);
   		$caracval->charger($produit->id, $row->caracteristique);
   		
   		$query2 = "select * from $caracdisp->table where caracteristique='$row->caracteristique'";
   		$resul2 = mysql_query($query2);
   		$nbres = mysql_num_rows($resul2);
   ?>
        
	 <tr>
      <td height="30" class="titre_cellule"><span class="arial11_bold_626262">
        <?php echo($caracteristiquedesc->titre); ?>
:</span></td>
      <td class="cellule_sombre">
        <?php if(! $nbres) { ?>
        <input type="hidden" name="typecaract<?php echo($row->caracteristique); ?>" id="typecaract<?php echo($row->caracteristique); ?>" value="v" />
        <input type="text" name="caract<?php echo($row->caracteristique); ?>" id="caract<?php echo($row->caracteristique); ?>" value="<?php echo($caracval->valeur); ?>" />
        <?php } else {?>
        <input type="hidden" name="typecaract<?php echo($row->caracteristique); ?>" id="typecaract<?php echo($row->caracteristique); ?>" value="c" />
        <select name="caract<?php echo($row->caracteristique); ?>[]" id="caract<?php echo($row->caracteristique); ?>" size="5" multiple="multiple">
          <?php while($row2 = mysql_fetch_object($resul2)){ 
     		 	$caracdispdesc->charger_caracdisp($row2->id);
          		$caracval->charger_caracdisp($produit->id, $row2->caracteristique, $caracdispdesc->caracdisp);

     			if( $caracdispdesc->caracdisp == $caracval->caracdisp) $selected="selected"; else $selected="";
     	?>
          <option value="<?php echo($caracdispdesc->caracdisp); ?>" <?php echo($selected); ?>>
            <?php echo($caracdispdesc->titre); ?>            </option>
          <?php } ?>
        </select>
        <?php } ?></td>
    </tr>
    
         <?php } ?>
         
<?php
	if($ref){
?>    
   <?php
   	
   	$rubdeclinaison = new Rubdeclinaison();
   	$declinaisondesc = new Declinaisondesc();
   	$declidisp = new Declidisp();
    $declidispdesc = new Declidispdesc();
 	$declinaison = new Declinaison();
 	
   	$query = "select * from $rubdeclinaison->table,$declinaison->table  where $rubdeclinaison->table.declinaison=$declinaison->table.id and $rubdeclinaison->table.rubrique='" . $rubrique . "' order by $declinaison->table.classement";   	$resul = mysql_query($query);

  	
   	while($row = mysql_fetch_object($resul)){

   		$declinaisondesc->charger($row->declinaison);

   		
   		$query2 = "select * from $declidisp->table where declinaison='$row->declinaison'";
   		$resul2 = mysql_query($query2);
   		$nbres = mysql_num_rows($resul2);
   ?>

      
	 <tr>
      <td height="30" class="titre_cellule"><span class="arial11_bold_626262">
        <?php echo($declinaisondesc->titre); ?>
:</span></td>
      <td class="cellule_sombre">
    
		<table>
			<tr>
          <td width="200">&nbsp;</td>
          
          <td width="200">Stock</td>
          <td width="200">Surplus</td>
          
          
           <td width="200">&nbsp;</td>
        
			</tr>
		</table>
		    
        <input type="hidden" name="typedeclit<?php echo($row->declinaison); ?>" value="c" />
          <?php while($row2 = mysql_fetch_object($resul2)){ 
     		 	$declidispdesc->charger_declidisp($row2->id);
     		 	
     		 	$stock = new Stock();
     		 	$stock->charger($row2->id, $produit->id);
     	?>
		<?php
			$exdecprod = new Exdecprod();
			$res = $exdecprod->charger($produit->id, $row2->id); 
		?>  	
        
		<table>
			<tr>
            <td width="200"><?php echo($declidispdesc->titre); ?></td>
            
            <td width="200"><input type="text" name="stock<?php echo($row2->id); ?>" value="<?php echo($stock->valeur); ?>" size="4" /></td>
            
            <td width="200"><input type="text" name="surplus<?php echo($row2->id); ?>" value="<?php echo($stock->surplus); ?>" size="4" /></td>
            
            <td width="200"><?php if($res) { ?> <a href="produit_modifier.php?ref=<?php echo($ref); ?>&produit=<?php echo($produitdesc->produit); ?>&rubrique=<?php echo($rubrique); ?>&action=acdec&id=<?php echo($declidispdesc->declidisp); ?>" class="lien04 ">Activer</a> <?php } else {?> <a href="produit_modifier.php?ref=<?php echo($ref); ?>&produit=<?php echo($produitdesc->produit); ?>&rubrique=<?php echo($rubrique); ?>&action=desdec&id=<?php echo($declidispdesc->declidisp); ?>" class="lien04 ">D&eacute;sactiver</a> <?php } ?></td>
          
			</tr>
		</table>
				
			<?php } ?>


		</td>
    </tr>
    
         <?php } ?>        


         
	 <tr>
      <td height="30" class="titre_cellule">PHOTOS</td>
      <td class="cellule_sombre"><span class="arial11_bold_626262">

        <a href="#" class="lien04" onclick="window.open('photo_produit.php?ref=<?php echo($produit->ref); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=800');"> G&eacute;rer les photos</a>
        <br />
        
        <?php 
		$image = new Image();
		$imagedesc = new Imagedesc();

		$query = "select * from $image->table where produit='$produit->id' and produit>0";
		$resul = mysql_query($query, $image->link);
		
		while($row = mysql_fetch_object($resul)){
			$imagedesc->charger($row->id);
	?>
        &nbsp;<img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/<?php echo($row->fichier); ?>&width=150&height=&opacite=" border="0" />  
        <br />
        <?php
		}
	?>        
        
        </span></td>
    </tr>
	 <tr>
      <td height="30" class="titre_cellule">ACCESSOIRES</td>
      <td class="cellule_sombre"><span class="arial11_bold_626262">
        <?php
                $accessoire = new Accessoire();
                $produita = new Produit();
                $produitdesca = new Produitdesc();

                $query = "select * from $accessoire->table where produit='$produit->id'";
                $resul = mysql_query($query, $accessoire->link);

                while($row = mysql_fetch_object($resul)){
                        $produita->charger_id($row->accessoire);
                        $produitdesca->charger($produita->id);
        ?>
        <?php echo($produitdesca->titre); ?>
        <br />
        <?php
                }
        ?>
        <a href="#" class="lien04" onclick="window.open('accessoire.php?ref=<?php echo($produit->ref); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=300');"> G&eacute;rer les accessoires</a></span></td>
    </tr>
	 <tr>
      <td width="250" height="30" class="titre_cellule">CONTENUS ASSOCIES :</td>
      <td width="440" class="cellule_sombre">
  		   <a href="#" class="lien04" onclick="window.open('contenu_assoc.php?objet=<?php echo($produit->id); ?>&type=1', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=800');"> G&eacute;rer les contenus associ&eacute;s</a>      </td>
    </tr>    
	 <tr>
      <td height="30" class="titre_cellule">DOCUMENTS</td>
      <td class="cellule_sombre"><span class="arial11_bold_626262">
        <?php
                $document = new Document();
                $documentdesc = new Documentdesc();

                $query = "select * from $document->table where produit='$produit->id'";
                $resul = mysql_query($query, $document->link);

                while($row = mysql_fetch_object($resul)){
                        $documentdesc->charger($row->id);
        ?>
        <?php echo($row->fichier); ?>
        <br />
        <?php
                }
        ?>
        <a href="#" class="lien04" onclick="window.open('document_produit.php?ref=<?php echo($produit->ref); ?>', 'gestion', 'scrollbars=yes, resizable=yes, width=800, height=300');"> G&eacute;rer les documents</a> </span></td>
    </tr>
    
    <?php } ?>
  </table>

 <?php 
	admin_inclure("produitmodifier"); 
 ?>

	<?php
		$jour = substr($produit->datemodif, 8, 2);
		$mois = substr($produit->datemodif, 5, 2);
		$annee = substr($produit->datemodif, 0, 4);
		$heure = substr($produit->datemodif, 11, 2);
		$minute = substr($produit->datemodif, 14, 2);
		$seconde = substr($produit->datemodif, 17, 2);  	
	?>
	  
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr><input type="submit" id="boutoncache" style="display: none">
      <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" onClick="envoyer()" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onclick="envoyer()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
    </tr>
  </table>
   </form>

<?php if($ref != ""){ ?>

   <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" colspan="2" class="titre_cellule_tres_sombre">Informations sur le produit </td>
    </tr>
	<tr>
      <td width="246" height="30" class="titre_cellule">ID</td>
      <td width="444" class="titre_cellule"><?php echo($produit->id); ?></td>
	</tr>    
	<tr>
      <td width="246" height="30" class="titre_cellule">URL réécrite : </td>
      <td width="444" class="titre_cellule"><?php echo(rewrite_prod("$produit->ref", $lang)); ?></td>
	</tr>
	<tr>
      <td width="246" height="30" class="titre_cellule">Date modif : </td>
      <td width="444" class="titre_cellule"><?php echo "$jour/$mois/$annee $heure:$minute:$seconde"; ?></td>
	</tr>	
  </table>

<?php } ?>

</div>

</body>
</html>
