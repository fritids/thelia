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
	
	class Cache extends Baseobj{

		var $id;
		var $session;
		var $texte;
		var $args;
		var $variables;
		var $type_boucle;
		var $res;
		var $date;
				
		var $table="cache";
		var $bddvars=array("id", "session", "texte", "args", "variables", "type_boucle", "res", "date");
		
		function Cache(){
			$this->Baseobj();	
		}


		function charger_id($id){
			return $this->getVars("select * from $this->table where id=\"$id\"");
		}


		function charger($texte, $args, $variables, $type_boucle){
			return $this->getVars("select * from $this->table where texte=\"$texte\" and args=\"$args\" and variables=\"$variables\" and type_boucle=\"$type_boucle\"");
		}

		function charger_session($session, $texte, $args, $variables, $type_boucle){
			return $this->getVars("select * from $this->table where session=\"$session\" and texte=\"$texte\" and args=\"$args\" and variables=\"$variables\" and type_boucle=\"$type_boucle\"");
		}
			

		function vider($type_boucle, $variables){
			$query = "delete from $this->table where type_boucle=\"$type_boucle\" and variables like \"$variables\"";
			$resul = mysql_query($query, $this->link);
		}	
			
		function vider_session($session, $type_boucle, $variables){
			$query = "delete from $this->table where session=\"$session\" and type_boucle=\"$type_boucle\" and variables like \"$variables\"";
			$resul = mysql_query($query, $this->link);
		}	
				
	}

?>