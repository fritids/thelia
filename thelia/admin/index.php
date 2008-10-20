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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>

<link href="styles.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="wrapper">
<div id="subwrapper">
<div id="entete">
	<div id="logo">
		<img src="gfx/thelia.jpg" alt="THELIA solution e-commerce" />
	</div>
	<div id="telecommande"> 
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
		<div class="bienvenue">
      		<h2>Bienvenue dans votre <a href="#">zone administrateur </a></h2>
      	</div>
</div> 
<div id="coordonnees">
Thelia est une application d&eacute;velopp&eacute;e par : <br />
<a href="http://www.octolys.fr">Octolys</a>
17 rue du Pr&eacute; la Reine - 63100 Clermont-Ferrand<br />
T&eacute;l. : 04 73 74 31 19<br />
----------<br />
Design de l'outil d'administration <a href="http://www.scopika.com">Scopika</a><br />
</div>

<div id="contenu_int"> 

    <h3>L'actualit&eacute; THELIA</h3>
<table> 

<?php
$rssadmin = new Variable();
$rssadmin->charger("rssadmin");

$rss = @fetch_rss($rssadmin->valeur);
if(!$rss) return "";

$chantitle = $rss->channel['title'];
$chanlink = $rss->channel['link'];
		
$items = array_slice($rss->items, 0);

foreach ($items as $item) {
	$title = strip_tags($item['title']);
	$description = strip_tags($item['description']);
	$author = $item['dc']['creator'];
	$link = $item['link']; 
	$dateh = $item['dc']['date'];
	$jour = substr($dateh, 8,2);
	$mois = substr($dateh, 5, 2);
	$annee = substr($dateh, 2, 2);

	$heure = substr($dateh, 11, 2);
	$minute = substr($dateh, 14, 2);
	$seconde = substr($dateh, 17, 2);
	
?>

		<tr>
			<th colspan="2"  class="left"><a href="<?php echo($link); ?>"><?php echo($jour . "/" . $mois . "/" . $annee); ?></a> &bull; <?php echo($title); ?></th>
		</tr>


<?php
	}
?>  
</table>
</div>
</div>
</div>
</body>
</html>
