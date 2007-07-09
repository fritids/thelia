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

	include_once(realpath(dirname(__FILE__)) . "/Article.class.php");

	// Déninition du panier
	
	class Panier {

		var $nbart;
		var $tabarticle;
		
		function Panier(){
			$this->nbart = 0;
			$this->tabarticle=array();
		}

		function ajouter($ref, $quantite, $tdeclidisp="", $append=0){
			
			$existe = 0;
			
            for($i=0; $i<$this->nbart+1; $i++)
                if(isset($this->tabarticle[$i]))
                	if(isset($this->tabarticle[$i]->produit->ref) && $this->tabarticle[$i]->produit->ref == $ref){
                	      if(! count($tdeclidisp)) {$existe = 1; $indice = $i;}
              			  for($j=0; $j<count($this->tabarticle[$i]->perso); $j++){

                   if($this->tabarticle[$i]->perso[$j] == $tdeclidisp[$j]) {$existe = 1; $indice = $i;}
                   else { $existe = 0; break; }
                                        
                }
                                                
                                        
            }
            
			
			if(!$existe) 				
				$this->tabarticle[$this->nbart] = new Article($ref, $quantite, $tdeclidisp);
			else if($existe && $append)
				$this->tabarticle[$indice]->quantite += $quantite;
			
				if(isset($this->tabarticle[$this->nbart]) && $this->tabarticle[$this->nbart]->produit->ref) $this->nbart++; 
			
		}	
		
		function supprimer($id){
			
			if(! $this->tabarticle[$id]) return;
			
			$this->tabarticle[$id]="";
			
			for($i=$id+1; $i<$this->nbart+1; $i++)
				if(isset($this->tabarticle[$i]))
					$this->tabarticle[$i-1] = $this->tabarticle[$i];
				
			$this->tabarticle[$this->nbart]="";
			$this->nbart--;
			
		}
		
		function modifier($article, $quantite){
			$this->tabarticle[$article]->quantite = $quantite;
		
		}
		
		
		function total(){
			$total = 0;
		
			for($i=0; $i<$this->nbart; $i++){
			
				$quantite =  $this->tabarticle[$i]->quantite;
				if( ! $this->tabarticle[$i]->produit->promo)
				$prix = $this->tabarticle[$i]->produit->prix;
				else $prix = $this->tabarticle[$i]->produit->prix2;	
			
				$total+=round($prix*$quantite, 2);		
			
			}
		
			return $total;
		}	

		function poids(){
			$poids = 0;
		
			for($i=0; $i<$this->nbart; $i++) 
				$poids+=round($this->tabarticle[$i]->produit->poids * $this->tabarticle[$i]->quantite, 2);
		
			return $poids;
		}	

		function nbart(){
			$nbart = 0;
		
			for($i=0; $i<$this->nbart; $i++) 
				$nbart+=$this->tabarticle[$i]->quantite;
		
			return $nbart;
		}	
		
		function unitetr(){
			$unitetr = 0;
		
			for($i=0; $i<$this->nbart; $i++)
				$unitetr+=round($this->tabarticle[$i]->produit->unitetr * $this->tabarticle[$i]->quantite, 2);
	
			return $unitetr;
		}			


				
		function recupArticles(){
			return tabarticle;		
		}

	}

?>