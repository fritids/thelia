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
	include("auth.php");
	include_once("pre.php");
?>
<?php
        include("../lib/Sajax.php");
        include("../fonctions/divers.php");
        include("../classes/Zone.class.php");
        include("../classes/Pays.class.php");
        include("../classes/Paysdesc.class.php");
        include("../classes/Transport.class.php");
      
	if(file_exists("../lib/JSON.php")) include_once("../lib/JSON.php");
	else include_once("lib/JSON.php");
?>
<?php


	
	function chargerz(){
	
                $i=0;
                $tab="";
                $zone = new Zone();
                $query = "select * from $zone->table";
                $resul = mysql_query($query, $zone->link);
                while($row = mysql_fetch_object($resul)){
                        $zone->charger($row->id);
                        $tab[$i] = new Zone();
                        $tab[$i++] = $zone;
                }
			
				if(count($tab)) return tabSerialise($tab);
                else return "";

	}

	function chargerp($zone, $type){
	
                $i=0;
              
                
                $pays = new Pays();
                $paysdesc = new paysdesc();
               if($type=="d") $query = "select * from $pays->table where zone='$zone'";
               else if($type=="h")  $query = "select * from $pays->table where zone='-1'";
               else  $query = "select * from $pays->table where zone='-1'";
                $resul = mysql_query($query, $pays->link);        
                while($row = mysql_fetch_object($resul)){
                		$paysdesc = new paysdesc();
                        $paysdesc->charger($row->id);
                        $paysdesc->titre = supprAccent($paysdesc->titre);
                        
                        $tab[$i] = new Paysdesc();
                        $tab[$i] = $paysdesc;
                
                        $i++;
                }


                if(!$tab || !isset($tab)) { return "";}
                return tabSerialise($tab);
                


	}	
	
	function validem($zcours, $strd, $strh){

				$pays = new Pays();
				$zone = new Zone();
					
	 			$json = new Services_JSON();

	 			$zcours = stripslashes($zcours);		
	 			$zcours = $json->decode($zcours);
				
				$zone->charger($zcours->id);
				
				$zone->id = $zcours->id;
				$zone->nom = $zcours->nom;
				$zone->unite = $zcours->unite;
				$zone->maj();

	 			$strd = stripslashes($strd);
	 			$resd = $json->decode($strd);
	 			
	 			$strh = stripslashes($strh);
	 			$resh = $json->decode($strh);
	
	 		
	 			$list = "";
	 			
	 			for($i=0; $i<count($resh); $i++)
	 				$list .= "'" . $resh[$i] . "'" . ",";
	 			
	 			$list = substr($list, 0, strlen($list)-1);	
	 			$query = "update pays set zone='-1' where id in ($list) and zone='" . $zcours->id . "'";	
				if($list) $resul = mysql_query($query, $pays->link);
	 			$list = "";
	 			for($i=0; $i<count($resd); $i++)
	 				$list .= "'" . $resd[$i] . "'" . ",";
	 			
	 			$list = substr($list, 0, strlen($list)-1);	
	 			$query = "update pays set zone='" . $zcours->id . "' where id in ($list)";
				if($list) $resul = mysql_query($query, $pays->link);

	}


	function validea($zcours, $strd, $strh){

				$pays = new Pays();
				$zone = new Zone();
				$transport = new Transport();
	 			$json = new Services_JSON();

	 			$zcours = stripslashes($zcours);
	 			$zcours = $json->decode($zcours);

				$zone->id = "";
				$zone->nom = $zcours->nom;
				$zone->unite = $zcours->unite;
				
				$zone->moddoc = 1;
				$zone->tva = 1;				
				
				
				$lastid = $zone->add();

				$zone->charger($lastid);
				
	 			$strd = stripslashes($strd);
	 			$resd = $json->decode($strd);
	 			
	 			$strh = stripslashes($strh);
	 			$resh = $json->decode($strh);
	
	 		
	 			$list = "";
	 			
	 			for($i=0; $i<count($resh); $i++)
	 				$list .= "'" . $resh[$i] . "'" . ",";
	 			
	 			$list = substr($list, 0, strlen($list)-1);	
	 			$query = "update pays set zone='' where id in ($list) and zone='" . $zone->id . "'";	
				if($list) $resul = mysql_query($query, $pays->link);
	 			$list = "";
	 			for($i=0; $i<count($resd); $i++)
	 				$list .= "'" . $resd[$i] . "'" . ",";
	 			
	 			$list = substr($list, 0, strlen($list)-1);	
	 			$query = "update pays set zone='" . $zone->id . "' where id in ($list)";
				if($list) $resul = mysql_query($query, $pays->link);
					

	}

	function supprz($zcours){

				$pays = new Pays();
				$zone = new Zone();

	 		
	 			$query = "update pays set zone='-1' where zone='$zcours'";	
				$resul = mysql_query($query, $pays->link);	
				
				$zone->charger($zcours);
				$zone->delete();

	}	
