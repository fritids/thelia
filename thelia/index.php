<?php


	include_once("classes/Config.class.php");

    $page = $_GET['page'] ? $_GET['page'] : "index";
    $config = new Config();
    $squelettes = $config->get('squelettes');
    
    
    //Rechercher d'un fichier php gerant la page demandée
    if (file_exists($squelettes."/".$page.".php")) {
        include_once($squelettes."/".$page.".php");
        exit(0);
    }
    
    //Rercherhce le fond html à defaut
    if (file_exists($squelettes."/".$page.".html")) {
        $fond = $page.".html";
    } else {
        $fond = "404.html";
    }
    
	$pageret=1;
	include("fonctions/moteur.php");
?>
