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
	include_once("classes/Rubrique.class.php");
	include_once("classes/Rubriquedesc.class.php");
	
	/* Subsitutions de type rubrique */
		
	function substitrubriques($texte){
		global $id_rubrique;

		if(! $id_rubrique) return "";
		
		$trubrique = new Rubrique();
	
		$query = "select * from $trubrique->table where id='$id_rubrique'";
		$resul = mysql_query($query, $trubrique->link);
		$row = mysql_fetch_object($resul);

		$trubriquedesc = new Rubriquedesc();

		$trubriquedesc->charger($row->id, $_SESSION['navig']->lang);
		$texte = ereg_replace("#RUBRIQUE_CHAPO", "$trubriquedesc->chapo", $texte);
		$texte = ereg_replace("#RUBRIQUE_ID", "$row->id", $texte);
		$texte = ereg_replace("#RUBRIQUE_NOM", "$trubriquedesc->titre", $texte);
		$texte = ereg_replace("#RUBRIQUE_PARENT", "$row->parent", $texte);
		$texte = ereg_replace("#RUBRIQUE_REWRITEURL", rewrite_rub("$row->id"), $texte);	

		return $texte;
	}
	
?>