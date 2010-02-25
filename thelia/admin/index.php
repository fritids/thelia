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
<?php
	function couperTexte($texte, $nbcar){
        $res="";
        $cut = explode(" ", $texte);

        for($i=0; $i<count($cut); $i++)
                if(strlen($res) + strlen($cut[$i]) + 1 <= $nbcar) $res .= $cut[$i] . " ";
        else return $res;

        return $res;
	}

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
       			Nom d'utilisateur : 
             	<input name="identifiant" type="text" class="form" size="19" />
          		Mot de passe :
             	<input name="motdepasse" type="password" class="form" size="19" />
             	<input name="action" type="hidden" value="identifier" />
         		<input type="submit" value="valider"/>
         	</form>   
     </div>
     
</div>

<div id="contenu_int">
<?php

$rss = @fetch_rss("http://blog.thelia.fr/rss.php?cat=Formation");
if(!$rss) return "";

$chantitle = $rss->channel['title'];
$chanlink = $rss->channel['link'];
		
$items = array_slice($rss->items, 0, 1);

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
	<div class="Bloc_news_index">
		<div class="picto"><img src="gfx/picto-formation.gif" /></div>
		<div class="texte"><li class="date"><?php echo($jour . "/" . $mois . "/" . $annee); ?></li><li class="titre"><a href="<?php echo($link); ?>" target="_blank"><?php echo($title); ?></a></li><br /><?php echo(couperTexte($description, 100)) . "..."; ?></div>
	</div>
<?php
	}
?>

<?php

$rss = @fetch_rss("http://blog.thelia.fr/rss.php?cat=General");
if(!$rss) return "";

$chantitle = $rss->channel['title'];
$chanlink = $rss->channel['link'];
		
$items = array_slice($rss->items, 0, 1);

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
	<div class="Bloc_news_index">
		<div class="picto"><img src="gfx/picto-news.gif" /></div>
		<div class="texte"><li class="date"><?php echo($jour . "/" . $mois . "/" . $annee); ?></li><li class="titre"><a href="<?php echo($link); ?>" target="_blank"><?php echo($title); ?></a></li><br /><?php echo(couperTexte($description, 100)) . "..."; ?></div>
	</div>
<?php
	}
?>

<?php

$rss = @fetch_rss("http://blog.thelia.fr/rss.php?cat=Securite");
if(!$rss) return "";

$chantitle = $rss->channel['title'];
$chanlink = $rss->channel['link'];
		
$items = array_slice($rss->items, 0, 1);

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

	<div class="Bloc_news_index">
		<div class="picto"><img src="gfx/picto-alertes.gif" /></div>
		<div class="texte"><li class="date"><?php echo($jour . "/" . $mois . "/" . $annee); ?></li><li class="titre"><a href="<?php echo($link); ?>" target="_blank"><?php echo($title); ?></a></li><br /><?php echo(couperTexte($description, 100)) . "..."; ?></div>
	</div>

<?php 
	}
?>	
</div>

</div> 

    <?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
