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
	include_once("../fonctions/divers.php");
	include_once("../classes/Image.class.php");
	include_once("../classes/Produit.class.php");
	include_once("../classes/Variable.class.php");
	if(!isset($action)) $action="";
	if(!isset($lang)) $lang="1";
	if(!isset($page)) $page="";
	if(!isset($id)) $id="";
	if(!isset($promo)) $promo="";
	if(!isset($nouveaute)) $nouveaute="";
	if(!isset($perso)) $perso="";
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
    
    include_once("ajax/accessoire.php");  
    include_once("ajax/contenu_associe.php");  

?>
<?php
	switch($action){
		case 'modclassement' : modclassement($ref, $parent, $type); break;
		case 'modifier' : modifier($id, $lang, $ref, $prix, $ecotaxe, $promo, $prix2, $rubrique, $nouveaute, $perso, $poids, $stock, $tva, $ligne, $titre, $chapo, $description, $postscriptum, $urlsuiv); break;
		case 'ajouter' : ajouter($lang, $ref, $prix, $ecotaxe, $promo, $prix2, $rubrique, $nouveaute, $perso, $poids, $stock, $tva, $ligne, $titre, $chapo, $description, $postscriptum); break;
		case 'acdec' : moddecli($produit, $id, 1); break;
		case 'desdec' : moddecli($produit, $id, 0); break;
		case 'supprimer' : supprimer($ref, $parent);
		case 'ajouter_photo' : ajouter_photo($produit,$lang); break;
		case 'modifier_photo' : modifier_photo($id_photo, $titre_photo, $chapo_photo, $description_photo,$lang); break;
		case 'supprimer_photo' : supprimer_photo($id_photo,$lang); break;
		case 'ajouter_document' : ajouter_document($produit, $_FILES['doc']['tmp_name'], $_FILES['doc']['name'],$lang); break;
		case 'supprimer_document' : supprimer_document($id_document,$lang); break;
		case 'modifierdoc' : modifierdoc($id_document,$titredoc,$chapodoc,$descriptiondoc,$lang); break;
		case 'modclassementdoc' : modclassementdoc($id_document,$type); break;
		case 'dupliquer' : dupliquer($ref,$refn,$rubrique); break;
	}
	

