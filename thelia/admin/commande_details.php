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
<?php if(! est_autorise("acces_commandes")) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>
<?php
	include_once("../classes/Commande.class.php");
	include_once("../classes/Devise.class.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Paysdesc.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Venteadr.class.php");
	include_once("../classes/Statut.class.php");
	include_once("../classes/Modules.class.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../fonctions/divers.php");
	
	if(!isset($action)) $action="";
	if(!isset($statutch)) $statutch="";

?>

<?php
	$commande = new Commande();
	$commande->charger_ref($ref);
	$modules = new Modules();
	$modules->charger_id($commande->paiement);

	$devise = new Devise();
	$devise->charger($commande->devise);

?>

<?php
		
        if($statutch){
                $commande->statut = $statutch;


                if($statutch == 2 && $commande->facture == 0) 
                	$commande->genfact();

				else if($statutch == 4 && $commande->datelivraison == "0000-00-00")
					$commande->datelivraison = date("Y-m-d");

                $commande->maj();

				modules_fonction("statut", $commande);

        }

    if(isset($colis) && $colis != ""){
		$commande->colis = $colis;
		$commande->maj();
		modules_fonction("statut", $commande);
		
	}
		
?>

</head>

<body>

<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="commande";
	include_once("entete.php");
?> 

<div id="contenu_int"> 
    <p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des commandes</a></p> 
  
<!-- DŽbut de la colonne de gauche -->  
<div id="bloc_description">
<div class="entete_liste_client">
	<div class="titre">INFORMATIONS SUR LA COMMANDE </div>
</div>
<ul class="Nav_bloc_description">
		<li style="width:400px;">D&eacute;signation</li>
		<li style="width:80px; border-left:1px solid #96A8B5;">Prix unitaire</li>
		<li style="width:30px; border-left:1px solid #96A8B5;">Qt&eacute;</li>
		<li style="border-left:1px solid #96A8B5;">Total</li>
</ul>

  <?php
  	
	$venteprod = new Venteprod();

  	$query = "select * from $venteprod->table where commande='$commande->id'";
  	$resul = mysql_query($query, $venteprod->link);
  	
  	$i=0;
  	
  	while($row = mysql_fetch_object($resul)){
  	
  	$venteprod->charger($row->id);
  	
  	$produit = new Produit();
  	$produitdesc = new Produitdesc();
  	
  	$produit->charger($venteprod->ref);
  	$produitdesc->charger($produit->id);
  	
  	$rubrique = new Rubrique();
  	$rubrique->charger($produit->rubrique);
  	
  	$rubriquedesc = new Rubriquedesc();
  	$rubriquedesc->charger($rubrique->id);
  	
  	if($rubriquedesc->titre !="") $titrerub = $rubriquedesc->titre;
  	else $titrerub = "//";

  		if(!($i%2)) $fond="ligne_fonce_BlocDescription";
  		else $fond="ligne_claire_BlocDescription";
  		$i++;
  		  	
  ?>     
    <ul class="<?php echo($fond); ?>">
		<li style="width:392px;"><?php echo $venteprod->ref . " - " . $titrerub; ?> - <?php echo(str_replace("\n", "<br />", $venteprod->titre)); ?></li>
		<li style="width:73px;"><?php echo(round($venteprod->prixu, 2)); ?></li>
		<li style="width:23px;"><?php echo($venteprod->quantite); ?></li>
		<li style="width:20px;"><?php echo(round($venteprod->quantite*$venteprod->prixu, 2)); ?></li> 
    </ul>  
  
 <?php } ?> 

 <?php
   	
  		$client = new Client();
  		$client->charger_id($commande->client);
 
 		$venteprod = new Venteprod();
 
   		$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$commande->id'"; 
  		$resul2 = mysql_query($query2, $venteprod->link);
  		$total = mysql_result($resul2, 0, "total");

  		$totalremise = $total - $commande->remise;
		
  		$port = $commande->port;
  		if($port<0) $port=0;
  		
  		$statutdesc = new Statutdesc();
  		$statutdesc->charger($commande->statut);
  		
  		$dateaff = substr($commande->date, 8, 2) . "/" . substr($commande->date, 5, 2) . "/" . substr($commande->date, 2, 2);
  		$heureaff =  substr($commande->date, 11); 
 
 ?>
 <ul class="ligne_total_BlocDescription">
 	<li style="width:392px;">Total</li>
 	<li><?php echo(round($total, 2)); ?> <?php echo $devise->symbole; ?></li>
 </ul>
