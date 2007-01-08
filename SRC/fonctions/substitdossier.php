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
	include_once("classes/Dossier.class.php");
	include_once("classes/Dossierdesc.class.php");
	
	/* Subsitutions de type dossier */
		
	function substitdossier($texte){
		global $id_dossier;
		
		$tdossier = new Dossier();
	
		$query = "select * from $tdossier->table where id='$id_dossier'";
		$resul = mysql_query($query, $tdossier->link);
		$row = mysql_fetch_object($resul);

		$tdossierdesc = new Dossierdesc();
		$query2 = "select * from $tdossierdesc->table where dossier='$row->id'";
		$resul2 = mysql_query($query2, $tdossier->link);
		$row2 = mysql_fetch_object($resul2);

		$texte = ereg_replace("#DOSSIER_CHAPO", "$row2->chapo", $texte);
		$texte = ereg_replace("#DOSSIER_ID", "$row->id", $texte);
		$texte = ereg_replace("#DOSSIER_NOM", "$row2->titre", $texte);
		$texte = ereg_replace("#DOSSIER_PARENT", "$row->parent", $texte);

		return $texte;
	}
	
?>