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
	include_once("Baseobj.class.php");
	
	class Produitperso extends Baseobj{

		var $id;
		var $champsperso;
		var $produit;
		var $commande;
		var $valeur;
		
		var $table="produitperso";
		var $bddvars=array("id", "champsperso", "produit", "commande", "valeur");
		
		function Produitperso(){
			$this->Baseobj();	
		}


		function charger($champerso, $produit){
			return $this->getVars("select * from $this->table where champsperso=\"$champsperso\" and produit=\"$produit\"");

		}
		


		
	}


?>
