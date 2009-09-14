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
<div id="entete">
	<div class="logo">
		<a href="accueil.php"><img src="gfx/thelia_logo.jpg" alt="THELIA solution e-commerce" /></a>
	</div>
	<div class="Blocmoncompte">
		<dt><a href="index.php?action=deconnexion" >D&eacute;connexion</a></dt><dt> | </dt><dt><strong><?php echo($_SESSION["util"]->prenom); ?> <?php echo($_SESSION["util"]->nom); ?></strong> </dt>
	</div>
	<div class="Blocversion">V <?php echo substr($version, 0, 1) . "." . substr($version, 1, 1) . "." . substr($version, 2, 1) ?></div>
</div>   
<div id="menuGeneral">
	<div>
   		<ul id="menu">
	        <li><a href="accueil.php" <?php if($menu == "accueil") { ?>class="selected"<?php } ?>>Accueil</a></li>
	    </ul>
	   <ul class="separation_menu">&nbsp;</ul>
	        	
	  <?php	if(est_autorise("acces_clients")){ ?>
	        <ul id="menu1">	
	        <li><a href="client.php" <?php if($menu == "client") { ?>class="selected"<?php } ?>>Clients</a></li>
	        </ul>
	        <ul class="separation_menu">&nbsp;</ul>
	  <?php } ?>
	  <?php	if(est_autorise("acces_commandes")){ ?>
	        <ul id="menu2">
            <li><a href="commande.php" <?php if($menu == "commande") { ?>class="selected"<?php } ?>>Commandes</a></li>
            </ul>
            <ul class="separation_menu">&nbsp;</ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_catalogue")){ ?>
             <ul id="menu3">
            <li><a href="parcourir.php" <?php if($menu == "catalogue") { ?>class="selected"<?php } ?>>Catalogue </a></li>
            </ul>
            <ul class="separation_menu">&nbsp;</ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_contenu")){ ?>
             <ul id="menu4">
            <li><a href="listdos.php" <?php if($menu == "contenu") { ?>class="selected"<?php } ?>>Contenu</a></li>
            </ul>
            <ul class="separation_menu">&nbsp;</ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_codespromos")){ ?>
             <ul id="menu5">
            <li><a href="promo.php" <?php if($menu == "paiement") { ?>class="selected"<?php } ?>>Codes promos</a></li>
            </ul>
            <ul class="separation_menu">&nbsp;</ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_configuration")){ ?>
             <ul id="menu6">
            <li><a href="configuration.php" <?php if($menu == "configuration") { ?>class="selected"<?php } ?>>Configuration</a></li>
            </ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_modules")){ ?>
            <ul class="separation_menu">&nbsp;</ul>
            <ul id="menu7">
			<li><a href="module_liste.php" <?php if($menu == "plugins") { ?>class="selected"<?php } ?>>Modules</a></li>
			</ul>
			<ul class="separation_menu">&nbsp;</ul>
			</ul>
		  <?php } ?>
      	</div>
		  <?php	if(est_autorise("acces_rechercher")){ ?>
            <div id="moteur_recherche"> 
             <form action="recherche.php" method="post">
              <div class="bouton_recherche">
	         	<input type="image" src="gfx/icone_recherche.jpg" alt="Valider la recherche" />
	         </div>
             <div class="champs_recherche">
	         	<input type="text" name="motcle" value="Rechercher ..." class="zonerecherche" onClick="this.value=''" size="14" />
	         </div>
	        
             </form>
            </div>
           <?php } ?>
        
</div>

  

</div>