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

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsTransports.class.php");
	
	class Colissimo extends PluginsTransports{

		var $poids;
		var $nbart;
		var $total;
		var $zone;
		var $pays;
		var $unitetr;
		var $cpostal;

		function Colissimo(){
			$this->PluginsTransports;
		}
		
		
		function calcule(){
			if($this->poids<=0.5) return 6;
			else if($this->poids>0.5 && $this->poids<=1) return 6.50;
			else if($this->poids>1 && $this->poids<=2) return 7;
			else if($this->poids>2 && $this->poids<=3) return 8;
			else if($this->poids>3 && $this->poids<=5) return 9;
			else if($this->poids>5 && $this->poids<=7) return 10;
			else if($this->poids>7 && $this->poids<=10) return 12;
			else if($this->poids>10 && $this->poids<=15) return 14;
			else if($this->poids>15 && $this->poids<=30) return 20;
			else if($this->poids>30) return 20;
		
		}
		
		function getTitre(){
			
			if($this->type == "1")
				$rep="paiement";
				
			else if($this->type == "2")
				$rep="transports";
				
	     	include(realpath(dirname(__FILE__)) . "/config.php");
			
			if($_SESSION['navig']->lang == "")
				$lang="1";
			else $lang=$_SESSION['navig']->lang;
			
			$titre="titre" . $lang;
			
			if(isset($$titre))
				return $$titre;
		}
				
		function getChapo(){
			
			if($this->type == "1")
				$rep="paiement";
				
			else if($this->type == "2")
				$rep="transports";
				
	     	include(realpath(dirname(__FILE__)) . "/config.php");
			
			if($_SESSION['navig']->lang == "")
					$lang="1";
				else $lang=$_SESSION['navig']->lang;
		
			
			$chapo="chapo" . $lang;
		
			if(isset($$chapo))
				return $$chapo;
		}
		
		function getDescription(){
			
			if($this->type == "1")
				$rep="paiement";
				
			else if($this->type == "2")
				$rep="transports";
							
	     	include(realpath(dirname(__FILE__)) . "/config.php");
			
			if($_SESSION['navig']->lang == "")
				$lang="1";
			else $lang=$_SESSION['navig']->lang;
			
			$description="description" . $lang;
		
			if(isset($$description))
				return $$description;			
		}

		function getDevise(){
			
			if($this->type == "1")
				$rep="paiement";
				
			else if($this->type == "2")
				$rep="transports";
							
	     	include(realpath(dirname(__FILE__)) . "/config.php");
	
		
			if(isset($devise))
				return $devise;			
		}
	
	}


?>
