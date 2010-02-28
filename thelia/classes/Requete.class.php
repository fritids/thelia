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

	include_once(realpath(dirname(__FILE__)) . "/Cnx.class.php");
	
	// Classe Request

	// table --> table à requêter

	class Requete extends Cnx{

		var $table = "";

		function Requete(){
			$this->Cnx();
			
			Cnx::$host="";
			Cnx::$login_mysql="";
			Cnx::$password_mysql="";
			Cnx::$db="";
		}

		function charger(){
			$varid = $this->bddvars[0];
			return $this->getVars("select * from $this->table where $varid=\"" . $this->$varid . "\"");

		}
		
		function add(){
				
			$query = "insert into $this->table(" . $this->getListVarsSql() . ") values(" . $this->getListValsSql() . ")";
			$resul = mysql_query($query, $this->link);
			return mysql_insert_id();
			
		}
				

		function delete(){
			$varid = $this->bddvars[0];
			$query = "delete from $this->table where $varid=\"" . $this->$varid . "\"";
			if($this->$varid != "")
				$resul = mysql_query($query, $this->link);
		}
		
		function maj(){
			$listv = "";
			
			$varid = $this->bddvars[0];
			
			for($i=0; $i<count($this->bddvars); $i++){
				$varn = $this->bddvars[$i];

			 if(get_magic_quotes_gpc())
			  		$this->$varn = stripslashes($this->$varn);

			 $this->$varn = mysql_real_escape_string($this->$varn, $this->link);
			 
				$listv.= $this->bddvars[$i] . "=\"" . $this->$varn . "\", ";
			}
			
			$query = "update $this->table set " . substr($listv, 0, strlen($listv)-2) . " where $varid=\"" . $this->$varid . "\"";
			if($this->$varid != "")
				$resul = mysql_query($query, $this->link);
			return $query;
		}
				
		function purge(){
			$query = "truncate table `$this->table` ";
			$resul = mysql_query($query, $this->link);
		}
		
	}
	
?>