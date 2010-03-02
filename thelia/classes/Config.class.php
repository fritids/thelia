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

    class Config {

        private $host;
        private $login_mysql;
        private $password_mysql;
        private $db;   
        private $squelettes; 

    
        public function Config() {
                require(realpath(dirname(__FILE__)) . "/../config/config.var.php");
                $this->host = $host;
                $this->login_mysql = $login_mysql;
                $this->password_mysql = $password_mysql;
                $this->db = $db;
                $this->squelettes = $squelettes;
        }  
    
        public function get($config) {
            switch ($config) {
                case 'host' :
                    return $this->host;
                    break;
                case 'login_mysql' :
                    return $this->login_mysql;
                    break;
                case 'password_mysql' :
                    return $this->password_mysql;
                    break;
                case 'db' :
                    return $this->db;
                    break;
                case 'squelettes' :
                	return $this->squelettes ? this->squelettes : "./" ;
                	break
            }
        }
    }
?>
