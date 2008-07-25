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

	include_once(realpath(dirname(__FILE__)) . "/Produit.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Produitdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Perso.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Stock.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Declinaison.class.php");

	// D�niniftion de l'article

	class Article {

		var $produit;
		var $produitdesc;
		var $quantite;
		var $perso=array();
		
		function Article($ref, $quantite, $perso=""){
			
				$this->perso = new Perso();
				
				$this->produit = new Produit();	
				$this->produit->charger($ref);
				$this->produitdesc = new Produitdesc();	
				$this->produitdesc->charger($this->produit->id);	
			    $this->quantite = $quantite;
			    $this->perso = $perso;
			
				for($i=0;$i<count($perso); $i++){
					$declinaison = new Declinaison();
					$declinaison->charger($perso[$i]->declinaison);
					
					if($declinaison->isDeclidisp()){
						$stock = new Stock();
						$stock->charger($perso[$i]->valeur, $this->produit->id);
						if($stock->surplus != 0)
							$this->produit->prix += $stock->surplus;
					}
					
				}
		}

	}


?>