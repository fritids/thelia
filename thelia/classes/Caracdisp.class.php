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
	include_once(realpath(dirname(__FILE__)) . "/Caracdispdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Caracval.class.php");

	// Classe Activite

	// id --> identifiant activite
	// desc --> nom de l'activit�
	
	class Caracdisp extends Baseobj{

		var $id;
		var $caracteristique;


				
		var $table="caracdisp";
		var $bddvars = array("id", "caracteristique");

		function Caracdisp(){
			$this->Baseobj();
		}

		function charger($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}

		function delete($requete){
			
				$resul = mysql_query($requete);	
		}
			
			
		function supprimer(){
			$caracdispdesc =  new Caracdispdesc();
			$caracdisp =  new Caracdisp();
			$caracval = new Caracval();
			
			$this->delete("delete from $caracdispdesc->table where caracdisp=\"$this->id\"");	
			$this->delete("delete from $this->table where id=\"$this->id\"");	
			$this->delete("delete from $caracval->table where caracdisp=\"$this->id\"");
			$this->delete("delete from $this->table where id=\"$this->id\"");

			
			return 1;
		
		}

	}


?>
