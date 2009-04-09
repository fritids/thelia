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
	@ini_set('default_socket_timeout', 5);
	include_once("pre.php");
	include_once("../classes/Administrateur.class.php");
	
	session_start();
	
	if(isset($action))
		if($action == "deconnexion") unset($_SESSION["util"]);
	
	include_once("../lib/magpierss/rss_fetch.inc");
	include_once("../classes/Variable.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">
<div id="entete">
	<div class="logo">
		<a href="accueil.php"><img src="gfx/thelia_logo.jpg" alt="THELIA solution e-commerce" /></a>
	</div>
<div id="menuGeneral">
	<div id="formConnex">
       		<form action="accueil.php" method="post" id="formulaire">
       			Login : 
             <input name="identifiant" type="text" class="form" size="19" />
          		Mot de passe :
             <input name="motdepasse" type="password" class="form" size="19" />
             <input name="action" type="hidden" value="identifier" />
         	<input type="submit" value="valider"/>
         	</form>   
     </div>
     
</div>

</div> 

    <?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
