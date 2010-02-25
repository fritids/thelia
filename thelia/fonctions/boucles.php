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

	/* Gestion des boucles */
	include_once("classes/Rubrique.class.php");
	include_once("classes/Rubriquedesc.class.php");
	include_once("classes/Client.class.php");
	include_once("classes/Dossier.class.php");
	include_once("classes/Dossierdesc.class.php");
	include_once("classes/Contenu.class.php");
	include_once("classes/Contenudesc.class.php");
	include_once("classes/Produit.class.php");
	include_once("classes/Produitdesc.class.php");
	include_once("classes/Modules.class.php");
	include_once("classes/Adresse.class.php");
	include_once("classes/Venteadr.class.php");
	include_once("classes/Commande.class.php");
	include_once("classes/Venteprod.class.php");
	include_once("classes/Statutdesc.class.php");
	include_once("classes/Image.class.php");
	include_once("classes/Imagedesc.class.php");
	include_once("classes/Document.class.php");
	include_once("classes/Documentdesc.class.php");
	include_once("classes/Accessoire.class.php");
	include_once("classes/Pays.class.php");
	include_once("classes/Paysdesc.class.php");
	include_once("classes/Zone.class.php");
	include_once("classes/Caracteristique.class.php");
	include_once("classes/Rubcaracteristique.class.php");
	include_once("classes/Caracval.class.php");
	include_once("classes/Caracdisp.class.php");
	include_once("classes/Devise.class.php");
	include_once("classes/Rubdeclinaison.class.php");
	include_once("classes/Declinaison.class.php");
	include_once("classes/Declinaisondesc.class.php");
	include_once("classes/Declidisp.class.php");
	include_once("classes/Declidispdesc.class.php");
	include_once("classes/Exdecprod.class.php");
	include_once("classes/Contenuassoc.class.php");
	include_once("classes/Stock.class.php");
	include_once("classes/Perso.class.php");

	include_once("divers.php");
	include_once("lib/magpierss/rss_fetch.inc");
			
	/* Gestion des boucles de type Rubrique*/
	function boucleRubrique($texte, $args){
		global $id_rubrique;
		// récupération des arguments
		$id = lireTag($args, "id", "int");
		$parent = lireTag($args, "parent", "int");
		$courante = lireTag($args, "courante", "int");
		$pasvide = lireTag($args, "pasvide", "int");
		$ligne = lireTag($args, "ligne", "int");
		$lien = lireTag($args, "lien", "string+\/-\s\.\,;");
		$classement = lireTag($args, "classement", "int");
		$aleatoire = lireTag($args, "aleatoire", "int");
		$exclusion = lireTag($args, "exclusion", "int_liste");
		$deb = lireTag($args, "deb", "int");
		$num = lireTag($args, "num", "int");
		
		$res="";
		$search="";
		$limit="";
		
		if(!$deb) $deb=0;
		
		$rubrique = new Rubrique();
		$rubriquedesc = new Rubriquedesc();
		
		// preparation de la requete
		
		if($ligne == "") $ligne="1";
		
		$search.=" and $rubrique->table.ligne=\"$ligne\"";
		
		if($id!="")  $search.=" and $rubrique->table.id in ($id)";
		if($parent!="") $search.=" and $rubrique->table.parent in ($parent)";
		if($courante == "1") $search .=" and $rubrique->table.id='$id_rubrique'";
		else if($courante == "0") $search .=" and $rubrique->table.id!='$id_rubrique'";
		if($num!="") $limit .= " limit $deb,$num";
		if($exclusion!="") $search .= " and $rubrique->table.id not in($exclusion)";
		if($lien!="")  $search.=" and $rubrique->table.lien in ($lien)";
		
		$search .= " and lang=" . $_SESSION['navig']->lang;
		
		if($aleatoire) $order = "order by "  . " RAND()";
		else if($classement == "alpha") $order = "order by $rubriquedesc->table.titre";
		else if($classement == "alphainv") $order = "order by $rubriquedesc->table.titre desc";
		else $order = "order by $rubrique->table.classement";

				
		$query = "select $rubrique->table.id from $rubrique->table,$rubriquedesc->table where $rubrique->table.id=$rubriquedesc->table.rubrique $search $order $limit";
		$resul = mysql_query($query, $rubrique->link);
	
		$compt = 1;

		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
				
			$rubrique->charger($row->id);
			
			if($pasvide != ""){
						$rec = arbreBoucle($rubrique->id);
						if(substr($rec, strlen($rec)-1) == ",") $rec = substr($rec, 0, strlen($rec)-1);
						if($rec) $virg=",";
						else $virg="";
						
				$tmprod = new Produit();
				$query4 = "select count(*) as nbres from $tmprod->table where rubrique in('" . $rubrique->id . "'$virg$rec) and ligne='1'";
				$resul4 = mysql_query($query4, $tmprod->link);
				if(!mysql_result($resul4, 0, "nbres")) continue;
			
			}
		
			$rubriquedesc = new Rubriquedesc();
			$rubriquedesc->charger($rubrique->id, $_SESSION['navig']->lang);
			
			$query3 = "select * from $rubrique->table where 1 and parent=\"$rubrique->id\"";
			$resul3 = mysql_query($query3, $rubrique->link);	
			if($resul3) $nbenfant = mysql_num_rows($resul3);

			$temp = str_replace("#TITRE", "$rubriquedesc->titre", $texte);
			$temp = str_replace("#STRIPTITRE", strip_tags($rubriquedesc->titre), $temp);	
			$temp = str_replace("#CHAPO", "$rubriquedesc->chapo", $temp);
			$temp = str_replace("#STRIPCHAPO", strip_tags($rubriquedesc->chapo), $temp);	
			$temp = str_replace("#DESCRIPTION", "$rubriquedesc->description", $temp);
			$temp = str_replace("#POSTSCRIPTUM", "$rubriquedesc->postscriptum", $temp);	
			$temp = str_replace("#PARENT", "$rubrique->parent", $temp);
			$temp = str_replace("#ID", "$rubrique->id", $temp);		
			$temp = str_replace("#URL", "rubrique.php?id_rubrique=" . "$rubrique->id", $temp);	
			$temp = str_replace("#REWRITEURL", rewrite_rub("$rubrique->id"), $temp);	
			$temp = str_replace("#LIEN", "$rubrique->lien", $temp);	
			$temp = str_replace("#COMPT", "$compt", $temp);		
			$temp = str_replace("#NBRES", "$nbres", $temp);
			$temp = str_replace("#NBENFANT", "$nbenfant", $temp);		
		
			
			$compt ++;
			
			if(trim($temp) !="") $res .= $temp;
			
		}

	
		return $res;
		
	
	}

	/* Gestion des boucles de type Dossier*/
	function boucleDossier($texte, $args){
	
		global $id_dossier;
		
		// récupération des arguments
		$id = lireTag($args, "id", "int");
		$parent = lireTag($args, "parent", "int");
		$deb = lireTag($args, "deb", "int");
		$num = lireTag($args, "num", "int");
		$courant = lireTag($args, "courant", "int");
		$ligne = lireTag($args, "ligne", "int");
		$lien = lireTag($args, "lien", "string+\/-\s\.\,;");
		$aleatoire = lireTag($args, "aleatoire", "int");
		$exclusion = lireTag($args, "exclusion", "int_liste");	
		
		$search="";
		$res="";
		$limit="";
		
		if(!$deb) $deb=0;
		
		if($ligne == "") $ligne="1";
		
		// preparation de la requete
		$search .=" and ligne='$ligne'";
		if($id!="") $search.=" and id in($id)";
		if($lien!="")  $search.=" and $rubrique->table.lien in ($lien)";
		if($parent!="") $search.=" and parent=\"$parent\"";
		if($courant == "1") $search .=" and id='$id_dossier'";
		else if($courant == "0") $search .=" and id!='$id_dossier'";
		if($num!="") $limit .= " limit $deb,$num";
		if($exclusion!="") $search .= " and id not in($exclusion)";
		
		$dossier = new Dossier();
		
		if($aleatoire) $order = "order by "  . " RAND()";
		else $order = "order by classement";
		
		$query = "select * from $dossier->table where 1 $search $order $limit";
		$resul = mysql_query($query, $dossier->link);
	
		$dossierdesc = new Dossierdesc();
		
		$compt = 1;

		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
			$dossierdesc = new Dossierdesc();
			if( ! $dossierdesc->charger($row->id, $_SESSION['navig']->lang)) continue;
			
			$query3 = "select * from $dossier->table where 1 and parent=\"$row->id\"";
			$resul3 = mysql_query($query3, $dossier->link);	
			if($resul3) $nbenfant = mysql_num_rows($resul3);

			$temp = str_replace("#TITRE", "$dossierdesc->titre", $texte);
			$temp = str_replace("#STRIPTITRE", strip_tags($dossierdesc->titre), $temp);	
			$temp = str_replace("#CHAPO", "$dossierdesc->chapo", $temp);
			$temp = str_replace("#STRIPCHAPO", strip_tags($dossierdesc->chapo), $temp);	
			$temp = str_replace("#DESCRIPTION", "$dossierdesc->description", $temp);
			$temp = str_replace("#POSTSCRIPTUM", "$dossierdesc->postscriptum", $temp);				
			$temp = str_replace("#PARENT", "$row->parent", $temp);
			$temp = str_replace("#ID", "$row->id", $temp);		
			$temp = str_replace("#URL", "dossier.php?id_dossier=" . "$row->id", $temp);
			$temp = str_replace("#REWRITEURL", rewrite_dos("$row->id"), $temp);	
			$temp = str_replace("#LIEN", "$row->lien", $temp);	
			$temp = str_replace("#COMPT", "$compt", $temp);		
			$temp = str_replace("#NBRES", "$nbres", $temp);
			$temp = str_replace("#NBENFANT", "$nbenfant", $temp);		
		
			
			$compt ++;
			
			if(trim($temp) !="") $res .= $temp;
			
		}
	

	
		return $res;
		
	
	}	
	
	function boucleImage($texte, $args){

		// récupération des arguments
		$produit = lireTag($args, "produit", "int");
		$id = lireTag($args, "id", "int");
		$num = lireTag($args, "num", "int");
		$nb = lireTag($args, "nb", "int");
		$debut = lireTag($args, "debut", "int");
		$deb = lireTag($args, "deb", "int");
		$rubrique = lireTag($args, "rubrique", "int");
		$largeur = lireTag($args, "largeur", "int");
		$hauteur = lireTag($args, "hauteur", "int");
		$dossier = lireTag($args, "dossier", "int");
		$contenu = lireTag($args, "contenu", "int");
		$opacite = lireTag($args, "opacite", "int");
		$noiretblanc = lireTag($args, "noiretblanc", "int");
		$miroir = lireTag($args, "miroir", "int");
		$aleatoire = lireTag($args, "aleatoire", "int");
		$exclusion = lireTag($args, "exclusion", "int_liste");	
		
		$search="";
		$res="";
		$limit="";

		if($deb != "") $debut = $deb;
		
		if($aleatoire) $order = "order by "  . " RAND()";
		else $order=" order by classement";	
		
		if($id != "") $search .= " and id=\"$id\"";
		if($produit != "") $search .= " and produit=\"$produit\"";
		if($rubrique != "") $search .= " and rubrique=\"$rubrique\"";
		if($dossier != "") $search .= " and dossier=\"$dossier\"";
		if($contenu != "") $search .= " and contenu=\"$contenu\"";
		if($exclusion!="") $search .= " and id not in($exclusion)";
		
		$image = new Image();

		if($debut !="") $debut--;
		else $debut=0;

        $query = "select * from $image->table where 1 $search";
        $resul = mysql_query($query, $image->link);
        $nbres = mysql_num_rows($resul);
        if($debut!="" && $num=="") $num=$nbres;
                		
		if($debut!="" || $num!="") $limit .= " limit $debut,$num";
		
		if($nb!="") { $nb--; $limit .= " limit $nb,1"; }

		$query = "select * from $image->table where 1 $search $order $limit";
		$resul = mysql_query($query, $image->link);
	
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";

		$pr = new Produit();
		$prdesc = new Produitdesc();
		$rudesc = new Rubriquedesc();
		$contenudesc = new Contenudesc();
		$dossierdesc = new Dossierdesc();
		
		$compt=1;
		
		while( $row = mysql_fetch_object($resul)){
			$image = new Image();
			$image->charger($row->id);
			$imagedesc = new Imagedesc();
			$imagedesc->charger($image->id, $_SESSION['navig']->lang);
			$temp = $texte;
			
			$temp = str_replace("#FGRANDE", "#FICHIER", $temp);
			$temp = str_replace("#FPETITE", "#FICHIER", $temp);
			$temp = str_replace("#GRANDE", "#IMAGE", $temp);
			$temp = str_replace("#PETITE", "#IMAGE", $temp);
						
			if($image->produit != 0){
					$pr->charger_id($image->produit);
					$prdesc->charger($image->produit, $_SESSION['navig']->lang);
					$temp = str_replace("#PRODTITRE", $prdesc->titre, $temp);
					$temp = str_replace("#PRODUIT", $image->produit, $temp);
					$temp = str_replace("#PRODREF", $pr->ref, $temp);
					$temp = str_replace("#RUBRIQUE", $pr->rubrique, $temp);
					
			  		$nomcache = redim("produit", $image->fichier, $largeur, $hauteur, $opacite, $noiretblanc, $miroir);
					 
					
					if(!$largeur && !$hauteur) 
						$temp = str_replace("#IMAGE", "client/gfx/photos/produit/" . $image->fichier, $temp);
					else 
						$temp = str_replace("#IMAGE", $nomcache, $temp);

						
					$temp = str_replace("#FICHIER",  "client/gfx/photos/produit/" . $image->fichier, $temp);

			}
			
			else if($image->rubrique != 0){

		  		$nomcache = redim("rubrique", $image->fichier, $largeur, $hauteur, $opacite, $noiretblanc, $miroir);
				
				$rudesc->charger($image->rubrique, $_SESSION['navig']->lang);
				$temp = str_replace("#RUBRIQUE", $image->rubrique, $temp);
				$temp = str_replace("#RUBTITRE", $rudesc->titre, $temp);
			
				if(!$largeur && !$hauteur) 
					$temp = str_replace("#IMAGE", "client/gfx/photos/rubrique/" . $image->fichier, $temp);
				else 
					$temp = str_replace("#IMAGE", $nomcache, $temp);

				$temp = str_replace("#FICHIER",  "client/gfx/photos/rubrique/" . $image->fichier, $temp);

			}
	
			else if($image->dossier != 0){

		  		$nomcache = redim("dossier", $image->fichier, $largeur, $hauteur, $opacite, $noiretblanc, $miroir);
				$dosdesc = new Dossierdesc();
				$dosdesc->charger($image->dossier, $_SESSION['navig']->lang);
				$temp = str_replace("#DOSSIER", $image->dossier, $temp);
				$temp = str_replace("#DOSTITRE", $dosdesc->titre, $temp);
			
				if(!$largeur && !$hauteur) 
					$temp = str_replace("#IMAGE", "client/gfx/photos/dossier/" . $image->fichier, $temp);
				else 
					$temp = str_replace("#IMAGE", $nomcache, $temp);

				$temp = str_replace("#FICHIER",  "client/gfx/photos/dossier/" . $image->fichier, $temp);
			}	
	
			else if($image->contenu != 0){
			
		  		$nomcache = redim("contenu", $image->fichier, $largeur, $hauteur, $opacite, $noiretblanc, $miroir);
				
					$ctdesc = new Contenudesc();
					$ctdesc->charger($image->contenu, $_SESSION['navig']->lang);
					$temp = str_replace("#CONTTITRE", $ctdesc->titre, $temp);
					$temp = str_replace("#CONTENU", $image->contenu, $temp);					
					if(!$largeur && !$hauteur) 
						$temp = str_replace("#IMAGE", "client/gfx/photos/contenu/" . $image->fichier, $temp);
					else 
						$temp = str_replace("#IMAGE", $nomcache, $temp);
					
					$temp = str_replace("#FICHIER",  "client/gfx/photos/contenu/" . $image->fichier, $temp);
			}	
	
				$temp = str_replace("#ID",  $image->id, $temp);	
				$temp = str_replace("#TITRE",  $imagedesc->titre, $temp);	
				$temp = str_replace("#CHAPO",  $imagedesc->chapo, $temp);	
				$temp = str_replace("#DESCRIPTION",  $imagedesc->description, $temp);	
				$temp = str_replace("#COMPT", "$compt", $temp);
				$temp = str_replace("#NOMCACHE", "$nomcache", $temp);
				$temp = str_replace("#CACHE", "$nomcache", $temp);
				
			$compt++;
				
			$res .= $temp;
		}



		
		return $res;
	
	}
	
	
	/* Gestion des boucles de type Client*/
	function boucleClient($texte, $args){
		// récupération des arguments
		$id = lireTag($args, "id", "int");
		$ref = lireTag($args, "ref", "string");
		$raison = lireTag($args, "raison", "int");
		$nom = lireTag($args, "nom", "string+\-\'\,\s\/\(\)\&\"");
		$prenom = lireTag($args, "prenom", "string+\-\'\,\s\/\(\)\&\"");
		$cpostal = lireTag($args, "cpostal", "int");
		$ville = lireTag($args, "ville", "string+\s\'\/\&\"");
		$email = lireTag($args, "email", "string+\@\.");
		$pays = lireTag($args, "pays", "int");
		$parrain = lireTag($args, "parrain", "int");
		$revendeur = lireTag($args, "revendeur", "int");

		
		$search="";
		$res="";
		
		// preparation de la requete
		if($id!="")  $search.=" and id=\"$id\"";
		if($ref!="")  $search.=" and ref=\"$ref\"";
		if($raison!="")  $search.=" and raison=\"$raison\"";
		if($prenom!="")  $search.=" and prenom=\"$prenom\"";
		if($nom!="")  $search.=" and nom=\"$nom\"";
		if($cpostal!="")  $search.=" and cpostal=\"$cpostal\"";
		if($ville!="")  $search.=" and ville=\"$ville\"";
		if($email!="")  $search.=" and email=\"$email\"";
		if($pays!="")  $search.=" and pays=\"$pays\"";
		if($parrain!="")  $search.=" and parrain=\"$parrain\"";
		if($revendeur!="")  $search.=" and type=\"$revendeur\"";
		
		$client = new Client();
		$order = "order by nom";
		
		$query = "select * from $client->table where 1 $search $order";
		$resul = mysql_query($query, $client->link);
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
	
				$temp = str_replace("#ID", "$row->id", $texte);		
				$temp = str_replace("#REF", "$row->ref", $temp);		
				$temp = str_replace("#RAISON", "$row->raison", $temp);		
				$temp = str_replace("#ENTREPRISE", "$row->entreprise", $temp);
				$temp = str_replace("#SIRET", "$row->siret", $temp);					
				$temp = str_replace("#INTRACOM", "$row->intracom", $temp);					
				$temp = str_replace("#NOM", "$row->nom", $temp);					
				$temp = str_replace("#PRENOM", "$row->prenom", $temp);					
				$temp = str_replace("#TELFIXE", "$row->telfixe", $temp);	
				$temp = str_replace("#TELPORT", "$row->telport", $temp);					
				$temp = str_replace("#EMAIL", "$row->email", $temp);					
				$temp = str_replace("#ADRESSE1", "$row->adresse1", $temp);					
				$temp = str_replace("#ADRESSE2", "$row->adresse2", $temp);					
				$temp = str_replace("#ADRESSE3", "$row->adresse3", $temp);					
				$temp = str_replace("#CPOSTAL", "$row->cpostal", $temp);					
				$temp = str_replace("#VILLE", "$row->ville", $temp);					
				$temp = str_replace("#PAYS", "$row->pays", $temp);					
				$temp = str_replace("#PARRAIN", "$row->parrain", $temp);					
				$temp = str_replace("#TYPE", "$row->type", $temp);					
				$temp = str_replace("#POURCENTAGE", "$row->pourcentage", $temp);					

			
			$res .= $temp;
			
		}
	

	
		return $res;
		
	
	}
	
	function boucleDevise($texte, $args){

		// récupération des arguments
		$produit = lireTag($args, "produit", "int");
		$id = lireTag($args, "id", "int");
		$somme = lireTag($args, "somme", "float");
		$exclusion = lireTag($args, "exclusion", "int_list");
	
		$search="";
		$limit="";
		$res="";
		
		if($somme == "") $somme=0;
		
		$prod = new Produit();
		$prod->charger_id($produit);

		if($id != "") $search .= " and id in($id)";
		if($exclusion != "") $search .= " and id not in($exclusion)";
		
		$devise = new Devise();

		$query = "select * from $devise->table where 1 $search $limit";
 		
		$resul = mysql_query($query, $devise->link);
	
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
			
		while( $row = mysql_fetch_object($resul)){
			$devise->charger($row->id);
			$prix = round($prod->prix * $devise->taux, 2);
			$prix2 = round($prod->prix2 * $devise->taux, 2);
			$convert = round($somme * $devise->taux, 2);
			$total = round( $_SESSION['navig']->panier->total(1) * $devise->taux, 2);
		
			$prix = number_format($prix, 2, ".", ""); 
			$prix2 = number_format($prix2, 2, ".", ""); 
			$total = number_format($total, 2, ".", ""); 
			$convert = number_format($convert, 2, ".", ""); 
		
			$temp = str_replace("#ID",  "$devise->id", $texte);	
			$temp = str_replace("#PRIX2",  "$prix2", $temp);	
			$temp = str_replace("#PRIX", "$prix", $temp);
			$temp = str_replace("#TOTAL", "$total", $temp);
			$temp = str_replace("#CONVERT", "$convert", $temp);
			$temp = str_replace("#NOM",  "$devise->nom", $temp);	
			$temp = str_replace("#CODE",  "$devise->code", $temp);	
			$temp = str_replace("#TAUX", "$devise->taux", $temp);

			$res .= $temp;
		}

		return $res;
	
	}

	function boucleDocument($texte, $args){

		// récupération des arguments
		$produit = lireTag($args, "produit", "int");
		$rubrique = lireTag($args, "rubrique", "int");
		$nb = lireTag($args, "nb", "int");
		$debut = lireTag($args, "debut", "int");
		$deb = lireTag($args, "deb", "int");
		$num = lireTag($args, "num", "int");
		$dossier = lireTag($args, "dossier", "int");
		$contenu = lireTag($args, "contenu", "int");
		$exclusion = lireTag($args, "exclusion", "int_list");	
		$aleatoire = lireTag($args, "aleatoire", "int");	
		$classement = lireTag($args, "classement","string");	
		
		$search="";
		$order="";
		$limit="";
		$res="";

		if($deb != "") $debut = $deb;

		if($aleatoire) $order = "order by "  . " RAND()";
		else $order=" order by classement";
					
		if($produit) $search .= " and produit=\"$produit\"";
		if($rubrique != "") $search .= " and rubrique=\"$rubrique\"";
		if($dossier != "") $search .= " and dossier=\"$dossier\"";
		if($contenu != "") $search .= " and contenu=\"$contenu\"";
		if($exclusion!="") $search .= " and id not in($exclusion)";
						
		$document = new Document();
		$documentdesc = new Documentdesc();

		if($debut !="") $debut--;
		else $debut=0;

        $query = "select * from $document->table where 1 $search";
        $resul = mysql_query($query, $document->link);
        $nbres = mysql_num_rows($resul);
        if($debut!="" && $num=="") $num=$nbres;
                		
		if($num!="") $limit .= " limit $debut,$num";
		if($nb!="") { $nb--; $limit .= " limit $nb,1"; }

		$query = "select * from $document->table where 1 $search $order $limit";
 		
		$resul = mysql_query($query, $document->link);
	
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
			$document->charger($row->id);
			$documentdesc->charger($document->id);

			$ext = substr($document->fichier, strlen($document->fichier)-3);

			$temp = str_replace("#TITRE", "$documentdesc->titre", $texte);
			$temp = str_replace("#CHAPO", "$documentdesc->chapo", $temp);
			$temp = str_replace("#DESCRIPTION", "$documentdesc->description", $temp);
			$temp = str_replace("#FICHIER", "client/document/" . $document->fichier, $temp);
			$temp = str_replace("#EXTENSION", "$ext", $temp);

			$res .= $temp;
		}
	

		
		return $res;
	
	}

	function boucleAccessoire($texte, $args){

		// récupération des arguments
		$produit = lireTag($args, "produit", "int");
		$deb = lireTag($args, "deb" ,"int");
		$num = lireTag($args, "num", "int");
		$aleatoire = lireTag($args, "aleatoire", "int");
		$classement = lireTag($args, "classement", "string");
		$unique = lireTag($args, "unique", "int");
		
		$search="";
		$order = "";
		$limit="";
		$res="";
				
		if(!$deb) $deb=0;	
		if(!$num) $num = "999999999";
		
		if($produit) $search .= " and produit=\"$produit\"";
		$limit .= " limit $deb,$num";

		if($classement == "manuel") $order = "order by classement";		
		else if($aleatoire) $order = "order by "  . " RAND()";		
		
		
		$accessoire = new Accessoire();

		if($unique == "")
			$query = "select * from $accessoire->table where 1 $search $order $limit";
		else
			$query = "select DISTINCT(id) from $accessoire->table where 1 $search $order $limit";
			
		$resul = mysql_query($query, $accessoire->link);
	
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";

		$compt = 1;

		while( $row = mysql_fetch_object($resul)){

			$prod = new Produit();
			$prod->charger_id($row->produit);
			
			$temp = str_replace("#ACCESSOIRE", "$row->accessoire", $texte);
			$temp = str_replace("#PRODID", "$row->produit", $temp);
			$temp = str_replace("#PRODREF", $prod->ref, $temp);
            $temp = str_replace("#COMPT", "$compt", $temp);

			$compt ++;
			
			$res .= $temp;
		}

		return $res;
	
	}
	
	function boucleProduit($texte, $args, $type=0){
			global $page, $totbloc, $ref, $pagesess;
			
			// récupération des arguments
			$rubrique = lireTag($args, "rubrique", "int");
			$deb = lireTag($args, "deb", "int");
			$num = lireTag($args, "num", "int");
			$passage = lireTag($args, "passage", "int");
			$ligne = lireTag($args, "ligne", "int");
			$bloc = lireTag($args, "bloc", "int+-");
			$nouveaute = lireTag($args, "nouveaute", "int");
			$promo = lireTag($args, "promo", "int");
			$reappro = lireTag($args, "reappro", "int");
			$refp = lireTag($args, "ref", "string");
			$id = lireTag($args, "id", "int");
			$garantie = lireTag($args, "garantie", "int");
			$motcle = lireTag($args, "motcle", "string+\s\'");
			$classement = lireTag($args, "classement", "string");
			$aleatoire = lireTag($args, "aleatoire", "int");
			$prixmin = lireTag($args, "prixmin", "float");
			$prixmax = lireTag($args, "prixmax", "float");
			$caracteristique = lireTag($args, "caracteristique", "int+-");
			$caracdisp = lireTag($args, "caracdisp", "int+-");
			$caracval = lireTag($args, "caracval", "string+\s\'\/");
			$typech = lireTag($args, "typech", "string");
			$declinaison = lireTag($args, "declinaison", "int+-");			
			$declidisp = lireTag($args, "declidisp", "int+-");
			$declistockmini = lireTag($args, "declistockmini", "int");
			$stockmini = lireTag($args, "stockmini", "int");
			$courant = lireTag($args, "courant", "int");
			$profondeur = lireTag($args, "profondeur", "int");		
			$exclusion = lireTag($args, "exclusion", "int");
			$exclurub = lireTag($args, "exclurub", "int_list");			
			$poids = lireTag($args, "poids", "float");
			$stockvide = lireTag($args, "stockvide", "int");
			$forcepage = lireTag($args, "forcepage", "int");
						
			if($bloc) $totbloc=$bloc;
			if($deb) $debsave = $deb;
			else $debsave = 0;
			
			if(!$deb) $deb=0;
			
			if($page) $_SESSION['navig']->page = $page;
			if($pagesess == 1) $page =  $_SESSION['navig']->page;
			
			if(!$page ||  $page==1 ) $page=0; 
			
			if(!$totbloc) $totbloc=1;
			if($page) $deb = ($page-1)*$totbloc*$num+$deb; 

			if($forcepage != "") {
				if($forcepage == 1){
					$forcepage = 0;
					$deb = 0;
				}	
				
				if($forcepage) $deb = ($forcepage-1)*$totbloc*$num+$deb;
			}
				
			// initialisation de variables
			$search = "";
			$order = "";
			$comptbloc=0;
			$limit="";
			$pourcentage="";
			$res="";
			$virg="";
			
			// preparation de la requete
			
			if($courant == "1") $search .= " and ref=\"$ref\"";
			else if($courant == "0") $search .= " and ref<>\"$ref\"";
			
			if($exclusion!="") $search .= " and id not in($exclusion)";
			if($exclurub!="") $search .= " and rubrique not in($exclurub)";
			
			if($rubrique!=""){
				$srub = "";
				
				if($profondeur == "") $profondeur=0;
				$tabrub = explode(",", $rubrique);
				for($compt = 0; $compt<count($tabrub); $compt++){
					$rec = arbreBoucle($tabrub[$compt], $profondeur);
					if(substr($rec, strlen($rec)-1) == ",") $rec = substr($rec, 0, strlen($rec)-1);
					if($rec) $virg=",";
					$srub .= $tabrub[$compt] . $virg . $rec . $virg;
				}
				if(substr($srub, strlen($srub)-1) == ",")
					$srub = substr($srub, 0, strlen($srub)-1);
				 $search .= " and rubrique in($srub)";
			}
			
			if($ligne == "") $ligne="1";

			if($ligne != "-1") $search .= " and ligne=\"$ligne\"";
			if($id!="") $search .= " and id in ($id)";	 
			if($nouveaute!="") $search .= " and nouveaute=\"$nouveaute\"";
			if($promo!="") $search .= " and promo=\"$promo\"";
			if($reappro!="") $search .= " and reappro=\"$reappro\"";
			if($garantie!="") $search .= " and garantie=\"$garantie\"";
			if($prixmin!="") $search .= " and ((prix2>=\"$prixmin\" and promo=\"1\") or (prix>=\"$prixmin\" and promo=\"0\"))";
			if($prixmax!="") $search .= " and ((prix2<=\"$prixmax\" and promo=\"1\") or (prix<=\"$prixmax\" and promo=\"0\"))";
			if($poids!="") $search .= " and poids<=\"$poids\"";
			if($stockmini!="" && $declistockmini == "") $search .= " and stock>=\"$stockmini\"";

			if (""!=$stockvide) {
				if (0 < $stockvide) { $search .= " and stock<=\"0\""; }
				elseif (0 >= $stockvide) { $search .= " and stock>\"0\""; }
			}
									
			if($refp!="") $search .= " and ref=\"$refp\"";

			if($bloc == "-1") $bloc = "999999999";
			if($bloc!="" && $num!="") $limit .= " limit $deb,$bloc";
			else if($num!="") $limit .= " limit $deb,$num";
			
			if($aleatoire) $order = "order by "  . " RAND()";
			else if($classement == "prixmin") $order = "order by "  . " prix";
			else if($classement == "prixmax") $order = "order by "  . " prix desc";
			else if($classement == "rubrique") $order = "order by "  . " rubrique";
			else if($classement == "manuel") $order = "order by classement";
			else if($classement == "inverse") $order = "order by classement desc";
			else if($classement == "date") $order = "order by datemodif desc";
			else if($classement == "titre") $order = "order by titre";
            else if($classement == "titreinverse") $order = "order by titre desc";
            else if($classement == "ref") $order = "order by ref";
            else if($classement == "promo") $order = "order by promo desc";
			else $order = "order by classement";
			
		
			
			$produit = new Produit();
			/* Demande de caracteristiques */
			if($caracdisp != ""){
			
			if(! strstr($caracteristique, "-")) $caracteristique .= "-";
			if(! strstr($caracdisp, "-")) $caracdisp .= "-";
			
			$lcaracteristique = explode("-", $caracteristique);
			$lcaracdisp = explode("-", $caracdisp);
			
			$i = 0;

			$tcaracval = new Caracval();

			while($i<count($lcaracteristique)-1){
				$caracteristique = $lcaracteristique[$i];
				$caracdisp = $lcaracdisp[$i];
				if($caracdisp == "*")
					$query = "select * from $tcaracval->table where caracteristique='$caracteristique' and caracdisp<>''";
				else if($caracdisp == "-")	$query = "select * from $tcaracval->table where caracteristique='$caracteristique' and caracdisp=''";
				else $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and caracdisp='$caracdisp'";

				$resul = mysql_query($query);
				if(!mysql_num_rows($resul)) return;
				
				$liste="";
				
				while($row = mysql_fetch_object($resul))
					$liste .= "'$row->produit', ";
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				
				$i++;
				
				if($liste!="") $search .= " and $produit->table.id in($liste)";	
				else return "";
			}

			

		}	

			if($caracval != ""){
			
			$i = 0;
			$liste="";

			$tcaracval = new Caracval();

				if($caracval == "*") $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and valeur<>''";
				else if($caracval == "-") $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and valeur=''";
				else if($typech == "like") $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and valeur like '$caracval'";
				else $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and valeur ='$caracval'";

				$resul = mysql_query($query);
				
				$liste="";
				
				while($row = mysql_fetch_object($resul))
					$liste .= "'$row->produit', ";
				$liste = substr($liste, 0, strlen($liste) - 2);
				
				$i++;
			
			
			if($liste!="") $search .= " and $produit->table.id in($liste)";	
			else return "";
		}	


			/* Demande de declinaisons */
			if($declidisp != ""){

			if(! strstr($declinaison, "-")) $declinaison .= "-";
			if(! strstr($declidisp, "-")) $declidisp .= "-";
			if(! strstr($ldeclistockmini, "-")) $ldeclistockmini .= "-";
				
			$ldeclinaison = explode("-", $declinaison);
			$ldeclidisp = explode("-", $declidisp);
			$ldeclistockmini = explode("-", $declistockmini);
			
			$i = 0;
			$liste="";
			$exdecprod = new Exdecprod();
			$stock = new Stock();

			while($i<count($ldeclinaison)-1){

				$declinaison = $ldeclinaison[$i];
				$declidisp = $ldeclidisp[$i];
				$declistockmini = $ldeclistockmini[$i];
				
		 		$query = "select * from $exdecprod->table where declidisp='$declidisp'";
				$resul = mysql_query($query);
		
				if(mysql_num_rows($resul)) 
						while($row = mysql_fetch_object($resul))
							$liste .= "'$row->produit', ";
	
				if($liste!="") {
						$liste = substr($liste, 0, strlen($liste) - 2);
						$search .= " and $produit->table.id not in($liste)";
				}	
		
				$liste="";
				
				if($declistockmini != ""){
					$query = "select * from $stock->table where declidisp='$declidisp' and valeur>='$declistockmini'";
					$resul = mysql_query($query);

					if(mysql_num_rows($resul)) 
							while($row = mysql_fetch_object($resul))
								$liste .= "'$row->produit', ";

					if($liste!="") {
								$liste = substr($liste, 0, strlen($liste) - 2);
								$search .= " and $produit->table.id in($liste)";
					}
					else return "";
				}	
			
				$i++;

			}
		
		}
				
			$produit = new Produit();
			$produitdesc = new Produitdesc();
			
		
			if($motcle){
				$motcle = strip_tags($motcle);
				$liste="";
				
  				$query = "select * from $produitdesc->table  LEFT JOIN $produit->table ON $produit->table.id=$produitdesc->table.produit WHERE $produit->table.ref='$motcle' or titre like '% $motcle%' or titre like '%$motcle %' OR titre='$motcle' OR chapo like '% $motcle%' OR chapo like '%$motcle %' OR description like '% $motcle%' OR description like '%$motcle %' OR postscriptum like '% $motcle%' OR postscriptum like '%$motcle %'";
			
			    $resul = mysql_query($query, $produitdesc->link);
				$nbres = mysql_num_rows($resul);

			
				if(!$nbres) return "";
				
			
				while( $row = mysql_fetch_object($resul) ){
					$liste .= "'$row->produit', ";
				}
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				$search .= "and $produit->table.id in ($liste)";
			
			}
		
		if($classement != "titre" && $classement != "titreinverse"){
			$query = "select * from $produit->table where 1 $search $order $limit";
			$classement = "produit";
	    }
	
		else {
			$query = "select * from $produit->table, $produitdesc->table where $produit->table.id=$produitdesc->table.produit and $produitdesc->table.lang=\"" . $_SESSION['navig']->lang . "\" $search $order $limit";
			$classement = "produitdesc";
		}
            
		$resul = mysql_query($query, $produit->link);
		$nbres = mysql_num_rows($resul);
		$saveReq = "select * from $produit->table where 1 $search";

		if(!$nbres) return "";
		// substitutions
		if($type) return $query;
		
		$saveReq = str_replace("*", "count(*) as totcount", $saveReq);
		$saveRes = mysql_query($saveReq);
		$countRes = mysql_result($saveRes, 0, "totcount") . " ";
	
		$compt = 0;
		
		while( $row = mysql_fetch_object($resul) ){
			
			$compt++;
			
			if($passage != "" && $comptbloc>$passage-1)
			      break;
			
			if($num>0) 
				if($comptbloc>=ceil($countRes/$num) && $bloc!="") continue;

			if($comptbloc == 0) $debcourant=0;
			else $debcourant = $num * ($comptbloc);
			$comptbloc++;
						
			if($classement == "produit")
				$prodid = $row->id;
			else 
				$prodid = $row->produit;
				
			$rubriquedesc = new Rubriquedesc();
			$rubriquedesc->charger($row->rubrique, $_SESSION['navig']->lang);
		
			$produitdesc->charger($prodid, $_SESSION['navig']->lang);
				
			$temp = $texte;
			
			if( $row->promo == "1" ) $temp = preg_replace("/\#PROMO\[([^]]*)\]\[([^]]*)\]/", "\\1", $temp);
	 		else $temp = preg_replace("/\#PROMO\[([^]]*)\]\[([^]]*)\]/", "\\2", $temp);
	 		
			if( $row->promo == "1" && $row->prix) $pourcentage =  ceil((100 * ($row->prix - $row->prix2)/$row->prix));

			$prixorig = $row->prix;
			$prix2orig = $row->prix2;
			
			$prix = $row->prix - ($row->prix * $_SESSION['navig']->client->pourcentage / 100);
			$prix2 = $row->prix2 - ($row->prix2 * $_SESSION['navig']->client->pourcentage / 100);
			
			$ecotaxe = $row->ecotaxe;
			
			$pays = new Pays();
			$pays->charger($_SESSION['navig']->client->pays);
			
			$zone = new Zone();
			$zone->charger($pays->zone);
			
			$prixht = $prix/(1+$row->tva/100);
			$prix2ht = $prix2/(1+$row->tva/100);
			$prixoright = $prixorig/(1+$row->tva/100);
			$prix2oright = $prix2orig/(1+$row->tva/100);			
			
			$ecotaxeht = $row->ecotaxe/(1+$row->tva/100);
				
			
			$prix = round($prix, 2);
			$prix2 = round($prix2, 2);
			$prixht = round($prixht, 2);
			$prix2ht = round($prix2ht, 2);
			$prixorig = round($prixorig, 2);
			$prix2orig = round($prix2orig, 2);
			$prixoright = round($prixoright, 2);
			$prix2oright = round($prix2oright, 2);
								
			$prix = number_format($prix, 2, ".", ""); 
			$prix2 = number_format($prix2, 2, ".", ""); 
			$prixht = number_format($prixht, 2, ".", ""); 
			$prix2ht = number_format($prix2ht, 2, ".", ""); 
			$prixorig = number_format($prixorig, 2, ".", ""); 
			$prix2orig = number_format($prix2orig, 2, ".", ""); 
			$prixoright = number_format($prixoright, 2, ".", ""); 
			$prix2oright = number_format($prix2oright, 2, ".", "");
			
			if($deb != "" && !$page) $debcourant+=$deb-1;
			
			$temp = str_replace("#REF", "$row->ref", $temp);
			$temp = str_replace("#COMPT", "$compt", $temp);
			$temp = str_replace("#DATE", substr($row->datemodif, 0, 10), $temp);
			$temp = str_replace("#HEURE", substr($row->datemodif, 11), $temp);
			$temp = str_replace("#DEBCOURANT", "$debcourant", $temp);
			$temp = str_replace("#ID", "$prodid", $temp);	
			$temp = str_replace("#PRIX2ORIGHT", "$prix2oright", $temp);	
			$temp = str_replace("#PRIX2ORIG", "$prix2orig", $temp);	
			$temp = str_replace("#PRIXORIGHT", "$prixoright", $temp);				
			$temp = str_replace("#PRIXORIG", "$prixorig", $temp);
			$temp = str_replace("#PRIX2HT", "$prix2ht", $temp);	
			$temp = str_replace("#PRIX2", "$prix2", $temp);	
			$temp = str_replace("#PRIXHT", "$prixht", $temp);				
			$temp = str_replace("#PRIX", "$prix", $temp);
			$temp = str_replace("#PROMO", "$row->promo", $temp);	
			$temp = str_replace("#TVA", "$row->tva", $temp);	
			$temp = str_replace("#ECOTAXEHT", "$ecotaxeht", $temp);	
			$temp = str_replace("#ECOTAXE", "$row->ecotaxe", $temp);	
			$temp = str_replace("#STOCK", "$row->stock", $temp);	
			$temp = str_replace("#POURCENTAGE", "$pourcentage", $temp);	
			$temp = str_replace("#RUBRIQUE", "$row->rubrique", $temp);			
			$temp = str_replace("#PERSO", "$row->perso", $temp);			
			$temp = str_replace("#POIDS", "$row->poids", $temp);			
			$temp = str_replace("#TITRE", "$produitdesc->titre", $temp);
			$temp = str_replace("#STRIPTITRE", strip_tags($produitdesc->titre), $temp);	
			$temp = str_replace("#CHAPO", "$produitdesc->chapo", $temp);	
			$temp = str_replace("#STRIPCHAPO", strip_tags($produitdesc->chapo), $temp);	
			$temp = str_replace("#DESCRIPTION", html_entity_decode(str_replace("../","",$produitdesc->description)), $temp);
			$temp = str_replace("#POSTSCRIPTUM", "$produitdesc->postscriptum", $temp);	
			$temp = str_replace("#STRIPDESCRIPTION", strip_tags($produitdesc->description), $temp);	
			$temp = str_replace("#URL", "produit.php?ref=" . "$row->ref" . "&amp;id_rubrique=" . "$row->rubrique", $temp);	
			$temp = str_replace("#REWRITEURL", rewrite_prod("$row->ref"), $temp);	
			$temp = str_replace("#GARANTIE", "$row->garantie", $temp);			
			$temp = str_replace("#PANIERAPPEND", "panier.php?action=" . "ajouter" . "&amp;" . "ref=" . "$row->ref" . "&amp;" . "append=1", $temp);	
			$temp = str_replace("#PANIER", "panier.php?action=" . "ajouter" . "&amp;" . "ref=" . "$row->ref" , $temp);	
			$temp = str_replace("#RUBTITRE", "$rubriquedesc->titre", $temp);
			
			
			$res .= $temp;
			
		}
	

		return $res;
	
	}

		
	function boucleContenu($texte, $args, $type=0){
			global $page, $totbloc, $id_contenu, $pagesess;
			
			// récupération des arguments
			$dossier = lireTag($args, "dossier", "int");
			$ligne = lireTag($args, "ligne", "int");
			$deb = lireTag($args, "deb", "int");
			$num = lireTag($args, "num", "int");
			$bloc = lireTag($args, "bloc", "int");
			$id = lireTag($args, "id", "int");
			$motcle = lireTag($args, "motcle", "int+-");
			$classement = lireTag($args, "classement", "string");
			$aleatoire = lireTag($args, "aleatoire", "int");
			$produit = lireTag($args, "produit", "int");
			$rubrique = lireTag($args, "rubrique", "int");
			$profondeur = lireTag($args, "profondeur", "int");		
			$courant = lireTag($args, "courant", "int");			
			$exclusion = lireTag($args, "exclusion", "int_list");	
			
			if($bloc) $totbloc=$bloc;
			if(!$deb) $deb=0;

			if($page) $_SESSION['navig']->page = $page;
			if(isset($pagesess) && $pagesess == 1) $page =  $_SESSION['navig']->page;

			if(!$page ||  $page==1 ) $page=0; 

			if(!$totbloc) $totbloc=1;
			if($page) $deb = ($page-1)*$totbloc*$num+$deb;

			// initialisation de variables
			$search = "";
			$order = "";
			$comptbloc=0;
			$virg="";
			$limit="";
			$res="";
			
			// preparation de la requete
			if($dossier!=""){
				if($profondeur == "") $profondeur=0;
				$rec = arbreBoucle_dos($dossier, $profondeur);
				if(substr($rec, strlen($rec)-1) == ",") $rec = substr($rec, 0, strlen($rec)-1);
				if($rec) $virg=",";
				
				 $search .= " and dossier in('$dossier'$virg$rec)";
			}
			
			if($ligne == "") $ligne="1";
			
			$search .= " and ligne=\"$ligne\"";

			if($id!="") $search.=" and id in($id)";
			if($courant == "1") $search .=" and id='$id_contenu'";
			else if($courant == "0") $search .=" and id!='$id_contenu'";
			if($exclusion!="") $search .= " and id not in($exclusion)";

			if($bloc == "-1") $bloc = "999999999";
			if($bloc!="" && $num!="") $limit .= " limit $deb,$bloc";
			else if($num!="") $limit .= " limit $deb,$num";
			
			$liste= "";
			
			if($rubrique != "" || $produit !=""){
				if($rubrique){
					$type_obj = 0; 
					$objet = $rubrique;
				}
				
				else{
					 $type_obj = 1;
					 $objet = $produit;
				}
				
				$contenuassoc = new Contenuassoc();
				$query = "select * from $contenuassoc->table where objet=\"" . $objet . "\" and type=\"" . $type_obj . "\"";
				$resul = mysql_query($query, $contenuassoc->link);
				while($row = mysql_fetch_object($resul)) 
					$liste .= "'" . $row->contenu . "',"; 
					
					
				$liste = substr($liste, 0, strlen($liste)-1);
				if($liste != "") $search .= " and id in ($liste)";	
				else $search .= " and id in ('')";
				
				$type_obj="";
			}

		
			if($aleatoire) $order = "order by "  . " RAND()";
			else if($classement == "manuel") $order = "order by classement";
			else if($classement == "inverse") $order = "order by classement desc";
			
			
			$contenu = new Contenu();
			$contenudesc = new Contenudesc();
			
			if($motcle){
				$liste="";
				
				$query = "select * from $contenudesc->table  LEFT JOIN $contenu->table ON $contenu->table.id=$contenudesc->table.id WHERE titre like '% $motcle%' or titre like '%$motcle %' OR titre='$motcle' OR chapo like '% $motcle%' OR chapo like '%$motcle %' OR description like '% $motcle%' OR description like '%$motcle %' OR postscriptum like '% $motcle%' OR postscriptum like '%$motcle %'";
			
			    $resul = mysql_query($query, $contenudesc->link);
				$nbres = mysql_num_rows($resul);

			
				if(!$nbres) return "";
				
			
				while( $row = mysql_fetch_object($resul) ){
					$liste .= "'$row->contenu', ";
				}
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				$query = "select * from $contenu->table where id in ($liste) and ligne=\"$ligne\" $limit";
				$saveReq = "select * from $contenu->table where id in ($liste) and ligne=\"$ligne\"";
			}
			
		else $query = "select * from $contenu->table where 1 $search $order $limit";
		$saveReq = "select * from $contenu->table where 1 $search";
		
		$resul = mysql_query($query, $contenu->link);
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
		// substitutions
		if($type) return $query;

		$saveReq = str_replace("*", "count(*) as totcount", $saveReq);
		$saveRes = mysql_query($saveReq);
		$countRes = mysql_result($saveRes, 0, "totcount") . " ";
		
		$compt = 1;
		
		while( $row = mysql_fetch_object($resul) ){
	
			if($num>0) 
				if($comptbloc>=ceil($countRes/$num) && $bloc!="") continue;
				
			if($comptbloc == 0) $debcourant=0;
			else $debcourant = $num * ($comptbloc);
			$comptbloc++;
			
			$dossierdesc = new Dossierdesc();
			$dossierdesc->charger($row->dossier, $_SESSION['navig']->lang);
			$contenudesc = new Contenudesc();
			$contenudesc->charger($row->id, $_SESSION['navig']->lang);
				
			$temp = $texte;
			
			$temp = str_replace("#DATE", substr($row->datemodif, 0, 10), $temp);
			$temp = str_replace("#HEURE", substr($row->datemodif, 11), $temp);
			$temp = str_replace("#DEBCOURANT", "$debcourant", $temp);
			$temp = str_replace("#ID", "$row->id", $temp);		
			$temp = str_replace("#DOSSIER", "$row->dossier", $temp);			
			$temp = str_replace("#TITRE", "$contenudesc->titre", $temp);
			$temp = str_replace("#STRIPTITRE", strip_tags($contenudesc->titre), $temp);	
			$temp = str_replace("#CHAPO", "$contenudesc->chapo", $temp);	
			$temp = str_replace("#STRIPCHAPO", strip_tags($contenudesc->chapo), $temp);	
			$temp = str_replace("#DESCRIPTION", html_entity_decode(str_replace("../","",$contenudesc->description)), $temp);
			$temp = str_replace("#POSTSCRIPTUM", "$contenudesc->postscriptum", $temp);	
			$temp = str_replace("#STRIPDESCRIPTION", strip_tags($contenudesc->description), $temp);	
			$temp = str_replace("#URL", "contenu.php?id_contenu=" . "$row->id&amp;id_dossier=" . $row->dossier, $temp);	
			$temp = str_replace("#REWRITEURL", rewrite_cont("$row->id"), $temp);			
			$temp = str_replace("#DOSTITRE", "$dossierdesc->titre", $temp);
			$temp = str_replace("#PRODUIT", "$produit", $temp);
			$temp = str_replace("#RUBRIQUE", "$rubrique", $temp);			
			$temp = str_replace("#COMPT", "$compt", $temp);		
			
			$res .= $temp;
			
			$compt ++;
		}
	
	
		return $res;
	
	}


	function boucleContenuassoc($texte, $args){
        $objet = lireTag($args, "objet", "int");
        $typeobj = lireTag($args, "typeobj", "int");
        $contenu = lireTag($args, "contenu", "int");
        $classement = lireTag($args, "classement", "string");
        $num = lireTag($args, "num", "int");
      	$deb = lireTag($args, "deb", "int");
		
		if(!$deb) $deb=0;
		
		$search = "";

		if($objet != "")
        	$search .= " and objet=\"$objet\"";

		if($typeobj != "")
        	$search .= " and type=\"$typeobj\"";

		if($contenu != "")
        	$search .= " and contenu=\"$contenu\"";

		$order="";
		$limit="";
		
		if($num!="") $limit .= " limit $deb,$num";
		
		if($classement == "manuel")
			$order = "order by classement";
		
		$contenuassoc = new Contenuassoc();
		$query = "select * from $contenuassoc->table where 1 $search $order $limit";
		$resul = mysql_query($query, $contenuassoc->link);
		
		if(! mysql_num_rows($resul))
			return "";
			
		$compt = 1;
		while($row = mysql_fetch_object($resul)){
              $temp = str_replace("#OBJET", $row->objet, $texte);
              $temp = str_replace("#TYPE", $row->type, $temp);
              $temp = str_replace("#CONTENU", $row->contenu, $temp);
			  $temp = str_replace("#COMPTE",$compt,$temp);

			  $compt++;
              $res .= $temp;

        }

              return $res;
		
	}
	
	function bouclePage($texte, $args){
			global $page, $id_rubrique, $id_dossier;
			
			// récupération des arguments
			
			$num = lireTag($args, "num", "int");
			$courante = lireTag($args, "courante", "int");
			$pagecourante = lireTag($args, "pagecourante", "int");
			$typeaff = lireTag($args, "typeaff", "int");
			$max = lireTag($args, "max", "int");
			$affmin = lireTag($args, "affmin", "int");
            $avance = lireTag($args, "avance", "int");
			$type_page = lireTag($args, "type_page", "int");
			
			$i="";
			
			if( $page<=0) $page=1;
			$bpage=$page;
			$res="";
				
				$cnx = new Cnx();
				if(!$type_page)
			    	$query = boucleProduit($texte, str_replace("num", "null", $args), 1);
				else
			    	$query = boucleContenu($texte, str_replace("num", "null", $args), 1);
				
				if($query != ""){ 
					$pos = strpos($query, "limit");
					if($pos>0) $query = substr($query, 0, $pos);
	
					$resul = mysql_query($query, $cnx->link);
					$nbres = mysql_num_rows($resul);
				}
				
				else $nbres = 0;

				$page = $bpage;
				
				$nbpage = ceil($nbres/$num);
				if($page+1>$nbpage) $pagesuiv=$page;
				else $pagesuiv=$page+1;
				
				if($page-1<=0) $pageprec=1;
				else $pageprec=$page-1;				


				if($nbpage<$affmin) return;
				if($nbpage == 1) return;
				
				if($typeaff == 1){
					if(!$max) $max=$nbpage+1;
					if($page && $max && $page>$max) $i=ceil(($page)/$max)*$max-$max+1;	
				
					if($i == 0) $i=1;
				
					$fin = $i+$max;	


					
					
					for( ; $i<$nbpage+1 && $i<$fin; $i++ ){
					
						$temp = str_replace("#PAGE_NUM", "$i", $texte);		
						$temp = str_replace("#PAGE_SUIV", "$pagesuiv", $temp);
						$temp = str_replace("#PAGE_PREC", "$pageprec", $temp);
						$temp = str_replace("#RUBRIQUE", "$id_rubrique", $temp);
						$temp = str_replace("#DOSSIER", "$id_dossier", $temp);
				
						if($pagecourante && $pagecourante == $i){		

							if($courante =="1" && $page == $i ) $res .= $temp;	
							else if($courante == "0" && $page != $i ) $res .= $temp;	
							else if($courante == "") $res .= $temp;
						}	
						
						else if(!$pagecourante) $res .= $temp;								
					}
				
				}
				
                else if($typeaff == "0" && ($avance == "precedente" && $pageprec != $page)){

                        $temp = str_replace("#PAGE_NUM", "$page", $texte);
                        $temp = str_replace("#PAGE_PREC", "$pageprec", $temp);
                        $temp = str_replace("#RUBRIQUE", "$id_rubrique", $temp);
						$temp = str_replace("#DOSSIER", "$id_dossier", $temp);
                        $res .= $temp;
                }

                else if($typeaff == "0" && ($avance == "suivante" && $pagesuiv != $page)){

                        $temp = str_replace("#PAGE_NUM", "$page", $texte);
                        $temp = str_replace("#PAGE_SUIV", "$pagesuiv", $temp);
                        $temp = str_replace("#RUBRIQUE", "$id_rubrique", $temp);
						$temp = str_replace("#DOSSIER", "$id_dossier", $temp);
                        $res .= $temp;
                }

                else if($typeaff == "0" && $avance == ""){

                        $temp = str_replace("#PAGE_NUM", "$page", $texte);
                        $temp = str_replace("#PAGE_SUIV", "$pagesuiv", $temp);
                        $temp = str_replace("#PAGE_PREC", "$pageprec", $temp);
                        $temp = str_replace("#RUBRIQUE", "$id_rubrique", $temp);
						$temp = str_replace("#DOSSIER", "$id_dossier", $temp);
                        $res .= $temp;
                }					
			
		
				return $res;
			
			
	}
	

	function bouclePanier($texte, $args){

		$deb = lireTag($args, "deb", "int");
		$fin = lireTag($args, "fin", "int");
		$dernier = lireTag($args, "dernier", "int");
		$ref = lireTag($args, "ref", "string");
		
		if(!$deb) $deb=0;
		if(!$fin) $fin=$_SESSION['navig']->panier->nbart;
		if($dernier == 1) 
			$deb = $_SESSION['navig']->panier->nbart - 1;
				
		$total = 0;
		$res="";
		
		if(! $_SESSION['navig']->panier->nbart) return;
		
		for($i=$deb; $i<$fin; $i++){
			
			if($ref != "" && $_SESSION['navig']->panier->tabarticle[$i]->produit->ref != $ref)
				continue;
				
			$plus = $_SESSION['navig']->panier->tabarticle[$i]->quantite+1;
			$moins = $_SESSION['navig']->panier->tabarticle[$i]->quantite-1;
			
			if($moins == 0) $moins++;
			
			$quantite =  $_SESSION['navig']->panier->tabarticle[$i]->quantite;
			$tva = $_SESSION['navig']->panier->tabarticle[$i]->produit->tva;
			
			if( ! $_SESSION['navig']->panier->tabarticle[$i]->produit->promo)
				$prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix * $_SESSION['navig']->client->pourcentage / 100);
			else $prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 * $_SESSION['navig']->client->pourcentage / 100);	

            $prixht=round($prix/(1+($tva/100)),2);
            $totalht = $prixht*$quantite;
			
			$total=round($prix*$quantite, 2);
			$prix = round($prix, 2);
			
			$port = port();
			if($port<0)
				$port = 0;
				
			$totcmdport = $total + $port;
			
			$totsansport = $_SESSION['navig']->panier->total();

			$pays = new Pays();
			$pays->charger($_SESSION['navig']->client->pays);
			
			$zone = new Zone();
			$zone->charger($pays->zone);
						
			$portht = round($port*100/(100+$tva), 2);
			$totcmdportht = round($totcmdport*100/(100+$tva), 2);
			$totsansportht = round($totsansport*100/(100+$tva), 2);
		
			$produitdesc = new Produitdesc();
			$produitdesc->charger($_SESSION['navig']->panier->tabarticle[$i]->produit->id,  $_SESSION['navig']->lang);

			$declidisp = new Declidisp();
			$declidispdesc = new Declidispdesc();
			$declinaison = new Declinaison();
			$declinaisondesc = new Declinaisondesc();
			
			$dectexte = "";
			$decval = "";
			

			if($_SESSION['navig']->adresse){
				$adr = new Adresse();
				$adr->charger($_SESSION['navig']->adresse);
				$idpays = $adr->pays;
			} else {
				$idpays = $_SESSION['navig']->client->pays;
			}

			$pays = new Pays();
			$pays->charger($idpays);
			
		    for($compt = 0; $compt<count($_SESSION['navig']->panier->tabarticle[$i]->perso); $compt++){
				$tperso = $_SESSION['navig']->panier->tabarticle[$i]->perso[$compt];
				$declinaison->charger($tperso->declinaison);
				// recup valeur declidisp ou string
				if($declinaison->isDeclidisp($tperso->declinaison)){
					$declidisp->charger($tperso->valeur);
					$declidispdesc->charger($declidisp->id);
					$decval .= $declidispdesc->titre . " ";
				}
				
				else $decval .= $tperso->valeur . " ";
				
				// recup declinaison associee
				$declinaisondesc->charger($tperso->declinaison, $_SESSION['navig']->lang);
				
				$dectexte .= $declinaisondesc->titre . " " . $declidispdesc->titre . " ";
			}	
		
			$prix = number_format($prix, 2, ".", ""); 
			$total = number_format($total, 2, ".", ""); 
			$totcmdport = number_format($totcmdport, 2); 
			$port = number_format($port, 2, ".", ""); 
			$prixht = number_format($prixht, 2, ".", "");
			$totalht = number_format($totalht, 2, ".", "");

			if($pays->tva != "" && (! $pays->tva || ($pays->tva && $_SESSION['navig']->client->intracom != ""))){
				$prix = $prixht; 
				$total = $totalht; 
			}
			
			$temp = str_replace("#REF", $_SESSION['navig']->panier->tabarticle[$i]->produit->ref, $texte);
			$temp = str_replace("#TITRE", $produitdesc->titre, $temp);
			$temp = str_replace("#QUANTITE", "$quantite", $temp);
			$temp = str_replace("#PRODUIT", $produitdesc->produit, $temp);
			$temp = str_replace("#PRIXUHT", "$prixht", $temp);
            $temp = str_replace("#PRIXHT", "$prixht", $temp);
			$temp = str_replace("#TOTALHT", "$totalht", $temp);	
			$temp = str_replace("#PRIXU", "$prix", $temp);
			$temp = str_replace("#PRIX", "$prix", $temp);
            $temp = str_replace("#TVA", "$tva", $temp);
			$temp = str_replace("#TOTAL", "$total", $temp);			
			$temp = str_replace("#ID", $_SESSION['navig']->panier->tabarticle[$i]->produit->id, $temp);
			$temp = str_replace("#ARTICLE", "$i", $temp);
			$temp = str_replace("#PLUSURL", "panier.php?action=" . "modifier" . "&amp;" . "article=" . $i . "&amp;" . "quantite=" . $plus, $temp);			
			$temp = str_replace("#MOINSURL", "panier.php?action=" . "modifier" . "&amp;" . "article=" . $i . "&amp;" . "quantite=" . $moins, $temp);
			$temp = str_replace("#SUPPRURL", "panier.php?action=" . "supprimer" . "&amp;" . "article=" . $i, $temp);			
			$temp = str_replace("#PRODURL", "produit.php?ref=".$_SESSION['navig']->panier->tabarticle[$i]->produit->ref . "&amp;" . "id_rubrique=" . $_SESSION['navig']->panier->tabarticle[$i]->produit->rubrique, $temp);		
			$temp = str_replace("#TOTSANSPORTHT", "$totsansportht", $temp);
			$temp = str_replace("#PORTHT", "$portht", $temp);
			$temp = str_replace("#TOTPORTHT", "$totcmdportht", $temp);
			$temp = str_replace("#TOTSANSPORT", "$totsansport", $temp);
			$temp = str_replace("#PORT", "$port", $temp);
			$temp = str_replace("#TOTPORT", "$totcmdport", $temp);
			$temp = str_replace("#DECTEXTE", "$dectexte", $temp);
			$temp = str_replace("#DECVAL", "$decval", $temp);

			$res .= $temp;
		}
		
		return $res;
	
	}
	
		
	function boucleQuantite($texte, $args){
		// récupération des arguments

          $res="";

          $article = lireTag($args, "article", "int");
          $ref = lireTag($args, "ref", "string");
          $max = lireTag($args, "max", "int");
          $min = lireTag($args, "min", "int");
          $force = lireTag($args, "force", "int");
          $valeur = lireTag($args, "valeur", "int");


          $prodtemp = new Produit();
          if($article != "")
                  $prodtemp->charger($_SESSION['navig']->panier->tabarticle[$article]->produit->ref);
          else if($ref != "")
                  $prodtemp->charger($ref);
          if($min == "") $min=1;

          if($max == "")
                  $max = $prodtemp->stock;

          if($max == "" && $force == "")
                  return;

          if($min > $prodtemp->stock && $force == "") return;

          $j = 0;

          if($force != ""){
                  $min = 1;
                  $max = $valeur;
          }

          for($i=$min; $i<=$max; $i++){
                  if($i==$_SESSION['navig']->panier->tabarticle[$article]->quantite) $selected="selected=\"selected\"";
                  else $selected="";

                  $temp = str_replace("#NUM", "$i", $texte);
                  $temp = str_replace("#SELECTED", $selected, $temp);
                  $temp = str_replace("#REF", $ref, $temp);

                  $res.="$temp";
          }


          return $res;

	}
		
	function boucleChemin($texte, $args){
		global $id_rubrique;

		// récupération des arguments

		$rubrique = lireTag($args, "rubrique", "int");		
		$profondeur = lireTag($args, "profondeur", "int");		
		$niveau = lireTag($args, "niveau", "int");		
		
		if($rubrique=="") return "";

		$res="";
		
		$trubrique = new Rubrique();
		$trubrique->charger($rubrique);
		$trubriquedesc = new Rubriquedesc();
		
		$i =  0;
		
        if(! $trubrique->parent)
                return "";

        $rubtab = "";
        $tmp = new Rubrique();
        $tmp->charger($trubrique->parent);
        $rubtab[$i] = new Rubrique();
        $rubtab[$i++] = $tmp;

        while($tmp->parent != 0) {
                $tmp = new Rubrique();
                $tmp->charger($rubtab[$i-1]->parent);

                $rubtab[$i] = new Rubrique();
                $rubtab[$i++] = $tmp;
        }

        $compt = 0;
        
        for($i=count($rubtab)-1; $i>=0; $i--){
                        if($profondeur != "" && $compt==$profondeur) break;
                        if($niveau != "" && $niveau != $compt +1 ) { $compt++; continue; }          
                        $trubriquedesc->charger($rubtab[$i]->id, $_SESSION['navig']->lang);
                        $temp = str_replace("#ID", $rubtab[$i]->id, $texte);
                        $temp = str_replace("#TITRE", "$trubriquedesc->titre", $temp);
                        $temp = str_replace("#URL", "rubrique.php?id_rubrique=" . $rubtab[$i]->id, $temp);
                        $temp = str_replace("#REWRITEURL", rewrite_rub($rubtab[$i]->id), $temp);

                        $compt++;
                        
                        $res .= $temp;
        }
	
		return $res;
	
	}	

	function boucleChemindos($texte, $args){
		global $id_dossier;

		// récupération des arguments

		$dossier = lireTag($args, "dossier", "int");		
		$profondeur = lireTag($args, "profondeur", "int");		
		$niveau = lireTag($args, "niveau", "int");		

		if($dossier=="") return "";

		$res="";

		$tdossier = new Dossier();
		$tdossier->charger($dossier);
		$tdossierdesc = new Dossierdesc();

		$i =  0;

	    if(! $tdossier->parent)
	            return "";

	    $dostab = "";
	    $tmp = new Dossier();
	    $tmp->charger($tdossier->parent);
	    $dostab[$i] = new Dossier();
	    $dostab[$i++] = $tmp;

	    while($tmp->parent != 0) {
	            $tmp = new Dossier();
	            $tmp->charger($dostab[$i-1]->parent);

	            $dostab[$i] = new Dossier();
	            $dostab[$i++] = $tmp;
	    }

	    $compt = 0;

	    for($i=count($dostab)-1; $i>=0; $i--){
	                    if($profondeur != "" && $compt==$profondeur) break;
	                    if($niveau != "" && $niveau != $compt +1 ) { $compt++; continue; }          
	                    $tdossierdesc->charger($dostab[$i]->id, $_SESSION['navig']->lang);
	                    $temp = str_replace("#ID", $dostab[$i]->id, $texte);
	                    $temp = str_replace("#TITRE", "$tdossierdesc->titre", $temp);
	                    $temp = str_replace("#URL", "dossier.php?id_dossier=" . $dostab[$i]->id, $temp);
	                    $temp = str_replace("#REWRITEURL", rewrite_dos($dostab[$i]->id), $temp);

	                    $compt++;

	                    $res .= $temp;
	    }

		return $res;

	}	
	
	function bouclePaiement($texte, $args){

		$res="";
		
		$id = lireTag($args, "id", "int");		
		$nom = lireTag($args, "nom", "string");		
		$exclusion = lireTag($args, "exclusion", "string_list");		

		$search ="";
	
		// preparation de la requete
		if($id!="")  $search.=" and id=\"$id\"";
		if($nom!="")  $search.=" and nom=\"$nom\"";
		
		if($exclusion!=""){
			$liste="";
			$tabexcl = explode(",", $exclusion);
			for($i=0;$i<count($tabexcl);$i++)
				$liste .= "'" . $tabexcl[$i] . "'" . ",";
			if(substr($liste, strlen($liste)-1) == ",")
				$liste = substr($liste, 0, strlen($liste)-1);
				
			$search.=" and nom not in ($liste)";
		} 
		
		
 		$modules = new Modules();
		
		$query = "select * from $modules->table where type='1' and actif='1' $search order by classement";
	//	$resul = mysql_query($query, $modules->link);
		$nbres = CacheBase::getCache()->mysql_query_count($query, $modules->link);
	
		if(!$nbres) return "";
		
		//$resul = mysql_query($query, $modules->link);
			$resul = CacheBase::getCache()->mysql_query($query, $modules->link);
		
//		while($row = mysql_fetch_object($resul)){
		foreach($resul as $row) {
		
			$modules = new Modules();
			$modules->charger_id($row->id);
			
			$nom = $modules->nom;
			$nom[0] = strtoupper($nom[0]);

			include_once("client/plugins/" . $modules->nom . "/$nom.class.php");
			$tmpobj = new $nom();
			
			$titre = $tmpobj->getTitre();
			$chapo = $tmpobj->getChapo();
			$description = $tmpobj->getDescription();
										
			$temp = str_replace("#ID", "$row->id", $texte);
			$temp = str_replace("#URLPAYER", "commande.php?action=paiement&amp;type_paiement=" . $row->id, $temp);
			$temp = str_replace("#LOGO", "client/plugins/" . "$row->nom" . "/logo.jpg", $temp);
			$temp = str_replace("#TITRE", $titre, $temp);
			$temp = str_replace("#CHAPO", $chapo, $temp);
			$temp = str_replace("#DESCRIPTION", $description, $temp);		
			$temp = str_replace("#NOM", $row->nom, $temp);		
			$res .= $temp;
		}
	

		return $res;
	
	}	

	function bouclePays($texte, $args){


		$id = lireTag($args, "id", "int");		
		$zone = lireTag($args, "zone", "int");	 
		$zdefinie = lireTag($args, "zdefinie", "int");
        $select = lireTag($args, "select", "int");
        $default = lireTag($args, "default", "int");
        $exclusion = lireTag($args, "exclusion", "int");


		$search ="";
		$res="";
		
		// preparation de la requete
		if($id!="")  $search.=" and id=\"$id\"";
		if($zone!="")  $search.=" and zone=\"$zone\"";
		if($zdefinie!="") $search.=" and zone<>\"-1\"";
		if($default!="") $search.=" and `default`=\"1\"";
		if($exclusion!="") $search.=" and id not in($exclusion)";
	
		if($_SESSION['navig']->lang == "") $lang=1; else $lang=$_SESSION['navig']->lang ;
		
		$pays = new Pays();
		$paysdesc = new Paysdesc();
	
		$query = "select * from $pays->table where 1 $search";
		$resul = mysql_query($query, $pays->link);

		$liste=""; 
		while( $row = mysql_fetch_object($resul))					
			 $liste .= "'$row->id', ";
			
		$liste = substr($liste, 0, strlen($liste) - 2);
	
		if(!$liste) $liste="''";
		
        $query = "select * from $paysdesc->table where pays in ($liste) and lang='$lang' order by titre";

		$resul = mysql_query($query, $paysdesc->link);
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){
			$paysdesc->charger_id($row->id);
			$pays->charger($paysdesc->pays);
			$temp = str_replace("#ID", "$row->pays", $texte);
			$temp = str_replace("#TITRE", "$paysdesc->titre", $temp);
			$temp = str_replace("#CHAPO", "$paysdesc->chapo", $temp);
			$temp = str_replace("#DESCRIPTION", "$paysdesc->description", $temp);	
			if(($_SESSION['navig']->formcli->pays == $row->pays || $_SESSION['navig']->client->pays == $row->pays) && $select=="") 	
				$temp = str_replace("#SELECTED", "selected=\"selected\"", $temp);
			if($select !="" && $select == $row->pays) $temp = str_replace("#SELECTED", "selected=\"selected\"", $temp);	
			else $temp = str_replace("#SELECTED", "", $temp);
			if($pays->default == "1") $temp = str_replace("#DEFAULT", "selected=\"selected\"", $temp);	
			else $temp = str_replace("#DEFAULT", "", $temp);
			$res .= $temp;
		}
	

		return $res;
	
	}	

	function boucleCaracteristique($texte, $args){

		global $caracteristique;
		
		$id = lireTag($args, "id", "int");		
		$rubrique = lireTag($args, "rubrique", "int");		
		$affiche = lireTag($args, "affiche", "int");		
		$produit = lireTag($args, "produit", "int");	
		$courante = lireTag($args, "courante", "int");	
				
		$search ="";
		$res="";
		
		// preparation de la requete
		 
		if($produit!=""){
			$tprod = new Produit();
			$tprod->charger_id($produit);
			$rubrique = $tprod->rubrique;
		}

		if($rubrique!="")  $search.=" and rubrique=\"$rubrique\"";
		if($id!="")  $search.=" and caracteristique in($id)";
		
		
		$rubcaracteristique = new Rubcaracteristique();
		$tmpcaracteristique = new Caracteristique();
		$tmpcaracteristiquedesc = new Caracteristiquedesc();
		
		
        $order = "order by $tmpcaracteristique->table.classement";

        $query = "select DISTINCT(caracteristique) from $rubcaracteristique->table,$tmpcaracteristique->table  where 1 $search and $rubcaracteristique->table.caracteristique=$tmpcaracteristique->table.id $order";
		//if($id != "") $query = "select * from $tmpcaracteristique->table where 1 $search";
		$resul = mysql_query($query, $rubcaracteristique->link);
	
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){

			if($courante == "1" && ($id  != $caracteristique && ! strstr($caracteristique, $id . "-")))
			   continue;

			else if($courante == "0" && ($id  == $caracteristique || strstr($caracteristique, $id . "-")))
				 continue;
							
			 $tmpcaracteristiquedesc->charger($row->caracteristique, $_SESSION['navig']->lang);
			 $temp = str_replace("#ID", "$row->caracteristique", $texte);

			$tmpcaracteristique->charger($tmpcaracteristiquedesc->caracteristique);
			
			if($tmpcaracteristique->affiche == "0" && $affiche == "1") continue;

			$temp = str_replace("#TITRE", "$tmpcaracteristiquedesc->titre", $temp);
			$temp = str_replace("#CHAPO", "$tmpcaracteristiquedesc->chapo", $temp);
			$temp = str_replace("#DESCRIPTION", "$tmpcaracteristiquedesc->description", $temp);		
			$temp = str_replace("#PRODUIT", "$produit", $temp);	
			
			$res .= $temp;
		}

		return $res;
	
	}	


	function boucleCaracdisp($texte, $args){

		global $caracdisp;
		
		$caracteristique = lireTag($args, "caracteristique", "int");		
		$etcaracteristique = lireTag($args, "etcaracteristique", "int+-");		
		$etcaracdisp = lireTag($args, "etcaracdisp", "int+-");	
		$stockmini = lireTag($args, "stockmini", "int");
		$courante = lireTag($args, "courante", "int");
		$rubrique = lireTag($args, "rubrique", "int");
		$classement = lireTag($args, "classement", "string");
		
		$deb = lireTag($args, "deb", "int");
		$num = lireTag($args, "num", "int");
		
		$id = lireTag($args, "caracdisp", "int");
		if($id == "")
			$id = lireTag($args, "id", "int");
			
		
		$idsave = $id;
		$liste="";
		$tabliste[0]="";		
		$res="";
		
		$caracteristiquesave = $caracteristique;
		
		if( preg_match( "/^$caracteristique-/", $etcaracteristique) || strstr($etcaracteristique, "-$caracteristique-") ) $deja="1";
		else $deja="0";
		
		
		$search ="";
		$limit="";
		
		// preparation de la requete
		if($caracteristique!="")  $search.=" and caracteristique=\"$caracteristique\"";
		if($id !="") $search.=" and id IN ($id)";
		if($classement == "alpha") $order="order by titre";
		else if($classement == "alphainv") $order="order by titre desc";
		
		if($deb =="")
			$deb = 0;
		
		if($num != "")
			$limit = "limit $deb,$num";
			
		$tcaracdisp = new Caracdisp();
		$tcaracdispdesc = new Caracdispdesc();
		
		
		$query = "select * from $tcaracdisp->table where 1 $search $limit";
		$resul = mysql_query($query, $tcaracdisp->link);

        if(! mysql_num_rows($resul))
                return "";

		$i=0;
				
		while($row = mysql_fetch_object($resul)){
				$liste .= "'" . $row->id . "',";
				$tabliste[$i++] = $row->id;
		}
			
		$liste = substr($liste, 0, strlen($liste) - 1);	

						
							
		if($classement != ""){
			$liste2="";
			$query = "select * from $tcaracdispdesc->table where caracdisp in ($liste) and lang='" . $_SESSION['navig']->lang . "' $order";
			$resul = mysql_query($query, $tcaracdispdesc->link);
					
		
		
			$i=0;
			
			while($row = mysql_fetch_object($resul)){
				$liste2 .= "'" . $row->caracdisp . "',";
				$tabliste2[$i++] = $row->caracdisp;
			}
			$liste2 = substr($liste2, 0, strlen($liste2) - 1);

		}
		
	
		if($classement != "" && isset($tabliste2)) $tabliste = $tabliste2;

		if(! count($tabliste))
			return "";
				
		$compt = 1;
					
		for($i=0; $i<count($tabliste); $i++){
			
			if($courante == "1" && ($id  != $caracdisp && ! strstr($caracdisp, "-" . $id )))
			   continue;
			
			else if($courante == "0" && ($id  == $caracdisp || strstr($caracdisp, "-" . $id)))
				 continue;
				
            if($stockmini != ""){
                  $caracvalch = new Caracval();
				  $prod = new Produit();
				  $querych = "select count(*) as nb from $prod->table,$caracvalch->table where $prod->table.id=$caracvalch->table.produit and $prod->table.ligne=1 and $caracvalch->table.caracdisp='" . $tabliste[$i] . "'";                  
				  $resulch = mysql_query($querych, $caracvalch->link);
                  if(mysql_result($resulch, 0, "nb")<$stockmini) continue;
            }
			$tcaracdispdesc->charger_caracdisp($tabliste[$i], $_SESSION['navig']->lang);
			$tcaracdisp->charger($tabliste[$i]);
			
			if(!$deja) $id=$tabliste[$i]."-"; else $id="";
			if(!$deja) $caracteristique=$tcaracdisp->caracteristique."-"; else $caracteristique ="";
			
			if($caracteristique == "$tcaracdisp->caracteristique" . "-" && $caracdisp == $tabliste[$i] . "-") 
				$selected = "selected=\"selected\""; else $selected = "";

			$temp = str_replace("#IDC", $id . $etcaracdisp, $texte);
			$temp = str_replace("#ID", $tcaracdisp->id, $temp);
			$temp = str_replace("#RUBRIQUE", "$rubrique", $temp);
			$temp = str_replace("#CARACTERISTIQUE", $tcaracdisp->caracteristique, $temp);			
			$temp = str_replace("#CARACTERISTIQUEC", $caracteristique . $etcaracteristique, $temp);
			$temp = str_replace("#TITRE", "$tcaracdispdesc->titre", $temp);
			$temp = str_replace("#SELECTED", "$selected", $temp);
			$temp = str_replace("#COMPT", $compt, $temp);
			$temp = str_replace("#NBRES", count($tabliste), $temp);
			$res .= $temp;
			
			$compt ++;
		}
	
		
		return $res;
	
	
	}	
	
	function boucleCaracval($texte, $args){
		$produit = lireTag($args, "produit", "int");
		$caracteristique = lireTag($args, "caracteristique", "int");		
		$valeur = lireTag($args, "valeur", "string+\s\'");		
		$classement = lireTag($args, "classement", "string");
		$article = lireTag($args, "article", "int");

		if($produit == "" || $caracteristique == "") return "";
		
		if(substr($valeur, 0, 1) == "!") {
			$different=1;
			$valeur = substr($valeur, 1);
		}
		else $different=0;

		$search ="";
		$res="";
		$order = "";
		
		// preparation de la requete
		$search.=" and caracteristique=\"$caracteristique\"";
		$search.=" and produit=\"$produit\"";

		if($classement == "caracdisp")
			$order = "order by caracdisp";
			
		$caracval = new Caracval();
		$prodtemp = new Produit();
		
		$query = "select * from $caracval->table where 1 $search $order";
		$resul = mysql_query($query, $caracval->link);

		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";

	
		while( $row = mysql_fetch_object($resul)){

			$temp = str_replace("#ID", $row->id, $texte);
				$temp = str_replace("#CARACDISP", $row->caracdisp, $temp);
				if($row->caracdisp != 0){
					$caracdispdesc = new Caracdispdesc();
					$caracdispdesc->charger_caracdisp($row->caracdisp, $_SESSION['navig']->lang);
					if($valeur != "" && (($different == 0 && $caracdispdesc->caracdisp != $valeur) || ($different == 1 && $caracdispdesc->caracdisp == $valeur))) continue;
					$temp = str_replace("#VALEUR", $caracdispdesc->titre, $temp);
					
				}
				
				else {
					if($valeur != "" && (($different == 0 && $row->valeur != $valeur) || ($different == 1 && $row->valeur == $valeur))) continue;
					if( $row->valeur=="") continue;
					$temp = str_replace("#VALEUR", $row->valeur, $temp);
				}
			
			$prodtemp->charger_id($produit);
			$temp = str_replace("#RUBRIQUE", $prodtemp->rubrique, $temp);
			$temp = str_replace("#REF",$prodtemp->ref, $temp);
			
			$caractemp = new Caracteristiquedesc();
			$caractemp ->charger($row->caracteristique,  $_SESSION['navig']->lang);
		
			$temp = str_replace("#TITRECARAC", $caractemp->titre, $temp);
			$temp = str_replace("#PRODUIT",$prodtemp->id,$temp);
			$temp = str_replace("#ARTICLE", $article, $temp);
				
			$res .= $temp;
		}
	
	
		return $res;
	
	}		
			
	function boucleAdresse($texte, $args){
	
		$adresse = new Adresse();
	

		// récupération des arguments

		$adresse_id = lireTag($args, "adresse", "int");		
		$client_id = lireTag($args, "client", "int");
		$defaut = lireTag($args, "defaut", "int");
		
		
		$search ="";
		$res="";
		
		$raison[1] = "Mme";
		$raison[2] = "Mlle";
		$raison[3] = "M";
				
		// preparation de la requete
		if($adresse_id!="")  $search.=" and id=\"$adresse_id\"";
		if($client_id!="")  $search.=" and client=\"$client_id\"";
		
		if($defaut =="1" && $adresse_id != "0")
			return "";
		
		else if($defaut =="0" && $adresse_id == "0")
			return "";
			
		if($adresse_id != "0" ) {
			$query = "select * from $adresse->table where 1 $search";
			$resul = mysql_query($query, $adresse->link);
	
			$nbres = mysql_num_rows($resul);
			if(!$nbres) return "";
			

			while( $row = mysql_fetch_object($resul)){
			
                if($row->raison == 1) $raison1f="selected=\"selected\"";
                else $raison1f="";

                if($row->raison == 2) $raison2f="selected=\"selected\"";
                else $raison2f="";

                if($row->raison == 3) $raison3f="selected=\"selected\"";
                else $raison3f="";			
			
			
				$temp = str_replace("#ID", "$row->id", $texte);
				$temp = str_replace("#PRENOM", "$row->prenom", $temp);
				$temp = str_replace("#NOM", "$row->nom", $temp);
     		    $temp = str_replace("#RAISON1F", "$raison1f", $temp);
       		    $temp = str_replace("#RAISON2F", "$raison2f", $temp);
       		    $temp = str_replace("#RAISON3F", "$raison3f", $temp);				
				$temp = str_replace("#ENTREPRISE", "$row->entreprise", $temp);
				$temp = str_replace("#RAISON", $raison[$row->raison], $temp);
				$temp = str_replace("#LIBELLE", "$row->libelle", $temp);
				$temp = str_replace("#ADRESSE1", "$row->adresse1", $temp);
				$temp = str_replace("#ADRESSE2", "$row->adresse2", $temp);
				$temp = str_replace("#ADRESSE3", "$row->adresse3", $temp);
				$temp = str_replace("#CPOSTAL", "$row->cpostal", $temp);
				$temp = str_replace("#PAYS", "$row->pays", $temp);
				$temp = str_replace("#VILLE", "$row->ville", $temp);
				$temp = str_replace("#TEL", "$row->tel", $temp);
				$temp = str_replace("#SUPPRURL", "livraison_adresse.php?action=supprimerlivraison&amp;id=$row->id", $temp);
				$temp = str_replace("#URL", "commande.php?action=modadresse&amp;adresse=$row->id", $temp);

				$res .= $temp;
			}
	
		
		}
		
		else {
		
		$raison[1] = "Mme";
		$raison[2] = "Mlle";
		$raison[3] = "M";

                if($_SESSION['navig']->client->raison == 1) $raison1f="selected=\"selected\"";
                else $raison1f="";

                if($_SESSION['navig']->client->raison == 2) $raison2f="selected=\"selected\"";
                else $raison2f="";

                if($_SESSION['navig']->client->raison == 3) $raison3f="selected=\"selected\"";
                else $raison3f="";

        $temp = str_replace("#RAISON1F", "$raison1f", $texte);
        $temp = str_replace("#RAISON2F", "$raison2f", $temp);
        $temp = str_replace("#RAISON3F", "$raison3f", $temp);
		
		$temp = str_replace("#ID", $_SESSION['navig']->client->id, $temp);
		$temp = str_replace("#LIBELLE", "", $temp);
		$temp = str_replace("#RAISON", $raison[$_SESSION['navig']->client->raison], $temp);
		$temp = str_replace("#NOM", $_SESSION['navig']->client->nom, $temp);
		$temp = str_replace("#PRENOM", $_SESSION['navig']->client->prenom, $temp);
		$temp = str_replace("#ADRESSE1", $_SESSION['navig']->client->adresse1, $temp);
		$temp = str_replace("#ADRESSE2", $_SESSION['navig']->client->adresse2, $temp);
		$temp = str_replace("#ADRESSE3", $_SESSION['navig']->client->adresse3, $temp);
		$temp = str_replace("#CPOSTAL", $_SESSION['navig']->client->cpostal, $temp);
		$temp = str_replace("#VILLE", strtoupper($_SESSION['navig']->client->ville), $temp);
		$temp = str_replace("#PAYS", strtoupper($_SESSION['navig']->client->pays), $temp);
		$temp = str_replace("#EMAIL", $_SESSION['navig']->client->email, $temp);
		$temp = str_replace("#TELFIXE", $_SESSION['navig']->client->telfixe, $temp);
		$temp = str_replace("#TELPORT", $_SESSION['navig']->client->telport, $temp);		
		
		$res .= $temp;
		
		}
		
		return $res;
	
	}		
	
	function boucleVenteadr($texte, $args){

		$venteadr = new Venteadr();


		// récupération des arguments

		$id = lireTag($args, "id", "int");		

		$search ="";
		$res="";

		$raison[1] = "Mme";
		$raison[2] = "Mlle";
		$raison[3] = "M";

		// preparation de la requete
		if($id!="")  $search.=" and id=\"$id\"";

		$query = "select * from $venteadr->table where 1 $search";
		$resul = mysql_query($query, $venteadr->link);

		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";


		while( $row = mysql_fetch_object($resul)){

	        if($row->raison == 1) $raison1f="selected=\"selected\"";
	        else $raison1f="";

	        if($row->raison == 2) $raison2f="selected=\"selected\"";
	        else $raison2f="";

	        if($row->raison == 3) $raison3f="selected=\"selected\"";
	        else $raison3f="";			


			$temp = str_replace("#ID", "$row->id", $texte);
			$temp = str_replace("#PRENOM", "$row->prenom", $temp);
			$temp = str_replace("#NOM", "$row->nom", $temp);
	 		$temp = str_replace("#RAISON1F", "$raison1f", $temp);
	   		$temp = str_replace("#RAISON2F", "$raison2f", $temp);
	   		$temp = str_replace("#RAISON3F", "$raison3f", $temp);				
			$temp = str_replace("#RAISON", $raison[$row->raison], $temp);
			$temp = str_replace("#ADRESSE1", "$row->adresse1", $temp);
			$temp = str_replace("#ADRESSE2", "$row->adresse2", $temp);
			$temp = str_replace("#ADRESSE3", "$row->adresse3", $temp);
			$temp = str_replace("#CPOSTAL", "$row->cpostal", $temp);
			$temp = str_replace("#PAYS", "$row->pays", $temp);
			$temp = str_replace("#VILLE", "$row->ville", $temp);
			$temp = str_replace("#TEL", "$row->tel", $temp);
			$res .= $temp;
		}

		return $res;

	}


	function boucleCommande($texte, $args){
	
		$commande = new Commande();
	
	
		// récupération des arguments
		$commande_id = lireTag($args, "id", "int");		
		$commande_ref = lireTag($args, "ref", "string");		
		$client_id = lireTag($args, "client", "int");
		$statut = lireTag($args, "statut", "int");
		$classement = lireTag($args, "classement", "string");
		$statutexcl = lireTag($args, "statutexcl", "int_list");
		$deb = lireTag($args, "deb", "int");
		$num = lireTag($args, "num", "int");
		
		if($commande_ref == "" && $client_id == "") return;
		
		$search ="";
		$order="";
		$limit="";
		$res="";
		
		// preparation de la requete
		if($commande_id!="")  $search.=" and id=\"$commande_id\"";		
		if($commande_ref!="")  $search.=" and ref=\"$commande_ref\"";
		if($client_id!="")  $search.=" and client=\"$client_id\"";
		if($statutexcl!="")  $search.=" and statut not in ($statutexcl)";
		if($statut!="" && $statut!="paye")  $search.=" and statut=\"$statut\"";
		else if($statut=="paye")  $search.=" and statut>\"1\" and statut<>\"5\"";

		if($deb == "") $deb = 0;
		
		if($num != "") $limit = "limit $deb,$num";
		
		if($classement == "inverse")
			$order = "order by date";
		else $order = "order by date desc";
	
		$query = "select * from $commande->table where 1 $search $order $limit";
		$resul = mysql_query($query, $commande->link);
		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
		
		
		$statutdesc = new Statutdesc();
		$venteprod = new Venteprod();
		
		while( $row = mysql_fetch_object($resul)){
		  	
		  	$jour = substr($row->date, 8, 2);
  			$mois = substr($row->date, 5, 2);
  			$annee = substr($row->date, 0, 4);
  		
  			$heure = substr($row->date, 11, 2);
  			$minute = substr($row->date, 14, 2);
  			$seconde = substr($row->date, 17, 2);

			$jour_livraison = substr($row->datelivraison, 8, 2);
			$mois_livraison = substr($row->datelivraison, 5, 2);
			$annee_livraison = substr($row->datelivraison, 0, 4);
	
			$datelivraison = $jour_livraison . "/" . $mois_livraison . "/" . $annee_livraison;
	
			  		
  			$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$row->id'"; 
  			$resul2 = mysql_query($query2, $venteprod->link);
  			$total = round(mysql_result($resul2, 0, "total"), 2);
  			$total = round($total - $row->remise, 2);

		/*	$queryht = "SELECT sum (prixu/(1+(tva/100))*quantite) as totalht FROM $venteprod->table where commande='$row->id'";
			$resulht = mysql_query($queryht,$venteprod->link);
			$totalht = round(mysql_result($queryht,0,"totalht"),2);
			$totalht = round($totalht - $row->remise,2);*/
			

			$port = $row->port;
			$totcmdport = $row->port + $total;
			 	  	
		  	$statutdesc->charger($row->statut, $_SESSION['navig']->lang);

			$temp = str_replace("#ID", "$row->id", $texte);
			$temp = str_replace("#ADRESSE", "$row->adrfact", $temp);
			$temp = str_replace("#ADRFACT", "$row->adrfact", $temp);
			$temp = str_replace("#ADRLIVR", "$row->adrlivr", $temp);
			
			if($jour_livraison !="00")
				$temp = str_replace("#DATELIVRAISON", $jour_livraison . "/" . $mois_livraison . "/" . $annee_livraison, $temp);
			else
				$temp = str_replace("#DATELIVRAISON", "", $temp);
			$temp = str_replace("#DATE", $jour . "/" . $mois . "/" . $annee, $temp);
			$temp = str_replace("#REF", "$row->ref", $temp);
			$temp = str_replace("#ADRFACT", "$row->adrfact", $temp);
			$temp = str_replace("#ADRLIVR", "$row->adrlivr", $temp);
			$temp = str_replace("#LIVRAISON", "$row->livraison", $temp);
			$temp = str_replace("#FACTURE", "$row->facture", $temp);
			$temp = str_replace("#PAIEMENT", "$row->paiement", $temp);
			$temp = str_replace("#REMISE", "$row->remise", $temp);
			$temp = str_replace("#STATUT", "$statutdesc->titre", $temp);
			$temp = str_replace("#TOTALCMD", "$total", $temp);
			$temp = str_replace("#PORT", "$port", $temp);
			$temp = str_replace("#TOTCMDPORT", "$totcmdport", $temp);
            $temp = str_replace("#COMDEVISE", "$row->devise", $temp);
            $temp = str_replace("#TAUX", "$row->taux", $temp);
			$temp = str_replace("#COLIS", "$row->colis", $temp);
			$temp = str_replace("#TRANSPORT", "$row->transport", $temp);
			$temp = str_replace("#FICHIER", "client/pdf/visudoc.php?ref=" . $row->ref, $temp);

			$res .= $temp;
		}
	


		return $res;
	
	}	
	
	function boucleVenteprod($texte, $args){	
	
		// récupération des arguments
		$commande_id = lireTag($args, "commande", "int");		
		$produit = lireTag($args, "produit", "int");
				
		$search ="";
		$res="";
		
		// preparation de la requete
		if($commande_id!="")  $search.=" and commande=\"$commande_id\"";		
		if($produit!="")  $search.=" and ref=\"$produit\"";		
	
		$venteprod = new Venteprod();

		$query = "select * from $venteprod->table where 1 $search";
		$resul = mysql_query($query, $venteprod->link);

		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";
		
		
		while( $row = mysql_fetch_object($resul)){
			
			$prixu = number_format($row->prixu, 2, ".", "");
			$totalprod = $row->prixu * $row->quantite;
			$totalprod = number_format($totalprod, 2, ".", "");
			
			$query2 = "select count(*) as nbvente from $venteprod->table where ref=\"" . $row->ref . "\"";
			$resul2 = mysql_query($query2, $venteprod->link);
			$nbvente = mysql_result($resul2, 0, "nbvente");
			
			$temp = str_replace("#ID", "$row->id", $texte);
			$temp = str_replace("#COMMANDE", "$row->commande", $temp);
			$temp = str_replace("#REF", "$row->ref", $temp);
			$temp = str_replace("#TITRE", "$row->titre", $temp);
			$temp = str_replace("#CHAPO", "$row->chapo", $temp);
			$temp = str_replace("#DESCRIPTION", "$row->description", $temp);
			$temp = str_replace("#QUANTITE", "$row->quantite", $temp);
			$temp = str_replace("#PRIXU", "$row->prixu", $temp);
			$temp = str_replace("#TVA", "$row->tva", $temp);	
			$temp = str_replace("#TOTALPROD", "$totalprod", $temp);

			$res .= $temp;
		}
	


		return $res;
	
	}	

	function boucleTransport($texte, $args){	

		// récupération des arguments

		$id = lireTag($args, "id", "int");	
		$nom = 	lireTag($args, "nom", "string");
		$exclusion = lireTag($args, "exclusion", "string_list");		
		
		$search="";
		$res="";
		
		if($id != "") $search .= "and id in ($id)";
		if($nom != "") $search .= "and nom=\"$nom\"";
		if($exclusion!=""){
			$liste="";
			$tabexcl = explode(",", $exclusion);
			for($i=0;$i<count($tabexcl);$i++)
				$liste .= "'" . $tabexcl[$i] . "'" . ",";
			if(substr($liste, strlen($liste)-1) == ",")
				$liste = substr($liste, 0, strlen($liste)-1);
			$search.=" and nom not in ($liste)";
		}
					
		$modules = new Modules();
	
		$query = "select * from $modules->table where type='2' and actif='1' $search order by classement";

		//$resul = mysql_query($query, $modules->link);
		$nbres = CacheBase::getCache()->mysql_query_count($query, $modules->link);
		
		if(!$nbres) return "";

		$pays = new Pays();
		
		if($_SESSION['navig']->adresse != "" && $_SESSION['navig']->adresse != 0){
			$adr = new Adresse();
			$adr->charger($_SESSION['navig']->adresse);
			$pays->charger($adr->pays);
		}	
			
		else 
			$pays->charger($_SESSION['navig']->client->pays);

		$transzone = new Transzone();
		
		   $compt = 0;
		
		   while( $row = mysql_fetch_object($resul)){
			
		  	 if( ! $transzone->charger($row->id, $pays->zone)) continue;
		
			$compt ++;
		
			$modules = new Modules();
			$modules->charger_id($row->id);
			
			$nom = $modules->nom;
			$nom[0] = strtoupper($nom[0]);

			include_once("client/plugins/" . $modules->nom . "/$nom.class.php");
			$tmpobj = new $nom();
			
			$port = round(port($row->id), 2);
			$titre = $tmpobj->getTitre();
			$chapo = $tmpobj->getChapo();
			$description = $tmpobj->getDescription();
	
			$port = number_format($port, 2, ".", ""); 
			
			$temp = str_replace("#TITRE", "$titre", $texte);
			$temp = str_replace("#CHAPO", "$chapo", $temp);
			$temp = str_replace("#DESCRIPTION", "$description", $temp);
			$temp = str_replace("#URLCMD", "commande.php?action=transport&amp;id=" . $row->id, $temp);
			$temp = str_replace("#ID", "$row->id", $temp);	
			$temp = str_replace("#PORT", "$port", $temp);
			$temp = str_replace("#COMPT", "$compt", $temp);

			$res .= $temp;
			
		}
	
	
			return $res;

	}	


        function boucleRSS($texte, $args){
			
		@ini_set('default_socket_timeout', 5);
                
		// récupération des arguments
                $url = lireTag($args, "url", "string+\/:.");
                $nb = lireTag($args, "nb", "int");
				$deb = lireTag($args, "deb", "int");
				
		if($url == "") return;

		$i=0;
		$compt=0;
                $rss = @fetch_rss( $url );
		if(!$rss) return "";

                $chantitle = $rss->channel['title'];
		$chanlink = $rss->channel['link'];
		
                $items = array_slice($rss->items, 0);
				
                foreach ($items as $item) {
                   if($compt<$deb) {$compt++; continue;}
                  
                    $title = strip_tags($item['title']);
                 	$description = strip_tags($item['description']);
                    $author = $item['dc']['creator'];
                    $link = $item['link']; 
					$dateh = $item['dc']['date'];
					$jour = substr($dateh, 8,2);
					$mois = substr($dateh, 5, 2);
					$annee = substr($dateh, 0, 4);

					$heure = substr($dateh, 11, 2);
					$minute = substr($dateh, 14, 2);
					$seconde = substr($dateh, 17, 2);
				
					$temp =  str_replace("#SALON", "$chantitle", $texte);
					$temp = str_replace("#WEB", "$chanlink", $temp);			
					$temp = str_replace("#TITRE", "$title", $temp);
					$temp = str_replace("#LIEN", "$link", $temp);
					$temp = str_replace("#DESCRIPTION", "$description", $temp);
            		$temp = str_replace("#AUTEUR", "$author", $temp);
					$temp = str_replace("#DATE", "$jour/$mois/$annee", $temp);
					$temp = str_replace("#HEURE", "$heure:$minute:$seconde", $temp);
			
					$i++;

					$res .= $temp;
					if($i == $nb) return $res;
                }

                return $res;

        }


	
	function boucleDeclinaison($texte, $args){

		global $declinaison;

		$id = lireTag($args, "id", "int");		
		$rubrique = lireTag($args, "rubrique", "int");		
		$produit = lireTag($args, "produit", "int");		
		$courante = lireTag($args, "courante", "int");	
		$exclusion = lireTag($args, "exclusion", "int_list");
		
		$search ="";
		$res="";
		
		// preparation de la requete
		if($rubrique!="")  $search.=" and rubrique=\"$rubrique\"";
		if($id!="")  $search.=" and id=\"$id\"";
		if($exclusion!="") $search .= " and id not in ($exclusion)";
			
		$rubdeclinaison = new Rubdeclinaison();
		$tmpdeclinaison = new Declinaison();
		$tmpdeclinaisondesc = new Declinaisondesc();
		
		
		$query = "select DISTINCT(declinaison) from $rubdeclinaison->table where 1 $search";
		if($id != "") $query = "select * from $tmpdeclinaison->table where 1 $search";
		$resul = mysql_query($query, $rubdeclinaison->link);

		$nbres = mysql_num_rows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){
			
			if($courante == "1" && ($row->id  != $declinaison))
			   continue;
			
			else if($courante == "0" && ($row->id  == $declinaison))
			   continue;
						
			if($id != "") $tmpdeclinaisondesc->charger($row->id, $_SESSION['navig']->lang);
			else $tmpdeclinaisondesc->charger($row->declinaison, $_SESSION['navig']->lang);
			if($id != "") $temp = str_replace("#ID", "$row->id", $texte);
			else $temp = str_replace("#ID", "$row->declinaison", $texte);

			$temp = str_replace("#TITRE", "$tmpdeclinaisondesc->titre", $temp);
			$temp = str_replace("#CHAPO", "$tmpdeclinaisondesc->chapo", $temp);
			$temp = str_replace("#DESCRIPTION", "$tmpdeclinaisondesc->description", $temp);	
			$temp = str_replace("#PRODUIT", "$produit", $temp);
	
			$res .= $temp;
		}

		return $res;
	}	

	function boucleDeclidisp($texte, $args){

		global $declidisp;
		
		$declinaison = lireTag($args, "declinaison", "int");		
		$id = lireTag($args, "id", "int");
		$produit = lireTag($args, "produit", "int");
		$classement = lireTag($args, "classement", "string");
		$stockmini = lireTag($args, "stockmini", "int");
		$courante = lireTag($args, "courante", "int");
		$num = lireTag($args, "num", "int");
		
		$search ="";
		$liste="";
		$tabliste[0]="";
		$res="";
		
		// preparation de la requete
		if($declinaison!="")  $search.=" and declinaison=\"$declinaison\"";
		if($id !="") $search.=" and id=\"$id\"";
		$tdeclidisp = new Declidisp();
		$tdeclidispdesc = new Declidispdesc();
	
		$exdecprod = new Exdecprod();

		if($classement == "alpha") $order="order by titre";
		else if($classement == "alphainv") $order="order by titre desc";

		$query = "select * from $tdeclidisp->table where 1 $search";
		$resul = mysql_query($query, $tdeclidisp->link);
		
		$i=0;

		$stockok = 0;
						
		while($row = mysql_fetch_object($resul)){
			
				if($stockmini && $produit){
					$stock = new Stock();
					$stock->charger($row->id, $produit);
					if($stock->valeur<$stockmini) continue;
					$stockok = 1;
				}
			
				$liste .= "'" . $row->id . "',";
				$tabliste[$i++] = $row->id;
		}
		
		if($stockmini && !$stockok) return "";
		
		$liste = substr($liste, 0, strlen($liste) - 1);	

						
							
		if($classement != ""){
			$liste2="";
			if($liste != ""){
				$query = "select * from $tdeclidispdesc->table where declidisp in ($liste) and lang='" . $_SESSION['navig']->lang . "' $order";
				$resul = mysql_query($query, $tdeclidispdesc->link);
			}		
		
		
			$i=0;
			
			while($row = mysql_fetch_object($resul)){
				$liste2 .= "'" . $row->declidisp . "',";
				$tabliste2[$i++] = $row->declidisp;
			}
			$liste2 = substr($liste2, 0, strlen($liste2) - 1);

		}
		
	
		if($classement != "" && isset($tabliste2)) $tabliste = $tabliste2;
		
		if(! count($tabliste))
			return "";
	
		for($i=0; $i<count($tabliste); $i++){

			if($num != "" && $num == $i)
				break ;
						
			if($courante == "1" && ($tabliste[$i] . "-"  != $declidisp))
			   continue;
			
			else if($courante == "0" && ($tabliste[$i] ."-"  == $declidisp))
			   continue;
				
			if($exdecprod->charger($produit, $tabliste[$i])) continue;		
			
			$tdeclidisp = new Declidisp();
			$tdeclidisp->charger($tabliste[$i]); 
			
			$tdeclidispdesc = new Declidispdesc();
			$tdeclidispdesc->charger_declidisp($tabliste[$i], $_SESSION['navig']->lang);
			if(! $tdeclidispdesc->titre) $tdeclidispdesc->charger_declidisp($tabliste[$i]);
			$temp = str_replace("#ID", $tdeclidispdesc->declidisp, $texte);
			$temp = str_replace("#DECLINAISON", $tdeclidisp->declinaison, $temp);
			$temp = str_replace("#TITRE", "$tdeclidispdesc->titre", $temp);
			$temp = str_replace("#PRODUIT", "$produit", $temp);

			$res .= $temp;
		}
	
	
		return $res;
	
	
	}	

	function boucleStock($texte, $args){

	
		$declidisp = lireTag($args, "declidisp", "int");
		$produit = lireTag($args, "produit", "int");
		$article = lireTag($args, "article", "int");
		$declinaison = lireTag($args, "declinaison", "int");
		
		if($declinaison)
			for($i=0; $i<count($_SESSION['navig']->panier->tabarticle[$article]->perso); $i++)
				if($_SESSION['navig']->panier->tabarticle[$article]->perso[$i]->declinaison == $declinaison)
					$declidisp = $_SESSION['navig']->panier->tabarticle[$article]->perso[$i]->valeur;
												
		if($produit == "") return "";
		
		if($declidisp != ""){
			$stock = new Stock();		
			$stock->charger($declidisp, $produit);
			$stock_dispo = $stock->valeur;
		}
		else {
			$tmpprod = new Produit();
			$tmpprod->charger_id($produit);
			$stock_dispo = $tmpprod->stock;
		}
		
		$tmpprod = new Produit();
		$tmpprod->charger_id($produit);
		$prix = $tmpprod->prix + $stock->surplus;
		$prix2 = $tmpprod->prix2 + $stock->surplus;
		
		$temp = str_replace("#ID", "$stock->id", $texte);
		$temp = str_replace("#PRIX2", "$prix2", $temp);
		$temp = str_replace("#PRIX", "$prix", $temp);
		$temp = str_replace("#SURPLUS", "$stock->surplus", $temp);
		$temp = str_replace("#DECLIDISP", "$declidisp", $temp);	
		$temp = str_replace("#PRODUIT", "$produit", $temp);
		$temp = str_replace("#VALEUR", "$stock_dispo", $temp);	
		$temp = str_replace("#ARTICLE", "$article", $temp);	
			
			
		$compt ++;
			
		if(trim($temp) !="") $res .= $temp;
	
		return $res;
		
	
	}
	function boucleDecval($texte, $args){

	
		$article = lireTag($args, "article", "int");
		$declinaison = lireTag($args, "declinaison", "int");
		$ref = lireTag($args, "ref", "string");
		
		if($article == "") return "";
		
		$res = "";
		
		$tdeclinaison = new Declinaison();
		$tdeclinaisondesc = new Declinaisondesc();
		$tdeclidisp = new Declidisp();
		$tdeclidispdesc = new Declidispdesc();
		
		for($compt = 0; $compt<count($_SESSION['navig']->panier->tabarticle[$article]->perso); $compt++){

		   	$tperso = $_SESSION['navig']->panier->tabarticle[$article]->perso[$compt];

			if($declinaison != "" && $declinaison != $tperso->declinaison)
				continue;

			$tdeclinaison->charger($tperso->declinaison);
			$tdeclinaisondesc->charger($tdeclinaison->id, $_SESSION['navig']->lang);
			// recup valeur declidisp ou string
			if($tdeclinaison->isDeclidisp($tperso->declinaison)){
				$tdeclidisp->charger($tperso->valeur);
				$tdeclidispdesc->charger_declidisp($tdeclidisp->id, $_SESSION['navig']->lang);
				$valeur = $tdeclidispdesc->titre;
			}
				
			else $valeur = $tperso->valeur;

			$temp = str_replace("#DECLITITRE", "$tdeclinaisondesc->titre", $texte);
			$temp = str_replace("#DECLINAISON", "$tdeclinaisondesc->declinaison", $temp);
			$temp = str_replace("#REF", "$ref", $temp);	
			$temp = str_replace("#ARTICLE", "$article", $temp);	
			$temp = str_replace("#VALEUR", "$valeur", $temp);	
			$temp = str_replace("#DECLIDISP", "$tdeclidispdesc->declidisp", $temp);	
			
			$res .= $temp;				
		}		
	
		return $res;
			
	}

?>