?>
<?php

	function modclassementdoc($id,$type){
      	$doc = new Document();
        $doc->charger($id);
        $doc->changer_classement($id, $type);	
	}

	function modifierdoc($id, $titre, $chapo, $description,$lang){

		$tmp = new Produit();
		$tmp->charger($_REQUEST['ref']);
				
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
			
        header("Location: produit_modifier.php?ref=" . $tmp->ref . "&id_rubrique=" . $tmp->rubrique."&lang=".$lang);

	}

	function dupliquer($ref,$refn,$rubrique){
		$test = new Produit();
			
			if(! $test->charger($refn)){
				$produit = new Produit();
			
				if($produit->charger($ref)){
					
					$newproduit = new Produit();
					
					$newproduit = $produit;
					$newproduit->id = "";
					$newproduit->ref = $refn;
					
					$query = "select max(classement) as maxClassement from $produit->table where rubrique='" . $rubrique . "'";

					$resul = mysql_query($query, $produit->link);
				    $maxClassement = mysql_result($resul, 0, "maxClassement");
					
					$newproduit->classement = $maxClassement+1;
					
					$lastid = $newproduit->add();

					$produit->charger($ref);
					
					$lang = new Lang();
					$query = "select * from $lang->table";
					$result = mysql_query($query);
					$nb = mysql_num_rows($result);
					while($row = mysql_fetch_object($result)){	
						$produitdesc = new Produitdesc();
						
						if($produitdesc->charger($produit->id, $row->id)){
							
							$newproduitdesc = new Produitdesc();
							$newproduitdesc = $produitdesc;
							$newproduitdesc->id = "";
							$newproduitdesc->produit = $lastid;
							$newproduitdesc->add();

						}
					}
					
					$caracval = new Caracval();
					
					$query = "select * from $caracval->table where produit=$produit->id";
					$resul = mysql_query($query);
					while($row = mysql_fetch_object($resul)){
						$anciencarac = new Caracval();
						$anciencarac->charger($row->produit,$row->caracteristique);
						
						$newcarac = new Caracval();
						$newcarac = $anciencarac;
						$newcarac->id = "";
						$newcarac->produit = $lastid;
						$newcarac->add();
					}
					
					$exdecprod = new Exdecprod();
					$query = "select * from $exdecprod->table where produit=$produit->id";
					$resul = mysql_query($query);
					while($row = mysql_fetch_object($resul)){
						$oldexdec = new Exdecprod();
						$oldexdec->charger($row->produit,$row->declidisp);
						
						$newexdec = new Exdecprod();
						$newexdec = $oldexdec;
						$newexdec->id = "";
						$newexdec->produit = $lastid;
						$newexdec->add();
					}
					
					$stock = new Stock();
					$query = "select * from $stock->table where produit=$produit->id";
					$resul = mysql_query($query);
					while($row = mysql_fetch_object($resul)){
						$oldstock = new Stock();
						$oldstock->charger($row->declidisp,$row->produit);
						
						$newstock = new Stock();
						$newstock = $oldstock;
						$newstock->id = "";
						$newstock->produit = $lastid;
						$newstock->add();
					}
					
					
					?>
					<script type="text/javascript">
						alert("Duplication correcte");
						location="produit_modifier.php?rubrique=<?php echo $produit->rubrique; ?>&ref=<?php echo $refn; ?>";
					</script>
					<?php
				}else{				
	?>
					<script type="text/javascript">
						alert("Le produit n'existe pas");
					</script>
<?php
			}
		}
	}

	function supprimer_document($id_document,$lang){

				$tmp = new Produit();
				$tmp->charger($_REQUEST['ref']);
			
				$document = new Document();
				$document->charger($id_document);

				if(file_exists("../client/document/$document->fichier")){
					 unlink("../client/document/$document->fichier");
				}

				$document->supprimer();
  			    
  			    header("Location: produit_modifier.php?ref=" . $tmp->ref . "&id_rubrique=" . $tmp->rubrique."&lang=".$lang);

	}
		

	function ajouter_document($produit, $doc, $doc_name,$lang){

			$tmp = new Produit();
			$tmp->charger($_REQUEST['ref']);
		
			if($doc != ""){

				$fich = substr($doc_name, 0, strlen($doc_name)-4);
				$ext = substr($doc_name, strlen($doc_name)-3);

				$document = new Document();
				$documentdesc = new Documentdesc();

			 	$query = "select max(classement) as maxClassement from $document->table where produit='" . $produit . "'";

		 		$resul = mysql_query($query, $document->link);
	     		$maxClassement = mysql_result($resul, 0, "maxClassement");

				$document->produit = $produit;
				$document->classement = $maxClassement+1;


				$lastid = $document->add();
				$document->charger($lastid);
				$fich = eregfic($fich);
				$document->fichier = $fich . "_" . $produit . "." . $ext;
				$document->maj();

				copy("$doc", "../client/document/" . $fich . "_" . $produit . "." . $ext);	
			}

	 		 header("Location: produit_modifier.php?ref=" . $tmp->ref . "&id_rubrique=" . $tmp->rubrique . "&lang=". $lang);

	}

	function modclassement($ref, $parent, $type){
        $prod = new Produit();
        $prod->charger($ref);
        $prod->changer_classement($ref, $type);


	    header("Location: parcourir.php?parent=" . $parent);
	}
	

		
	
	function modifier($id, $lang, $ref, $prix, $ecotaxe, $promo, $prix2, $rubrique, $nouveaute, $perso, $poids, $stock, $tva, $ligne, $titre, $chapo, $description, $postscriptum, $urlsuiv){

     
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
	 	 if($nouveaute == "on") $produit->nouveaute = 1; else $produit->nouveaute = 0;
	 	 if($ligne == "on") $produit->ligne = 1; else $produit->ligne = 0;

		 $produit->perso = $perso;  
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
	
		if($urlsuiv){
		?>
			<script type="text/javascript">
				window.location="parcourir.php?parent=<?php echo $produit->rubrique; ?>";
			</script>
		<?php
		}
		else{
			?>
			<script type="text/javascript">
				window.location="<?php echo $_SERVER['PHP_SELF']; ?>?ref=<?php echo $produit->ref; ?>&rubrique=<?php echo  $produit->rubrique?>&lang=<?php echo $lang; ?>";
			</script>
		<?php
		}
	}

	function ajouter($lang, $ref, $prix, $ecotaxe, $promo, $prix2, $rubrique, $nouveaute, $perso, $poids, $stock, $tva, $ligne, $titre, $chapo, $description, $postscriptum){
  

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
	 if($nouveaute == "on") $produit->nouveaute = 1; else $produit->nouveaute = 0;
	 if($ligne == "on") $produit->ligne = 1; else $produit->ligne = 0;
	 $produit->perso = $perso;  
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

  header("Location: " . $_SERVER['PHP_SELF'] . "?ref=" . $produit->ref . "&rubrique=" . $produit->rubrique."&lang=".$lang);

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
		header("location: parcourir.php?parent=".$parent); exit;
	}
	
	function ajouter_photo($produit,$lang){

		$tmp = new Produit();
		$tmp->charger($_REQUEST['ref']);
		
		if(!isset($nomorig)) $nomorig="";
		
		for($i = 1; $i<6; $i++){
			$photo = $_FILES["photo" . $i]['tmp_name'];
			$photo_name = $_FILES["photo" . $i]['name'];


		if($photo != ""){
			
			
       	    $extension = substr($photo_name, strlen($nomorig)-3);
			$fich = substr($photo_name, 0, strlen($photo_name)-4);

			$photoprodw = new Variable();
			$photoprodw->charger("photoprodw");
			 
			$image = new Image();
			$imagedesc = new Imagedesc();


		 	$query = "select max(classement) as maxClassement from $image->table where produit='" . $produit . "'";

	 		$resul = mysql_query($query, $image->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");

			
			$image->produit = $produit;
			$image->classement = $maxClassement + 1;

			$lastid = $image->add();								

			$image->charger($lastid);
			$image->fichier = $fich . "_" . $lastid . "." . $extension;
			$image->maj();
			
			copy("$photo", "../client/gfx/photos/produit/" . $fich . "_" . $lastid . "." . $extension);
		}
		
	  }	
	  
	  header("Location: produit_modifier.php?ref=" . $tmp->ref . "&id_rubrique=" . $tmp->rubrique."&lang=".$lang);
		
	}
	
	function modifier_photo($id_photo, $titre_photo, $chapo_photo, $description_photo,$lang){
	
		$tmp = new Produit();
		$tmp->charger($_REQUEST['ref']);
				
		$imagedesc = new Imagedesc();
		$imagedesc->image = $id_photo;
		$imagedesc->lang = $lang;
	
		$imagedesc->charger($id_photo,$lang);
		
		$imagedesc->titre = $titre_photo;
		$imagedesc->chapo = $chapo_photo;
		$imagedesc->description = $description_photo;
	
		if(!$imagedesc->id)
			$imagedesc->add();
		else 
			$imagedesc->maj();

	    header("Location: produit_modifier.php?ref=" . $tmp->ref . "&id_rubrique=" . $tmp->rubrique."&lang=".$lang);

	}
	
	function supprimer_photo($id_photo,$lang){

			$tmp = new Produit();
			$tmp->charger($_REQUEST['ref']);
						
			$image = new Image();
			$image->charger($id_photo);
			$imagedesc = new Imagedesc();
			$imagedesc->charger($image->id);
			
			if(file_exists("../client/gfx/photos/produit/$image->fichier"))
				 unlink("../client/gfx/photos/produit/$image->fichier");
		
			
			$image->supprimer();
			$imagedesc->delete();
			
 		    header("Location: produit_modifier.php?ref=" . $tmp->ref . "&id_rubrique=" . $tmp->rubrique . "&lang=".$lang);


	}
	

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
<?php include_once("title.php"); ?>

<script type="text/javascript">
<!--
	function envoyer(){
	        var ref=document.getElementById('ref_c').value;
	        if( ref == ""){
	            alert("Veuillez entrer une reference");
	            } else {
	            var reg=new RegExp("^[a-zA-Z0-9-_/:,\.]+$", "g");
	            if (!ref.match(reg)){
	                alert("Pour la référence, les seuls caractères autorisés sont : les chiffres, les lettres, et -_/:,.");
	            } else {
	                document.getElementById('formulaire').submit();
	            }
	        }
	 }
	
	function supprimer(id,ref){
		window.location="produit_modifier.php?id_photo="+id+"&ref="+ref+"action=supprimer_photo";
	}
	
	function verifref(){
		$.ajax({
			type:'GET',
			url:'ajax/ref.php',
			data:'ref_c='+document.getElementById('ref_c').value,
			success : function(html){
				$("#verification_ref_c").html(html);
			}
		})
	}
	
	function dupliquer(){
	    var ref = prompt("référence du nouveau produit");
	    if(ref != null){
	        $.ajax({
	            type:'GET',
	            url:'ajax/refdupl.php',
	            data:'ref_c='+ref,
	             async: false,
	            success : function(html){
	                if(html == "1"){
						if((ref!="")&&(ref!=null)){
	                    	alert("Référence déjà existante");
	                    	dupliquer();
						}else{
	                    	alert("Veuillez saisir une référence");
	                    	dupliquer();
						}
	                }
	                else{
	                	location="produit_modifier.php?ref=<?php echo $_GET['ref']; ?>&refn="+ref+"&rubrique=<?php echo $_GET['rubrique']; ?>&action=dupliquer";
	                }
	                }
	            })
	    }
	}
-->
</script>

<?php include_once("js/accessoire.php"); ?>
<?php include_once("js/contenu_associe.php"); ?>

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
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="catalogue";
	include_once("entete.php");
?>
<div id="contenu_int">
  <p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" alt="-" /><a href="parcourir.php" class="lien04">Gestion du catalogue</a>               
   
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
					<img src="gfx/suivant.gif" width="12" height="9" border="0" alt="-" />
			<?php	
				}
				while($tot --){
			?><a href="parcourir.php?parent=<?php echo($res[$tot+1]->rubrique); ?>" class="lien04"><?php echo($res[$tot+1]->titre); ?></a> <img src="gfx/suivant.gif" width="12" height="9" border="0" alt="-" />                             
            <?php
            	}
            
            ?>
            
			<?php
                    $parentdesc = new Rubriquedesc();
					$parentdesc->charger($rubrique);
					$parentnom = $parentdesc->titre;	
					
			?>
			<a href="parcourir.php?parent=<?php echo($parentdesc->rubrique); ?>" class="lien04"><?php echo($parentdesc->titre); ?></a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" alt="-" /> 

			 <?php if( $ref) { ?>
			<?php echo($produitdesc->titre); ?> / &nbsp;
           Modifier<?php } else { ?> Ajouter <?php } ?> </p>	                         

