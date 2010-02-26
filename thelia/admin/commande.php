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
<?php
	include_once("title.php");
?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<script src="../lib/jquery/jeditable.js" type="text/javascript"></script>
<script src="../lib/jquery/menu.js" type="text/javascript"></script>
<script type="text/javascript">
function tri(order,critere){
	$.ajax({
		type:"GET",
		url:"ajax/tricommande.php",
		data : 'order='+order+'&critere='+critere,
		success : function(html){
			$("#resul").html(html);  
		}
	})
}
</script>
</head>
<?php

	include_once("../classes/Commande.class.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Ventedeclidisp.class.php");
	include_once("../classes/Stock.class.php");
	include_once("../classes/Statut.class.php");
	include_once("../classes/Statutdesc.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Devise.class.php");
	include_once("../classes/Modules.class.php");

	if(!isset($action)) $action="";
	if(!isset($client)) $client="";
	if(!isset($page)) $page=0;
	if(!isset($classement)) $classement="";

?>

<?php
	if($action == "supprimer"){
	
		$tempcmd = new Commande();
		$tempcmd->charger($id);
		
		$modules = new Modules();
		$modules->charger_id($tempcmd->paiement);

		$nomclass=$modules->nom;
		$nomclass[0] = strtoupper($nomclass[0]);

		include_once("../client/plugins/" . $modules->nom . "/" . $nomclass . ".class.php");
		$modpaiement = new $nomclass();

		// On remet le stock si il a été défalqué

        if($modpaiement->defalqcmd || (! $modpaiement->defalqcmd && $tempcmd->statut != "1")){
   			$venteprod = new Venteprod();
   			$query = "select * from $venteprod->table where commande='" . $id . "'";
   			$resul = mysql_query($query, $venteprod->link);

			while($row = mysql_fetch_object($resul)){
				// incrémentation du stock général
    			$produit = new Produit();   
				$produit->charger($row->ref);
				$produit->stock = $produit->stock + $row->quantite;
    			$produit->maj();

				$vdec = new Ventedeclidisp();
			
				$query2 = "select * from $vdec->table where venteprod='" . $row->id . "'";
				$resul2 = mysql_query($query2, $vdec->link);
			
			
				while($row2 = mysql_fetch_object($resul2)){
					$stock = new Stock();
					if($stock->charger($row2->declidisp, $produit->id)){
						$stock->valeur = $stock->valeur + $row->quantite;
						$stock->maj();					
					}
				
				
				}
			}
		}

		$tempcmd->statut = "5";
		$tempcmd->maj();
		
		modules_fonction("statut", $tempcmd);
		
	}
	
?>

<?php
	if(isset($_GET['statut']) && $_GET['statut'] != "")
  		$search="and statut=" . $_GET['statut'];		
	else
		 $search="and statut not in (5,4)";
			
  	if($client != "") $search .= " and client=\"$client\"";
  	$commande = new Commande();
  	if($page=="") $page=1;
  		 
   	$query = "select * from $commande->table where 1 $search";
  	$resul = mysql_query($query, $commande->link);
  	$num = mysql_num_rows($resul);

  	$nbpage = 20;
  	$totnbpage = ceil($num/30);
  	
  	$debut = ($page-1) * 30;
  	
  	if($page>1) $pageprec=$page-1;
  	else $pageprec=$page;

  	if($page<$nbpage) $pagesuiv=$page+1;
  	else $pagesuiv=$page;
  	 
  	if($classement == "client") $ordclassement = "order by client";  	
  	else if($classement == "statut") $ordclassement = "order by statut";
  	else $ordclassement = "order by date desc";

?>

<script type="text/JavaScript">

function supprimer(id){
	if(confirm("Voulez-vous vraiment annuler cette commande ?")) location="commande.php?action=supprimer&id=" + id;

}

</script>

<body>

<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="commande";
	include_once("entete.php");
?>


<div id="contenu_int"> 
    <p align="left"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des commandes</a>              
    </p>
<div class="entete_liste_client">
	<div class="titre">LISTE DES COMMANDES</div><div class="fonction_ajout"><a href="commande_creer.php">CREER UNE COMMANDE</a> </div>
