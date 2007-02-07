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

	include_once("classes/Contenu.class.php");
	include_once("classes/Contenudesc.class.php");
	
	/* Subsitutions de type contenu */
		
	function substitcontenu($texte){
		global $motcle, $id_contenu;

		if(! $id_contenu) return $texte;
		
		$tcontenu = new Contenu();
		$tcontenudesc = new Contenudesc();
		
		$query = "select * from $tcontenu->table where id='$id_contenu'";
		$resul = mysql_query($query, $tcontenu->link);
		$row = mysql_fetch_object($resul);
		$tcontenudesc->charger($row->id);
		    
		$texte = ereg_replace("#CONTENU_ID", "$id_contenu", $texte);
		$texte = ereg_replace("#CONTENU_MOTCLE", "$motcle", $texte);
		$texte = ereg_replace("#CONTENU_NOM", $tcontenudesc->titre, $texte);

		return $texte;
	
	}
	
?>
