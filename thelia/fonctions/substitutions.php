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

	/* Subsitutions simples */
	include_once(realpath(dirname(__FILE__)) . "/substitrubriques.php");
	include_once(realpath(dirname(__FILE__)) . "/substitproduits.php");
	include_once(realpath(dirname(__FILE__)) . "/substitpanier.php");
	include_once(realpath(dirname(__FILE__)) . "/substitclient.php");
	include_once(realpath(dirname(__FILE__)) . "/substitpage.php");
	include_once(realpath(dirname(__FILE__)) . "/substitadresse.php");
	include_once(realpath(dirname(__FILE__)) . "/substitcommande.php");
	include_once(realpath(dirname(__FILE__)) . "/substitmessage.php");
	include_once(realpath(dirname(__FILE__)) . "/substitvariable.php");
	include_once(realpath(dirname(__FILE__)) . "/substitcaracteristique.php");
	include_once(realpath(dirname(__FILE__)) . "/substitdeclinaison.php");
	include_once(realpath(dirname(__FILE__)) . "/substitimage.php");
	include_once(realpath(dirname(__FILE__)) . "/substitdossier.php");
	include_once(realpath(dirname(__FILE__)) . "/substitcontenu.php");
	include_once(realpath(dirname(__FILE__)) . "/substitparrain.php");
						 
	function substitutions($texte){
		
		global $rt75;
		
		$texte = str_replace("#URLPREC",  $_SESSION['navig']->urlprec, $texte);
		$texte = str_replace("#URLPAGERET",  $_SESSION['navig']->urlpageret, $texte);
		$texte = str_replace("#URLPANIER",  "panier.php", $texte);
		$texte = str_replace("#URLCOMMANDER",  "commande.php", $texte);
		$texte = str_replace("#URLNOUVEAU",  "nouveau.php", $texte);
		$texte = str_replace("#URLDECONNEXION", "index.php" . "?" . "action=deconnexion", $texte);
		$texte = str_replace("#URLRECHERCHE", "recherche.php", $texte);
		$texte = str_replace("#URLCOURANTE", $_SERVER['PHP_SELF'], $texte);		
		$texte = str_replace("#URLCOURANTEPARAM", $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'], $texte);
		$texte = str_replace("#URLADRESSE",  "adresse.php", $texte);
		$texte = str_replace("#URLPAIEMENT",  "paiement.php", $texte);
		$texte = str_replace("#URLSOMMAIRE",  "index.php", $texte);
		$texte = str_replace("#URLCOMPTE",  "compte.php", $texte);
		$texte = str_replace("#LANG",  $_SESSION['navig']->lang, $texte);
		$texte = str_replace("#RT75",  "$rt75", $texte);

		if(strstr($texte, "#VARIABLE")) $texte = substitvariable($texte);				
		
		if(strstr($texte, "#MESSAGE_")) $texte = substitmessage($texte);
		if(strstr($texte, "#CHAMPS")) $texte = substitchamps($texte);		
		if(strstr($texte, "#RUBRIQUE_")) $texte = substitrubriques($texte);
		if(strstr($texte, "#PRODUIT_")) $texte = substitproduits($texte);	
		if(strstr($texte, "#PANIER_")) $texte = substitpanier($texte);
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
		
		return $texte;
	}
	
?>
