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
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Client.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Message.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Messagedesc.class.php");

	class Validcli extends PluginsClassiques{	
	
		function Validcli(){
			$this->PluginsClassiques();	
		}


		function init(){
			$message = new Message();
			if($message->charger("mailactive")) return;
			$message->nom = "mailactive";
			$lastid = $message->add();
			
			$messagedesc = new Messagedesc();
			$messagedesc->message = $lastid;
			$messagedesc->lang = 1;
			$messagedesc->titre = "Activation compte";
			$messagedesc->chapo = "";
			$messagedesc->description = "Après vérification, votre compte a été activé. Merci.";
			$messagedesc->add();		
								
		
		}

		function destroy(){
		
		}		
		
		function inclusion(){
			include_once(realpath(dirname(__FILE__)) . "/config.php");
			
			if(strstr($_SERVER['HTTP_REFERER'], "nouveau.php")){
				$fond = "index.php";
				$_SESSION['navig']->connecte = 0;
				$client = new Client();
				$client->charger_id($_SESSION['navig']->client->id);
				$client->email = $valid_chainesecu . $client->email;
				$client->maj();	
			}
			
			
		}

		
	}


?>
