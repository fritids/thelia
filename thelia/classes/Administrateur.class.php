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
	include_once(realpath(dirname(__FILE__)) . "/Autorisation.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Autorisation_administrateur.class.php");

	class Administrateur extends Baseobj{

		var $id;
		var $identifiant;
		var $motdepasse;
		var $prenom;
		var $nom;
		var $profil;
		var $lang;
		var $autorisation;
				
		var $table="administrateur";
		var $bddvars = array("id", "identifiant", "motdepasse", "prenom", "nom", "profil", "lang");

		function Administrateur(){
			$this->Baseobj();
		}

		function charger(){
			$identifiant = func_get_arg(0);
			$motdepasse = func_get_arg(1);
			$query = sprintf("select * from $this->table where identifiant='%s' and motdepasse=PASSWORD('%s')",
			mysql_real_escape_string($identifiant),
			mysql_real_escape_string($motdepasse));
				
			if($this->getVars($query)){
				$this->autorisation();
				return 1;
					
			} else {
				
				return 0;
				
			}
			
		}


		function charger_id($id){
			if($this->getVars("select * from $this->table where id=\"$id\"")){
				$this->autorisation();
				return 1;
				
			} else {
				return 0;				
			}
		}
		
		function autorisation(){
			
			$autorisation_administrateur = new Autorisation_administrateur();
			$query = "select * from $autorisation_administrateur->table where administrateur=\"" . $this->id . "\"";
			$resul = mysql_query($query, $autorisation_administrateur->link);

			while($row = mysql_fetch_object($resul)){
				$autorisation = new Autorisation();
				$autorisation->charger_id($row->autorisation);
				$temp_auth = new Autorisation_administrateur();
				$temp_auth->id = $row->id;
				$temp_auth->administrateur = $row->administrateur;
				$temp_auth->autorisation = $row->autorisation;
				$temp_auth->lecture = $row->lecture;
				$temp_auth->ecriture = $row->ecriture;

				$this->autorisation[$autorisation->nom] = new Autorisation_administrateur();
                $this->autorisation[$autorisation->nom] = $temp_auth;

			}
			
			
		}	
			
		function crypter(){
			$query = "select PASSWORD('$this->motdepasse') as resultat";
			$resul = mysql_query($query, $this->link);
			$this->motdepasse = mysql_result($resul, 0, "resultat");
		
		}

	}


?>