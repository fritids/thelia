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
	include_once(realpath(dirname(__FILE__)) . "/Baseobj.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Produitdesc.class.php");
	
	class Produit extends Baseobj{

		var $id;
		var $ref;
		var $datemodif;
		var $prix; 
		var $ecotaxe;
		var $promo;
		var $ligne;
		var $garantie;
		var $prix2;
		var $reappro;
		var $rubrique; 
		var $nouveaute;
		var $perso;  
		var $stock;
		var $appro;  			
		var $poids;  
		var $tva;		
		var $classement;
	 
		var $table="produit";
		var $bddvars=array("id", "ref", "datemodif", "prix", "ecotaxe", "promo", "reappro", "ligne", "garantie", "prix2", "rubrique", "nouveaute", "perso", "stock", "appro", "poids", "tva", "classement");
		
		function Produit(){
			$this->Baseobj();	
		}


		function charger($ref){
		
			return $this->getVars("select * from $this->table where ref=\"$ref\"");

		}

		function charger_id($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}

		function changer_classement($ref, $type){
			
			$this->charger($ref);
			$remplace = new Produit();
			
			if($type == "M")
				$res = $remplace->getVars("select * from $this->table where rubrique=\"" . $this->rubrique. "\" and classement<" . $this->classement . " order by classement desc limit 0,1");
			
			else if($type == "D")
				$res  = $remplace->getVars("select * from $this->table where rubrique=\"" . $this->rubrique. "\" and classement>" . $this->classement . " order by classement limit 0,1");
		
			if(! $res)
				return "";
				
			$sauv = $remplace->classement;
			
			$remplace->classement = $this->classement;
			$this->classement = $sauv;

            $remplace->maj();
            $this->maj();

		}
		
		function delete($requete){
			
				$resul = mysql_query($requete);	
		}
				
		function supprimer(){

			$produitdesc =  new Produitdesc();
			
			
			$this->delete("delete from $this->table where id=\"$this->id\"");	
			$this->delete("delete from $produitdesc->table where produit=\"$this->id\"");	
				
			return 1;
		
		}

		
	}

?>