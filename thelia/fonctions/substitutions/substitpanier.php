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
	
	/* Substitutionss de type panier */
		
	function substitpanier($texte){

		$total = 0;
        $totalht = 0;

		$nb_article = 0;
		
		for($i=0; $i<$_SESSION['navig']->panier->nbart; $i++){
		
				if($_SESSION['navig']->adresse){
					$adr = new Adresse();
					$adr->charger($_SESSION['navig']->adresse);
					$idpays = $adr->pays;
				} else {
					$idpays = $_SESSION['navig']->client->pays;
				}

				$pays = new Pays();
				$pays->charger($idpays);
				
				if( ! $_SESSION['navig']->panier->tabarticle[$i]->produit->promo)
				$prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix * $_SESSION['navig']->client->pourcentage / 100);
			else $prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 * $_SESSION['navig']->client->pourcentage / 100);
			
			if($pays->tva != "" && (! $pays->tva || ($pays->tva && $_SESSION['navig']->client->intracom != "")))
				$prix = $prix/(1+($_SESSION['navig']->panier->tabarticle[$i]->produit->tva/100)); 
							
			$prixht = $prix/(1+($_SESSION['navig']->panier->tabarticle[$i]->produit->tva/100)); 
	           	
			$quantite = $_SESSION['navig']->panier->tabarticle[$i]->quantite;
	
			$total += round($prix, 2) * $quantite;
			$totalht += round($prixht, 2) * $quantite;
			
			$nb_article += $_SESSION['navig']->panier->tabarticle[$i]->quantite;
		}
		
		//$tva = $_SESSION['navig']->panier->tabarticle[$i]->produit->tva;
		
		$total = round($total, 2);
		$port = port();
		if($port<0)
			$port = 0;		
		
		$totcmdport = $total + $port;
			
		if($_SESSION['navig']->client->type) {
//			$total = round($total*100/(100+$tva), 2);
 			$total = $totalht;
           	$port = round($port*100/(100+$tva), 2);
          	$totcmdport = round($totcmdport*100/(100+$tva), 2);
		}

		$remise=0;
		
		if($_SESSION['navig']->promo->type == "1" && $_SESSION['navig']->promo->mini <= $total) $remise = $_SESSION['navig']->promo->valeur;
		else if($_SESSION['navig']->promo->type == "2" && $_SESSION['navig']->promo->mini <= $total) $remise = $total * $_SESSION['navig']->promo->valeur / 100;
		
        $totcmdport -= $remise;
		$totremise = $total-$remise;

	    if($totcmdport<$port)
		    $totcmdport = $port;
		
	//	$totalht = round($total*100/(100+$tva), 2);
		
        $totcmdportht = $totalht+$port;
		if($pays->tva != "" && (! $pays->tva || ($pays->tva && $_SESSION['navig']->client->intracom != "")))
			$tva = 0;
		else
			$tva = $total-$totalht;
			
		$totalht = number_format($totalht, 2, ".", "");
		$total = number_format($total, 2, ".", "");
		$port = number_format($port, 2, ".", "");
		$totcmdport = number_format($totcmdport, 2, ".", "");
		$remise = number_format($remise, 2, ".", "");
		$totremise = number_format($totremise,2,".","");
        $totcmdportht = number_format($totcmdportht, 2, ".", "");
		$tva = number_format($tva,2,".","");
		
		$totpoids = $_SESSION['navig']->panier->poids();
		
		$texte = str_replace("#PANIER_TOTALHT", "$totalht", $texte);	 
		$texte = str_replace("#PANIER_TOTAL", "$total", $texte);
		$texte = str_replace("#PANIER_PORT", "$port", $texte);
        $texte = str_replace("#PANIER_TOTPORTHT", "$totcmdportht", $texte);
		$texte = str_replace("#PANIER_TOTPORT", "$totcmdport", $texte);
		$texte = str_replace("#PANIER_TOTREMISE","$totremise",$texte);
		$texte = str_replace("#PANIER_REMISE", "$remise", $texte);
		$texte = str_replace("#PANIER_NBART", "" . $nb_article . "", $texte);
		$texte = str_replace("#PANIER_POIDS", "$totpoids", $texte);
		$texte = str_replace("#PANIER_TVA","$tva",$texte);
		
		
		return $texte;
	
	}
?>