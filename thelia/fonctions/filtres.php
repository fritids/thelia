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

	include_once(realpath(dirname(__FILE__)) . "/filtres/filtrevide.php");
	include_once(realpath(dirname(__FILE__)) . "/filtres/filtrefonction.php");
	include_once(realpath(dirname(__FILE__)) . "/filtres/filtrevraifaux.php");
	include_once(realpath(dirname(__FILE__)) . "/filtres/filtreegalite.php");
	
						 
	function filtres($texte){
		
		if(strstr($texte, "#FILTRE_vide")) $texte = filtrevide($texte);				
		if(strstr($texte, "#FILTRE_min")) $texte = filtre_fonction($texte, "min", "strtolower");	
		if(strstr($texte, "#FILTRE_maj")) $texte = filtre_fonction($texte, "maj", "strtoupper");			
		if(strstr($texte, "#FILTRE_sanstags")) $texte = filtre_fonction($texte, "sanstags", "strip_tags");			
		if(strstr($texte, "#FILTRE_vrai")) $texte = filtrevrai($texte);				
		if(strstr($texte, "#FILTRE_faux")) $texte = filtrefaux($texte);				
		if(strstr($texte, "#FILTRE_egalite")) $texte = filtreegalite($texte);				
		if(strstr($texte, "#FILTRE_different")) $texte = filtredifferent($texte);				
	
			
		return $texte;
	}
	
?>