?>
<?php
		//$sajax_debug_mode = 1;
      	sajax_init();
        sajax_export("chargerz");
        sajax_export("chargerp");
        sajax_export("validem");
        sajax_export("validea");
        sajax_export("supprz");

        sajax_handle_client_request();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../fonctions/json.js"></script>

<script>
	var resz;
	var indexCours;
	
	function charger(){
		resz = eval(sx_chargerz());

		var contenu;
		
		document.getElementById("divz").innerHTML="";
		
		if(resz) for(i=0; i<resz.length; i++)  {
			contenu="";
			contenu=contenu + "<div style='border-bottom: solid #CDCDCD 1px;'>";
			
			contenu=contenu + "<span style=' position: absolute;  margin-left: 50px;'>";
			contenu=contenu + resz[i]['nom'];
			contenu=contenu + "</span>";

			contenu=contenu + "<span style=' width: 50px;margin-left: 400px;'>";
			contenu=contenu + "<a href='#' onClick=\"chargerz('" + i + "')\"><img src='gfx/b_edit.png' width='16' height='16' border='0'></a>";
			contenu=contenu + "</span>";

			contenu=contenu + "<span style='width: 50px;margin-left: 50px;'>";
			contenu=contenu + "<a href='#' onClick=\"supprz('" + i + "')\"><img src='gfx/b_drop.png' width='16' height='16' border='0'></a>";
			contenu=contenu + "</span>";

			contenu=contenu + "</div>";
			document.getElementById("divz").innerHTML=document.getElementById("divz").innerHTML + contenu;
			
 		
				
		}	

			var element = document.createElement("option");
			element.text = "liste des pays";
			element.value="0";
			
         	document.getElementById('selectspaysha').options.length = 0;
         	document.getElementById('selectspaysda').options.length = 0;
        // 	document.getElementById('selectspaysda').options[0] = element;
         	document.getElementById('nzonea').value='Nom de la zone'; 
         	document.getElementById('nunitea').value='Unite de transport'; 

  			var resp = eval(sx_chargerp('', ''));

    	  	for(i=0; i<resp.length; i++){

      		element = document.createElement("option");
      		 element.text=resp[i]['titre'];
      		 element.value=resp[i]['pays'];
      		
      		 document.getElementById('selectspaysha').options[document.getElementById('selectspaysha').length]=element;

      		}	                        
                    	     
	}
	

      function chargerz(index){

      	indexCours = index;

      	document.getElementById('nzonem').value=resz[index]['nom'];
      	document.getElementById('nunitem').value=resz[index]['unite'];
    
     	var respd = eval(sx_chargerp(resz[index]['id'], 'd'));
   		var resph = eval(sx_chargerp(resz[index]['id'], 'h'));

      	document.getElementById('selectspaysdm').options.length = 0;
       	document.getElementById('selectspayshm').options.length = 0;

      	if(respd)
      		for(i=0; i<respd.length; i++){

     	 	 var element = document.createElement("option");
      		 element.text=respd[i]['titre'];
      		 element.value=respd[i]['pays'];

      		document.getElementById('selectspaysdm').options[document.getElementById('selectspaysdm').length]=element;
      	}
      	
    	if(resph)

      	for(i=0; i<resph.length; i++){
      	 var element = document.createElement("option");
      	 element.text=resph[i]['titre'];
      	 element.value=resph[i]['pays'];
      	document.getElementById('selectspayshm').options[document.getElementById('selectspayshm').length]=element;
      	}

      }	  


	  function ajoutpays(type){
	  		
	      var index = document.getElementById('selectspaysh' + type).selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectspaysh' + type).options[index].text;
      	    element.value=document.getElementById('selectspaysh' + type).options[index].value;
      	    document.getElementById('selectspaysd' + type).options[document.getElementById('selectspaysd'+type).length]=element;
      	    
	 		document.getElementById('selectspaysh' + type).remove(index);      
	 		
	  }

	  function supprpays(type){
	  		
	      var index = document.getElementById('selectspaysd' + type).selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectspaysd' + type).options[index].text;
      	    element.value=document.getElementById('selectspaysd' + type).options[index].value;
      	    document.getElementById('selectspaysh' + type).options[document.getElementById('selectspaysh'+type).length]=element;
      	    
	 		document.getElementById('selectspaysd' + type).remove(index);      
	     
	  }	  
	  
	  
	  function validem(){

	  	var zObj = new Object();
	 	zObj.id =   resz[indexCours]['id'];
	 	zObj.nom = document.getElementById('nzonem').value;
	 	zObj.unite = document.getElementById('nunitem').value;

	  	var paysd = new Array();
	  	var paysh = new Array();
	  	
	  	for(i=0; i<document.getElementById('selectspaysdm').length; i++)
	  		paysd[i] = document.getElementById('selectspaysdm').options[i].value;
	
	 	for(i=0; i<document.getElementById('selectspayshm').length; i++)
	  		paysh[i] = document.getElementById('selectspayshm').options[i].value;

	     sx_validem(JSON.stringify(zObj), JSON.stringify(paysd), JSON.stringify(paysh)); 
	
	 	 charger();
	 	 
	 	 alert("Modification effectuee");
	 	 
}	
	  function validea(){

	  	var zObj = new Object();
	 	zObj.id =  '';	 		 

	 	zObj.nom = document.getElementById('nzonea').value;
	 	zObj.unite = document.getElementById('nunitea').value;
	 	
	  	var paysd = new Array();
	  	var paysh = new Array();
	  	
	  	for(i=0; i<document.getElementById('selectspaysda').length; i++)
	  		paysd[i] = document.getElementById('selectspaysda').options[i].value;
	
	 	for(i=0; i<document.getElementById('selectspaysha').length; i++)
	  		paysh[i] = document.getElementById('selectspaysha').options[i].value;

	  	 sx_validea(JSON.stringify(zObj), JSON.stringify(paysd), JSON.stringify(paysh)); 
	 	 charger();	  
	 	 alert("Modification effectuee");
	  
	  }
	  
	  
      function supprz(index){
      	sx_supprz(resz[index]['id']);
	 	 charger();	  
	 	 alert("Suppression effectuee");      	
      }	            
