<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Variable.class.php");

	$variable = new Variable();
	$variable->charger("rssadmin");
	$variable->protege = 1;
	$variable->cache = 1;
	$variable->valeur = "http://blog.thelia.fr/rss.php";
	$variable->maj();
	
	$var = "<Files *>
 			  <limit GET POST>
 			  order deny,allow
 			  deny from all
 			  </Limit>
			</Files>";
			
	file_put_contents("../client/plugins/atos/conf/.htaccess", $var);
			
	$version = new Variable();
	$version->charger("version");
	$version->valeur = "139";
	$version->maj();
	
?>