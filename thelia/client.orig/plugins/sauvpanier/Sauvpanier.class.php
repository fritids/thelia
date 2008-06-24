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
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Panier.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Client.class.php");
		
	class Sauvpanier extends PluginsClassiques{

		var $id;
		var $client;
		var $panier;
		var $date;

        var $table="sauvpanier";
        var $bddvars = array("id", "client", "panier", "date");
	
		function Sauvpanier(){
			$this->Baseobj();	
		}

		function init(){
			$cnx = new Cnx();
			$query_sauvpanier = "CREATE TABLE  `sauvpanier` (
			 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			 `client` INT NOT NULL ,
			 `panier` TEXT NOT NULL,
			 `date` datetime NOT NULL default '0000-00-00 00:00:00'		  
			) ;";
			$resul_sauvpanier = mysql_query($query_sauvpanier, $cnx->link);     
		}

        function charger($client){
                return $this->getVars("select * from $this->table where client=\"$client\"");
        }

		
		function post(){
			if(($_REQUEST['action'] == "ajouter" || $_REQUEST['action'] == "supprimer") && $_SESSION['navig']->client->id){
							
				$tempsauv = new Sauvpanier();
		
				if($tempsauv->charger($_SESSION['navig']->client->id)){
				 	$this->charger($_SESSION['navig']->client->id);
 					$this->panier = addslashes(serialize($_SESSION['navig']->panier));
					$this->date = date("Y-m-d H:i:s");
					$this->maj();
				}
				else {
					$this->client = $_SESSION['navig']->client->id;
					$this->panier = addslashes(serialize($_SESSION['navig']->panier));
					$this->date = date("Y-m-d H:i:s");
					$this->add();
				}
			}
		  				
		}
		

		function demarrage(){
			if($_REQUEST['action'] == "connexion") {
				
				$client = new Client();
				if($client->charger($_REQUEST['email'], $_REQUEST['motdepasse'])){
				
					$recsauv = new Sauvpanier();
					if(! $recsauv->charger($client->id)){
						$recsauv->client = $client->id;
						$recsauv->panier = addslashes(serialize($_SESSION['navig']->panier));
						$recsauv->date = date("Y-m-d H:i:s");
						$recsauv->add();
						return;					
					}
					
					if(! $_SESSION['navig']->panier->nbart){	
						$_SESSION['navig']->panier = new Panier();
						$_SESSION['navig']->panier = unserialize($recsauv->panier);
					}
					else {
						$recsauv->panier = addslashes(serialize($_SESSION['navig']->panier));
						$this->date = date("Y-m-d H:i:s");
						$recsauv->maj();
						
					}				
				}	
			}
		}

	}


?>