<div class="bordure_bottom" style="margin:0 0 10px 0;">
<div class="entete_liste_client">
	<div class="titre">INFORMATIONS SUR LA FACTURE</div>
</div>
<ul class="Nav_bloc_description">
		<li style="width:60px;">N&deg; Fact.</li>
		<li style="width:240px; border-left:1px solid #96A8B5;">Soci&eacute;t&eacute;</li>
		<li style="width:150px; border-left:1px solid #96A8B5;">Nom &amp; Pr&eacute;nom</li>
		<li style="border-left:1px solid #96A8B5;">Date et heure</li>
</ul>
<ul class="ligne_claire_BlocDescription">
		<li style="width:59px;"><?php echo($commande->facture); ?></li>
		<li style="width:240px;"><?php echo($client->entreprise); ?></li>
		<li style="width:150px;"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>"><?php echo($client->prenom); ?> <?php echo($client->nom); ?></a></li> 
		<li><?php echo($dateaff . " " . $heureaff); ?></li> 
</ul> 
</div>

<div class="bordure_bottom" style="margin:0 0 10px 0;">
<div class="entete_liste_client">
	<div class="titre">INFORMATIONS SUR LE R&Egrave;GLEMENT</div>
</div>
	<ul class="ligne_claire_BlocDescription" style="background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">
		<li class="designation" style="width:290px; background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">Type de r&egrave;glement</li>
		<li>
		<?php 
	            $nom = $modules->nom; 
	            $nom[0] = strtoupper($nom[0]);                                
				include_once("../client/plugins/" . $modules->nom . "/$nom.class.php");
	           	$tmpobj = new $nom();
                echo $tmpobj->getTitre();                
	     ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">R&eacute;f&eacute;rence de la transaction</li>
		<li><?php echo($commande->transaction); ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Total de la commande avant remise</li>
		<li><?php echo(round($total, 2)); ?> <?php echo $devise->symbole; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Remise</li>
		<li><?php echo(round($commande->remise, 2)); ?> <?php echo $devise->symbole; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Total avec remise</li>
		<li><?php echo(round($totalremise, 2)); ?> <?php echo $devise->symbole; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Frais de transport</li>
		<li><?php echo(round($port, 2)); ?> <?php echo $devise->symbole; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Total</li>
		<li><?php echo(round($totalremise + $port, 2)); ?> <?php echo $devise->symbole; ?></li>
	</ul>
</div>
 


<?php
	$adr = new Venteadr();
	$adr->charger($commande->adrfact);
	
	$nompays = new Paysdesc();
	$nompays->charger($adr->pays);
?>
<div class="bordure_bottom" style="margin:0 0 10px 0;">
<div class="entete_liste_client">
	<div class="titre">ADRESSE DE FACTURATION</div>
</div>
	<ul class="ligne_claire_BlocDescription" style="background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">
		<li class="designation" style="width:290px; background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">Pr&eacute;nom</li>
		<li><?php echo $adr->prenom; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Nom</li>
		<li><?php echo $adr->nom; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Adresse</li>
		<li><?php echo $adr->adresse1;?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Adresse suite</li>
		<li><?php echo $adr->adresse2; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Compl&eacute;ment d'adresse </li>
		<li><?php echo $adr->adresse3; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Code postal</li>
		<li><?php echo $adr->cpostal; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Ville</li>
		<li><?php echo $adr->ville; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Pays</li>
		<li><?php echo $nompays->titre; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">T&eacute;l&eacute;phone</li>
		<li><?php echo $adr->tel; ?></li>
	</ul>	
