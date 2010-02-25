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
	include_once(realpath(dirname(__FILE__)) . "/Commande.class.php");

	class Client extends Baseobj{

		var $id;
		var $ref;
		var $raison;
		var $entreprise;
		var $siret;
		var $intracom;
		var $nom;
		var $prenom;
		var $telfixe;
		var $telport;
		var $email;
		var $motdepasse;	
		var $adresse1;
		var $adresse2;
		var $adresse3;
		var $cpostal;
		var $ville;	
		var $pays;	
		var $parrain;
		var $type;
		var $pourcentage;
		var $lang;
				
		var $table="client";
		var $bddvars = array("id", "ref", "raison", "entreprise", "siret", "intracom", "nom", "prenom", "telfixe", "telport", "email", "motdepasse", "adresse1", "adresse2", "adresse3", "cpostal", "ville", "pays", "parrain", "type", "pourcentage", "lang");

		function Client(){
			$this->Baseobj();
		}

		function charger(){
			$email = func_get_arg(0);
			$motdepasse = func_get_arg(1);
					
			$query = sprintf("select * from $this->table where email='%s' and motdepasse=PASSWORD('%s')",
			mysql_real_escape_string($email),
			mysql_real_escape_string($motdepasse));
				
			return $this->getVars($query);
		}
		
		function charger_mail($email){
			return $this->getVars("select * from $this->table where email=\"$email\"");
		}	

		function charger_id($id){
			return $this->getVars("select * from $this->table where id=\"$id\"");		
		}
		
		function existe($email){
			$query = "select * from $this->table where email=\"$email\"";
			$resul = mysql_query($query, $this->link);
			return mysql_num_rows($resul);
		
		}
		
		function crypter(){
			$query = "select PASSWORD('$this->motdepasse') as resultat";
			$resul = mysql_query($query, $this->link);
			$this->motdepasse = mysql_result($resul, 0, "resultat");
		
		}
		
		function charger_ref($ref){
			return $this->getVars("select * from $this->table where ref=\"$ref\"");		
		}
		
		function acommande(){
			$commande = new Commande();
			$query = "select * from $commande->table where statut>1 and statut<>5 and client=\"" . $this->id . "\"";
			$resul = mysql_query($query, $commande->link);
			if(mysql_num_rows($resul)) 
				return 1;
			else 
				return 0;
			
		}

		function nbcommandes(){
			$commande = new Commande();
            $query = "select * from $commande->table where statut>1 and statut<>5 and client=\"" . $this->id . "\" and statut<>5";
			$resul = mysql_query($query, $commande->link);
			return mysql_num_rows($resul);
		}		
		
	}

?>