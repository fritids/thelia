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
	include_once("classes/Commande.class.php");
	
	/* Subsitutions de type commande */
		
	function substitcommande($texte){
		global $commande;

		if($commande) $refs = $commande;
		else $refs = $_SESSION['navig']->commande->ref;
	
		$tcommande = new Commande();
	
		$query = "select * from $tcommande->table where ref='" . $refs . "'";
		$resul = mysql_query($query, $tcommande->link);
		$row = mysql_fetch_object($resul);	
		$texte = ereg_replace("#COMMANDE_ID", "$row->id", $texte);
		$texte = ereg_replace("#COMMANDE_REF", "$row->ref", $texte);
		$texte = ereg_replace("#COMMANDE_TRANSACTION", "$row->transaction", $texte);

		return $texte;
	}
	
?>