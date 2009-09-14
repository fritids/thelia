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
/*      This program is distributed in the hope that it will be useful,             */
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

	/* Subsitutions simples */
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitrubriques.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitproduits.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitpanier.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitclient.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitpage.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitadresse.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitcommande.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitmessage.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitvariable.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitcaracteristique.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitdeclinaison.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitimage.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitdossier.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitcontenu.php");
	include_once(realpath(dirname(__FILE__)) . "/substitutions/substitparrain.php");
						 
	function substitutions($texte){
		global $fond;

		$texte = str_replace("#FOND", $fond, $texte);
		
		$texte = str_replace("#URLPREC", $_SESSION['navig']->urlprec, $texte);
		$texte = str_replace("#URLPAGERET", $_SESSION['navig']->urlpageret, $texte);
		$texte = str_replace("#URLPANIER", "panier.php", $texte);
		$texte = str_replace("#URLCOMMANDER", "commande.php", $texte);
		$texte = str_replace("#URLNOUVEAU", "nouveau.php", $texte);

        if($_SERVER['QUERY_STRING'] != "")
                $texte = str_replace("#URLDECONNEXION", $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "&amp;action=deconnexion", $texte);
        else    
                $texte = str_replace("#URLDECONNEXION", $_SERVER['PHP_SELF'] . "?" . "action=deconnexion", $texte);

		$texte = str_replace("#URLRECHERCHE", "recherche.php", $texte);
		$texte = str_replace("#URLCOURANTEPARAM", $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'], $texte);
		if($_SERVER['QUERY_STRING'] != ""){
			$param = str_replace("action=deconnexion", "", $_SERVER['QUERY_STRING']);
			$texte = str_replace("#URLCOURANTE", $_SERVER['PHP_SELF'] . "?" . $param, $texte);
		}
		else
			$texte = str_replace("#URLCOURANTE", $_SERVER['PHP_SELF'], $texte);
			
		$texte = str_replace("#URLADRESSE", "adresse.php", $texte);
		$texte = str_replace("#URLPAIEMENT", "commande.php", $texte);
		$texte = str_replace("#URLSOMMAIRE", "index.php", $texte);
		$texte = str_replace("#URLCOMPTE", "compte.php", $texte);
		$texte = str_replace("#LANG", $_SESSION['navig']->lang, $texte);
        $texte = str_replace("#DEVISE", $_SESSION['navig']->devise, $texte);

		if(strstr($texte, "#VARIABLE")) $texte = substitvariable($texte);				
		
		if(strstr($texte, "#MESSAGE_")) $texte = substitmessage($texte);
		if(strstr($texte, "#CHAMPS")) $texte = substitchamps($texte);		
		if(strstr($texte, "#RUBRIQUE_")) $texte = substitrubriques($texte);
		if(strstr($texte, "#PRODUIT_")) $texte = substitproduits($texte);	
		if(strstr($texte, "#PANIER_")) $texte = substitpanier($texte);
		if(strstr($texte, "#CLIENT_")) $texte = substitclient($texte);		
		if(strstr($texte, "#PAGE_")) $texte = substitpage($texte);		
		if(strstr($texte, "#ADRESSE_")) $texte = substitadresse($texte);		
		if(strstr($texte, "#COMMANDE_")) $texte = substitcommande($texte);		
		if(strstr($texte, "#IMAGE_")) $texte = substitimage($texte);		
		if(strstr($texte, "#CARACTERISTIQUE_")) $texte = substitcaracteristique($texte);		
		if(strstr($texte, "#DECLINAISON_")) $texte = substitdeclinaison($texte);		
		if(strstr($texte, "#DOSSIER_")) $texte = substitdossier($texte);		
		if(strstr($texte, "#CONTENU_")) $texte = substitcontenu($texte);		
		if(strstr($texte, "#PARRAIN_")) $texte = substitparrain($texte);		
	
		if( isset($_GET['errconnex']) && $_GET['errconnex'] == "1")
			$texte = preg_replace("/\#ERRCONNEX\[([^]]*)\]/", "\\1", $texte);
		else 
			$texte = preg_replace("/\#ERRCONNEX\[([^]]*)\]/", "", $texte);
			
		return $texte;
	}
	
?>