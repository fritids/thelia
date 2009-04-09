<?php
include_once("pre.php");
include_once("auth.php");
include_once("../classes/Client.class.php");

$client = new Client();

$query = "select * from $client->table";
$resul = mysql_query($query,$client->link);

while($row = mysql_fetch_object($resul)){
	echo $row->ref.".".$row->nom.".".$row->prenom."."."\n";
}
?>