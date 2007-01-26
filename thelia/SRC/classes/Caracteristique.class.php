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
	include_once("Caracteristiquedesc.class.php");
		
	class Caracteristique extends Baseobj{

		var $id;
		var $boutique;
		var $affiche;
		var $classement;
		
		var $table="caracteristique";
		var $bddvars = array("id", "boutique", "affiche", "classement");

		function Caracteristique(){
			$this->Baseobj();	
		}
		
		function charger($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");


		}

		function delete($requete){
			
				$resul = mysql_query($requete);	
		}
		
		function supprimer(){

			$caracteristiquedesc =  new Caracteristiquedesc();
			
			
			$this->delete("delete from $this->table where id=\"$this->id\"");	
			$this->delete("delete from $caracteristiquedesc->table where caracteristique=\"$this->id\"");	
	
			
			return 1;
		
		}
		
	}


?>
