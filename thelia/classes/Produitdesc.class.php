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
	
	class Produitdesc extends Baseobj{

		var $id;
		var $produit;
		var $titre;
		var $chapo;
		var $description;
		var $lang;
				
		var $table="produitdesc";
		var $bddvars=array("id", "produit", "titre", "chapo", "description", "lang");
	
		function Produitdesc(){
			$this->Baseobj();
		}

		function charger($produit, $lang=1){
			if($lang==0 || $lang=="") $lang=1;
			return $this->getVars("select * from $this->table where produit=\"$produit\" and lang=\"$lang\"");

		
		}

		function charger_titre($titre){
			return $this->getVars("select * from $this->table where titre=\"$titre\"");
		
		}
		
	}

?>