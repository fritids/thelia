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
	include_once(realpath(dirname(__FILE__)) . "/Declinaisondesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Declidisp.class.php");
		
	class Declinaison extends Baseobj{

		var $id;
		var $classement;
		
		var $table="declinaison";
		var $bddvars = array("id", "classement");

		function Declinaison(){
			$this->Baseobj();	
		}
		
		function charger($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");


		}

		function changer_classement($id, $type){
			
			$this->charger($id);
			$remplace = new Declinaison();
			
			if($type == "M")
				$res = $remplace->getVars("select * from $this->table where classement<" . $this->classement . " order by classement desc limit 0,1");
			
			else if($type == "D")
				$res  = $remplace->getVars("select * from $this->table where classement>" . $this->classement . " order by classement limit 0,1");
		
			if(! $res)
				return "";
				
			$sauv = $remplace->classement;
			
			$remplace->classement = $this->classement;
			$this->classement = $sauv;

            $remplace->maj();
            $this->maj();

		}
		
		function isDeclidisp(){
			$declidisp = new Declidisp();
			$query = "select * from $declidisp->table where declinaison=\"" . $this->id . "\"";
			$resul = mysql_query($query);
			
			return mysql_num_rows($resul);
			 
		}
		
		function delete($requete){
			
				$resul = mysql_query($requete);	
		}
		
		function supprimer(){

			$declinaisondesc =  new Declinaisondesc();
			
			
			$this->delete("delete from $this->table where id=\"$this->id\"");	
			$this->delete("delete from $declinaisondesc->table where declinaison=\"$this->id\"");
			
			$queryclass = "select * from $this->table order by classement";
			$resclass = mysql_query($queryclass);
			
			if(mysql_num_rows($resclass) > 0){
				$i = 1;
				while($rowclass = mysql_fetch_object($resclass)){
					$dec = new Declinaison();
					$dec->charger($rowclass->id);
					$dec->classement = $i;
					$dec->maj();
					$i++;
				}
			}	

			
			return 1;
		
		}
		
	}

?>