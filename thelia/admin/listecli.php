<?php
include_once("pre.php");
include_once("auth.php");
?>
<?php if(! est_autorise("acces_clients")) exit; ?>
<?php
include_once("../classes/Client.class.php");

$client = new Client();
$querystring = $_GET['queryString'];
$query = "select * from $client->table where nom like '$querystring%' or prenom like '$querystring%' or entreprise like '$querystring%'";
$resul = mysql_query($query,$client->link);

while($row = mysql_fetch_object($resul)){
	?>
	<li onclick="fill('<?php echo $row->ref."|".$row->nom." ".$row->prenom; ?>')" style="left:200px;"><?php echo $row->nom." ".$row->prenom; ?></li>
	<?php
}
?>

