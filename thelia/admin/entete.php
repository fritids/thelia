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
	include_once("../classes/Racmodule.class.php");
?>
<div id="entete">
	<div id="logo">
		<a href="accueil.php"><img src="gfx/thelia.jpg" alt="THELIA solution e-commerce" /></a>
	
	<h1>V <?php echo substr($version, 0, 1) . "." . substr($version, 1, 1) . "." . substr($version, 2, 1) ?></h1>
	</div>
  
<div id="telecommande"> 
  
   	<div id="menuGeneral">
   		<ul id="menu">
	        <li><a href="accueil.php" <?php if($menu == "accueil") { ?>class="selected"<?php } ?>>Accueil</a></li>		
	        <li><a href="client.php" <?php if($menu == "client") { ?>class="selected"<?php } ?>>Clients</a></li>
            <li><a href="commande.php" <?php if($menu == "commande") { ?>class="selected"<?php } ?>>Commandes</a></li>
            <li><a href="parcourir.php" <?php if($menu == "catalogue") { ?>class="selected"<?php } ?>>Catalogue </a></li>
            <li><a href="listdos.php" <?php if($menu == "contenu") { ?>class="selected"<?php } ?>>Contenu</a></li>
            <li><a href="promo.php" <?php if($menu == "paiement") { ?>class="selected"<?php } ?>>Codes promos</a></li>
            <li><a href="configuration.php" <?php if($menu == "configuration") { ?>class="selected"<?php } ?>>Configuration</a></li>
			<li><a href="module_liste.php" <?php if($menu == "plugins") { ?>class="selected"<?php } ?>>Modules</a></li>
            <li><a href="#"><input type="text" name="motcle" value="Rechercher ..." onClick="this.value=''" size=14" /></a></li>
        </ul>
        
        		<div id="globalSearch">
        			<form action="recherche.php" method="post">

	  				</form>
      			</div>

			</div>
		</div>

  
</div> 
</div>