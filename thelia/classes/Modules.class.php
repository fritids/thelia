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
	include_once(realpath(dirname(__FILE__)) . "/BaseobjCacheable.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Autorisation_modules.class.php");

	class Modules extends BaseobjCacheable{

		var $id;
		var $nom;
		var $type;
		var $actif;
		var $classement;
		var $xml;
		
		var $table="modules";
		var $bddvars = array("id", "nom", "type", "actif", "classement");

		function Modules(){
			$this->Baseobj();	
		}
		
		function charger($nom){
			if($this->getVars("select * from $this->table where nom=\"$nom\"")){
				$this->chargement_xml();	
				return 1;
			}
			
			return 0;			
		}

		function charger_id($id){
			if($this->getVars("select * from $this->table where id=\"$id\"")){
				$this->chargement_xml();	
				return 1;
			}
			
			return 0;
		}	

		function chargement_xml(){
			if(file_exists(realpath(dirname(__FILE__)) . "/../client/plugins/" . $this->nom . "/plugin.xml"))
		 		$this->xml = @simplexml_load_file(realpath(dirname(__FILE__)) . "/../client/plugins/" . $this->nom . "/plugin.xml");
		}
		
		
		
		function est_autorise(){
			if($_SESSION['util']->profil == "1")
				return 1;
				
			$verif = new Autorisation_modules();
			if($verif->charger($this->id, $_SESSION['util']->id) && $verif->autorise)
				return 1;
			
			return 0;
			
		}
	}


?>