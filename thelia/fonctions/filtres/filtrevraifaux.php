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
			
function filtrevrai($texte){

	preg_match_all("`\#FILTRE_vrai\(([^\|]+)\|\|([^\)]+)\)`", $texte, $cut);

	$tab1 = "";
	$tab2 = "";

	for($i=0; $i<count($cut[2]); $i++){
	        if(trim($cut[1][$i]) == "1"){
	                $tab1[$i] = "#FILTRE_vrai(" . $cut[1][$i] . "||" . $cut[2][$i] . ")";
	                $tab2[$i] = $cut[2][$i];
	        }
	
			else{
                $tab1[$i] = "#FILTRE_vrai(" . $cut[1][$i] . "||" . $cut[2][$i] . ")";
                $tab2[$i] = "";
			}

	}

	$texte = str_replace($tab1, $tab2, $texte);

	return $texte;
}

function filtrefaux($texte){

	preg_match_all("`\#FILTRE_faux\(([^\|]+)\|\|([^\)]+)\)`", $texte, $cut);

	$tab1 = "";
	$tab2 = "";

	for($i=0; $i<count($cut[2]); $i++){
	        if(trim($cut[1][$i]) == "0"){
	                $tab1[$i] = "#FILTRE_faux(" . $cut[1][$i] . "||" . $cut[2][$i] . ")";
	                $tab2[$i] = $cut[2][$i];
	        }

			else{
                $tab1[$i] = "#FILTRE_faux(" . $cut[1][$i] . "||" . $cut[2][$i] . ")";
                $tab2[$i] = "";
			}
	}

	$texte = str_replace($tab1, $tab2, $texte);

	return $texte;
}
	
?>