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

include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");
	
	
	class Fichierlang extends PluginsClassiques{

		function Fichierlang(){
	
			$this->PluginsClassiques();	
	
		}
		
		function init(){
				

		}

		function destroy(){
		
		}		

		function post(){
			
            global $res;
                if(file_exists(realpath(dirname(__FILE__)) . "/../../../lang" . "/lang" .  $_SESSION['navig']->lang . ".php"))
                include_once(realpath(dirname(__FILE__)) . "/../../../lang" . "/lang" .  $_SESSION['navig']->lang . ".php");

            $cle = array_keys($GLOBALS['dictionnaire'] );
            $valeur = array_values($GLOBALS['dictionnaire'] );

                for($i=0;$i<count($cle);$i++)
                   $cle[$i]="::" . $cle[$i] . "::";
                   $res = str_replace($cle, $valeur, $res);

			
		}
		
	}


?>
