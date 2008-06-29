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
	
	class Contrib extends Baseobj{

		var $titre;
		var $description;
		var $auteur;
		var $nomplugin;
		var $lien;
	
		function Contrib(){
			$this->Baseobj();	
		}


		function recuperer($nomplugin){
			$tab = $this->charger_tous();
			return $this->chercher($nomplugin, $tab);
			
		}
		
		function chercher($nomplugin, $tab){

			for($i = 0; $i<count($tab); $i++){
				if($tab[$i]->nomplugin == $nomplugin)
					return $tab[$i];
			}
			return "";
			
		}

		function charger_tous(){
			include_once(realpath(dirname(__FILE__)) . "/../lib/magpierss/rss_fetch.inc");
			
			@ini_set('default_socket_timeout', 5);

			$tab = "";
			$i = 0;
			
			$rss = @fetch_rss("http://contrib.thelia.fr/spip.php?page=contrib");
			if(!$rss) return "";

			$items = array_slice($rss->items, 0);

			foreach ($items as $item) {
				$title = strip_tags($item['title']);
				$description = strip_tags($item['description']);
				$author = $item['dc']['creator'];
				$nomplugin = $item['dc']['nomplugin'];
				$link = $item['link']; 
				
				$tab[$i] = new Contrib();
				$tab[$i]->titre = $title;
				$tab[$i]->description = $description;
				$tab[$i]->auteur = $author;
				$tab[$i]->nomplugin = $nomplugin;
				$tab[$i]->lien = $link;	
				
				$i++;			
			}	
			
			return $tab;
		}

		
	}
?>