<!-- Début de la colonne de gauche / bloc de la fiche produit -->  
<div id="bloc_description">
 <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="formulaire" enctype="multipart/form-data">
	<input type="hidden" name="action" value="<?php if(!$ref) { ?>ajouter<?php } else { ?>modifier<?php } ?>" />
	<input type="hidden" name="ref" value="<?php echo($ref); ?>" /> 
 	<input type="hidden" name="lang" value="<?php echo($lang); ?>" /> 
 	<input type="hidden" name="rubrique" value="<?php echo($produit->rubrique); ?>" /> 
	<input type="hidden" name="urlsuiv" id="url" value="0" />

<!-- bloc descriptif du produit -->   	
		<div class="entete">
			<div class="titre">DESCRIPTION GÉNÉRALE DU PRODUIT</div>
			<div class="fonction_valider"><a href="#" onclick="envoyer()">VALIDER LES MODIFICATIONS</a></div>
		</div>
	<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
		<th width="133" class="designation" style="height:30px; padding-top:10px;">R&eacute;f&eacute;rence</th>
		<?php
		if($ref){
			?>
			<th style="padding-top:10px;"><?php echo($produit->ref); ?><input type="hidden" id="ref_c" value="<?php echo($produit->ref); ?>" /></th>
			<?php
		}
		else{
			?>
			<th style="padding-top:10px;"> <input type="text" name="ref" id="ref_c" class="form_reference" onblur="verifref();"> <span id="verification_ref_c"> </span></th>
			<?php
		}
		?>		
	</tr>
    <tr class="fonce">
        <td class="designation">Changer la langue</td>
        <td>
        <?php
								$langl = new Lang();
								$query = "select * from $langl->table";
								$resul = mysql_query($query);
								while($row = mysql_fetch_object($resul)){
									$langl->charger($row->id);
									if($_GET['lang'] == "")
										$lang = 1;
						    ?>
						  
						  		 <div class="flag<?php if($lang ==  $langl->id) { ?>Selected<?php } ?>"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?ref=<?php echo($ref); ?>&amp;rubrique=<?php echo($rubrique); ?>&amp;lang=<?php echo($langl->id); ?>"><img src="gfx/lang<?php echo($langl->id); ?>.gif" alt="-" /></a></div>
						  		 
						  <?php } ?>
        
        </td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Titre</td>
        <td><input name="titre" id="titre" type="text" class="form_long" value="<?php echo($produitdesc->titre); ?>" /></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Chapo<br /> <span class="note">(courte description d'introduction)</span></td>
        <td><textarea name="chapo" id="chapo" cols="40" rows="2" class="form_long"><?php echo($produitdesc->chapo); ?></textarea></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Description<br /> <span class="note">(description complète)</span></td>
        <td><textarea name="description" id="description" cols="40" rows="2"><?php echo($produitdesc->description); ?></textarea></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Postscriptum<br /> <span class="note">(champs d'information complémentaire)</span></td>
        <td><textarea name="postscriptum" id="postscriptum" cols="40" rows="2" class="form_long"><?php echo($produitdesc->postscriptum); ?></textarea>
</td>
   	</tr>
   		<tr class="claire">
        <td class="designation">Appartenance<br /> <span class="note">(déplacer dans une autre rubrique)</span></td>
        <td style="vertical-align:top;"><select name="rubrique" id="rubrique" class="form_long">
          <?php if($ref) echo arbreOption(0, 1, $produit->rubrique,1); else {  ?>
          	<option value="">&nbsp;</option>
          
         <?php 
          echo arbreOption(0, 1, $rubrique,1); } ?>
          </select></td>
   	</tr>
    </table>
    
<!-- bloc des caractéristiques de base du produit -->     
    <div class="entete">
			<div class="titre">CARACTÉRISTIQUES DU PRODUIT</div>
			<div class="fonction_valider"><a href="#" onclick="envoyer()">VALIDER LES MODIFICATIONS</a></div>
	</div>
	<table width="100%" cellpadding="5" cellspacing="0">
   
   	<tr class="claire">
        <th width="133" class="designation">Prix TTC</th>
        <th width="133"><input name="prix" id="prix" type="text" class="form_court" value="<?php echo($produit->prix); ?>" /></th>
        <th class="designation" width="133" >Nouveauté</th>
        <th width="133"><input name="nouveaute" id="nouveaute" type="checkbox" class="form" <?php if($produit->nouveaute) echo "checked=\"checked\""; ?> /></th>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Prix en promo TTC</td>
        <td><input name="prix2" id="prix2" type="text" class="form_court" value="<?php echo($produit->prix2); ?>" /></td>
        <td class="designation">En promotion</td>
        <td><input name="promo" id="promo" type="checkbox" class="form" <?php if($produit->promo) echo "checked=\"checked\""; ?> /></td>
   	</tr>
   		<tr class="claire">
        <td class="designation">TVA</td>
        <td><input name="tva" id="tva" type="text" class="form_court" value="<?php echo($tva); ?>" /></td>
        <td class="designation">En ligne</td>
        <td><input name="ligne" id="ligne" type="checkbox" class="form" <?php if($produit->ligne || $produit->ligne == "") echo "checked=\"checked\""; ?> /></td>
   	</tr>
   		<tr class="fonce">
        <td class="designation">Poids</td>
        <td><input type="text" name="poids" id="poids" class="form_court" value="<?php echo($produit->poids); ?>" /></td>
        <td class="designation">Stock</td>
        <td><input type="text" name="stock" id="stock" class="form_court" value="<?php if($produit->stock != "") echo($produit->stock); else echo "1"; ?>" /></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Eco-taxe</td>
        <td><input name="ecotaxe" id="ecotaxe" type="text" class="form_court" value="<?php echo($produit->ecotaxe); ?>" /></td>
        <td class="designation"></td>
        <td></td>
   	</tr>

    </table>

	
<?php
	if($ref){
?>    

<ul id="blocs_pliants_prod">
<!-- bloc de gestion des caractéristiques ajoutés -->  
	<li style="margin:0 0 10px 0">
	<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">CARACTÉRISTIQUES AJOUTÉES</a></h3>
	<ul>
	
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
				 <?php if(! $nbres) { ?>
				<li class="lignesimple">
						<div class="cellule_designation" style="width:290px;"><?php echo($caracteristiquedesc->titre); ?></div>
						<div class="cellule">
        <input type="hidden" name="typecaract<?php echo($row->caracteristique); ?>" id="typecaract<?php echo($row->caracteristique); ?>" value="v" />
        <input type="text" class="form_caracterisques_ajoutees" name="caract<?php echo($row->caracteristique); ?>" id="caract<?php echo($row->caracteristique); ?>" value="<?php echo($caracval->valeur); ?>" />
        <?php } else {?>
				<li class="lignemultiple">
					<div class="cellule_designation_multiple" style="width:290px; padding:5px 0 0 5px;"><?php echo($caracteristiquedesc->titre); ?></div>
					<div class="cellule"  style="padding:5px 0 0 5px;">
        <input type="hidden" name="typecaract<?php echo($row->caracteristique); ?>" id="typecaract<?php echo($row->caracteristique); ?>" value="c" />
        <select name="caract<?php echo($row->caracteristique); ?>[]" id="caract<?php echo($row->caracteristique); ?>" size="5" multiple="multiple"  class="form_caracterisques_ajoutees">
          <?php while($row2 = mysql_fetch_object($resul2)){ 
     		 	$caracdispdesc->charger_caracdisp($row2->id);
          		$caracval->charger_caracdisp($produit->id, $row2->caracteristique, $caracdispdesc->caracdisp);

     			if( $caracdispdesc->caracdisp == $caracval->caracdisp) $selected="selected=\"selected\""; else $selected="";
     	?>
          <option value="<?php echo($caracdispdesc->caracdisp); ?>" <?php echo($selected); ?>>
            <?php echo($caracdispdesc->titre); ?>            </option>
          <?php } ?>
        </select>
        <?php } ?>
				
				</div>

			</li>
	<?php } ?>
	<li><h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" alt="-" /></a></h3></li>
	</ul>
	
	</li>
<!-- bloc de gestion des déclinaisons simple -->  
	<li style="margin:0 0 10px 0">
		<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">GESTION DES DECLINAISONS</a></h3>
	<ul>

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
			<li class="ligne1">
				<div class="cellule" style="width:300px;"><?php echo($declinaisondesc->titre); ?></div>
				<div class="cellule" style="width:80px;">Stock</div>
				<div class="cellule" style="width:80px;">Surplus</div>
				
				<input type="hidden" name="typedeclit<?php echo($row->declinaison); ?>" value="c" />	
			</li>
    
        
          <?php while($row2 = mysql_fetch_object($resul2)){ 
     		 	$declidispdesc->charger_declidisp($row2->id);
     		 	
     		 	$stock = new Stock();
     		 	$stock->charger($row2->id, $produit->id);
     	?>
		<?php
			$exdecprod = new Exdecprod();
			$res = $exdecprod->charger($produit->id, $row2->id); 
		?>  	

			<li class="lignesimple">
				<div class="cellule" style="width:300px; padding: 5px 0 0 5px;"><?php echo($declidispdesc->titre); ?></div>
				<div class="cellule_prix" style="padding: 5px 0 0 5px;"><input type="text" name="stock<?php echo($row2->id); ?>" value="<?php echo($stock->valeur); ?>" size="4" class="form" /></div>
				<div class="cellule_prix" style="padding: 5px 0 0 5px;"><input type="text" name="surplus<?php echo($row2->id); ?>" value="<?php echo($stock->surplus); ?>" size="4" class="form" /></div>
				<div class="cellule_prix"  style="padding: 5px 0 0 5px;"><?php if($res) { ?> <a href="produit_modifier.php?ref=<?php echo($ref); ?>&amp;produit=<?php echo($produitdesc->produit); ?>&amp;rubrique=<?php echo($rubrique); ?>&amp;action=acdec&amp;id=<?php echo($declidispdesc->declidisp); ?>" class="lien04 ">Activer</a> <?php } else {?> <a href="produit_modifier.php?ref=<?php echo($ref); ?>&amp;produit=<?php echo($produitdesc->produit); ?>&amp;rubrique=<?php echo($rubrique); ?>&amp;action=desdec&amp;id=<?php echo($declidispdesc->declidisp); ?>" class="lien04 ">D&eacute;sactiver</a> <?php } ?></div>
			</li>
	<?php } ?>
	<?php } ?> 
		<li><h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" alt="-" /></a></h3></li>
		</ul>
		
	</li>

<!-- bloc de gestion des accessoires -->  

			<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">GESTION DES ACCESSOIRES</a></h3>
			<ul>
			
				<li class="ligne1">
					<div class="cellule">
					<select class="form_select" id="accessoire_rubrique" onchange="charger_listacc(this.value);">
			     	<option value="">&nbsp;</option>
			     	 <?php 
	 					echo arbreOption(0, 1, 0); 
				 	 ?>  
					</select></div>
					
					<div class="cellule">
					<select class="form_select" id="select_prodacc">
					<option value="">&nbsp;</option>
					</select>
					</div>
					
					<div class="cellule"><a href="javascript:accessoire_ajouter(document.getElementById('select_prodacc').value)">AJOUTER</a></div>
				</li>
	<li id="accessoire_liste">
		<ul>
		<?php	
                $accessoire = new Accessoire();
                $produita = new Produit();
                $produitdesca = new Produitdesc();

                $query = "select * from $accessoire->table where produit='$produit->id' order by classement";
                $resul = mysql_query($query, $accessoire->link);

				$i = 0;
				
                while($row = mysql_fetch_object($resul)){
                		
                		if($i%2)
                			$fond = "fonce";
                		else
                			$fond = "claire";
                		
                		$i++;
                		
                        $produita->charger_id($row->accessoire);
                        $produitdesca->charger($produita->id);
                        
                        $rubadesc = new Rubriquedesc();
                        $rubadesc->charger($produita->rubrique);
        ?>
        
        	 <li class="<?php echo $fond; ?>">
				<div class="cellule" style="width:260px;"><?php echo $rubadesc->titre; ?></div>
				<div class="cellule" style="width:260px;"><?php echo $produitdesca->titre; ?></div>
				<div class="cellule_supp"><a href="javascript:accessoire_supprimer(<?php echo $row->id; ?>)"><img src="gfx/supprimer.gif" alt="-" /></a></div>
			</li>

        <?php
                }
        ?>
        </ul>		
	</li>
		<li><h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" alt="-" /></a></h3></li>

		</ul>		
		
	</li>

<!-- bloc des contenus associés -->  

	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">GESTION DES CONTENUS ASSOCIÉS</a></h3>
			<ul>
				<li class="ligne1">
					<div class="cellule">
					<select class="form_select" id="contenuassoc_dossier" onchange="charger_listcont(this.value, 1,'<?php echo $produit->ref; ?>');">
			     	<option value="">&nbsp;</option>
			     	 <?php 
	 					echo arbreOption_dos(0, 1, 0);
	 				?>
					</select></div>
					
					<div class="cellule">
					<select class="form_select" id="select_prodcont">
					<option value="">&nbsp;</option>
					</select>
					</div>
					
					<div class="cellule"><a href="javascript:contenu_ajouter(document.getElementById('select_prodcont').value, 1,'<?php echo $produit->ref; ?>')">AJOUTER</a></div>
				</li>
			

				<li id="contenuassoc_liste">
					<ul>
		<?php	
                $contenuassoc = new Contenuassoc();
                $contenua = new Contenu();
                $contenuadesc = new Contenudesc();

                $query = "select * from $contenuassoc->table where type='1' and objet='$produit->id' order by classement";
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
				<div class="cellule_supp"><a href="javascript:contenuassoc_supprimer(<?php echo $row->id; ?>, 1,'<?php echo $produit->ref; ?>')"><img src="gfx/supprimer.gif" alt="-" /></a></div>
			</li>

        <?php
                }
        ?>
				</ul>			
	
	</li>
	

	<li><h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" alt="-" /></a></h3></li>

		</ul>		
		
	</li>
	

