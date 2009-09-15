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
<?php if(! est_autorise("acces_clients")) exit; ?>
<?php
	if(!isset($action)) $action="";
	if(!isset($page)) $page=0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<script src="../lib/jquery/jeditable.js" type="text/javascript"></script>
<script src="../lib/jquery/menu.js" type="text/javascript"></script>

<script type="text/javascript">
	function confirmSupp(ref){
		if(confirm("Voulez-vous vraiment supprimer ce client ?")) location="<?php echo($_SERVER['PHP_SELF']); ?>?action=supprimer&ref=" + ref;

	}
	
	function tri(order,critere,debut){
		$.ajax({
			type:"GET",
			url:"ajax/triclient.php",
			data : "order="+order+'&critere='+critere+"&debut="+debut,
			success : function(html){
				$("#resul").html(html);
			}
		})
	}
</script>

</head>

<?php
	include_once("../classes/Client.class.php");
	include_once("../classes/Commande.class.php");
	
?>

<?php
	if($action == "supprimer"){
	
		$tempcli = new Client();
		$tempcli->charger_ref($ref);
		
		$tempcli->delete();
	}
	
?>

<?php
	$client = new Client();
  	
  	
	if($page=="") $page=1;
  		 
	$query = "select * from $client->table";
  	$resul = mysql_query($query, $client->link);
  	$num = mysql_num_rows($resul);
  	
  	$nbpage = 20;
  	$totnbpage = ceil($num/20);
  	
  	$debut = ($page-1) * 20;
  	
  	if($page>1) $pageprec=$page-1;
  	else $pageprec=$page;

  	if($page<$nbpage) $pagesuiv=$page+1;
  	else $pagesuiv=$page;
  	if(isset($classement) && $classement != "") $ordclassement = "order by ".$classement;
  	else $ordclassement = "order by nom asc";

?>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="client";
	include_once("entete.php");
?>

<div id="contenu_int"> 
      <p align="left"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des clients</a>              
    </p>
<div class="entete_liste_client">
	<div class="titre">LISTE DES CLIENTS</div><div class="fonction_ajout"><a href="client_creer.php">CREER UN CLIENT</a> </div>
</div>
<ul id="Nav">
		<li style="height:25px; width:129px; border-left:1px solid #96A8B5;">N&deg; du client</li>
		<li style="height:25px; width:157px; border-left:1px solid #96A8B5;">Soci&eacute;t&eacute;</li>
		<li style="height:25px; width:257px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;">Nom &amp; Pr&eacute;nom 
			<ul class="Menu">
				<li style="width:267px;"><a href="javascript:tri('ASC','nom','<?php echo $debut; ?>')">Tri alphabétique</a></li>
				<li style="width:267px;"><a href="javascript:tri('DESC','nom','<?php echo $debut; ?>')">Tri alphabétique inverse</a></li>
				<li style="width:267px;"><a href="javascript:tri('ASC','nom','<?php echo $debut; ?>')">Tri par d&eacute;faut</a></li>
			</ul>
		</li>
		<li style="height:25px; width:130px; border-left:1px solid #96A8B5;">Derni&egrave;re commande</li>
		<li style="height:25px; width:155px; border-left:1px solid #96A8B5;">Montant de la commande</li>		
		<li style="height:25px; width:47px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:42px; border-left:1px solid #96A8B5;">Suppr.</li>	
</ul>
<span id="resul">
<?php
  	$i=0;
  	
  	$client = new Client();
  	
 	$query = "select * from $client->table $ordclassement limit $debut,20";
  	$resul = mysql_query($query, $client->link);
  	
  	while($row = mysql_fetch_object($resul)){
  		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;

		$commande = new Commande();
		
		$querycom = "select id from $commande->table where client=$row->id and statut not in(2,5) order by date DESC limit 0,1";
		$resulcom = mysql_query($querycom);
		$existe = 0;
		if(mysql_num_rows($resulcom)>0){
			$existe = 1;
			$idcom = mysql_result($resulcom,0,"id");
			$commande->charger($idcom);
		
			$jour = substr($commande->date, 8, 2);
  			$mois = substr($commande->date, 5, 2);
  			$annee = substr($commande->date, 2, 2);
		}

		
		
		

  ?>
<ul class="<?php echo($fond); ?>">
	<li style="width:122px;"><?php echo($row->ref); ?></li>
	<li style="width:150px;"><?php echo($row->entreprise); ?></li>
	<li style="width:250px;"><?php echo($row->nom); ?> <?php echo($row->prenom); ?></li>
	<li style="width:123px;"><?php if($existe) echo $jour."/".$mois."/".$annee; ?></li>
	<li style="width:148px;"><?php if($existe) echo $commande->total(); ?></li>
	<li style="width:40px;"><a href="client_visualiser.php?ref=<?php echo($row->ref); ?>">éditer</a></li>
	<li style="width:35px; text-align:center;"><a href="#" onclick="confirmSupp('<?php echo($row->ref); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
<?php } ?>  
</span>

<p id="pages">
	<?php if($page>1){ ?>
	<a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pageprec); ?>">Page pr&eacute;c&eacute;dente</a> |
	<?php } ?>
	<?php if($totnbpage > $nbpage){?>
		<?php if($page>1) {?><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=1">...</a> | <?php } ?>
		<?php if($page+$nbpage-1 > $totnbpage){ $max = $totnbpage; $min = $totnbpage-$nbpage;} else{$min = $page-1; $max=$page+$nbpage-1; }?>
    <?php for($i=$min; $i<$max; $i++){ ?>
   	 <?php if($page != $i+1){ ?>
 	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>&classement=<?php echo($classement); ?>"><?php echo($i+1); ?></a> |
   	 <?php } else {?>
   		  <span class="selected"><?php echo($i+1); ?></span>
   		|
  		  <?php } ?>
    <?php } ?>
		<?php if($page < $totnbpage){?><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo $totnbpage; ?>">...</a> | <?php } ?>
	<?php } 
	else{
		for($i=0; $i<$totnbpage; $i++){ ?>
	    	 <?php if($page != $i+1){ ?>
	  	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>&classement=<?php echo($classement); ?><?php echo $lien_voir; ?>"><?php echo($i+1); ?></a> |
	    	 <?php } else {?>
	    		 <span class="selected"><?php echo($i+1); ?></span>
	    		 |
	   		  <?php } ?>
	     <?php } ?>
	<?php } ?>
    

    <?php if($page < $totnbpage){ ?>
    <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pagesuiv); ?>">Page suivante</a></p>
	<?php } ?>
</div> 
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
