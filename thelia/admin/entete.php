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
	<img src="gfx/logo_thelia.gif" width="305" height="57" align="left" lowsrc="THELIA" />
</div> 
  
<div id="telecommande"> 
  <table width="248" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top"><img src="gfx/telecommande/header_telecommande.gif" width="248" height="48" /></td>
    </tr>
    <tr>
      <td align="center" valign="top" background="gfx/telecommande/fond_menu.gif"><table width="170"  border="0" align="center" cellpadding="0" cellspacing="0">

<!---------------------------------------------------------------------------------------------------------------------------->
	<?php if($menu != "accueil") { ?>

          <tr>
            <td height="18" align="left" valign="middle"><a href="accueil.php" class="lien04">Accueil</a></td>
          </tr>
    <?php } else { ?>             
        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"> Accueil </td>
        </tr>  
     <?php } ?>    
               
<!---------------------------------------------------------------------------------------------------------------------------->



<!---------------------------------------------------------------------------------------------------------------------------->

	<?php if($menu != "client") { ?>
          <tr>
            <td height="18" align="left" valign="middle"><a href="client.php" class="lien04">Gestion des clients</a></td>
          </tr>
    <?php } else { ?>      
        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"><a href="client.php" class="selection_menul">Gestion des clients</a></td>
        </tr>
    <?php } ?>    
<!---------------------------------------------------------------------------------------------------------------------------->


<!---------------------------------------------------------------------------------------------------------------------------->
	<?php if($menu != "commande") { ?>
          <tr>
            <td height="18" align="left" valign="middle"><a href="commande.php" class="lien04">Gestion des commandes</a></td>
          </tr>
    <?php } else { ?>      
        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"><a href="commande.php" class="selection_menul">Gestion des commandes</a></td>
        </tr>
    <?php } ?>    
        
<!---------------------------------------------------------------------------------------------------------------------------->

<!---------------------------------------------------------------------------------------------------------------------------->
<?php /*
	<?php if($menu != "devis") { ?>

          <tr>
            <td height="18" align="left" valign="middle"><a href="#" class="lien04">Gestion des devis </a></td>
          </tr>
    <?php } else { ?>      
          
        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu">Gestion des devis </td>
        </tr>
    <?php } ?>    
*/?>
<!---------------------------------------------------------------------------------------------------------------------------->
        
	<?php if($menu != "catalogue") { ?>
        
          <tr>
            <td height="18" align="left" valign="middle"><a href="catalogue.php" class="lien04">Gestion du catalogue </a></td>
          </tr>
    <?php } else { ?>      

        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"><a href="catalogue.php" class="selection_menul">Gestion du catalogue</a></td>
        </tr>          
    <?php } ?>    

<!---------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------->
   
        
	<?php if($menu != "contenu") { ?>
        
          <tr>
            <td height="18" align="left" valign="middle"><a href="contenu.php" class="lien04">Gestion du contenu</a></td>
          </tr>
    <?php } else { ?>      

        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"><a href="contenu.php" class="selection_menul">Gestion du contenu</a></td>
        </tr>          
    <?php } ?>    

<!---------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------->
	<?php if($menu != "paiement") { ?>

          <tr>
            <td height="18" align="left" valign="middle"><a href="paiement.php" class="lien04">Gestion du paiement </a></td>
          </tr>
    <?php } else { ?>      

        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"><a href="paiement.php" class="selection_menul">Gestion du paiement</a></td>
        </tr>          
    <?php } ?>    

<!---------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------->
	<?php if($menu != "livraisons") { ?>

          <tr>
            <td height="18" align="left" valign="middle"><a href="gestlivraison.php" class="lien04">Gestion des livraisons </a></td>
          </tr>
    <?php } else { ?>      

        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"><a href="gestlivraison.php" class="selection_menul">Gestion des livraisons</a></td>
        </tr>          
    <?php } ?>    

<!---------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------->
	<?php if($menu != "configuration") { ?>
	
          <tr>
            <td height="18" align="left" valign="middle"><a href="configuration.php" class="lien04">Configuration</a></td>
          </tr>
    <?php } else { ?>      

        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu"><a href="configuration.php" class="selection_menul">Configuration</a></td>
        </tr>          
    <?php } ?>    


<!--------------------------------------------------------------------------------------------------------------------------------->
		<?php if($menu != "plugins") { ?>

	          <tr>
	            <td height="18" align="left" valign="middle"><a href="module_liste.php" class="lien04">Plugins</a></td>
	          </tr>
	    <?php } else { ?>      

	        <tr>
	          <td height="18" align="left" valign="middle" class="selection_menu"><a href="module_liste.php" class="selection_menul">Plugins</a></td>
	        </tr>          
	    <?php } ?>    
<!--------------------------------------------------------------------------------------------------------------------------------->

	

	<?php if($menu != "fermer") { ?>

          <tr>
            <td height="18" align="left" valign="middle"><a href="index.php?action=deconnexion" class="lien04">Fermer la session </a></td>
          </tr>
    <?php } else { ?>      

        <tr>
          <td height="18" align="left" valign="middle" class="selection_menu">Fermer la session</td>
        </tr>          
    <?php } ?>    

<!---------------------------------------------------------------------------------------------------------------------------->


      </table></td>
    </tr>
    <tr>
      <td align="left" valign="top"><img src="gfx/telecommande/footer_ecran_telecommande.gif" width="248" height="17" /></td>
    </tr>
	<tr>
      <td height="20" align="center" valign="middle" background="gfx/telecommande/fond_telecommande.gif">
	  <form action="recherche.php" method="post">
	  <input type="text"  class="form" name="motcle" value="Rechercher ..." onClick="this.value=''"/>
	  </form>
	  </td>
    </tr>
    <tr>
      <td align="left" valign="top"><img src="gfx/telecommande/footer_telecommande.gif" width="248" height="210" /></td>
    </tr>
  </table>
</div> 