<!-- bloc d'informations sur le produit --> 
<li class="patchplugin">
<?php 
	admin_inclure("produitmodifier"); 
?>
</li>

	<?php
	
		$produit = new Produit();
		$produit->charger($ref);
		
		$jour = substr($produit->datemodif, 8, 2);
		$mois = substr($produit->datemodif, 5, 2);
		$annee = substr($produit->datemodif, 0, 4);
		$heure = substr($produit->datemodif, 11, 2);
		$minute = substr($produit->datemodif, 14, 2);
		$seconde = substr($produit->datemodif, 17, 2);  	
	?>  
<?php if($ref != ""){ ?>

	<li style="margin:0 0 10px 0">
			<h3 class="head" style="padding:6px 7px 0 7px; border-top:3px solid #bdf66f; height: 21px;"><a href="#">INFORMATIONS SUR LE PRODUIT</a></h3>
			<ul>
				<li class="lignesimple">
					<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">ID</div>
					<div class="cellule" style="width:450px; padding: 5px 0 0 5px; background-image:url(gfx/degrade_ligne1.png); background-repeat: repeat-x;"><?php echo($produit->id); ?></div>	
				</li>
			
			<li class="lignesimple">
				<div class="cellule_designation" style="width:128px; padding:5px 0 0 5px;">URL réécrite</div>
				<div class="cellule" style="width:450px;padding: 5px 0 0 5px;"><?php echo(rewrite_prod("$produit->ref", $lang)); ?></div>	
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="width:128px; padding: 5px 0 0 5px;">Dernière modif.</div>
				<div class="cellule" style="width:450px;padding: 5px 0 0 5px;"><?php echo "le $jour/$mois/$annee à $heure:$minute:$seconde"; ?></div>
			</li>
		<li><h3 class="head" style="margin:0 0 5px 0"><a href="#"><img src="gfx/fleche_accordeon_up.gif" alt="-" /></a></h3></li>

		</ul>		
		
	</li>
<?php } ?>

