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


	// remplace les tags qui ne doivent pas �tre touch�s lors de la passe courante	
	function pre(&$res){

		$res = preg_replace("|<THELIA([^>]*)>|Us", "\nSAUT_THELIA<THELIA\\1>\nSAUT_THELIA", $res);
		$res = preg_replace("|</THELIA([^>]*)>|Us", "\nSAUT_THELIA</THELIA\\1>\nSAUT_THELIA", $res);

		$tab = explode("\n", $res);
		$profondeur = 0;
		$res="";
		$bsinon = 0;
		$compt=0;
		$boucles="";
		
		for($i = 0; $i<count($tab); $i++){
			if(strstr($tab[$i], "<THELIA")) $profondeur++;
			if(strstr($tab[$i], "</THELIA")) $profondeur--;
			if(strstr($tab[$i], "<BTHELIA")) $profondeur++;
			if(strstr($tab[$i], "</BTHELIA")) $profondeur--;
			
			if(strstr($tab[$i], "<T_"))	$bsinon=1;
			else if(strstr($tab[$i], "<//T_"))	$bsinon=0;
			
			if( ($profondeur == 2 && ! strstr($tab[$i], "<THELIA")) || $profondeur>2 ) 
				$tab[$i] = str_replace("#", "#THNO", $tab[$i]);	
			else if(strstr($tab[$i], "<THELIA") && $profondeur < 2){
				preg_match("/<THELIA_([^ ]*) /", $tab[$i], $liste);
				$boucles[$compt++] = $liste[1];
			
			}	 
			
			if($bsinon == 1 &&  strstr($tab[$i], "<THELIA")) $tab[$i] = str_replace("<THELIA", "<BTHELIA", $tab[$i]);
			else if($bsinon == 1 &&  strstr($tab[$i], "</THELIA")) $tab[$i] = str_replace("</THELIA", "</BTHELIA", $tab[$i]);
			
			$res .= $tab[$i] . "\n";
		}

		$res = preg_replace("|\nSAUT_THELIA|Us", "", $res);
		$res = preg_replace("|\nSAUT_THELIA|Us", "", $res);				

		

		return $boucles;
	}
	
	
	// repositionne les tags
	function post($res){
		
		$res = str_replace("#THNO", "#", $res);
			
		return $res;
	}
	

	// charger Div
	function chargerDiv($lect){
		
		$res="";
		
		$i =0;
		$compt = -1;
		
		while($i<count($lect)) {

			$rec = $lect[$i];

			if(strstr($rec, "<STHELIA")){
				$compt++;
				$deb=1;
				
				// r�cup�re le nom de la boucle
				ereg("_([^ ]*) ", "$rec", $cut);
				$nomboucle = $cut[1];

				$args = $rec;
	
				$texte = "";
				
				$i++;

				// r�cup�re le contenue de la boucle
				while( ! strstr($lect[$i], "/STHELIA_$nomboucle") && $i<count($lect)){
					
		  			$texte .= $lect[$i++] . "\n";
		  			
		  		} 

	  			if( strstr($lect[$i], "/STHELIA_$nomboucle") ) $deb=0;
	  			else { echo "La boucle $nomboucle n'est pas ferm&eacute;e correctement !"; exit; }
	  			
	  			$res .= "<div id=\"thelia" . $nomboucle . "\">&nbsp;</div>";
		  	
		  		$_SESSION['navig']->tabDiv[$nomboucle] = $rec . $texte . $lect[$i];


			}
		

			else  $res .= $rec . "\n"; 
		
		 	
			$i++;
		
		}

		return $res; 
	}
		

	// filtre si connecte
	function filtre_connecte($lect){

		// r�cup�re les infos
			if($_SESSION['navig']->connecte){
				
				$lect = preg_replace("|<THELIA SI CONNECTE>(.*)</THELIA SI CONNECTE>|Us", "\\1", $lect);
				$lect = preg_replace("|<THELIA SI NON CONNECTE>.*</THELIA SI NON CONNECTE>|Us", "", $lect);
			
			}
				
			else if(! $_SESSION['navig']->connecte){
				
				$lect = preg_replace("|<THELIA SI CONNECTE>.*</THELIA SI CONNECTE>|Us", "", $lect);
				$lect = preg_replace("|<THELIA SI NON CONNECTE>(.*)</THELIA SI NON CONNECTE>|Us", "\\1", $lect);
			}
		  			
		  
		return $lect; 
		
	}
	


	// boucles sinon
	function boucle_sinon($lect){

		$i =0;
		$res="";
		$texte="";
		
		while($i<count($lect)) {

	
			$rec = $lect[$i];

			if(ereg("<T_([^>]*)", "$rec", $cut)) {
				$res="";
				$avant="";
				$apres="";
				$sinon="";
				$boucle="";
				$compt = 0;
				$nomboucle = $cut[1];
	
				$i++;	
				// r�cup�re le contenue de la boucle
				while( ! strstr($lect[$i], "//T_" . $nomboucle) && $i<count($lect)){
						
		  			$res[$compt++] = $lect[$i++] . "\n";

		  		} 

	  			if( strstr($lect[$i], "//T_" . $nomboucle)) $deb=0;
	  			else { echo "La boucle $nomboucle n'est pas ferm&eacute;e correctement !"; exit; }

		  			
		  		$res[$compt] = $lect[$i];
		  		
		  		$compt = 0;
		  	
				while( ! strstr($res[$compt], "<THELIA_") && $compt<count($res)){
					$avant .= $res[$compt++] . "\n";
					
				}  		  			

	  			if( strstr($res[$compt],"<THELIA_")) $deb=0;
	  			else { echo "La boucle $nomboucle n'est pas ferm&eacute;e correctement !"; exit; }
	  								
					$args = $res[$compt];

										
				while( ! strstr($res[$compt], "</THELIA_") && $compt<count($res)){
					$boucle .= $res[$compt++] . "\n";
				
				}  		

	  			if( strstr($res[$compt],"</THELIA_")) $deb=0;
	  			else { echo "La boucle $nomboucle n'est pas ferm&eacute;e correctement !"; exit; }
	  			
					$boucle .= $res[$compt++] . "\n";
  			
					$compt++;
					
				while( ! strstr($res[$compt], "</T_$nomboucle") && $compt<count($res)){
					$apres .= $res[$compt++] . "\n";
					
				}  	  			

	  			if( strstr($res[$compt],"</T_$nomboucle")) $deb=0;
	  			else { echo "La boucle $nomboucle n'est pas ferm&eacute;e correctement !"; exit; }
	  					  			
				$compt++;
							
				while( ! strstr($res[$compt], "<//T_$nomboucle") && $compt<count($res)){
					$sinon .= $res[$compt++] . "\n";
				}  		  			
		  		
	  			if( strstr($res[$compt],"<//T_$nomboucle")) $deb=0;
	  			else { echo "La boucle $nomboucle n'est pas ferm&eacute;e correctement !"; exit; }		  			

					//boucle 
			  		$type_boucle = lireTag($args, "type"); 
					$rec = boucle_exec($type_boucle, $args, $boucle, "T_$nomboucle");
			
					if( $rec == "") 
						$texte .= $sinon;
					else {
						$texte .= $avant;
						$texte .= $boucle;
						$texte .= $apres;
					}
					
		  			$i++;
				}
			
				else $texte .= $lect[$i++] . "\n";

		}

		return $texte; 
		  	
	}
	

	// Boucles classiques
	function boucle_simple($lect, $boucles){

		for($i=0; $i<count($boucles); $i++){

			preg_match("|<THELIA_" . $boucles[$i] . " ([^>]*)>(.*)</THELIA_" . $boucles[$i] . ">|Us", $lect, $liste);

			$type_boucle = lireTag($liste[1], "type");
		
			$args = $liste[1];
			$lect = preg_replace("|<THELIA_" . $boucles[$i] . " [^>]*>.*</THELIA_" . $boucles[$i] . ">|Us", boucle_exec($type_boucle, $args, $liste[2], "T_" . $boucles[$i]), $lect, 1);

		}	

		return $lect; 
	
	}


	function boucle_exec($type_boucle, $args, $texte){
		
		$res = "";
		
			switch($type_boucle){
			 	 case 'RUBRIQUE' : $res .= boucleRubrique($texte, $args); break;
			 	 case 'DOSSIER' : $res .= boucleDossier($texte, $args); break;
			 	 case 'CONTENU' : $res .= boucleContenu($texte, $args); break;
			 	 case 'PRODUIT' : $res .= boucleProduit($texte, $args); break;
			 	 case 'PAGE' : $res .= bouclePage($texte, $args); break;
			 	 case 'PANIER' : $res .= bouclePanier($texte, $args); break;			 	 
			 	 case 'QUANTITE' : $res .= boucleQuantite($texte, $args); break;
			 	 case 'CHEMIN' : $res .= boucleChemin($texte, $args); break;			 	 
			 	 case 'PAIEMENT' : $res .= bouclePaiement($texte, $args); break;			 	 
			 	 case 'ADRESSE' : $res .= boucleAdresse($texte, $args); break;			 	 
			 	 case 'COMMANDE' : $res .= boucleCommande($texte, $args); break;			 	 
			 	 case 'VENTEPROD' : $res .= boucleVenteprod($texte, $args); break;		
			 	 case 'IMAGE' : $res .= boucleImage($texte, $args); break;			 	 
			 	 case 'DOCUMENT' : $res .= boucleDocument($texte, $args); break;			 	 
			 	 case 'ACCESSOIRE' : $res .= boucleAccessoire($texte, $args); break;			 	 
			 	 case 'TRANSPORT' : $res .= boucleTransport($texte, $args); break;			 	 
			 	 case 'PAYS' : $res .= bouclePays($texte, $args); break;			 	 
			 	 case 'CARACTERISTIQUE' : $res .= boucleCaracteristique($texte, $args); break;			 	 
			 	 case 'CARACDISP' : $res .= boucleCaracdisp($texte, $args); break;		
			 	 case 'CARACVAL' : $res .= boucleCaracval($texte, $args); break;			 	 
			 	 case 'DEVISE' : $res .= boucleDevise($texte, $args); break;			 	 
			 	 case 'CLIENT' : $res .= boucleClient($texte, $args); break;		
			 	 case 'DECLINAISON' : $res .= boucleDeclinaison($texte, $args); break;		
			 	 case 'DECLIDISP' : $res .= boucleDeclidisp($texte, $args); break;					 	 	 	 
			 	 case 'DECVAL' : $res .= boucleDecval($texte, $args); break;					 	 	 	 
	 			 case 'RSS' : $res .= boucleRSS($texte, $args); break;	 
	 			 case 'STOCK' : $res .= boucleStock($texte, $args); break;	 
	 			 
			 }
			 
			 
			 return $res;
			 
		
		
	
	}

	function traitement_formulaire($res){
	
	 	if( $_SESSION['navig']->formcli->raison == "" ) $res = ereg_replace("#RAISON\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#RAISON\[[^]]*\]", "", $res);
	 	
	 	if( $_SESSION['navig']->formcli->prenom == "" ) $res = ereg_replace("#PRENOM\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#PRENOM\[[^]]*\]", "", $res);
	 	
	 	if( $_SESSION['navig']->formcli->nom == "" ) $res = ereg_replace("#NOM\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#NOM\[[^]]*\]", "", $res);
	 	
	 	if( $_SESSION['navig']->formcli->adresse1 == "" ) $res = ereg_replace("#ADRESSE1\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#ADRESSE1\[[^]]*\]", "", $res);

	 	if( $_SESSION['navig']->formcli->adresse2 == "" ) $res = ereg_replace("#ADRESSE2\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#ADRESSE2\[[^]]*\]", "", $res);
	 	
	 	if( $_SESSION['navig']->formcli->adresse3 == "" ) $res = ereg_replace("#ADRESSE3\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#ADRESSE3\[[^]]*\]", "", $res);
	 		 		 	
	 	if( $_SESSION['navig']->formcli->cpostal == "" ) $res = ereg_replace("#CPOSTAL\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#CPOSTAL\[[^]]*\]", "", $res);
	 	
	 	if( $_SESSION['navig']->formcli->ville == "" ) $res = ereg_replace("#VILLE\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#VILLE\[[^]]*\]", "", $res);

	 	if( $_SESSION['navig']->formcli->pays == "" ) $res = ereg_replace("#PAYS\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#PAYS\[[^]]*\]", "", $res);
	 		 	
	 	if( $_SESSION['navig']->formcli->email == "") $res = ereg_replace("#EMAIL\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#EMAIL\[[^]]*\]", "", $res);
	 	
	 	if( $_SESSION['navig']->formcli->motdepasse == "") $res = ereg_replace("#MOTDEPASSE\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#MOTDEPASSE\[[^]]*\]", "", $res);
	 	
	 	if( $_SESSION['navig']->formcli->telfixe == "") $res = ereg_replace("#TELFIXE\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#TELFIXE\[[^]]*\]", "", $res);	 

	 	if( $_SESSION['navig']->formcli->telport == "") $res = ereg_replace("#TELPORT\[([^]]*)\]", "\\1", $res);
	 	else $res = ereg_replace("#TELPORT\[[^]]*\]", "", $res);	

		$res = str_replace("#ENTREPRISE", $_SESSION['navig']->formcli->entreprise, $res);
		$res = str_replace("#PRENOM", $_SESSION['navig']->formcli->prenom, $res);
		$res = str_replace("#NOM", $_SESSION['navig']->formcli->nom, $res);
		$res = str_replace("#TELFIXE", $_SESSION['navig']->formcli->telfixe, $res);
		$res = str_replace("#TELPORT", $_SESSION['navig']->formcli->telport, $res);
		$res = str_replace("#EMAIL", $_SESSION['navig']->formcli->email, $res);
		$res = str_replace("#ADRESSE1", $_SESSION['navig']->formcli->adresse1, $res);
		$res = str_replace("#ADRESSE2", $_SESSION['navig']->formcli->adresse2, $res);
		$res = str_replace("#ADRESSE3", $_SESSION['navig']->formcli->adresse3, $res);
		$res = str_replace("#CPOSTAL", $_SESSION['navig']->formcli->cpostal, $res);
		$res = str_replace("#VILLE", $_SESSION['navig']->formcli->ville, $res);
		
		if($_SESSION['navig']->formcli->raison == "1") 
			$res = str_replace("#RAISON1", "selected", $res);
		else if($_SESSION['navig']->formcli->raison == "2") 
			$res = str_replace("#RAISON2", "selected", $res);
		else if($_SESSION['navig']->formcli->raison == "3") 
			$res = str_replace("#RAISON3", "selected", $res);			

		
		return $res;
	}
	
	// Inclusions
	
	function inclusion($lect){

		$res = "";
		$i =0;
	
		while($i<count($lect)) {

				$rec = $lect[$i++];
				
   			    if(strstr($rec, "#INCLURE")){
					
					ereg("\"([^\"]*)\"", "$rec", $cut);
					$fichier = $cut[1];
			
					if(!file_exists($fichier)) { echo "Impossible d'ouvrir $fichier"; exit; }
					$res .= file_get_contents($fichier);
								
					$res .= "\n";
				}
			
				else $res .= $rec . "\n";
			
		}
		
		return $res;
	}	

	
?>