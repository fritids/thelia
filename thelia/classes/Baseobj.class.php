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
	include_once(realpath(dirname(__FILE__)) . "/Requete.class.php");
	
	// Classe Baseobj
	
	class Baseobj extends Requete{

		var $bddvars = array();

		function Baseobj(){
			$this->Requete();
		}

		function getListVarsSql(){
			$listvars="";
			
			for($i=0; $i<count($this->bddvars); $i++){
				$listvars .= $this->bddvars[$i] . ",";			
			}
			
			return substr($listvars, 0, strlen($listvars)-1);
			
		
		}
		
		
		function getListValsSql(){
			$listvals="";
			
			for($i=0; $i<count($this->bddvars); $i++){
				$tempvar = $this->bddvars[$i];
				
				 if(get_magic_quotes_gpc())
			  		$this->$tempvar = stripslashes($this->$tempvar);
				
				$this->$tempvar = mysql_real_escape_string($this->$tempvar, $this->link);

				$listvals .= "\"" . $this->$tempvar . "\",";
			}
						
			return substr($listvals, 0, strlen($listvals)-1);
			
		
		}
		
		
		function getVars($query){
            if(! $resul = mysql_query($query, $this->link))
                    return 0;

			$row = mysql_fetch_object($resul);
			if($row){
				for($i=0; $i<count($this->bddvars); $i++){
					$tempvar = $this->bddvars[$i];
					$this->$tempvar =  $row->$tempvar;
				}
				
			return 1;	
			
			} 
			
			else return 0;
				
			return mysql_num_rows($resul);
		}
	
		function serialise_js(){
			$this->host= "";
			$this->login_mysql= "";
       		$this->password_mysql= "";
			$this->db = "";
			$this->link="";
 			$json = new Services_JSON();
			return $json->encode($this); 
		}
	
	}


?>