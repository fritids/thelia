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
		
	class Devise extends Baseobj{

		var $id;
		var $nom;
		var $code;
		var $symbole;
		var $taux;
		
		var $table="devise";
		var $bddvars = array("id", "nom", "code", "symbole", "taux");

		function Devise(){
			$this->Baseobj();	
		}
		
		function charger($id){
			return $this->getVars("select * from $this->table where id=\"$id\"");
		}

		function charger_nom($nom){
			return $this->getVars("select * from $this->table where nom=\"$nom\"");
		}
		
		function charger_symbole($symbole){
			return $this->getVars("select * from $this->table where symbole=\"$symbole\"");
		}
		
		function refresh(){
			$cnx = new Cnx();
			$file_contents = file_get_contents('http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml');
			$sxe = new SimpleXMLElement($file_contents);
			foreach ($sxe->Cube[0]->Cube[0]->Cube as $last)
			{
				$sql="UPDATE $this->table SET  taux='".$last["rate"]."' WHERE code='".$last["currency"]."'";
				$req=mysql_query($sql,$cnx->link);
			}
		}
	}


?>