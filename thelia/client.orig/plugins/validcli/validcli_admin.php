<?php

	include_once(realpath(dirname(__FILE__)) . "/Validcli.class.php");
	include_once(realpath(dirname(__FILE__)) . "/config.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Message.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Messagedesc.class.php");


	
	if(isset($action) && $action == "modifier"){

		$client = new Client();
		$client->charger_id($id);
	
		if($active == 1){ 
			$client->email = str_replace($valid_chainesecu, "", $client->email);
			$message = new Message();
			$message->charger("mailactive");
			
			$messagedesc = new Messagedesc();
			$messagedesc->charger($message->id);	
		
			mail($client->email, $messagedesc->titre, $messagedesc->description);
			
		}	
		
		else if($active == 0)
			$client->email = $valid_chainesecu . $client->email;

		$client->maj();
	}

?>

	<div id="contenu_int"> 
	   <p class="titre_rubrique">Gestion des activations client</p>
	     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des activations</a>              
	    </p>
	     <table width="710" border="0" cellpadding="5" cellspacing="0">
	     <tr>
	       <td width="600" height="30" class="titre_cellule_tres_sombre">Gestion des activations clients</td>
	   </tr>
		</table>	
		
		 <table width="710" border="0" cellpadding="5" cellspacing="0">
	    
	<?php
		$client = new Client();

		$query_clients = "select * from $client->table order by ref desc";
		$resul_clients = mysql_query($query_clients, $client->link);
		$i = 0;

		while($row = mysql_fetch_object($resul_clients)){
			if($i%2) $fond="sombre";
			else $fond="claire";

		$client = new Client();
		$client->charger_id($row->client);	
	?>

	  <tr class="cellule_<?php echo $fond; ?>">
	    <td width="20%" height="30"><a href="client_visualiser.php?ref=<?php echo $row->ref; ?>" class="lien04"><?php echo $row->ref; ?></a></td>
	 	<td width="20%" height="30"><a href="client_visualiser.php?ref=<?php echo $row->ref; ?>" class="lien04"><?php echo $row->nom . " " . $row->prenom; ?></a></td>
	 	<td width="20%" height="30">
			<?php
				if(strstr($row->email, $valid_chainesecu)){
			?>
			
				<a href="module.php?nom=validcli&amp;id=<?php echo $row->id; ?>&amp;action=modifier&amp;active=1" class="lien04">Activer</a>
			<?php 
				} else {
			?>
					<a href="module.php?nom=validcli&amp;id=<?php echo $row->id; ?>&amp;action=modifier&amp;active=0" class="lien04">Désactiver</a>
				
					
			<?php
				}
			?>
		</td>
	  </tr>


	<?php 

			$i++;
		}

	?>
	
	   </table>


</div>
</body>
</html>
