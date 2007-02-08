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
	include_once("classes/Message.class.php");
	include_once("classes/Messagedesc.class.php");
	
	/* Subsitutions de type message */
		
	function substitmessage($texte){
				
		ereg("\(([^\"]*)\)", "$texte", $cut);

		$message = new Message();
		$message->charger($cut[1]);
		$messagedesc = new Messagedesc();
		$messagedesc->charger($message->id, $_SESSION['navig']->lang);

		$texte = ereg_replace("#MESSAGE_TITRE\(([^\"]*)\)", "$messagedesc->titre", $texte);
		$texte = ereg_replace("#MESSAGE_CHAPO\(([^\"]*)\)", "$messagedesc->chapo", $texte);
		$texte = ereg_replace("#MESSAGE_DESCRIPTION\(([^\"]*)\)", "$messagedesc->description", $texte);

		return $texte;
	}
	
?>
