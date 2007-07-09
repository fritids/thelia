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

		
	class Nabaztag{

		var $serial;
		var $token;
	


		function Nabaztag($serial, $token){
			$this->serial = $serial;
			$this->token = $token;
		}
	
	        
		function parle($texte){
            $texte = stripslashes($texte);
            $texte = ereg_replace("\n", " ", $texte);
            $texte = urlencode($texte);

            if( ! $fp = @fopen("http://api.nabaztag.com/vl/FR/api.jsp?sn=" . $this->serial . "&token=" . $this->token . "&posleft=16&posright=16&idapp=10&tts=$texte", "r")) return;

            $msg="GET http://api.nabaztag.com/vl/FR/api.jsp?sn=" . $this->serial . "&token=" . $this->token . "&posleft=16&posright=16&idapp=10&tts=$texte HTTP/1.1\r\n";

            $msg.="Host: api.nabaztag.com\r\n";
            $msg .= "Accept-Language: fr-ch, en;q=0.50\r\n";

            $msg .= "Connection: Close\r\n\r\n";
                
			@fputs($fp,"$msg");

            @fclose($fp);

        }                		

	}

?>