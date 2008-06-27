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

	
		function Contrib(){
			$this->Baseobj();	
		}


		function charger_tous(){

			@ini_set('default_socket_timeout', 5);

			$rss = @fetch_rss("http://contrib.thelia.fr/spip.php?page=contrib");
			if(!$rss) return "";

			$chantitle = $rss->channel['title'];
			$chanlink = $rss->channel['link'];

			$items = array_slice($rss->items, 0);

			foreach ($items as $item) {
				$title = strip_tags($item['title']);
				$description = strip_tags($item['description']);
				$author = $item['dc']['creator'];
				$nomplugin = $item['dc']['nomplugin'];

				$link = $item['link']; 
				$dateh = $item['dc']['date'];
				$jour = substr($dateh, 8,2);
				$mois = substr($dateh, 5, 2);
				$annee = substr($dateh, 2, 2);

				$heure = substr($dateh, 11, 2);
				$minute = substr($dateh, 14, 2);
				$seconde = substr($dateh, 17, 2);
			}	
		}

		
	}
?>
