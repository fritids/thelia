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

	class Modules extends Baseobj{

		var $id;
		var $nom;
		var $type;
		var $actif;

		var $table="modules";
		var $bddvars = array("id", "nom", "type", "actif");

		function Modules(){
			$this->Baseobj();	
		}
		
		function charger($nom){
		
			return $this->getVars("select * from $this->table where nom=\"$nom\"");

		}

		function charger_id($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}	
		
		
		function getTitre(){
			
			if($this->type == "1")
				$rep="paiement";
				
			else if($this->type == "2")
				$rep="transports";
				
	     	include_once(realpath(dirname(__FILE__)) . "/../client/$rep/" . $this->nom . "/" . "config.php");
			
			if($_SESSION['navig']->lang == "")
				$lang="1";
			else $lang=$_SESSION['navig']->lang;
			
			$titre="titre" . $lang;
			
			if(isset($$titre))
				return $$titre;
		}
				
		function getChapo(){
			
			if($this->type == "1")
				$rep="paiement";
				
			else if($this->type == "2")
				$rep="transports";
				
	     	include_once(realpath(dirname(__FILE__)) . "/../client/$rep/" . $this->nom . "/" . "config.php");
			
			if($_SESSION['navig']->lang == "")
					$lang="1";
				else $lang=$_SESSION['navig']->lang;
		
			
			$chapo="chapo" . $lang;
		
			if(isset($$chapo))
				return $$chapo;
		}
		
		function getDescription(){
			
			if($this->type == "1")
				$rep="paiement";
				
			else if($this->type == "2")
				$rep="transports";
							
	     	include_once(realpath(dirname(__FILE__)) . "/../client/$rep/" . $this->nom . "/" . "config.php");
			
			if($_SESSION['navig']->lang == "")
				$lang="1";
			else $lang=$_SESSION['navig']->lang;
			
			$description="description" . $lang;
		
			if(isset($$description))
				return $$description;			
		}
		
	}


?>
