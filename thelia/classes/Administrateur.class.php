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

	class Administrateur extends Baseobj{

		var $id;
		var $identifiant;
		var $motdepasse;
		var $prenom;
		var $nom;
		var $niveau;

				
		var $table="administrateur";
		var $bddvars = array("id", "identifiant", "motdepasse", "prenom", "nom", "niveau");

		function Administrateur(){
			$this->Baseobj();
		}

		function charger($identifiant, $motdepasse){
		
			$query = sprintf("select * from $this->table where identifiant='%s' and motdepasse=PASSWORD('%s')",
			mysql_real_escape_string($identifiant),
			mysql_real_escape_string($motdepasse));
				
			return $this->getVars($query);

		}


		function charger_id($id){
			return $this->getVars("select * from $this->table where id=\"$id\"");		
		}
				
		function crypter(){
			$query = "select PASSWORD('$this->motdepasse') as resultat";
			$resul = mysql_query($query, $this->link);
			$this->motdepasse = mysql_result($resul, 0, "resultat");
		
		}

	}


?>