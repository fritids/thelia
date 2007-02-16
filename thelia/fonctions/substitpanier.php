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
	
	/* Subsitutions de type panier */
		
	function substitpanier($texte){

		$total = 0;

		$nb_article = 0;
		
		for($i=0; $i<$_SESSION['navig']->panier->nbart; $i++){
		
		
				if( ! $_SESSION['navig']->panier->tabarticle[$i]->produit->promo)
				$prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix * $_SESSION['navig']->client->pourcentage / 100);
			else $prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 * $_SESSION['navig']->client->pourcentage / 100);
			
					
				$quantite = $_SESSION['navig']->panier->tabarticle[$i]->quantite;
	
			$total += $prix * $quantite;
		
			$nb_article += $_SESSION['navig']->panier->tabarticle[$i]->quantite;
		}
		
		
		$total = round($total, 2);
		$port = port();
		$totcmdport = $total + $port;
			
		if($_SESSION['navig']->client->type) {
			$total = round($total/1.196, 2);
			$port = round($port/1.196, 2);
			$totcmdport = round($totcmdport/1.196, 2);
		}

		$remise=0;
		
		if($_SESSION['navig']->promo->type == "1" && $_SESSION['navig']->promo->mini <= $total) $remise = $_SESSION['navig']->promo->valeur;
		else if($_SESSION['navig']->promo->type == "2") $remise = $total * $_SESSION['navig']->promo->valeur / 100;
		
		
		
		$totcmdport -= $remise;
		 
		$totalht = round($total/1.196, 2);
		
		$texte = str_replace("#PANIER_TOTALHT", "$totalht", $texte);
		 
		$texte = str_replace("#PANIER_TOTAL", "$total", $texte);
		$texte = str_replace("#PANIER_PORT", "$port", $texte);
		$texte = str_replace("#PANIER_TOTPORT", "$totcmdport", $texte);
		$texte = str_replace("#PANIER_REMISE", "$remise", $texte);
		$texte = str_replace("#PANIER_NBART", "" . $nb_article . "", $texte);
		
		
		return $texte;
	
	}
	
?>