</div>

<?php

	$adr = new Venteadr();
	$adr->charger($commande->adrlivr);

	$nompays = new Paysdesc();
	$nompays->charger($adr->pays);
?>
<div class="bordure_bottom" style="margin:0 0 10px 0;">
<div class="entete_liste_client">
	<div class="titre">ADRESSE DE LIVRAISON</div>
</div>
	<ul class="ligne_claire_BlocDescription" style="background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">
		<li class="designation" style="width:290px; background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">Pr&eacute;nom</li>
		<li><?php echo $adr->prenom; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Nom</li>
		<li><?php echo $adr->nom; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Adresse</li>
		<li><?php echo $adr->adresse1;?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Adresse suite</li>
		<li><?php echo $adr->adresse2; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Compl&eacute;ment d'adresse </li>
		<li><?php echo $adr->adresse3; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Code postal</li>
		<li><?php echo $adr->cpostal; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">Ville</li>
		<li><?php echo $adr->ville; ?></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:290px;">Pays</li>
		<li><?php echo $nompays->titre; ?></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:290px;">T&eacute;l&eacute;phone</li>
		<li><?php echo $adr->tel; ?></li>
	</ul>	
</div>

<?php
	admin_inclure("commandedetails");		
?>     

</div>
<!-- fin du bloc description -->
<!-- bloc colonne de droite -->   
<div id="bloc_colonne_droite">
	<div class="entete_client">
		<div class="titre">STATUT DU R&Egrave;GLEMENT</div>
		<div class="statut">
      		<form action="<?php echo($_SERVER['PHP_SELF']); ?>" name="formchange" method="post">
                  <input type="hidden" name="ref" value="<?php echo($ref); ?>">
                  <select name="statutch" onChange="formchange.submit()" class="form">
                    <?php
                	$statut = new Statut();
                	$query = "select * from $statut->table where id<>5";
                	$resul = mysql_query($query);
                	while($row = mysql_fetch_object($resul)){
                		$statutcurdes = new Statutdesc();
                		$statutcurdes->charger($row->id);
                		if($row->id == $statutdesc->statut) $selected="selected"; else $selected="";
                ?>
                    <option value="<?php echo($row->id); ?>" <?php echo($selected); ?>>
                    <?php echo($statutcurdes->titre); ?>
                    </option>
                    <?php
                	
                	}
                	
                ?>
                  </select>
           </form>
     	</div>
	</div>
	<!-- fin du bloc statuts -->
	<div class="entete_client" style="margin:10px 0 0 0;">
		<div class="titre">SUIVI COLIS</div>
	</div>
	<ul class="claire">
		<li class="designation">N&deg; de colis</li>
		<li><form action="<?php echo($_SERVER['PHP_SELF']); ?>" name="formcolis" method="post">
	               <input type="hidden" name="ref" value="<?php echo($ref); ?>">
					<input type="text" name="colis" value="<?php echo $commande->colis ?>" /> <input type="submit" value="Valider" />

	             </form>
	    </li>
	</ul>
	<div class="entete_client" style="margin:10px 0 0 0;">
		<div class="titre">LES DOCUMENTS PDF</div>
	</div>
	<ul class="claire">
		<li class="designation">Facture</li>
		<li><a href="../client/pdf/facture.php?ref=<?php echo($commande->ref); ?>">Visualiser au format PDF</a></li>
	</ul>
	<ul class="fonce">
		<li class="designation">Bon de livraison</li>
		<li><a href="livraison.php?ref=<?php echo($commande->ref); ?>">Visualiser au format PDF</a></li>
	</ul>
	<!-- fin du bloc pdfs -->
</div>
<!-- fin du bloc colonne de droite -->

   </div>
   <?php include_once("pied.php");?>
</div>
</div>  

</body>
</html>