</script>

<script>
        <?php
	                sajax_show_javascript();
        ?>


</script>
</head>

<body onload="charger()">

<?php
	include("../classes/Boutique.class.php");
?>
<?php
	$menu="livraisons";
	include("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des zones </p>
    <p align="right" class="geneva11Reg_3B4B5B"><a href="index.php" class="lien04">Accueil<img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="gestlivraison.php" class="lien04"> Gestion des livraisons</a> </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des zones </a>    </p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES ZONES </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr class="cellule_sombre_vide">
    <td height="30" colspan="2" class="cellule_sombre_vide"> <div class="geneva11bol_3B4B5B" id="divz">
						Chargement ...
      </div>
    </td>
    </tr>

  </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">MODIFIER UNE ZONE </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr class="cellule_sombre_vide">
    <td width="34%" height="30" class="cellule_sombre_vide"><span class="arial11_bold_626262">
      <input name="text" type="text" class="form" id="nzonem" value="Nom de la zone" />
    </span></td>
    <td  height="30">&nbsp;</td>
    <td width="27%"></td>
  </tr>
 <tr class="cellule_claire_vide">
    <td width="34%" height="30" class="geneva11bol_3B4B5B">
      <div align="center">
        <select name="select" class="form" id="selectspayshm">
          <option selected="selected">listes des pays</option>
        </select>
      </div>
    </td>
    <td width="39%" height="30"> <div align="center">
                            <input name="Submit" type="button" onClick="ajoutpays('m')" class="geneva11bol_3B4B5B" value="Ajouter un pays &gt;&gt; ">
                            <br /><input name="Submit" type="button" onClick="supprpays('m')" class="geneva11bol_3B4B5B" value="&lt;&lt; Supprimer un pays">
    </div></td>
                          <td><div align="center">
                            <select size="5" multiple class="form" id="selectspaysdm">
                              <option selected>liste des pays</option>
                            </select>
    </div>
      </td>
    </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_sombre_vide"><span class="geneva11bol_3B4B5B">Definir l'unit&eacute; de transport: </span></td>
   <td height="30" class="cellule_sombre_vide">
     <input name="text2" type="text" class="form" id="nunitem" value="Unite de transport" />
   </td>
   <td class="cellule_sombre_vide"></td>
 </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_claire_vide">&nbsp;</td>
   <td height="30" class="cellule_claire_vide"></td>
   <td class="cellule_claire_vide"> 
     <input name="Submit" type="button" onClick="validem();" class="geneva11bol_3B4B5B" value="Valider">
   </td>
 </tr>
  </table>
  <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="5"></td>
    </tr>
    <tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre2">AJOUTER UNE ZONE</td>
    </tr>
  </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr class="cellule_sombre_vide">
    <td width="34%" height="30" class="cellule_sombre_vide"><span class="arial11_bold_626262">
       <input id="nzonea" type="text" class="form" onClick="this.value=''" value="Nom de la zone">
    </span></td>
    <td  height="30">    </td>
    <td width="27%"></td>
  </tr>
 <tr class="cellule_claire_vide">
    <td width="34%" height="30" class="geneva11bol_3B4B5B">
     <div align="center">
                          <select id="selectspaysha" class="form">
          </select>
        </div>
    </td>
    <td width="39%" height="30"> <div align="center">
                          <input name="Submit" type="button" onClick="ajoutpays('a');" class="geneva11bol_3B4B5B" value="Ajouter un pays &gt;&gt; ">
                          <br />
                          <input name="Submit" type="button" onClick="supprpays('a');" class="geneva11bol_3B4B5B" value="&lt;&lt; Supprimer un pays">
                      </div></td>
                          <td><div align="center">
                            <select name="select2" size="5" multiple="multiple" class="form" id="selectspaysda">
                            </select>
                          </div> 
      </td>
    </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_sombre_vide"><span class="geneva11bol_3B4B5B">Definir l'unit&eacute; de transport: </span></td>
   <td height="30" class="cellule_sombre_vide">
     <input name="text3" type="text" class="form" id="nunitea" onclick="this.value=''" value="Unite de transport" />
   </td>
   <td class="cellule_sombre_vide"></td>
 </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_claire_vide">&nbsp;</td>
   <td height="30" class="cellule_claire_vide"></td>
   <td class="cellule_claire_vide">
    <input name="Submit" type="button" onClick="validea();" class="geneva11bol_3B4B5B" value="Ajouter">
   </td>
 </tr>
  </table>
</div>
</body>
</html>