</div>
<ul id="Nav">
		<li style="height:25px; width:149px; border-left:1px solid #96A8B5;">N&deg; de commande</li>
		<li style="height:25px; width:111px; border-left:1px solid #96A8B5;">Date &amp; Heure</li>
		<li style="height:25px; width:207px; border-left:1px solid #96A8B5;">Soci&eacute;t&eacute;</li>
		<li style="height:25px; width:207px; border-left:1px solid #96A8B5;">Nom &amp; Pr&eacute;nom</li>	
		<li style="height:25px; width:66px; border-left:1px solid #96A8B5;">Montant</li>
		<li style="height:25px; width:77px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;">Statut
			<ul class="Menu">
			 <?php
       	 		$statut = new Statut();
       	 		$query_stat = "select * from $statut->table";
       	 		$resul_stat = mysql_query($query_stat, $statut->link);
       	 		while($row_stat = mysql_fetch_object($resul_stat)){
       	 			$statutdesc = new Statutdesc();
       	 			$statutdesc->charger($row_stat->id);
       	 	?>
				<li style="width:84px;"><a href="commande.php?statut=<?php echo $row_stat->id; ?>" name="<?php echo $row_stat->id; ?>"  <?php if(isset($_GET['statut']) && $_GET['statut'] == $row_stat->id) { ?>selected="selected" <?php } ?>><?php echo $statutdesc->titre; ?></a></li>
			<?php
       	 	}
       	 	?>	
			</ul>
		</li>
		<li style="height:25px; width:47px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:42px; border-left:1px solid #96A8B5;">Suppr.</li>	
</ul>
  <?php
  	$i=0;
  	
    	$query = "select * from $commande->table where 1 $search $ordclassement limit $debut,30";
  	$resul = mysql_query($query, $commande->link);

  	$venteprod = new Venteprod();
  	
  	while($row = mysql_fetch_object($resul)){
  	
  		$client = new Client();
  		$client->charger_id($row->client);
  		
  		$statutdesc = new Statutdesc();
  		$statutdesc->charger($row->statut);
	
		$devise = new Devise();
		$devise->charger($row->devise);

  		$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$row->id'"; 
  		$resul2 = mysql_query($query2, $venteprod->link);
  		$total = round(mysql_result($resul2, 0, "total"), 2);

		$port = $row->port;
		$total -= $row->remise;
		$total += $port;
		
  		$jour = substr($row->date, 8, 2);
  		$mois = substr($row->date, 5, 2);
  		$annee = substr($row->date, 2, 2);
  		
  		$heure = substr($row->date, 11, 2);
  		$minute = substr($row->date, 14, 2);
  		$seconde = substr($row->date, 17, 2);
  		
  		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;
  ?>
<span id="resul">
<ul class="<?php echo($fond); ?>">
	<li style="width:142px;"><?php echo($row->ref); ?></li>
	<li style="width:104px;"><?php echo($jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute . ":" . $seconde); ?></li>
	<li style="width:200px;"><?php echo($client->entreprise); ?></li>
	<li style="width:200px;"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>"><?php echo($client->nom . " " . $client->prenom); ?></a></li>
	<li style="width:59px;"><?php echo(round($total, 2)); ?> <?php echo $devise->symbole; ?></li>
	<li style="width:70px;"><?php echo($statutdesc->titre); ?></li>
	<li style="width:40px;"><a href="commande_details.php?ref=<?php echo($row->ref); ?>">éditer</a></li>
	<li style="width:35px; text-align:center;"><a href="#" onclick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
</span>
<?php
	}
?>
<p id="pages">
<?php if($page > 1){ ?>
   <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pageprec); ?>&statut=<?php echo $_GET['statut']; ?>" >Page pr&eacute;c&eacute;dente</a> |
	<?php } ?>
	<?php if($totnbpage > $nbpage){?>
		<?php if($page>1) {?><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=1&statut=<?php echo $_GET['statut']; ?>">...</a> | <?php } ?>
		<?php if($page+$nbpage-1 > $totnbpage){ $max = $totnbpage; $min = $totnbpage-$nbpage;} else{$min = $page-1; $max=$page+$nbpage-1; }?>
     <?php for($i=$min; $i<$max; $i++){ ?>
    	 <?php if($page != $i+1){ ?>
  	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>&classement=<?php echo($classement); ?>&statut=<?php echo $_GET['statut']; ?>" ><?php echo($i+1); ?></a> |
    	 <?php } else {?>
    		 <span class="selected"><?php echo($i+1); ?></span>
    		 |
   		  <?php } ?>
     <?php } ?>
		<?php if($page < $totnbpage){?><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo $totnbpage; ?>&statut=<?php echo $_GET['statut']; ?>">...</a> | <?php } ?>
	<?php } 
	else{
		for($i=0; $i<$totnbpage; $i++){ ?>
	    	 <?php if($page != $i+1){ ?>
	  	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>&statut=<?php echo $_GET['statut']; ?><?php echo $lien_voir; ?>"><?php echo($i+1); ?></a> |
	    	 <?php } else {?>
	    		 <span class="selected"><?php echo($i+1); ?></span>
	    		|
	   		  <?php } ?>
	     <?php } ?>
	<?php } ?>
     <?php if($page < $totnbpage){ ?>
<a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pagesuiv); ?>&statut=<?php echo $_GET['statut']; ?>">Page suivante</a></p>
	<?php } ?>
</div> 
<?php
	include_once("pied.php");
?>
</div>
</div>
</body>
</html>
