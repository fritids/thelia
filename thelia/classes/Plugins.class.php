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
	
	class Plugins extends Baseobj{
		
		var $nom_plugin;

		function Plugins($nom=""){
			$this->Baseobj();	
			$this->nom_plugin = $nom;			
		}
		
		function init(){
			
		}

		function destroy(){
			
		}		
		
		function getTitre(){
			
			if($_SESSION['navig']->lang == "")
				$lang="1";
			else $lang=$_SESSION['navig']->lang;

			$modulesdesc = new Modulesdesc();
			$modulesdesc->charger($this->nom_plugin);
			
			return $modulesdesc->titre;
			
		}
				
		function getChapo(){

			if($_SESSION['navig']->lang == "")
					$lang="1";
				else $lang=$_SESSION['navig']->lang;
		
				$modulesdesc = new Modulesdesc();
				$modulesdesc->charger($this->nom_plugin);

				return $modulesdesc->chapo;
		}
		
		function getDescription(){

			if($_SESSION['navig']->lang == "")
				$lang="1";
			else $lang=$_SESSION['navig']->lang;
			
			$modulesdesc = new Modulesdesc();
			$modulesdesc->charger($this->nom_plugin);
			
			return $modulesdesc->chapo;		
		}

		function ajout_desc($titre, $chapo, $description, $lang, $devise=""){
			if($lang == "") 
				$lang=1;
				
			$modulesdesc = new Modulesdesc();
			$res = $modulesdesc->charger($this->nom_plugin, $lang);
			
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
		
	}

?>
