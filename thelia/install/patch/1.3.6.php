<?php
	include_once(realpath(dirname(__FILE__)) . "/classes_1.3.6/Variable_136.class.php");
	
	$cnx = new Cnx();
	$query_cnx = "ALTER TABLE `message` ADD `cache` SMALLINT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
	$var = new Variable_136();
	
	$query_var = "update variable set protege='0'";
	$resul_var = mysql_query($query_var, $var->link);

	$query_var = "update message set protege='0'";
	$resul_var = mysql_query($query_var, $var->link);	
	
	$query_var = "ALTER TABLE `variable` ADD `cache` SMALLINT NOT NULL AFTER `protege`";
	$resul_var = mysql_query($query_var, $var->link);
	
	$var->nom = "version";
	$var->valeur = "136";
	$var->protege = "1";
	$var->cache = "1";
	$var->add();
	
	$query_var = "CREATE TABLE `ventedeclidisp` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`venteprod` INT NOT NULL ,
	`declidisp` INT NOT NULL
	) ;";
	$resul_var = mysql_query($query_var, $var->link);	

?>