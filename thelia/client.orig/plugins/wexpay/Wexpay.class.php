<?php
/*****************************************************************************
 *
 * Auteur   : Bolo | wexpay.com (contact: infos_web@wexpay.com)
 * Version  : 0.1
 * Date     : 22/10/2007
 *
 * Copyright (C) 2007 Bolo Michelin
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *****************************************************************************/
?>
<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsPaiements.class.php");
	
	class Wexpay extends PluginsPaiements{

		function init(){
			$this->ajout_desc("weXpay", "weXpay", "", 1);
	
		}

		function Wexpay(){
			$this->PluginsPaiements("wexpay");
		}
		
	
		function paiement($commande){

			header("Location: " . "client/plugins/wexpay/paiement.php");			
		}
	
	}

?>
