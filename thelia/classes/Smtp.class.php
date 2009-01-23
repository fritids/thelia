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

  class Smtp{

        var $server;
		var $port=25;
        var $from;
        var $rcpt;
        var $subject;
        var $texte;

        function Smtp(){

        }



        function ligne($fp, $msg, $vide=0){
             fputs($fp, "$msg");
             if($vide) fgets($fp, 1024);

        }

        function envoyer(){

             $fp = fsockopen($this->server, $this->port);

             $this->ligne($fp, "helo server\r\n");
             $this->ligne($fp, "mail from: " . $this->from . "\r\n");
             $this->ligne($fp, "rcpt to: " . $this->rcpt . "\r\n");
             $this->ligne($fp, "data\r\n");
             $this->ligne($fp, "From: " . $this->from . "\r\n");
             $this->ligne($fp, "To: " . $this->rcpt ."\r\n");
             $this->ligne($fp, "Subject: " . $this->subject . "\r\n");
             $this->ligne($fp, "\r\n");
             $this->ligne($fp, $this->texte . "\r\n");
             $this->ligne($fp, ".\r\n", 1);
       }

 }
?>