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
	
	
	class Filtreht extends PluginsClassiques{

		function Filtreht(){
	
			$this->PluginsClassiques();	
	
		}
		
		function init(){
				

		}

		function destroy(){
		
		}		

		function post(){
			
            global $res;

		    preg_match_all("`\#FILTRE_ht\(([^,)]+),([^\))]+)\)`", $res, $cut);

		    $tab1 = "";
		    $tab2 = "";
		    for($i=0; $i<count($cut[1]); $i++){
							$modif = round($cut[1][$i]/(1+$cut[2][$i]/100), 2);
			                $tab1[$i] = "#FILTRE_ht(" . $cut[1][$i] . "," . $cut[2][$i] . ")";
		                    $tab2[$i] = $modif;
		    }


		    $res = str_replace($tab1, $tab2, $res); 

		}
		
	}


?>
