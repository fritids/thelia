<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_clients")) exit; ?>
<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Client.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Commande.class.php");



$order = $_GET["order"];
$critere = $_GET["critere"];
$debut = $_GET["debut"];


	$i=0;
  	
  	$client = new Client();
  	
 	$query = "select * from $client->table order by $critere $order limit $debut,20";
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
	<li style="width:40px;"><a href="client_visualiser.php?ref=<?php echo($row->ref); ?>" class="txt_vert_11">&eacute;diter</a></li>
	<li style="width:35px; text-align:center;"><a href="#" onclick="confirmSupp('<?php echo($row->ref); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
<?php }
?>