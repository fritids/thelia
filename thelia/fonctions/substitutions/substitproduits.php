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

	include_once("classes/Produit.class.php");
	include_once("classes/Produitdesc.class.php");
	
	/* Substitutions de type produits */
		
	function substitproduits($texte){
		global $ref, $reforig, $motcle, $id_produit, $classement, $prixmin, $prixmax, $nouveaute, $promo, $stockmini;

		
		$tproduit = new Produit();
		$tproduitdesc = new Produitdesc();

		if($ref)
			$tproduit->charger($ref);
		else if($id_produit)
			$tproduit->charger_id($id_produit);
				
		if( $ref || $id_produit)        
			$tproduitdesc->charger($tproduit->id, $_SESSION['navig']->lang);
			
		$texte = str_replace("#PRODUIT_ID", $tproduit->id, $texte);
		$texte = str_replace("#PRODUIT_NOM", $tproduitdesc->titre, $texte);	
		$texte = str_replace("#PRODUIT_CHAPO", $tproduitdesc->chapo, $texte);
		$texte = str_replace("#PRODUIT_DESCRIPTION", $tproduitdesc->description, $texte);
		$texte = str_replace("#PRODUIT_POSTSCRIPTUM", $tproduitdesc->postscriptum, $texte);		
		$texte = str_replace("#PRODUIT_RUBRIQUE", $tproduit->rubrique, $texte);
		$texte = str_replace("#PRODUIT_CLASSEMENT", "$classement", $texte);
		$texte = str_replace("#PRODUIT_STOCKMINI", "$stockmini", $texte);
		$texte = str_replace("#PRODUIT_PRIXMIN", "$prixmin", $texte);
		$texte = str_replace("#PRODUIT_PRIXMAX", "$prixmax", $texte);
		$texte = str_replace("#PRODUIT_NOUVEAUTE", "$nouveaute", $texte);
		$texte = str_replace("#PRODUIT_PROMO", "$promo", $texte);
		$texte = str_replace("#PRODUIT_MOTCLE", str_replace("'","&#x27;",$motcle), $texte);
   	 	$texte = str_replace("#PRODUIT_REFORIG", "$reforig", $texte);
		$texte = str_replace("#PRODUIT_REF", "$ref", $texte);
						
		return $texte;
	
	}
?>