</ul>	

    <?php } ?>

</form>

</div>
<?php if($ref != ""){?>

<!-- bloc de gestion des photos / colonne de droite -->   
<div id="bloc_photos">
<!-- Boite à outils -->   
<div class="entete">
	<div class="titre">BOITE A OUTILS</div>
</div>
<div class="bloc_transfert">
	<div class="claire">
		<div class="champs" style="padding-top:10px; width:375px;">
			<?php 
			$query = "select max(classement) as maxClassement from $produit->table where rubrique='" . $rubrique . "'";
			$resul = mysql_query($query, $produit->link);
			$classementmax =  mysql_result($resul, 0, "maxClassement");
			//modif jhr
			$query = "select min(classement) as minClassement from $produit->table where rubrique='" . $rubrique . "'";
			$resul = mysql_query($query, $produit->link);
			$classementmin =  mysql_result($resul, 0, "minClassement");
			
			//
			
			
			$classement=$produit->classement;
				if($classement>$classementmin) {
					// modif jhr
					$precedent=$classement;
				
					do
					{
					$precedent--;
					$query = "select * from $produit->table where rubrique='" . $rubrique . "' and classement='" . $precedent . "' ";
					$resul = mysql_query($query, $produit->link);
					}
					while(!mysql_num_rows($resul) && $precedent>$classementmin);
				
					if(mysql_num_rows($resul) !=0){
						 $refprec =  mysql_result($resul,0,'ref');
				
				//	
					
			?>
			<a href="produit_modifier.php?ref=<?php echo $refprec;?>&amp;rubrique=<?php echo $rubrique;?>" ><img src="gfx/precedent.png" alt="Produit précédent" title="Produit précédent" style="padding:0 5px 0 0;margin-top:-5px;height:38px;"/></a>	
			<?php 
					}
				}
				//modif jhr
			$site=new Variable();
			$site->charger("urlsite");
				//

			?>
			<a title="Voir le produit en ligne" href="<?php echo $site->valeur; ?>/produit.php?ref=<?php echo $ref; ?>&amp;id_rubrique=<?php echo $rubrique; ?>" target="_blank" ><img src="gfx/site.png" alt="Voir le produit en ligne" title="Voir le produit en ligne" /></a>
			<a href="#" onclick="dupliquer();"><img src="gfx/dupliquer.png" alt="Dupliquer la fiche produit" title="Dupliquer la fiche produit" style="padding:0 5px 0 0;"/></a>
			<a href="#" onclick="envoyer();"><img src="gfx/valider.png" alt="Enregistrer les modifications" title="Enregistrer les modifications" style="padding:0 5px 0 0;"/></a>
			<a href="#" onclick="document.getElementById('url').value='1'; envoyer(); "><img src="gfx/validerfermer.png" alt="Enregistrer les modifications et fermer la fiche" title="Enregistrer les modifications et fermer la fiche" style="padding:0 5px 0 0;"/></a>
			
			<?php 
				if($classement!=$classementmax) {
					// modif jhr
					$precedent=$classement;
					do{
					$precedent++;
					$query = "select * from $produit->table where rubrique='" . $rubrique . "' and classement='" . $precedent . "' ";
					$resul = mysql_query($query, $produit->link);
					}
					while(!mysql_num_rows($resul) && $precedent<$classementmax);
					if(mysql_num_rows($resul) !=0){
						 $refprec =  mysql_result($resul,0,"ref");
				//	
					
			?>
			<a href="produit_modifier.php?ref=<?php echo $refprec;?>&amp;rubrique=<?php echo $rubrique;?>" ><img src="gfx/suivant.png" alt="Produit suivant" title="Produit suivant" style="padding:0 5px 0 0;"/></a>	
			<?php 
					}
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
			<form action="produit_modifier.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="ajouter_photo" />
				<input type="hidden" name="ref" value="<?php echo($ref); ?>" /> 
    			<input type="hidden" name="produit" value="<?php echo($produit->id); ?>" />
    			<input type="hidden" name="rubrique" value="<?php echo($produit->rubrique); ?>" />
        			<?php for($i=1; $i<6; $i++) { ?>
	      				<input type="file" size="18" name="photo<?php echo($i); ?>" /><br/>
	  				<?php } ?>
   				<input type="submit" value="Ajouter" />
   			</form>
   		</div>
   	</div>
</div>
<!-- fin du bloc transfert des images -->

<ul id="blocs_pliants_photo">
<li>
	<h3 class="head"><a href="#"><img src="gfx/fleche_accordeon_img_dn.gif" alt="-" /></a></h3>
	
	<?php 
			$image = new Image();
			

			$query = "select * from $image->table where produit='$produit->id' and produit>0 order by classement";
			$resul = mysql_query($query, $image->link);
		
			while($row = mysql_fetch_object($resul)){
			$imagedesc = new Imagedesc();
			$imagedesc->charger($row->id,$lang);
			?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<input type="hidden" name="action" value="modifier_photo" />
		<input type="hidden" name="id_photo" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="ref" value="<?php echo $ref; ?>" />
		<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
		<ul>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:208px;">&nbsp;</div>
				<div class="cellule_photos" style="height:200px; overflow:hidden;"><img src="../fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/<?php echo($row->fichier); ?>&amp;width=208&amp;height=&amp;opacite=" border="0" alt="-" /></div>
				<div class="cellule_supp"><a href="produit_modifier.php?id_photo=<?php echo($row->id); ?>&amp;ref=<?php echo($ref); ?>&amp;action=supprimer_photo"><img src="gfx/supprimer.gif" width="9" height="9" border="0" alt="-" /></a></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">Titre</div>
				<div class="cellule"><input type="text" name="titre_photo" style="width:219px;" class="form" value="<?php echo $imagedesc->titre ?>" /></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:50px;">Chapo</div>
				<div class="cellule"><textarea name="chapo_photo" rows="2" cols="" class="form" style="width:219px;"><?php echo $imagedesc->chapo ?></textarea></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:65px;">Description</div>
				<div class="cellule"><textarea name="description_photo" class="form" rows="3" cols="" style="width:219px;"><?php echo $imagedesc->description ?></textarea></div>
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">Classement</div>
				<div class="cellule">
					<div class="classement">
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&amp;ref=$ref&amp;action=modclassement&amp;type=M&amp;produit=".$produit->id; ?>"><img src="gfx/up.gif" border="0" alt="-" /></a></div>
					<div class="classement">
						<a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&amp;ref=$ref&amp;action=modclassement&amp;type=D&amp;produit=".$produit->id; ?>"><img src="gfx/dn.gif" border="0" alt="-" /></a></div>
				</div>
				
			</li>
			<li class="lignesimple">
				<div class="cellule_designation" style="height:30px;">&nbsp;</div>
				<div class="cellule" style="height:30px; border-bottom: 1px dotted #9DACB6"><input type="submit" value="Enregistrer" /></div>
			</li>
			</ul>
		</form>
   		<?php } ?>
   	</li>
	<li><h3 class="head" style="margin:0 0 5px 0"><a href="javascript:;"><img src="gfx/fleche_accordeon_img_up.gif" alt="-" /></a></h3></li>
	</ul>
	


<!-- bloc de gestion des documents -->
	<div class="entete" style="margin-top:10px;">
			<div class="titre">GESTION DES DOCUMENTS</div>
	</div>
	<div class="bloc_transfert">
	<div class="claire">
		<div class="designation" style="height:43px; padding-top:10px;">Transférer des documents</div>
		<div class="champs" style="padding-top:10px;">
			<form action="produit_modifier.php" method="post" enctype="multipart/form-data">
       		<input type="hidden" name="action" value="ajouter_document" />
	   		<input type="hidden" name="ref" value="<?php echo($ref); ?>" />
      		<input type="hidden" name="produit" value="<?php echo($produit->id); ?>" />
   			<input type="hidden" name="rubrique" value="<?php echo($produit->rubrique); ?>" />
      		<input type="file" name="doc" class="form" size="16" /><br />
      		<input type="submit" value="Ajouter" />
			</form>
		</div>
   	</div>
   	</div>
   	<!-- fin bloc transfert des documents -->
   	<ul id="blocs_pliants_fichier">
	<li>
	<h3 class="head"><a href="#"><img src="gfx/fleche_accordeon_img_dn.gif" alt="-" /></a></h3>
	
   	 <?php
                $document = new Document();
                $documentdesc = new Documentdesc();

                $query = "select * from $document->table where produit='$produit->id' order by classement";
                $resul = mysql_query($query, $document->link);

                while($row = mysql_fetch_object($resul)){
						$documentdesc = new Documentdesc();
                        $documentdesc->charger($row->id,$lang);
        ?>

   		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<input type="hidden" name="action" value="modifierdoc" />
			<input type="hidden" name="ref" value="<?php echo $ref; ?>" />
			<input type="hidden" name="rubrique" value="<?php echo $rubrique; ?>" />
			<input type="hidden" name="id_document" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="lang" value="<?php echo($lang); ?>" />
		<ul>	   
		<li class="lignesimple">
			<div class="cellule_designation" style="height:20px;">Fichier</div>
			<div class="cellule_document"><a href="../client/document/<?php echo($row->fichier); ?>" target="_blank"><?php
			if(strlen($row->fichier) > 26) echo(substr($row->fichier,0,26)." ... ".substr($row->fichier,strlen($row->fichier)-3,strlen($row->fichier))); 
			else echo $row->fichier; ?></a></div>
			<div class="cellule_supp_fichier">
			<a href="produit_modifier.php?ref=<?php echo($ref); ?>&rurbrique=<?php echo $rubrique; ?>&amp;id_document=<?php echo($row->id); ?>&amp;action=supprimer_document&amp;lang=<?php echo $lang; ?>"><img src="gfx/supprimer.gif" width="9" height="9" border="0" alt="-" /></a></div>
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
					<a href="<?php echo $_SERVER['PHP_SELF'] . "?ref=".$ref."&amp;id_document=".$row->id."&amp;action=modclassementdoc&amp;type=M&amp;lang=".$lang."&amp;rubrique=".$rubrique; ?>"><img src="gfx/up.gif" border="0" alt="-" /></a></div>
				<div class="classement">
					<a href="<?php echo $_SERVER['PHP_SELF'] . "?ref=".$ref."&amp;id_document=".$row->id."&amp;action=modclassementdoc&amp;type=D&amp;lang=".$lang."&amp;rubrique=".$rubrique; ?>"><img src="gfx/dn.gif" border="0" alt="-" /></a></div>
			</div>
			
		</li>
		<li class="lignesimple">
			<div class="cellule_designation" style="height:30px;">&nbsp;</div>
			<div class="cellule" style="height:30px; border-bottom: 1px dotted #9DACB6"><input type="submit" value="Enregistrer" /></div>
		</li>
		</ul>   
		</form>
	
    	<?php
                }
        ?>
 </li>
	<li><h3 class="head" style="margin:0 0 5px 0"><a href="javascript:;"><img src="gfx/fleche_accordeon_img_up.gif" alt="-" /></a></h3></li>
	</ul>
</div>
<?php
}
?>

</div>
<?php
	include_once("pied.php");
?>
</div>
<!-- </div> -->
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
		alwaysOpen: true,
		animated: false,
		showSpeed: 400,
		hideSpeed: 100
	});
	jQuery('#blocs_pliants_fichier').Accordion({
		active: 'h3.selected',
		header: 'h3.head',
		alwaysOpen: true,
		animated: false,
		showSpeed: 400,
		hideSpeed: 100
	});
	
});	
</script>
<!-- -->
</body>
</html>
