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
	include_once("pre.php");
	include_once("auth.php");
?>
<?php if(! est_autorise("acces_configuration")) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
     <p><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Configuration</a>              
    </p>
<div id="bloc_informations">
	<ul>
	<li class="entete_configuration">GESTION DU CATALOGUE PRODUIT</li>
	<li class="lignetop" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Gestion des caractéristiques</li>
	<li class="lignetop" style="width:72px;"><a href="caracteristique.php">&eacute;diter</a></li>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Gestion des déclinaisons</li>
	<li class="fonce" style="width:72px;"><a href="declinaison.php">&eacute;diter</a></li>
	<li class="claire" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Gestion des messages</li>
	<li class="claire" style="width:72px;"><a href="message.php">&eacute;diter</a></li>
	<li class="lignebottomfonce" style="width:222px; background-color:#9eb0be;">Gestion des devises</li>
	<li class="lignebottomfonce" style="width:72px;"><a href="devise.php">&eacute;diter</a></li>

	</ul>
	<ul>
	<li class="entete_configuration">GESTION DES TRANSPORTS ET LIVRAISONS</li>
	<li class="lignetop" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Gestion des types de transport</li>
	<li class="lignetop" style="width:72px;"><a href="transport.php">&eacute;diter</a></li>
	<li class="lignebottomfonce" style="width:222px; background-color:#9eb0be;">Gestion des zones de livraison</li>
	<li class="lignebottomfonce" style="width:72px;"><a href="zone.php">&eacute;diter</a></li>
	</ul>
	<ul>
	<li class="entete_configuration">PARAMETRES SYSTEME</li>
	<li class="lignetop" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Activation des plugins</li>
	<li class="lignetop" style="width:72px;"><a href="plugins.php">&eacute;diter </a></li>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Gestion des variables</li>
	<li class="fonce" style="width:72px;"><a href="variable.php">&eacute;diter </a></li>
	<li class="lignebottom" style="width:222px; background-color:#9eb0be;">Gestion des administrateurs</li>
	<li class="lignebottom" style="width:72px;"><a href="gestadm.php">&eacute;diter</a></li>
	<li class="lignebottom" style="width:222px; background-color:#9eb0be;">Gestion des droits</li>
	<li class="lignebottom" style="width:72px;"><a href="droits.php">&eacute;diter</a></li>
	</ul>
</div>
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
