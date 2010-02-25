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
	include_once(realpath(dirname(__FILE__)) . "/Cache.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Modulesdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../fonctions/divers.php");

	
	class PluginsClassiques extends Baseobj{

		var $nom_plugin;
		
		function PluginsClassiques($nom=""){
			$this->Baseobj();	
			$this->nom_plugin = $nom;	
		}

		function init(){
			
		}

		function destroy(){
			
		}		
		
		function getTitre(){
			
            if(! isset($_SESSION['navig']->lang))
                $lang="1";
            else if(isset($_SESSION['navig']->lang))
                $lang=$_SESSION['navig']->lang;

			$modulesdesc = new Modulesdesc();
			$modulesdesc->charger($this->nom_plugin, $lang);
			
			return $modulesdesc->titre;
			
		}
				
		function getChapo(){

            if(! isset($_SESSION['navig']->lang))
                $lang="1";
            else if(isset($_SESSION['navig']->lang))
                $lang=$_SESSION['navig']->lang;
		
				$modulesdesc = new Modulesdesc();
				$modulesdesc->charger($this->nom_plugin, $lang);

				return $modulesdesc->chapo;
		}
		
		function getDescription(){

            if(! isset($_SESSION['navig']->lang))
                $lang="1";
            else if(isset($_SESSION['navig']->lang))
                $lang=$_SESSION['navig']->lang;
			
			$modulesdesc = new Modulesdesc();
			$modulesdesc->charger($this->nom_plugin, $lang);
			
			return $modulesdesc->description;		
		}

		function ajout_desc($titre, $chapo, $description, $lang=1, $devise=""){
					
			$modulesdesc = new Modulesdesc();
			$res = $modulesdesc->verif($this->nom_plugin, $lang);
			
			$modulesdesc->plugin = $this->nom_plugin;
			$modulesdesc->titre = $titre;
			$modulesdesc->chapo = $chapo;
			$modulesdesc->description = $description;
			$modulesdesc->lang = $lang;
			$modulesdesc->devise = $devise;
			
			if($res)
				$modulesdesc->maj();
			else $modulesdesc->add();
			
			
		}
		
		function demarrage(){}

		function inclusion(){}
		
		function pre(){}
		
		function action(){}
		
		function boucle($texte, $args){}		

		function post(){}

		function apres(){}

		function avantcommande(){}

		function aprescommande(){}

		function mail(){}
		
		function avantclient(){}

		function apresclient(){}
						
		function statut($commande){}
		
		function confirmation($commande){}
		
		function modprod($ref){}
		
	}

?>