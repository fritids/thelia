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
	include_once("classes/Paiement.class.php");
	include_once("classes/Paiementdesc.class.php");
	include_once("classes/Adresse.class.php");
	include_once("classes/Commande.class.php");
	include_once("classes/Venteprod.class.php");
	include_once("classes/Statutdesc.class.php");
	include_once("classes/Image.class.php");
	include_once("classes/Imagedesc.class.php");
	include_once("classes/Document.class.php");
	include_once("classes/Documentdesc.class.php");
	include_once("classes/Accessoire.class.php");
	include_once("classes/Boutique.class.php");
	include_once("classes/Transport.class.php");
	include_once("classes/Transportdesc.class.php");
	include_once("classes/Transproduit.class.php");
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
		// rï¿½upï¿½ation des arguments
		$id = lireTag($args, "id");
		$parent = lireTag($args, "parent");
		$boutique = lireTag($args, "boutique");
		$courante = lireTag($args, "courante");
		$pasvide = lireTag($args, "pasvide");
		$ligne = lireTag($args, "ligne");
		$aleatoire = lireTag($args, "aleatoire");
		$res="";
		$search="";

		// prï¿½aration de la requï¿½e
		if($id!="")  $search.=" and id=\"$id\"";
		if($parent!="") $search.=" and parent=\"$parent\"";
		if($boutique != "") $search .=" and boutique='$boutique'";
		if($courante == "1") $search .=" and id='$id_rubrique'";
		else if($courante == "0") $search .=" and id!='$id_rubrique'";
		if($ligne!="") $search.=" and ligne=\"$ligne\"";

		$rubrique = new Rubrique();
		
		if($aleatoire) $order = "order by "  . " RAND()";
		else $order = "order by classement";
		
		$query = "select * from $rubrique->table where 1 $search $order";
		$resul = mysql_query($query, $rubrique->link);
	
		$rubriquedesc = new Rubriquedesc();
		
		$compt = 1;

		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
		
			if($pasvide != ""){
						$rec = arbreBoucle($row->id);
						if($rec) $virg=",";
						else $virg="";
						
				$tmprod = new Produit();
				$query4 = "select count(*) as nbres from $tmprod->table where rubrique in('" . $row->id . "'$virg$rec) and ligne='1'";
				$resul4 = mysql_query($query4, $tmprod->link);
				if(!mysql_result($resul4, 0, "nbres")) continue;
			
			}
		
			$rubriquedesc->charger($row->id, $_SESSION['navig']->lang);
			
			$query3 = "select * from $rubrique->table where 1 and parent=\"$row->id\"";
			$resul3 = mysql_query($query3, $rubrique->link);	
			if($resul3) $nbenfant = mysql_numrows($resul3);

			$temp = ereg_replace("#TITRE", "$rubriquedesc->titre", $texte);
			$temp = ereg_replace("#STRIPTITRE", strip_tags($rubriquedesc->titre), $temp);	
			$temp = ereg_replace("#CHAPO", "$rubriquedesc->chapo", $temp);
			$temp = ereg_replace("#STRIPCHAPO", strip_tags($rubriquedesc->chapo), $temp);	
			$temp = ereg_replace("#DESCRIPTION", "$rubriquedesc->description", $temp);
			$temp = ereg_replace("#PARENT", "$row->parent", $temp);
			$temp = ereg_replace("#ID", "$row->id", $temp);		
			$temp = ereg_replace("#URL", "rubrique.php?id_rubrique=" . "$row->id", $temp);	
			$temp = ereg_replace("#REWRITEURL", rewrite_rub("$row->id"), $temp);	
			$temp = ereg_replace("#LIEN", "$row->lien", $temp);	
			$temp = ereg_replace("#COMPT", "$compt", $temp);		
			$temp = ereg_replace("#NBRES", "$nbres", $temp);
			$temp = ereg_replace("#NBENFANT", "$nbenfant", $temp);		
		
			
			$compt ++;
			
			if(trim($temp) !="") $res .= $temp . "\n";
			
		}
	
		$rubrique->destroy();
		$rubriquedesc->destroy();
	
		return $res;
		
	
	}

	/* Gestion des boucles de type Dossier*/
	function boucleDossier($texte, $args){
	
		global $id_dossier;
		
		// rï¿½upï¿½ation des arguments
		$id = lireTag($args, "id");
		$parent = lireTag($args, "parent");
		$boutique = lireTag($args, "boutique");
		$courant = lireTag($args, "courant");
		$ligne = lireTag($args, "ligne");
		$aleatoire = lireTag($args, "aleatoire");
		
		$search="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($id!="")  $search.=" and id=\"$id\"";
		if($parent!="") $search.=" and parent=\"$parent\"";
		if($boutique != "") $search .=" and boutique='$boutique'";
		if($courant == "1") $search .=" and id='$id_dossier'";
		else if($courant == "0") $search .=" and id!='$id_dossier'";
		if($ligne != "") $search .=" and ligne='$ligne'";
		
		$dossier = new Dossier();
		
		if($aleatoire) $order = "order by "  . " RAND()";
		else $order = "order by classement";
		
		$query = "select * from $dossier->table where 1 $search $order";
		$resul = mysql_query($query, $dossier->link);
	
		$dossierdesc = new Dossierdesc();
		
		$compt = 1;

		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
		
			$dossierdesc->charger($row->id, $_SESSION['navig']->lang);
			
			$query3 = "select * from $dossier->table where 1 and parent=\"$row->id\"";
			$resul3 = mysql_query($query3, $dossier->link);	
			if($resul3) $nbenfant = mysql_numrows($resul3);

			$temp = ereg_replace("#TITRE", "$dossierdesc->titre", $texte);
			$temp = ereg_replace("#STRIPTITRE", strip_tags($dossierdesc->titre), $temp);	
			$temp = ereg_replace("#CHAPO", "$dossierdesc->chapo", $temp);
			$temp = ereg_replace("#STRIPCHAPO", strip_tags($dossierdesc->chapo), $temp);	
			$temp = ereg_replace("#DESCRIPTION", "$dossierdesc->description", $temp);
			$temp = ereg_replace("#PARENT", "$row->parent", $temp);
			$temp = ereg_replace("#ID", "$row->id", $temp);		
			$temp = ereg_replace("#URL", "dossier.php?id_dossier=" . "$row->id", $temp);
			$temp = ereg_replace("#REWRITEURL", rewrite_dos("$row->id"), $temp);	
			$temp = ereg_replace("#LIEN", "$row->lien", $temp);	
			$temp = ereg_replace("#COMPT", "$compt", $temp);		
			$temp = ereg_replace("#NBRES", "$nbres", $temp);
			$temp = ereg_replace("#NBENFANT", "$nbenfant", $temp);		
		
			
			$compt ++;
			
			if(trim($temp) !="") $res .= $temp . "\n";
			
		}
	
		$dossier->destroy();
		$dossierdesc->destroy();
	
		return $res;
		
	
	}	
	
	function boucleImage($texte, $args){

		// rï¿½upï¿½ation des arguments
		$produit = lireTag($args, "produit");
		$id = lireTag($args, "id");
		$num = lireTag($args, "num");
		$nb = lireTag($args, "nb");
		$debut = lireTag($args, "debut");
		$rubrique = lireTag($args, "rubrique");
		$largeur = lireTag($args, "largeur");
		$hauteur = lireTag($args, "hauteur");
		$dossier = lireTag($args, "dossier");
		$contenu = lireTag($args, "contenu");
		$opacite = lireTag($args, "opacite");
		$noiretblanc = lireTag($args, "noiretblanc");
		$miroir = lireTag($args, "miroir");
		$aleatoire = lireTag($args, "aleatoire");
		
		$search="";
		$res="";
		$limit="";
		
		if($aleatoire) $order = "order by "  . " RAND()";
		else $order=" order by classement";	
		
		if($id != "") $search .= " and id=\"$id\"";
		if($produit != "") $search .= " and produit=\"$produit\"";
		if($rubrique != "") $search .= " and rubrique=\"$rubrique\"";
		if($dossier != "") $search .= " and dossier=\"$dossier\"";
		if($contenu != "") $search .= " and contenu=\"$contenu\"";
		
		$image = new Image();
		$imagedesc = new Imagedesc();

		if($debut !="") $debut--;
		else $debut=0;

        $query = "select * from $image->table where 1 $search";
        $resul = mysql_query($query, $image->link);
        $nbres = mysql_numrows($resul);
        if($debut!="" && $num=="") $num=$nbres;
                		
		if($debut!="" || $num!="") $limit .= " limit $debut,$num";
		
		if($nb!="") { $nb--; $limit .= " limit $nb,1"; }

		$query = "select * from $image->table where 1 $search $order $limit";
		$resul = mysql_query($query, $image->link);
	
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

		$pr = new Produit();
		$prdesc = new Produitdesc();
		$rudesc = new Rubriquedesc();
		
		$compt=1;
		
		while( $row = mysql_fetch_object($resul)){
			$image->charger($row->id);
			$imagedesc->charger($image->id);
			$temp = $texte;
			
			if($image->produit != 0){
					$pr->charger_id($image->produit);
					$prdesc->charger($image->produit);
					$temp = ereg_replace("#PRODTITRE", $prdesc->titre, $temp);
					$temp = ereg_replace("#PRODUIT", $image->produit, $temp);
					$temp = ereg_replace("#PRODREF", $pr->ref, $temp);
					$temp = ereg_replace("#RUBRIQUE", $pr->rubrique, $temp);
					$temp = ereg_replace("#COMPT", "$compt", $temp);
					
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#GRANDE", "client/gfx/photos/produit/grande/" . $image->fichier, $temp);
					else $temp = ereg_replace("#GRANDE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/grande/" . $image->fichier . "&width=$largeur&height=$hauteur" . "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#PETITE",  "client/gfx/photos/produit/petite/" . $image->fichier, $temp);	
					else $temp = ereg_replace("#PETITE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/petite/" . $image->fichier . "&width=$largeur&height=$hauteur" . "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
						$temp = ereg_replace("#FPETITE",  "client/gfx/photos/produit/petite/" . $image->fichier, $temp);
						$temp = ereg_replace("#FGRANDE",  "client/gfx/photos/produit/grande/" . $image->fichier, $temp);

			}
			
			else if($image->rubrique != 0){
				
				$rudesc->charger($image->rubrique);
				$temp = ereg_replace("#RUBRIQUE", $image->rubrique, $temp);
				$temp = ereg_replace("#RUBTITRE", $rudesc->titre, $temp);
			
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#GRANDE", "client/gfx/photos/rubrique/grande/" . $image->fichier, $temp);
					else $temp = ereg_replace("#GRANDE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/rubrique/grande/" . $image->fichier . "&width=$largeur&height=$hauteur" . "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#PETITE",  "client/gfx/photos/rubrique/petite/" . $image->fichier, $temp);	
					else $temp = ereg_replace("#PETITE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/rubrique/petite/" . $image->fichier . "&width=$largeur&height=$hauteur" . "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
						$temp = ereg_replace("#FPETITE",  "client/gfx/photos/rubrique/petite/" . $image->fichier, $temp);
						$temp = ereg_replace("#FGRANDE",  "client/gfx/photos/rubrique/grande/" . $image->fichier, $temp);

			}
	
			else if($image->dossier != 0){
				
				$rudesc->charger($image->dossier);
				$temp = ereg_replace("#RUBRIQUE", $image->dossier, $temp);
				$temp = ereg_replace("#RUBTITRE", $rudesc->titre, $temp);
			
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#GRANDE", "client/gfx/photos/dossier/grande/" . $image->fichier, $temp);
					else $temp = ereg_replace("#GRANDE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/dossier/grande/" . $image->fichier . "&width=$largeur&height=$hauteur" . "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#PETITE",  "client/gfx/photos/dossier/petite/" . $image->fichier, $temp);	
					else $temp = ereg_replace("#PETITE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/dossier/petite/" . $image->fichier . "&width=$largeur&height=$hauteur" . "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
						$temp = ereg_replace("#FPETITE",  "client/gfx/photos/dossier/petite/" . $image->fichier, $temp);
						$temp = ereg_replace("#FGRANDE",  "client/gfx/photos/dossier/grande/" . $image->fichier, $temp);

			}	
	
			else if($image->contenu != 0){
			
					$prdesc->charger($image->contenu);
					$temp = ereg_replace("#PRODTITRE", $prdesc->titre, $temp);
					$temp = ereg_replace("#PRODUIT", $image->contenu, $temp);
					
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#GRANDE", "client/gfx/photos/contenu/grande/" . $image->fichier, $temp);
					else $temp = ereg_replace("#GRANDE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/contenu/grande/" . $image->fichier . "&width=$largeur&height=$hauteur". "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
					if(!$largeur && !$hauteur) 
						$temp = ereg_replace("#PETITE",  "client/gfx/photos/contenu/petite/" . $image->fichier, $temp);	
					else $temp = ereg_replace("#PETITE",  "fonctions/redimlive.php?nomorig=../client/gfx/photos/contenu/petite/" . $image->fichier . "&width=$largeur&height=$hauteur". "&opacite=" . $opacite . "&nb=" . "$noiretblanc" . "&miroir=" . "$miroir", $temp);
					
						$temp = ereg_replace("#FPETITE",  "client/gfx/photos/contenu/petite/" . $image->fichier, $temp);
						$temp = ereg_replace("#FGRANDE",  "client/gfx/photos/contenu/grande/" . $image->fichier, $temp);

			}	
	
				$temp = ereg_replace("#ID",  $image->id, $temp);	
				$temp = ereg_replace("#FPETITE",  "client/gfx/photos/rubrique/" . $image->fichier, $temp);	

			$compt++;
				
			$res .= $temp. "\n";;
		}


	
		$image->destroy();
		$prdesc->destroy();
		$rudesc->destroy();
		
		return $res;
	
	}

	/* Gestion des boucles de type Client*/
	function boucleClient($texte, $args){
		// rï¿½upï¿½ation des arguments
		$id = lireTag($args, "id");
		$ref = lireTag($args, "ref");
		$raison = lireTag($args, "raison");
		$nom = lireTag($args, "nom");
		$cpostal = lireTag($args, "cpostal");
		$ville = lireTag($args, "ville");
		$pays = lireTag($args, "pays");
		$parrain = lireTag($args, "parrain");
		$revendeur = lireTag($args, "revendeur");

		
		$search="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($id!="")  $search.=" and id=\"$id\"";
		if($ref!="")  $search.=" and ref=\"$ref\"";
		if($raison!="")  $search.=" and raison=\"$raison\"";
		if($nom!="")  $search.=" and nom=\"$nom\"";
		if($cpostal!="")  $search.=" and cpostal=\"$cpostal\"";
		if($ville!="")  $search.=" and ville=\"$ville\"";
		if($pays!="")  $search.=" and pays=\"$pays\"";
		if($parrain!="")  $search.=" and parrain=\"$parrain\"";
		if($revendeur!="")  $search.=" and type=\"$revendeur\"";
		
		$client = new Client();
		$order = "order by nom";
		
		$query = "select * from $client->table where 1 $search $order";
		$resul = mysql_query($query, $client->link);
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
	
				$temp = ereg_replace("#ID", "$row->id", $texte);		
				$temp = ereg_replace("#REF", "$row->ref", $temp);		
				$temp = ereg_replace("#RAISON", "$row->raison", $temp);		
				$temp = ereg_replace("#ENTREPRISE", "$row->entreprise", $temp);					
				$temp = ereg_replace("#NOM", "$row->nom", $temp);					
				$temp = ereg_replace("#PRENOM", "$row->prenom", $temp);					
				$temp = ereg_replace("#TELFIXE", "$row->telfixe", $temp);	
				$temp = ereg_replace("#TELPORT", "$row->telport", $temp);					
				$temp = ereg_replace("#EMAIL", "$row->email", $temp);					
				$temp = ereg_replace("#ADRESSE1", "$row->adresse1", $temp);					
				$temp = ereg_replace("#ADRESSE2", "$row->adresse2", $temp);					
				$temp = ereg_replace("#ADRESSE3", "$row->adresse3", $temp);					
				$temp = ereg_replace("#CPOSTAL", "$row->cpostal", $temp);					
				$temp = ereg_replace("#VILLE", "$row->ville", $temp);					
				$temp = ereg_replace("#PAYS", "$row->pays", $temp);					
				$temp = ereg_replace("#PARRAIN", "$row->parrain", $temp);					
				$temp = ereg_replace("#TYPE", "$row->type", $temp);					
				$temp = ereg_replace("#POURCENTAGE", "$row->pourcentage", $temp);					

			
			$res .= $temp . "\n";
			
		}
	
		$client->destroy();
	
		return $res;
		
	
	}
	
	function boucleDevise($texte, $args){

		// rï¿½upï¿½ation des arguments
		$produit = lireTag($args, "produit");
		$id = lireTag($args, "id");
		$somme = lireTag($args, "somme");
	
		$search="";
		$devise="";
		$limit="";
		$res="";
		
		if($somme == "") $somme=0;
		
		$prod = new Produit();
		$prod->charger_id($produit);

		if($devise) $search .= " and devise=\"$devise\"";
		
		$devise = new Devise();

		$query = "select * from $devise->table where 1 $search $limit";
 		
		$resul = mysql_query($query, $devise->link);
	
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){
			$devise->charger($row->id);
			$prix = round($prod->prix * $devise->taux, 2);
			$prix2 = round($prod->prix2 * $devise->taux, 2);
			$convert = round($somme * $devise->taux, 2);
			$total = round( $_SESSION['navig']->panier->total() * $devise->taux, 2);
			$temp = ereg_replace("#PRIX2",  "$prix2", $texte);	
			
			$temp = ereg_replace("#PRIX", "$prix", $temp);
			$temp = ereg_replace("#TOTAL", "$total", $temp);
			$temp = ereg_replace("#CONVERT", "$convert", $temp);
			$temp = ereg_replace("#NOM",  "$devise->nom", $temp);	
			$temp = ereg_replace("#CODE",  "$devise->code", $temp);	
			$temp = ereg_replace("#TAUX", "$devise->taux", $temp);

			$res .= $temp. "\n";;
		}

		$prod->destroy();
		$devise->destroy();
		
		return $res;
	
	}

	function boucleDocument($texte, $args){

		// rï¿½upï¿½ation des arguments
		$produit = lireTag($args, "produit");
		$rubrique = lireTag($args, "rubrique");
		$nb = lireTag($args, "nb");
		$debut = lireTag($args, "debut");
		$num = lireTag($args, "num");
		$dossier = lireTag($args, "dossier");
		$contenu = lireTag($args, "contenu");
		
		$search="";
		$order="";
		$limit="";
		$res="";
			
		if($produit) $search .= " and produit=\"$produit\"";
		if($rubrique != "") $search .= " and rubrique=\"$rubrique\"";
		if($dossier != "") $search .= " and dossier=\"$dossier\"";
		if($contenu != "") $search .= " and contenu=\"$contenu\"";
						
		$document = new Document();
		$documentdesc = new Documentdesc();

		if($debut !="") $debut--;
		else $debut=0;

        $query = "select * from $document->table where 1 $search";
        $resul = mysql_query($query, $document->link);
        $nbres = mysql_numrows($resul);
        if($debut!="" && $num=="") $num=$nbres;
                		
		if($num!="") $limit .= " limit $debut,$num";
		if($nb!="") { $nb--; $limit .= " limit $nb,1"; }

		$query = "select * from $document->table where 1 $search $order $limit";
 		
		$resul = mysql_query($query, $document->link);
	
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){
			$document->charger($row->id);
			$documentdesc->charger($document->id);
			$temp = ereg_replace("#TITRE", "$documentdesc->titre", $texte);
			$temp = ereg_replace("#FICHIER", "client/document/" . $document->fichier, $texte);

			$res .= $temp. "\n";;
		}
	
		$document->destroy();
		
		return $res;
	
	}

	function boucleAccessoire($texte, $args){

		// rï¿½upï¿½ation des arguments
		$produit = lireTag($args, "produit");
		$search="";
			
		if($produit) $search .= " and produit=\"$produit\"";
		
		$accessoire = new Accessoire();

		$query = "select * from $accessoire->table where 1 $search";
		$resul = mysql_query($query, $accessoire->link);
	
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){
			$accessoire->charger($row->id);
			$temp = ereg_replace("#ACCESSOIRE", "$accessoire->accessoire", $texte);

			$res .= $temp. "\n";;
		}
	
		$accessoire->destroy();
		return $res;
	
	}
	
	function boucleProduit($texte, $args, $type=0){
			global $page, $totbloc, $ref, $pagesess;
			
			// rï¿½upï¿½ation des arguments
			$rubrique = lireTag($args, "rubrique");
			$boutique = lireTag($args, "boutique");
			$deb = lireTag($args, "deb");
			$num = lireTag($args, "num");
			$bloc = lireTag($args, "bloc");
			$nouveaute = lireTag($args, "nouveaute");
			$promo = lireTag($args, "promo");
			$reappro = lireTag($args, "reappro");
			$refp = lireTag($args, "ref");
			$id = lireTag($args, "id");
			$garantie = lireTag($args, "garantie");
			$motcle = lireTag($args, "motcle");
			$classement = lireTag($args, "classement");
			$aleatoire = lireTag($args, "aleatoire");
			$prixmin = lireTag($args, "prixmin");
			$prixmax = lireTag($args, "prixmax");
			$nbmensualite = lireTag($args, "nbmensualite");
			$taux = lireTag($args, "taux");
			$caracteristique = lireTag($args, "caracteristique");
			$caracdisp = lireTag($args, "caracdisp");
			$caracval = lireTag($args, "caracval");
			$courant = lireTag($args, "courant");
			$profondeur = lireTag($args, "profondeur");		
						
			if($bloc) $totbloc=$bloc;
			if(!$deb) $deb=0;
			
			if($page) $_SESSION['navig']->page = $page;
			if($pagesess == 1) $page =  $_SESSION['navig']->page;
			
			if(!$page ||  $page==1 ) $page=0; 
			
			if(!$totbloc) $totbloc=1;
			if($page) $deb = ($page-1)*$totbloc*$num+$deb; 

			if(!$taux) $taux=1;
			if(!$nbmensualite) $nbmensualite=1;
			
			// initialisation de variables
			$search = "";
			$order = "";
			$comptbloc=0;
			$limit="";
			$pourcentage="";
			$res="";
			$virg="";
			
			// prï¿½aration de la requï¿½e
			
			if($courant == "1") $search .= " and ref=\"$ref\"";
			else if($courant == "0") $search .= " and ref!=\"$ref\"";
			
			if($rubrique!=""){
				if($profondeur == "") $profondeur=0;
				
				$rec = arbreBoucle($rubrique, $profondeur);
				if($rec) $virg=",";
				
				 $search .= " and rubrique in('$rubrique'$virg$rec)";
			}
			
			$search .= " and ligne=\"1\"";

			if($id!="") $search .= " and id=\"$id\"";				 
			if($boutique != "") $search .=" and boutique='$boutique'";
			if($nouveaute!="") $search .= " and nouveaute=\"$nouveaute\"";
			if($promo!="") $search .= " and promo=\"$promo\"";
			if($reappro!="") $search .= " and reappro=\"$reappro\"";
			if($garantie!="") $search .= " and garantie=\"$garantie\"";
			if($prixmin!="") $search .= " and prix2>=\"$prixmin\"";
			if($prixmax!="") $search .= " and prix2<=\"$prixmax\"";
			
			if($refp!="") $search .= " and ref=\"$refp\"";

			if($bloc == "-1") $bloc = "1844674407370955161";
			if($bloc!="" && $num!="") $limit .= " limit $deb,$bloc";
			else if($num!="") $limit .= " limit $deb,$num";
			
			if($classement == "prixmin") $order = "order by "  . " prix";
			else if($classement == "prixmax") $order = "order by "  . " prix desc";
			else if($classement == "rubrique") $order = "order by "  . " rubrique";
			else if($aleatoire) $order = "order by "  . " RAND()";
			else if($classement == "manuel") $order = "order by classement";
			else $order = "order by classement";
			
		
			
			
			/* Demande de caracteristiques */
			if($caracdisp != ""){
			
			$lcaracteristique = explode("-", $caracteristique);
			$lcaracdisp = explode("-", $caracdisp);
			
			$i = 0;
			$liste2="";

			$tcaracval = new Caracval();

			while($i<count($lcaracteristique)){
				$caracteristique = $lcaracteristique[$i];
				$caracdisp = $lcaracdisp[$i];
				if($caracdisp == "*")
					$query = "select * from $tcaracval->table where caracteristique='$caracteristique' and caracdisp<>''";
				else if($caracdisp == "-")	$query = "select * from $tcaracval->table where caracteristique='$caracteristique' and caracdisp=''";
				else $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and caracdisp='$caracdisp'";

				$resul = mysql_query($query);
				if(!mysql_numrows($resul)) break;
				
				$liste="";
				
				while($row = mysql_fetch_object($resul))
					if(strstr($liste2, "'$row->produit'") || $liste2 == "") $liste .= "'$row->produit', ";
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				
				$liste2 = $liste;
				$i++;
				
			}

			$tcaracval->destroy();
			if($liste!="") $search .= " and id in($liste)";	
			else return "";
		}	

			if($caracval != ""){
			
			$i = 0;
			$liste="";

			$tcaracval = new Caracval();

				if($caracval == "*") $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and valeur<>''";
				else if($caracval == "-") $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and valeur=''";
	
				else $query = "select * from $tcaracval->table where caracteristique='$caracteristique' and valeur ='$caracval'";

				$resul = mysql_query($query);
				
				$liste="";
				
				while($row = mysql_fetch_object($resul))
					if(strstr($liste2, "'$row->produit'") || $liste2 == "") $liste .= "'$row->produit', ";
				$liste = substr($liste, 0, strlen($liste) - 2);
				
				$i++;
			
			$tcaracval->destroy();
			if($liste!="") $search .= " and id in($liste)";	
			else return "";
		}	
				
			$produit = new Produit();
			$produitdesc = new Produitdesc();
			
			$boutiqueprod = new Boutique();
			
			
			if($motcle){
				$liste="";
				
  				$query = "select * from $produitdesc->table  LEFT JOIN $produit->table ON $produit->table.id=$produitdesc->table.produit WHERE $produit->table.ref='$motcle' or titre like '% $motcle%' or titre like '%$motcle %' OR titre='$motcle' OR chapo like '% $motcle%' OR chapo like '%$motcle %' OR description like '% $motcle%' OR description like '%$motcle %'";
			
			    $resul = mysql_query($query, $produitdesc->link);
				$nbres = mysql_numrows($resul);

			
				if(!$nbres) return "";
				
			
				while( $row = mysql_fetch_object($resul) ){
					$liste .= "'$row->produit', ";
				}
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				$query = "select * from $produit->table where id in ($liste) and ligne=1 $limit";
				$saveReq = "select * from $produit->table where id in ($liste) and ligne=1";
			}
			
		else $query = "select * from $produit->table where 1 $search $order $limit";
		$resul = mysql_query($query, $produit->link);
		$nbres = mysql_numrows($resul);
		$saveReq = "select * from $produit->table where 1 $search $order ";

		if(!$nbres) return "";
		// substitutions
		if($type) return $query;
		
		$saveReq = ereg_replace("\*", "count(*) as totcount", $saveReq);
		$saveRes = mysql_query($saveReq);
		$countRes = mysql_result($saveRes, 0, "totcount") . " ";
	
		while( $row = mysql_fetch_object($resul) ){
		
			
			$boutiqueprod->charger($row->boutique);
			
			if(!$promo){
				 $prixd3 = round($row->prix/3, 2);	
				 $prixd6 = round($row->prix/6, 2);
			}
        		else {
				$prixd3 = round($row->prix2/3, 2);
				$prixd6 = round($row->prix2/6, 2);
			}


			$prixtotcred = round($row->prix2 * $taux / 100 + $row->prix2, 2);
			$coutcredit = round($prixtotcred-$row->prix2, 2);
			$mensualite = round($prixtotcred/$nbmensualite, 2);
			
			if($num>0) 
				if($comptbloc>=ceil($countRes/$num) && $bloc!="") continue;

			if($comptbloc == 0) $debcourant=0;
			else $debcourant = $num * ($comptbloc);
			$comptbloc++;
			
			
		
			$rubriquedesc = new Rubriquedesc();
			$rubriquedesc->charger($row->rubrique, $_SESSION['navig']->lang);
		
			$produitdesc->charger($row->id, $_SESSION['navig']->lang);
				
			$temp = $texte;
			
			if( $row->promo == "1" ) $temp = ereg_replace("#PROMO\[([^]]*)\]\[([^]]*)\]", "\\1", $temp);
	 		else $temp = ereg_replace("#PROMO\[([^]]*)\]\[([^]]*)\]", "\\2", $temp);
	 		
			if( $row->promo == "1" ) $pourcentage =  ceil((100 * ($row->prix - $row->prix2)/$row->prix));

			$prix = $row->prix - ($row->prix * $_SESSION['navig']->client->pourcentage / 100);
			$prix2 = $row->prix2 - ($row->prix2 * $_SESSION['navig']->client->pourcentage / 100);
			
			$pays = new Pays();
			$pays->charger($_SESSION['navig']->client->pays);
			
			$zone = new Zone();
			$zone->charger($pays->zone);
			
			if($_SESSION['navig']->client->type == "1" || (! $zone->tva && $zone->id)){
				$prix = $prix/1.196;
				$prix2 = $prix2/1.196;
			}
			
			$prix = round($prix, 2);
			$prix2 = round($prix2, 2);
		
			if($deb != "" && !$page) $debcourant+=$deb-1;

			$temp = ereg_replace("#REF", "$row->ref", $temp);
			$temp = ereg_replace("#DATE", substr($row->datemodif, 0, 10), $temp);
			$temp = ereg_replace("#HEURE", substr($row->datemodif, 11), $temp);
			$temp = ereg_replace("#DEBCOURANT", "$debcourant", $temp);
			$temp = ereg_replace("#ID", "$row->id", $temp);		
            $temp = ereg_replace("#PRIXD3", "$prixd3", $temp);
            $temp = ereg_replace("#PRIXD6", "$prixd6", $temp);
 			$temp = ereg_replace("#PRIXTOTCRED", "$prixtotcred", $temp);
            $temp = ereg_replace("#COUTCREDIT", "$coutcredit", $temp);
            $temp = ereg_replace("#MENSUALITE", "$mensualite", $temp);               
			$temp = ereg_replace("#PRIX2", "$prix2", $temp);					
			$temp = ereg_replace("#PRIX", "$prix", $temp);	
			$temp = ereg_replace("#POURCENTAGE", "$pourcentage", $temp);	
			$temp = ereg_replace("#RUBRIQUE", "$row->rubrique", $temp);			
			$temp = ereg_replace("#PERSO", "$row->perso", $temp);			
			$temp = ereg_replace("#QUANTITE", "$row->quantite", $temp);			
			$temp = ereg_replace("#APPRO", "$row->appro", $temp);			
			$temp = ereg_replace("#POIDS", "$row->poids", $temp);			
			$temp = ereg_replace("#TITRE", "$produitdesc->titre", $temp);
			$temp = ereg_replace("#STRIPTITRE", strip_tags($produitdesc->titre), $temp);	
			$temp = ereg_replace("#CHAPO", "$produitdesc->chapo", $temp);	
			$temp = ereg_replace("#STRIPCHAPO", strip_tags($produitdesc->chapo), $temp);	
			$temp = ereg_replace("#DESCRIPTION", "$produitdesc->description", $temp);
			$temp = ereg_replace("#STRIPDESCRIPTION", strip_tags($produitdesc->description), $temp);	
			$temp = ereg_replace("#URLBOUTIQUE", $boutiqueprod->url, $temp);	
			$temp = ereg_replace("#URL", "produit.php?ref=" . "$row->ref" . "&id_rubrique=" . "$row->rubrique", $temp);	
			$temp = ereg_replace("#REWRITEURL", rewrite_prod("$row->ref"), $temp);	
			$temp = ereg_replace("#GARANTIE", "$row->garantie", $temp);			

			$temp = ereg_replace("#PANIER", "panier.php?action=" . "ajouter" . "&" . "ref=" . "$row->ref" , $temp);	

			$temp = ereg_replace("#RUBTITRE", "$rubriquedesc->titre", $temp);
			
			
			$res .= $temp . "\n";
			
		}
	
		$produit->destroy();
		$produitdesc->destroy();
		$boutiqueprod->destroy();
		
		return $res;
	
	}

		
	function boucleContenu($texte, $args, $type=0){
			global $page, $totbloc, $id_contenu;
			
			// rï¿½upï¿½ation des arguments
			$dossier = lireTag($args, "dossier");
			$boutique = lireTag($args, "boutique");
			$deb = lireTag($args, "deb");
			$num = lireTag($args, "num");
			$bloc = lireTag($args, "bloc");
			$id = lireTag($args, "id");
			$motcle = lireTag($args, "motcle");
			$classement = lireTag($args, "classement");
			$aleatoire = lireTag($args, "aleatoire");
			$produit = lireTag($args, "produit");
			$rubrique = lireTag($args, "rubrique");
			$profondeur = lireTag($args, "profondeur");		
			$courant = lireTag($args, "courant");			
				
			if(!$deb) $deb=0;
		
			// initialisation de variables
			$search = "";
			$order = "";
			$comptbloc=0;
			$virg="";
			$limit="";
			$res="";
			
			// prï¿½aration de la requï¿½e
			if($dossier!=""){
				if($profondeur == "") $profondeur=0;
				$rec = arbreBoucle_dos($dossier, $profondeur);
				if($rec) $virg=",";
				
				 $search .= " and dossier in('$dossier'$virg$rec)";
			}
			
			$search .= " and ligne=\"1\"";

			if($id!="") $search .= " and id=\"$id\"";				 
			if($boutique != "") $search .=" and boutique='$boutique'";
			if($courant == "1") $search .=" and id='$id_contenu'";
			else if($courant == "0") $search .=" and id!='$id_contenu'";
			
			$liste= "";
			
			if($rubrique != "" || $produit !=""){
				if($rubrique){
					$type = 0; 
					$objet = $rubrique;
				}
				
				else{
					 $type = 1;
					 $objet = $produit;
				}
				
				$contenuassoc = new Contenuassoc();
				$query = "select * from $contenuassoc->table where objet=\"" . $objet . "\" and type=\"" . $type . "\"";
				$resul = mysql_query($query, $contenuassoc->link);
				while($row = mysql_fetch_object($resul)) 
					$liste .= "'" . $row->contenu . "',"; 
					
					
				$liste = substr($liste, 0, strlen($liste)-1);
				if($liste != "") $search .= " and id in ($liste)";	
				else $search .= " and id in ('')";
				
				$type="";
			}

			
			
			if($num!="") $limit .= " limit $deb,$num";
			
			 if($aleatoire) $order = "order by "  . " RAND()";
			else if($classement == "manuel") $order = "order by classement";
			
			
			$contenu = new Contenu();
			$contenudesc = new Contenudesc();
			
			$boutiqueprod = new Boutique();
			
			
			if($motcle){
				$liste="";
				
				$query = "select * from $contenudesc->table  LEFT JOIN $contenu->table ON $contenu->table.id=$contenudesc->table.id WHERE titre like '%$motcle%' OR chapo like '%$motcle%' OR description like '%$motcle%'";
			
			    $resul = mysql_query($query, $contenudesc->link);
				$nbres = mysql_numrows($resul);

			
				if(!$nbres) return "";
				
			
				while( $row = mysql_fetch_object($resul) ){
					$liste .= "'$row->contenu', ";
				}
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				$query = "select * from $contenu->table where id in ($liste) and ligne=1 $limit";
			}
			
		else $query = "select * from $contenu->table where 1 $search $order $limit";
		$resul = mysql_query($query, $contenu->link);
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
		// substitutions
		if($type) return $query;

		while( $row = mysql_fetch_object($resul) ){
		
			
			$boutiqueprod->charger($row->boutique);
	

			if($comptbloc == 0) $debcourant=0;
			else $debcourant = $num * ($comptbloc);
			$comptbloc++;
			
			$dossierdesc = new Dossierdesc();
			$dossierdesc->charger($row->dossier, $_SESSION['navig']->lang);
		
			$contenudesc->charger($row->id, $_SESSION['navig']->lang);
				
			$temp = $texte;
			
			$temp = ereg_replace("#DATE", substr($row->datemodif, 0, 10), $temp);
			$temp = ereg_replace("#HEURE", substr($row->datemodif, 11), $temp);
			$temp = ereg_replace("#DEBCOURANT", "$debcourant", $temp);
			$temp = ereg_replace("#ID", "$row->id", $temp);		
			$temp = ereg_replace("#DOSSIER", "$row->dossier", $temp);			
			$temp = ereg_replace("#TITRE", "$contenudesc->titre", $temp);
			$temp = ereg_replace("#STRIPTITRE", strip_tags($contenudesc->titre), $temp);	
			$temp = ereg_replace("#CHAPO", "$contenudesc->chapo", $temp);	
			$temp = ereg_replace("#STRIPCHAPO", strip_tags($contenudesc->chapo), $temp);	
			$temp = ereg_replace("#DESCRIPTION", "$contenudesc->description", $temp);
			$temp = ereg_replace("#STRIPDESCRIPTION", strip_tags($contenudesc->description), $temp);	
			$temp = ereg_replace("#URLBOUTIQUE", $boutiqueprod->url, $temp);	
			$temp = ereg_replace("#URL", "contenu.php?id_contenu=" . "$row->id", $temp);	
			$temp = ereg_replace("#REWRITEURL", rewrite_cont("$row->id"), $temp);			
			$temp = ereg_replace("#RUBTITRE", "$dossierdesc->titre", $temp);
			
			
			$res .= $temp . "\n";
			
		}
	
		$contenu->destroy();
		$contenudesc->destroy();
		$boutiqueprod->destroy();
		
		return $res;
	
	}


	function bouclePage($texte, $args){
			global $page, $id_rubrique;
			
			// rï¿½upï¿½ation des arguments
			
			$num = lireTag($args, "num");
			$courante = lireTag($args, "courante");
			$pagecourante = lireTag($args, "pagecourante");
			$typeaff = lireTag($args, "typeaff");
			$max = lireTag($args, "max");
			$affmin = lireTag($args, "affmin");
			
			$i="";
			
			if( $page<=0) $page=1;
			$bpage=$page;
			$res="";
				
				$produit = new Produit();
				
				 $query = boucleProduit($texte, ereg_replace("num", "null", $args), 1);

				if($query != ""){ 
					$pos = strpos($query, "limit");
					if($pos>0) $query = substr($query, 0, $pos);
	
					$resul = mysql_query($query, $produit->link);
					$nbres = mysql_numrows($resul);
				}
				
				else $nbres = 0;

				$page = $bpage;
				
				$nbpage = ceil($nbres/$num);
				if($page+1>$nbpage) $pagesuiv=$page;
				else $pagesuiv=$page+1;
				
				if($page-1<=0) $pageprec=1;
				else $pageprec=$page-1;				


				if($nbpage<$affmin) return;
				
				if($typeaff == 1){
					if(!$max) $max=$nbpage+1;
					if($page && $max && $page>$max) $i=ceil(($page)/$max)*$max-$max+1;	
				
					if($i == 0) $i=1;
				
					$fin = $i+$max;	


					
					
					for( ; $i<$nbpage+1 && $i<$fin; $i++ ){
					
						$temp = ereg_replace("#PAGE_NUM", "$i", $texte);		
						$temp = ereg_replace("#PAGE_SUIV", "$pagesuiv", $temp);
						$temp = ereg_replace("#PAGE_PREC", "$pageprec", $temp);
						$temp = ereg_replace("#RUBRIQUE", "$id_rubrique", $temp);
				
						if($pagecourante && $pagecourante == $i){		

							if($courante =="1" && $page == $i ) $res .= $temp;	
							else if($courante == "0" && $page != $i ) $res .= $temp;	
							else if($courante == "") $res .= $temp;
						}	
						
						else if(!$pagecourante) $res .= $temp;								
					}
				
				}
				
				else if($typeaff == "0"){

					$temp = ereg_replace("#PAGE_NUM", "$i", $texte);
					$temp = ereg_replace("#PAGE_SUIV", "$pagesuiv", $temp);
					$temp = ereg_replace("#PAGE_PREC", "$pageprec", $temp);
					$temp = ereg_replace("#RUBRIQUE", "$id_rubrique", $temp);
					$res .= $temp;
				}						
			
				$produit->destroy();
				
				return $res;
			
			
	}
	

	function bouclePanier($texte, $args){
		
		$total = 0;
		$res="";
		
		for($i=0; $i<$_SESSION['navig']->panier->nbart; $i++){
			$plus = $_SESSION['navig']->panier->tabarticle[$i]->quantite+1;
			$moins = $_SESSION['navig']->panier->tabarticle[$i]->quantite-1;
			
			if($moins == 0) $moins++;
			
			$quantite =  $_SESSION['navig']->panier->tabarticle[$i]->quantite;
			if( ! $_SESSION['navig']->panier->tabarticle[$i]->produit->promo)
				$prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix * $_SESSION['navig']->client->pourcentage / 100);
			else $prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 * $_SESSION['navig']->client->pourcentage / 100);	
			
			$total=round($prix*$quantite, 2);
			$prix = round($prix, 2);
			
			$port = port();
			$totcmdport = $total + $port;
			
			$totsansport = $_SESSION['navig']->panier->total();

			$pays = new Pays();
			$pays->charger($_SESSION['navig']->client->pays);
			
			$zone = new Zone();
			$zone->charger($pays->zone);
						
			if($_SESSION['navig']->client->type ||  (!$zone->tva && $zone->id)) {
				$prix = round($prix/1.196, 2);
				$total = round($total/1.196, 2);
				$port = round($port/1.196, 2);
				$totcmdport = round($totcmdport/1.196, 2);
				$totsansport = round($totsansport/1.196, 2);
			}
			
			$produitdesc = new Produitdesc();
			$produitdesc->charger($_SESSION['navig']->panier->tabarticle[$i]->produit->id,  $_SESSION['navig']->lang);

			$declidisp = new Declidisp();
			$declidispdesc = new Declidispdesc();
			$declinaison = new Declinaison();
			$declinaisondesc = new Declinaisondesc();
			
			$dectexte = "";
			$decval = "";
			
			if(isset($compt) && isset($_SESSION['navig']->panier->tabarticle[$compt]))
			
			  for($compt = 0; $compt<count($_SESSION['navig']->panier->tabarticle[$compt]->perso); $compt++){
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
				$declinaisondesc->charger($tperso->declinaison);
				
				$dectexte .= $declinaisondesc->titre . " " . $declidispdesc->titre . " ";
				
				
				
				
			}	
		

			$temp = ereg_replace("#REF", $_SESSION['navig']->panier->tabarticle[$i]->produit->ref, $texte);
			$temp = ereg_replace("#TITRE", $produitdesc->titre, $temp);
			$temp = ereg_replace("#QUANTITE", "$quantite", $temp);
			$temp = ereg_replace("#PRODUIT", "$i", $temp);
			$temp = ereg_replace("#PRIXU", "$prix", $temp);
			$temp = ereg_replace("#TOTAL", "$total", $temp);			
			$temp = ereg_replace("#ID", $_SESSION['navig']->panier->tabarticle[$i]->produit->id, $temp);
			$temp = ereg_replace("#ARTICLE", "$i", $temp);
			$temp = ereg_replace("#PLUSURL", "panier.php?action=" . "modifier" . "&" . "id=" . $i . "&" . "quantite=" . $plus, $temp);			
			$temp = ereg_replace("#MOINSURL", "panier.php?action=" . "modifier" . "&" . "id=" . $i . "&" . "quantite=" . $moins, $temp);
			$temp = ereg_replace("#SUPPRURL", "panier.php?action=" . "supprimer" . "&" . "id=" . $i, $temp);			
			$temp = ereg_replace("#PRODURL", "produit.php?ref=".$_SESSION['navig']->panier->tabarticle[$i]->produit->ref, $temp);		
			$temp = ereg_replace("#TOTSANSPORT", "$totsansport", $temp);
			$temp = ereg_replace("#PORT", "$port", $temp);
			$temp = ereg_replace("#TOTPORT", "$totcmdport", $temp);
			$temp = ereg_replace("#DECTEXTE", "$dectexte", $temp);
			$temp = ereg_replace("#DECVAL", "$decval", $temp);

			$res .= $temp;
		}
		
		return $res;
	
	}
	
		
	function boucleQuantite($texte, $args){
		// rï¿½upï¿½ation des arguments

		$res="";
		
		$produit = lireTag($args, "produit");
		
		$prodtemp = new Produit();
		$prodtemp->charger($_SESSION['navig']->panier->tabarticle[$produit]->produit->ref);

		$j = 0;
		
		for($i=1; $i<$prodtemp->quantite; $i++){
			if($i==$_SESSION['navig']->panier->tabarticle[$produit]->quantite) $selected=" selected";
			else $selected="";
		
			$temp = ereg_replace("#NUM", "$i", $texte);
			$temp = ereg_replace("#SELECTED", $selected, $temp);

			$res.="$temp"; 
		}
		
		
		$prodtemp->destroy();						
					
		return $res;
	
	}
		
	function boucleChemin($texte, $args){
		global $id_rubrique;

		// rï¿½upï¿½ation des arguments

		$rubrique = lireTag($args, "rubrique");		
		$profondeur = lireTag($args, "profondeur");		
		$niveau = lireTag($args, "niveau");		
		
		
		if($rubrique=="") $rubrique=$id_rubrique;
		if($rubrique=="") return "";

		$search ="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($rubrique!="" && isset($id))  $search.=" and id=\"$id\"";

		$trubrique = new Rubrique();
		$trubrique->charger($rubrique);
		$trubriquedesc = new Rubriquedesc();

		
		$i =  0;
 		do {
			$trubrique->charger("$trubrique->parent");	
			$rubtab[$i++] = $trubrique;
				
			
		} while($trubrique->parent != 0);
	
		$i--;
		
		do {
		if(($i == $niveau-1 && $niveau != "") || $niveau == "") {
				$trubriquedesc->charger($rubtab[$i]->id, $_SESSION['navig']->lang);
				$temp = ereg_replace("#ID", $rubtab[$i]->id, $texte);
				$temp = ereg_replace("#TITRE", "$trubriquedesc->titre", $temp);	
				$temp = ereg_replace("#URL", "rubrique.php?id_rubrique=" . $rubtab[$i]->id, $temp);	
		
		
			if(trim($temp) !="") $res .= $temp . "\n";
		}	
			if($i >= $profondeur && $profondeur != "") break;
		} while($i--);
	
		$trubrique->destroy();
	
		return $res;
		
	
	
	}	
	
	function bouclePaiement($texte, $args){

		$res="";
		
		$id = lireTag($args, "id");		
		$search ="";
		
		// prï¿½aration de la requï¿½e
		if($id!="")  $search.=" and id=\"$id\"";
	
		$paiement = new Paiement();
		$paiementdesc = new Paiementdesc();
	
		$query = "select * from $paiement->table where active='1' $search";

		$resul = mysql_query($query, $paiement->link);
	
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
		

		while( $row = mysql_fetch_object($resul)){
			$paiementdesc->charger($row->id, $_SESSION['navig']->lang);
			$temp = ereg_replace("#ID", "$row->id", $texte);
			$temp = ereg_replace("#URLTYPE", "$row->url", $temp);
			$temp = ereg_replace("#URLPAYER", "paiement.php?action=paiement&type_paiement=" . $row->id, $temp);
			$temp = ereg_replace("#LOGO", "client/gfx/paiement/$row->logo", $temp);
			$temp = ereg_replace("#TITRE", "$paiementdesc->titre", $temp);
			$temp = ereg_replace("#CHAPO", "$paiementdesc->chapo", $temp);
			$temp = ereg_replace("#DESCRIPTION", "$paiementdesc->description", $temp);		
			$res .= $temp. "\n";
		}
	
		$paiement->destroy();
		$paiementdesc->destroy();

		return $res;
	
	}	

	function bouclePays($texte, $args){


		$id = lireTag($args, "id");		
		$zone = lireTag($args, "zone");	 
		$zdefinie = lireTag($args, "zdefinie");
        $classement = lireTag($args, "classement");
        $select = lireTag($args, "select");
        $default = lireTag($args, "default");


		$search ="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($id!="")  $search.=" and id=\"$id\"";
		if($zone!="")  $search.=" and zone=\"$zone\"";
		if($zdefinie!="") $search.=" and zone!=\"-1\"";
	
		if($_SESSION['navig']->lang == "") $lang=1; else $lang=$_SESSION['navig']->lang ;
		
		$pays = new Pays();
		$paysdesc = new Paysdesc();
	
		$query = "select * from $pays->table where 1 $search";
		$resul = mysql_query($query, $pays->link);

		$liste=""; 
		while( $row = mysql_fetch_object($resul))					
			 $liste .= "'$row->id', ";
			
		$liste = substr($liste, 0, strlen($liste) - 2);
	

        $query = "select * from $paysdesc->table where pays in ($liste) and lang='$lang' order by titre";

		$resul = mysql_query($query, $paysdesc->link);
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){
			$paysdesc->charger_id($row->id);
			$pays->charger($paysdesc->pays);
			$temp = ereg_replace("#ID", "$row->pays", $texte);
			$temp = ereg_replace("#TITRE", "$paysdesc->titre", $temp);
			$temp = ereg_replace("#CHAPO", "$paysdesc->chapo", $temp);
			$temp = ereg_replace("#DESCRIPTION", "$paysdesc->description", $temp);	
			if(($_SESSION['navig']->formcli->pays == $row->pays || $_SESSION['navig']->client->pays == $row->pays) && $select=="") 	
				$temp = ereg_replace("#SELECTED", "selected", $temp);
			if($select !="" && $select == $row->pays) $temp = ereg_replace("#SELECTED", "selected", $temp);	
			else $temp = ereg_replace("#SELECTED", "", $temp);
			if($default == "1" && $pays->default == "1") $temp = ereg_replace("#DEFAULT", "selected", $temp);	
			else $temp = ereg_replace("#DEFAULT", "", $temp);
			$res .= $temp. "\n";
		}
	
		$pays->destroy();
		$paysdesc->destroy();

		return $res;
	
	}	

	function boucleCaracteristique($texte, $args){

		$id = lireTag($args, "id");		
		$rubrique = lireTag($args, "rubrique");		
		$affiche = lireTag($args, "affiche");		
		
		$search ="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($rubrique!="")  $search.=" and rubrique=\"$rubrique\"";
		if($id!="")  $search.=" and id=\"$id\"";
		
		
		$rubcaracteristique = new Rubcaracteristique();
		$caracteristique = new Caracteristique();
		$caracteristiquedesc = new Caracteristiquedesc();
		
		
		$query = "select DISTINCT(caracteristique) from $rubcaracteristique->table where 1 $search";
		if($id != "") $query = "select * from $caracteristique->table where 1 $search";
		$resul = mysql_query($query, $rubcaracteristique->link);
	
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){

			$caracteristique->charger($row->id);
			if($caracteristique->affiche == "0" && $affiche == "1") continue;
			
			if($id != "") $caracteristiquedesc->charger($row->id, $_SESSION['navig']->lang);
			else $caracteristiquedesc->charger($row->caracteristique, $_SESSION['navig']->lang);
			if($id != "") $temp = ereg_replace("#ID", "$row->id", $texte);
			else $temp = ereg_replace("#ID", "$row->caracteristique", $texte);

			$temp = ereg_replace("#TITRE", "$caracteristiquedesc->titre", $temp);
			$temp = ereg_replace("#CHAPO", "$caracteristiquedesc->chapo", $temp);
			$temp = ereg_replace("#DESCRIPTION", "$caracteristiquedesc->description", $temp);		
			$res .= $temp. "\n";
		}
	
		$rubcaracteristique->destroy();
		$caracteristique->destroy();
		$caracteristiquedesc->destroy();
		
		return $res;
	
	}	

	function boucleCaracdisp($texte, $args){

		global $caracdisp;
		
		$caracteristique = lireTag($args, "caracteristique");		
		$etcaracteristique = lireTag($args, "etcaracteristique");		
		$etcaracdisp = lireTag($args, "etcaracdisp");	
		$id = lireTag($args, "caracdisp");
	
		$idsave = $id;
		$res="";
		
		$caracteristiquesave = $caracteristique;
		
		if( (ereg( "^$caracteristique-", $etcaracteristique)) ||(ereg( "-$caracteristique-", $etcaracteristique)) ) $deja="1";
		else $deja="0";
		
		
		$search ="";
		
		// prï¿½aration de la requï¿½e
		if($caracteristique!="")  $search.=" and caracteristique=\"$caracteristique\"";
		if($id !="") $search.=" and id=\"$id\"";
		$tcaracdisp = new Caracdisp();
		$tcaracdispdesc = new Caracdispdesc();
		
		
		$query = "select * from $tcaracdisp->table where 1 $search";
		$resul = mysql_query($query, $tcaracdisp->link);

		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
		
		while( $row = mysql_fetch_object($resul)){
			$tcaracdispdesc->charger_caracdisp($row->id, $_SESSION['navig']->lang);
			if(!$deja) $id=$row->id."-"; else $id="";
			if(!$deja) $caracteristique=$row->caracteristique."-"; else $caracteristique ="";
			
			if($caracteristique == "$row->caracteristique" . "-" && $caracdisp == $row->id . "-") 
				$selected = "selected=\"selected\""; else $selected = "";
				
			$temp = ereg_replace("#ID", $id . $etcaracdisp, $texte);
			$temp = ereg_replace("#CARACTERISTIQUE", $caracteristique . $etcaracteristique, $temp);
			$temp = ereg_replace("#TITRE", "$tcaracdispdesc->titre", $temp);
			$temp = ereg_replace("#SELECTED", "$selected", $temp);
			
			$res .= $temp. "\n";
		}
	
		$tcaracdisp->destroy();
		$tcaracdispdesc->destroy();
		
		return $res;
	
	
	}	
	
	function boucleCaracval($texte, $args){
		$produit = lireTag($args, "produit");
		$caracteristique = lireTag($args, "caracteristique");		
		$valeur = lireTag($args, "valeur");		

		if($produit == "" || $caracteristique == "") return "";
		
		if(substr($valeur, 0, 1) == "!") {
			$different=1;
			$valeur = substr($valeur, 1);
		}
		else $different=0;

		$search ="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		$search.=" and caracteristique=\"$caracteristique\"";
		$search.=" and produit=\"$produit\"";
		
		$caracval = new Caracval();
		$prodtemp = new Produit();
		
		$query = "select * from $caracval->table where 1 $search";
		$resul = mysql_query($query, $caracval->link);

		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

	
		while( $row = mysql_fetch_object($resul)){

			$temp = ereg_replace("#ID", $row->id, $texte);
				$temp = ereg_replace("#CARACDISP", $row->caracdisp, $temp);
				if($row->caracdisp != 0){
					$caracdispdesc = new Caracdispdesc();
					$caracdispdesc->charger_caracdisp($row->caracdisp);
					if($valeur != "" && (($different == 0 && $caracdispdesc->caracdisp != $valeur) || ($different == 1 && $caracdispdesc->caracdisp == $valeur))) continue;
					$temp = ereg_replace("#VALEUR", $caracdispdesc->titre, $temp);
					
				}
				
				else {
					if($valeur != "" && (($different == 0 && $row->valeur != $valeur) || ($different == 1 && $row->valeur == $valeur))) continue;
					if( $row->valeur=="") continue;
					$temp = ereg_replace("#VALEUR", $row->valeur, $temp);
				}
			
			$prodtemp->charger_id($produit);
			$temp = ereg_replace("#RUBRIQUE", $prodtemp->rubrique, $temp);
			
			$caractemp = new Caracteristiquedesc();
			$caractemp ->charger($row->caracteristique,  $_SESSION['navig']->lang);
		
			$temp = ereg_replace("#TITRECARAC", $caractemp->titre, $temp);
			
				
			$res .= $temp. "\n";
		}
	
		$caracval->destroy();
		
		return $res;
	
	}		
			
	function boucleAdresse($texte, $args){
	
		$adresse = new Adresse();
	

		// rï¿½upï¿½ation des arguments

		$adresse_id = lireTag($args, "adresse");		
		$client_id = lireTag($args, "client");
	
		$search ="";
		$res="";
		
		$raison[1] = "Mme";
		$raison[2] = "Mlle";
		$raison[3] = "M";
				
		// prï¿½aration de la requï¿½e
		if($adresse_id!="")  $search.=" and id=\"$adresse_id\"";
		if($client_id!="")  $search.=" and client=\"$client_id\"";
		
	
		if($adresse_id != "0" ) {
			$query = "select * from $adresse->table where 1 $search";
			$resul = mysql_query($query, $adresse->link);
	
			$nbres = mysql_numrows($resul);
			if(!$nbres) return "";
			

			while( $row = mysql_fetch_object($resul)){
			
                if($row->raison == 1) $raison1f="selected=\"selected\"";
                else $raison1f="";

                if($row->raison == 2) $raison2f="selected=\"selected\"";
                else $raison2f="";

                if($row->raison == 3) $raison3f="selected=\"selected\"";
                else $raison3f="";			
			
			
				$temp = ereg_replace("#ID", "$row->id", $texte);
				$temp = ereg_replace("#PRENOM", "$row->prenom", $temp);
				$temp = ereg_replace("#NOM", "$row->nom", $temp);
     		    $temp = ereg_replace("#RAISON1F", "$raison1f", $temp);
       		    $temp = ereg_replace("#RAISON2F", "$raison2f", $temp);
       		    $temp = ereg_replace("#RAISON3F", "$raison3f", $temp);				
				$temp = ereg_replace("#RAISON", $raison[$row->raison], $temp);
				$temp = ereg_replace("#LIBELLE", "$row->libelle", $temp);
				$temp = ereg_replace("#ADRESSE1", "$row->adresse1", $temp);
				$temp = ereg_replace("#ADRESSE2", "$row->adresse2", $temp);
				$temp = ereg_replace("#ADRESSE3", "$row->adresse3", $temp);
				$temp = ereg_replace("#CPOSTAL", "$row->cpostal", $temp);
				$temp = ereg_replace("#PAYS", "$row->pays", $temp);
				$temp = ereg_replace("#VILLE", "$row->ville", $temp);
				$temp = ereg_replace("#SUPPRURL", "livraison_adresse.php?action=supprimerlivraison&id=$row->id", $temp);
				$temp = ereg_replace("#URL", "paiement.php?action=modadresse&adresse=$row->id", $temp);

				$res .= $temp. "\n";
			}
	
			$adresse->destroy();
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

        $temp = ereg_replace("#RAISON1F", "$raison1f", $texte);
        $temp = ereg_replace("#RAISON2F", "$raison2f", $temp);
        $temp = ereg_replace("#RAISON3F", "$raison3f", $temp);
		
		$temp = ereg_replace("#ID", $_SESSION['navig']->client->id, $temp);
		$temp = ereg_replace("#LIBELLE", "", $temp);
		$temp = ereg_replace("#RAISON", $raison[$_SESSION['navig']->client->raison], $temp);
		$temp = ereg_replace("#NOM", $_SESSION['navig']->client->nom, $temp);
		$temp = ereg_replace("#PRENOM", $_SESSION['navig']->client->prenom, $temp);
		$temp = ereg_replace("#ADRESSE1", $_SESSION['navig']->client->adresse1, $temp);
		$temp = ereg_replace("#ADRESSE2", $_SESSION['navig']->client->adresse2, $temp);
		$temp = ereg_replace("#ADRESSE3", $_SESSION['navig']->client->adresse3, $temp);
		$temp = ereg_replace("#CPOSTAL", $_SESSION['navig']->client->cpostal, $temp);
		$temp = ereg_replace("#VILLE", strtoupper($_SESSION['navig']->client->ville), $temp);
		$temp = ereg_replace("#PAYS", strtoupper($_SESSION['navig']->client->pays), $temp);
		$temp = ereg_replace("#EMAIL", $_SESSION['navig']->client->email, $temp);
		$temp = ereg_replace("#TELFIXE", $_SESSION['navig']->client->telfixe, $temp);
		$temp = ereg_replace("#TELPORT", $_SESSION['navig']->client->telport, $temp);		
		
		$res .= $temp. "\n";
		
		}
		
		return $res;
	
	}		
	

	function boucleCommande($texte, $args){
	
		$commande = new Commande();
	
	
		// rï¿½upï¿½ation des arguments

		$commande_ref = lireTag($args, "ref");		
		$client_id = lireTag($args, "client");
		$statut = lireTag($args, "statut");
		
		if($commande_ref == "" && $client_id == "") return;

		$search ="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($commande_ref!="")  $search.=" and ref=\"$commande_ref\"";
		if($client_id!="")  $search.=" and client=\"$client_id\"";
		if($statut!="" && $statut!="paye")  $search.=" and statut=\"$statut\"";
		else if($statut=="paye")  $search.=" and statut>\"1\"";

	
		$query = "select * from $commande->table where 1 $search";
		$resul = mysql_query($query, $commande->link);
		$nbres = mysql_numrows($resul);
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
  		
  			$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$row->id'"; 
  			$resul2 = mysql_query($query2, $venteprod->link);
  			$total = round(mysql_result($resul2, 0, "total"), 2);
  			$total = round($total - $row->remise, 2);

			$port = $row->port;
			$totcmdport = $row->port + $total;
			 	  	
		  	$statutdesc->charger($row->statut, $_SESSION['navig']->lang);

			$temp = ereg_replace("#ID", "$row->id", $texte);
			$temp = ereg_replace("#ADRESSE", "$row->adresse", $temp);
			$temp = ereg_replace("#DATE", $jour . "/" . $mois . "/" . $annee, $temp);
			$temp = ereg_replace("#REF", "$row->ref", $temp);
			$temp = ereg_replace("#LIVRAISON", "$row->livraison", $temp);
			$temp = ereg_replace("#FACTURE", "$row->facture", $temp);
			$temp = ereg_replace("#DATELIVRAISON", "$row->datelivraison", $temp);
			$temp = ereg_replace("#ENVOI", "$row->envoi", $temp);
			$temp = ereg_replace("#PAIEMENT", "$row->paiement", $temp);
			$temp = ereg_replace("#REMISE", "$row->remise", $temp);
			$temp = ereg_replace("#STATUT", "$statutdesc->titre", $temp);
			$temp = ereg_replace("#TOTALCMD", "$total", $temp);
			$temp = ereg_replace("#PORT", "$port", $temp);
			$temp = ereg_replace("#TOTCMDPORT", "$totcmdport", $temp);
			$temp = ereg_replace("#FICHIER", "client/pdf/visudoc.php?ref=" . $row->ref, $temp);

			$res .= $temp. "\n";
		}
	
		$commande->destroy();

		return $res;
	
	}	
	
	function boucleVenteprod($texte, $args){	
	
		// rï¿½upï¿½ation des arguments
		$commande_id = lireTag($args, "commande");		
		
		$search ="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($commande_id!="")  $search.=" and commande=\"$commande_id\"";		
	
		$venteprod = new Venteprod();

		$query = "select * from $venteprod->table where 1 $search";
		$resul = mysql_query($query, $venteprod->link);

		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
					
		
		while( $row = mysql_fetch_object($resul)){
		  	$totalprod = $row->prixu * $row->quantite;
			$temp = ereg_replace("#ID", "$row->id", $texte);
			$temp = ereg_replace("#REF", "$row->ref", $temp);
			$temp = ereg_replace("#TITRE", "$row->titre", $temp);
			$temp = ereg_replace("#CHAPO", "$row->chapo", $temp);
			$temp = ereg_replace("#DESCRIPTION", "$row->description", $temp);
			$temp = ereg_replace("#QUANTITE", "$row->quantite", $temp);
			$temp = ereg_replace("#PRIXU", "$row->prixu", $temp);
			$temp = ereg_replace("#TOTALPROD", "$totalprod", $temp);

			$res .= $temp. "\n";
		}
	
		$venteprod->destroy();

		return $res;
	
	}	

	function boucleTransport($texte, $args){	

		// rï¿½upï¿½ation des arguments

		$id = lireTag($args, "id");		
		
	//penser à la zone			
	// penser au produit
	
		$res="";
		
		$transport = new Transport();
	
		$query = "select * from $transport->table";

		$resul = mysql_query($query, $transport->link);
		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";
		
		$transportdesc = new Transportdesc();
	
		$pays = new Pays();
		
		if($_SESSION['navig']->adresse != ""){
			$adr = new Adresse();
			$adr->charger($_SESSION['navig']->adresse);
			$pays->charger($adr->pays);
		}	
			
		else 
			$pays->charger($_SESSION['navig']->client->pays);
		
		$transproduit = new Transproduit();
		$transzone = new Transzone();
		
		   while( $row = mysql_fetch_object($resul)){
		   
		  	 if( ! $transzone->charger($row->id, $pays->zone)) continue;

		/*
			for($i=0; $i<$_SESSION['navig']->panier->nbart; $i++){
				
				if(! $transproduit->charger($row->id, $_SESSION['navig']->panier->tabarticle[$i]->produit->id)
					|| ! $transzone->charger($row->id, $pays->zone)) continue;
			}
		*/		
		
			$port = round(port($row->id), 2);
				
			$transportdesc->charger($row->id, $_SESSION['navig']->lang);
			$temp = ereg_replace("#TITRE", "$transportdesc->titre", $texte);
			$temp = ereg_replace("#CHAPO", "$transportdesc->chapo", $temp);
			$temp = ereg_replace("#DESCRIPTION", "$transportdesc->description", $temp);
			$temp = ereg_replace("#URLCMD", "commande.php?action=transport&id=" . $row->id, $temp);
			$temp = ereg_replace("#ID", "$row->id", $temp);	
			$temp = ereg_replace("#PORT", "$port", $temp);
			$res .= $temp. "\n";
			
		}
	
		$transport->destroy();
		$transportdesc->destroy();
		$pays->destroy();
		$transproduit->destroy(); 
		$transzone->destroy();
		return $res;
	
	}	


        function boucleRSS($texte, $args){
                
		// rï¿½upï¿½ation des arguments
                $url = lireTag($args, "url");
                $nb = lireTag($args, "nb");
				$deb = lireTag($args, "deb");
				
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
				
			$temp =  ereg_replace("#SALON", "$chantitle", $texte);
			$temp = ereg_replace("#WEB", "$chanlink", $temp);			
			$temp = ereg_replace("#TITRE", "$title", $temp);
			$temp = ereg_replace("#LIEN", "$link", $temp);
			$temp = ereg_replace("#DESCRIPTION", "$description", $temp);
            $temp = ereg_replace("#AUTEUR", "$author", $temp);
			$temp = ereg_replace("#DATE", "$jour/$mois/$annee", $temp);
			$temp = ereg_replace("#HEURE", "$heure:$minute:$seconde", $temp);
			
			$i++;

			$res .= $temp;
			if($i == $nb) return $res;
                }

                return $res;

        }


	
	function boucleDeclinaison($texte, $args){

		$id = lireTag($args, "id");		
		$rubrique = lireTag($args, "rubrique");		
		$produit = lireTag($args, "produit");		
		
		$search ="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($rubrique!="")  $search.=" and rubrique=\"$rubrique\"";
		if($id!="")  $search.=" and id=\"$id\"";
			
		$rubdeclinaison = new Rubdeclinaison();
		$declinaison = new Declinaison();
		$declinaisondesc = new Declinaisondesc();
		
		
		$query = "select DISTINCT(declinaison) from $rubdeclinaison->table where 1 $search";
		if($id != "") $query = "select * from $declinaison->table where 1 $search";
		$resul = mysql_query($query, $rubdeclinaison->link);

		$nbres = mysql_numrows($resul);
		if(!$nbres) return "";

		while( $row = mysql_fetch_object($resul)){
			if($id != "") $declinaisondesc->charger($row->id, $_SESSION['navig']->lang);
			else $declinaisondesc->charger($row->declinaison, $_SESSION['navig']->lang);
			if($id != "") $temp = ereg_replace("#ID", "$row->id", $texte);
			else $temp = ereg_replace("#ID", "$row->declinaison", $texte);

			$temp = ereg_replace("#TITRE", "$declinaisondesc->titre", $temp);
			$temp = ereg_replace("#CHAPO", "$declinaisondesc->chapo", $temp);
			$temp = ereg_replace("#DESCRIPTION", "$declinaisondesc->description", $temp);	
			$temp = ereg_replace("#PRODUIT", "$produit", $temp);
	
			$res .= $temp. "\n";
		}
	
		$rubdeclinaison->destroy();
		$declinaison->destroy();
		$declinaisondesc->destroy();
		
		return $res;
	
	}	

	function boucleDeclidisp($texte, $args){

		$declinaison = lireTag($args, "declinaison");		
		$id = lireTag($args, "id");
		$produit = lireTag($args, "produit");
		$classement = lireTag($args, "classement");
		$search ="";
		$liste="";
		$tabliste[0]="";
		$res="";
		
		// prï¿½aration de la requï¿½e
		if($declinaison!="")  $search.=" and declinaison=\"$declinaison\"";
		if($id !="") $search.=" and id=\"$id\"";
		$tdeclidisp = new Declidisp();
		$tdeclidispdesc = new Declidispdesc();
	
		$exdecprod = new Exdecprod();

		if($classement == "alpha") $order="order by titre";


		$query = "select * from $tdeclidisp->table where 1 $search";
		$resul = mysql_query($query, $tdeclidisp->link);
		
		
		$i=0;
				
		while($row = mysql_fetch_object($resul)){
				$liste .= "'" . $row->id . "',";
				$tabliste[$i++] = $row->id;
		}
			
		$liste = substr($liste, 0, strlen($liste) - 1);	

						
							
		if($classement != ""){
			$liste2="";
			$query = "select * from $tdeclidispdesc->table where declidisp in ($liste) and lang='" . $_SESSION['navig']->lang . "' $order";
			$resul = mysql_query($query, $tdeclidispdesc->link);
					
		
		
			$i=0;
			
			while($row = mysql_fetch_object($resul)){
				$liste2 .= "'" . $row->declidisp . "',";
				$tabliste2[$i++] = $row->declidisp;
			}
			$liste2 = substr($liste2, 0, strlen($liste2) - 1);

		}
		
	
		if($classement != "" && isset($tabliste2)) $tabliste = $tabliste2;
		
	
		for($i=0; $i<count($tabliste); $i++){
		
			if($exdecprod->charger($produit, $tabliste[$i])) continue;		
			
			$tdeclidispdesc->charger_declidisp($tabliste[$i], $_SESSION['navig']->lang);
			if(! $tdeclidispdesc->titre) $tdeclidispdesc->charger_declidisp($tabliste[$i]);
			$temp = ereg_replace("#ID", $tdeclidispdesc->declidisp, $texte);
			$temp = ereg_replace("#DECLINAISON", $declinaison, $temp);
			$temp = ereg_replace("#TITRE", "$tdeclidispdesc->titre", $temp);
			$temp = ereg_replace("#PRODUIT", "$produit", $temp);

			$res .= $temp. "\n";
		}
	
		$tdeclidisp->destroy();
		$tdeclidispdesc->destroy();
		
		return $res;
	
	
	}	

	function boucleStock($texte, $args){

	
		$declidisp = lireTag($args, "declidisp");
		$produit = lireTag($args, "produit");
		
		if($declidisp == "" || $produit == "") return "";
		
		$stock = new Stock();		
		$stock->charger($declidisp, $produit);
				
		$temp = ereg_replace("#ID", "$stock->id", $texte);
		$temp = ereg_replace("#DECLIDISP", "$declidisp", $temp);	
		$temp = ereg_replace("#PRODUIT", "$produit", $temp);
		$temp = ereg_replace("#VALEUR", "$stock->valeur", $temp);	
			
			
		$compt ++;
			
		if(trim($temp) !="") $res .= $temp . "\n";
			
		$stock->destroy();
	
		return $res;
		
	
	}

	function boucleDecval($texte, $args){

	
		$article = lireTag($args, "article");
		
		if($article == "") return "";
		
		$res = "";
		
		$declinaison = new Declinaison();
		$declinaisondesc = new Declinaisondesc();
		$declidisp = new Declidisp();
		$declidispdesc = new Declidispdesc();
		
		for($compt = 0; $compt<count($_SESSION['navig']->panier->tabarticle[$article]->perso); $compt++){
		   	$tperso = $_SESSION['navig']->panier->tabarticle[$article]->perso[$compt];
			$declinaison->charger($tperso->declinaison);
			$declinaisondesc->charger($declinaison->id, $_SESSION['navig']->lang);
			// recup valeur declidisp ou string
			if($declinaison->isDeclidisp($tperso->declinaison)){
				$declidisp->charger($tperso->valeur);
				$declidispdesc->charger_declidisp($declidisp->id, $_SESSION['navig']->lang);
				$valeur = $declidispdesc->titre;
			}
				
			else $valeur .= $tperso->valeur;

			$temp = ereg_replace("#DECLITITRE", "$declinaisondesc->titre", $texte);
			$temp = ereg_replace("#VALEUR", "$valeur", $temp);	
			
			$res .= $temp;				
		}		
	


		return $res;
		
	
	}

?>
