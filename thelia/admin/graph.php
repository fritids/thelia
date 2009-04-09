<?php

include_once("../classes/Commande.class.php");
include_once("../classes/Venteprod.class.php");

function ca_mois($mois, $annee, $jour, $pourc=100, $port=1){

      if($jour != "" && $jour != "%" && strlen($jour)<2)
              $jour = "0$jour";
      
      if($mois != "" && strlen($mois)<2){
              $mois = "0$mois";
              $date = "$annee-$mois-$jour %:%:%";
      }

      else if($mois != ""){
              $date = "$annee-$mois-$jour %:%:%";
      } 	 	

      else 
              $date ="%-%-% %:%:%";

      $commande = new Commande();

      $query = "select * from $commande->table where statut>=2 and statut<>5 and date like '$date'";
      $resul = mysql_query($query);

      $list="";
      while($row = mysql_fetch_object($resul)){

              $list .= "'" . $row->id . "'" . ",";
      }

      $list = substr($list, 0, strlen($list)-1);
      $list == "";

      if($list == "") $list="''";

      $query = "SELECT sum(venteprod.quantite*venteprod.prixu) as ca FROM venteprod where commande in ($list)";
      $resul = mysql_query($query);
      $ca = round(mysql_result($resul, 0, "ca"), 2);

      $query = "SELECT sum(port)as ca FROM commande where id in ($list)";
      $resul = mysql_query($query);

      $ca += mysql_result($resul, 0, "ca");

      $query = "SELECT sum(remise)as ca FROM commande where id in ($list)";
      $resul = mysql_query($query);

      $ca -= mysql_result($resul, 0, "ca");


      if(!$port){
              $query = "SELECT sum(port)as port FROM commande where id in ($list)";
              $resul = mysql_query($query);

              $ca -= mysql_result($resul, 0, "port");
	     }

	        return round($ca*$pourc/100, 2);

	 }
	
	$mois = date("m");
	$annee = date("Y");
	$values = array();
	$days = array();
	for($i=1;$i<32;$i++){
		$values[] = ca_mois($mois, $annee, "$i", 100, 1);
		$days[] = $i;
	}
	
	function getmonth($mois){
		switch($mois){
			case "1" : return "janvier "; break;
			case "2" : return "février "; break;
			case "3" : return "mars "; break;
			case "4" : return "avril "; break;
			case "5" : return "mai "; break;
			case "6" : return "juin"; break;
			case "7" : return "juillet "; break;
			case "8" : return "août "; break;
			case "9" : return "septembre "; break;
			case "10" : return "octobre "; break;
			case "11" : return "novembre "; break;
			case "12" : return "décembre "; break;
		}
	}
	
include_once("../lib/artichow/LinePlot.class.php");	
   	$graph = new archiGraph(968, 200);
	$graph->border->hide();
	$graph->title = new archiLabel("Progression du chiffre d'Affaires journalier. Total de ".getmonth($mois)." : ".ca_mois($mois, $annee, "%", 100, 1)." €",new archiFileFont(ARTICHOW_FONT.'/Arial', 10));

	$plot = new archiLinePlot($values);
//	$plot->setBackgroundColor(new archiColor(240, 240, 240));
	$plot->hideLine(TRUE);
	$plot->setFillColor(new archiColor(180, 180, 180, 75));
	//$plot->grid->setBackgroundColor(new archiColor(235, 235, 180, 60));
	$plot->grid->hideVertical();
	
	

 /*  $plot->setBackgroundGradient(
      new archiLinearGradient(
         new archiColor(210, 210, 210),
         new archiColor(250, 250, 250),
         0
      )
   );*/
   	$plot->yAxis->setLabelPrecision(2);
	$plot->xAxis->setLabelText($days);

	$plot->mark->setType(archiMark::IMAGE);
	$plot->mark->setImage(new archiFileImage("gfx/point_graph.png"));
	
	
	$plot->label->set($values);
	$plot->label->setColor(new archiColor(236, 128, 0));
	$plot->label->move(0, -12);
	$plot->label->setFont(new archiFileFont(ARTICHOW_FONT.'/Arial', 8));
	
	
	$plot->label->setPadding(3, 1, 1, 0);

   	$plot->setSpace(2, 2, NULL, NULL);

   	$graph->add($plot);
   	$graph->draw();
?>