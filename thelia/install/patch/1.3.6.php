<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Variable.class.php");
	
	$cnx = new Cnx();
	$query_cnx = "ALTER TABLE `message` ADD `cache` SMALLINT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
	$var = new Variable();
	
	$query_var = "update variable set protege='0'";
	$resul_var = mysql_query($query_var, $var->link);

	$query_var = "update message set protege='0'";
	$resul_var = mysql_query($query_var, $var->link);	
	
	$query_var = "ALTER TABLE `variable` ADD `cache` SMALLINT NOT NULL AFTER `protege`";
	$resul_var = mysql_query($query_var, $var->link);
	
	$query_var = "INSERT INTO variable(nom,valeur,protege,cache) values('version', '136', '1', '1')";
	$resul_var = mysql_query($query_var, $var->link);
	
	$query_var = "CREATE TABLE `ventedeclidisp` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`venteprod` INT NOT NULL ,
	`declidisp` INT NOT NULL
	) ;";
	$resul_var = mysql_query($query_var, $var->link);	

?>