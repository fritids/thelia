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

// redimensionnement des images + effets

	include_once("divers.php");

	// Déclaration des variables
	if(isset($_REQUEST['type'])) 
		$type = $_REQUEST['type'] ; else  $type = "";
	if(isset($_REQUEST['nomorig'])) 
		$nomorig = $_REQUEST['nomorig'] ; else  $nomorig = "";
	if(isset($_REQUEST['height'])) 
		$height = $_REQUEST['height'] ; else  $height = "";
	if(isset($_REQUEST['width'])) 
		$width=$_REQUEST['width']; else $width="";
	if(isset($_REQUEST['opacite'])) 
		$opacite=$_REQUEST['opacite']; else $opacite="";
	if(isset($_REQUEST['nb'])) 
		$nb=$_REQUEST['nb']; else $nb="";
	if(isset($_REQUEST['miroir'])) 
		$miroir=$_REQUEST['miroir']; else $miroir="";

	 $nomcache = redim($type, $nomorig, $width, $height, $opacite, $nb, $miroir);
	 header("Location: ../$nomcache");


 ?>