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
	include("../classes/Administrateur.class.php");
	
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

<div id="entete">
	<img src="gfx/logo_thelia.gif" width="305" height="57" align="left" lowsrc="THELIA" />
</div> 

<div id="contenu"> 
  <div class="chapeau">
    <p class="geneva14bold_3B4B5B">
    Bienvenue dans votre <a href="#" class="lien01">zone administrateur </a></p>
    <p class="geneva12Reg_3B4B5B"> &nbsp;</p>
  </div>  
  <div class="chapeau"> 
   <table width="559" border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td align="left" valign="top"><img src="gfx/zone_d_identification/header.gif" width="559" height="40" /></td>
     </tr>
     <tr>
       <td height="22" align="center" valign="middle" background="gfx/zone_d_identification/background.gif"><table width="501" height="22" border="0" cellpadding="0" cellspacing="0">
       <form action="accueil.php" method="post" id="formulaire">
         <tr align="left" valign="middle">
           <td width="190" class="geneva11Reg_3B4B5B">Login : 
             <input name="identifiant" type="text" class="form" size="19" /></td>
           <td width="251" class="geneva11Reg_3B4B5B">Mot de passe : <span class="geneva11Reg_3B4B5B">
             <input name="motdepasse" type="password" class="form" size="19" />
             <input name="action" type="hidden" value="identifier" />
           </span></td>
           <td width="60" align="left"><a href="#" onClick="document.getElementById('formulaire').submit()"><img src="gfx/zone_d_identification/bt_valider.gif" width="54" height="20" border="0" /></a></td>
         </tr>
         </form>   
       </table></td>
     </tr>
     <tr>
       <td align="left" valign="top"><img src="gfx/zone_d_identification/footer.gif" width="559" height="54" /></td>
     </tr>
   </table>
  </div>
  

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

  
  <div class="paragraphe"> 
   <p>
	<span class="geneva12Bold_3B4B5B"><?php echo($jour . "/" . $mois . "/" . $annee); ?> &bull; <?php echo($title); ?></span><br />
    <span class="geneva11Reg_3B4B5B"><?php echo($description); ?></span><br />
    <span class="geneva12Reg_3B4B5B"><span class="geneva11Reg_3B4B5B"><a href="<?php echo($link); ?>"><img src="gfx/bt_lireLaSuite.gif" width="78" height="11" border="0" align="right" /></a></span></span>
    </p> 
  </div> 
  
<?php
	}
?>  
  
  
  
</div> 
  
<div id="coordonnees"> 
  <p class="geneva11Reg_A3ADB8">    <span class="geneva11Reg_BAC2CA">Thelia<br />
    est une application <br />
    d&eacute;velopp&eacute; par : </span><br />
    <a href="http://www.octolys.fr" class="lien02">octolys.fr</a></p>
  <p class="geneva11Reg_BAC2CA">18 rue Camille Joubert<br />
    63300 Thiers<br />
    T&eacute;l. : 04 73 51 34 41<br />
  Fax : 04 73 80 14 73</p>
  <p class="geneva11Reg_BAC2CA">B114<br />
  17 rue du Pr&eacute; la Reine<br />
    63000 Clermont-Ferrand<br />
    T&eacute;l. : 04 73 74 31 26<br />
    Fax : 04 73 90 58 99</p>
</div> 
</body>

</html>
