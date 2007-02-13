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
	include_once("pre.php");
	include("auth.php");
?>
<?php
        include("../lib/Sajax.php");
        include("../fonctions/divers.php");
        include("../classes/Zone.class.php"); 
        include("../classes/Transport.class.php");
        include("../classes/Transportdesc.class.php");
        include("../classes/Transzone.class.php");
       
		include_once("../lib/JSON.php");
		
?>
<?php


	function chargert(){

                $i=0;
                   
                $tab = array();
                $transport = new Transport();
                $query = "select * from $transport->table";
                $resul = mysql_query($query, $transport->link);
                if(! mysql_numrows($resul)) return "";
                
                while($row = mysql_fetch_object($resul)){
                		$transport = new Transport();
                		$transport->charger($row->id);	
                        $tab[$i] = new Transport();
                        $tab[$i++] = $transport;
                }
			   
                return tabSerialise($tab);
	}	
	
	function chargertdesc(){

                $i=0;
                
                $tab = array();
                $transport = new Transport();
                $transportdesc = new Transportdesc();
                	
                $query = "select * from $transport->table";
                $resul = mysql_query($query, $transport->link);
                if(! mysql_numrows($resul)) return "";
                
                while($row = mysql_fetch_object($resul)){
                		$transport = new Transport();
                		$transportdesc = new Transportdesc();
                		$transportdesc->charger($row->id);	
                        $transport->charger($row->id);
                        $tab[$i] = new Transportdesc();
                        $tab[$i++] = $transportdesc;
                }


                return tabSerialise($tab);
                
	}

	function chargerz($transport, $type){
                $i=0;
                $zone = new Zone();
				$transzone = new Transzone();
				$liste="";
				$tab="";
				
               if($type=="d"){
					$query = "select * from $transzone->table where transport='$transport'";               	
					$resul = mysql_query($query, $transzone->link);				
				
					while($row = mysql_fetch_object($resul))
						$liste .= "'$row->zone', ";
				
					$liste = substr($liste, 0, strlen($liste) - 2);
					
					if(!$liste) $liste="''";
					$query = "select * from $zone->table where id in ($liste)";
              
               }
               
               else if($type=="h")  {
               
				$query = "select * from $transzone->table where transport='$transport'";               			
				$resul = mysql_query($query, $transzone->link);				
				
				while($row = mysql_fetch_object($resul))
					$liste .= "'$row->zone', ";
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				if(!$liste) $liste="''";
				$query = "select * from $zone->table where id not in ($liste)";

              
               }	
              
               else  $query = "select * from $zone->table";
               
                $resul = mysql_query($query, $zone->link);	
               
                while($row = mysql_fetch_object($resul)){
                        $zone = new Zone();
                        $zone->charger($row->id);

                        
                        $zone->nom = supprAccent($zone->nom);
                        
                        $tab[$i] = new Zone();
                        $tab[$i++] = $zone;
                }

 
               if(!$tab) { return "";}
         
                return tabSerialise($tab);
                


	}	
	
	function validem($tcours, $strd, $strh){

				$transport = new Transport();
				$transzone = new Transzone();
								
	 			$json = new Services_JSON();

	 			$tcours = stripslashes($tcours);
	 			$tcours = $json->decode($tcours);

				$transport->id = $tcours->id;
				$transport->classe = $tcours->classe;
				$transport->maj();
				
	 			$strd = stripslashes($strd);
	 			$resd = $json->decode($strd);
	 			
	 			$strh = stripslashes($strh);
	 			$resh = $json->decode($strh);
	 		
	 			$query = "delete from $transzone->table where transport='$tcours->id'";
				$resul = mysql_query($query, $transzone->link);
			
	 			for($i=0; $i<count($resd); $i++){
	 				$transzone = new Transzone();
	 				$transzone->transport=$tcours->id;
	 				$transzone->zone=$resd[$i];
	 				$transzone->actif="1";
	 				$transzone->add();	 			
	 			
	 			}
	 			

	}


	function validea($tcours, $strd, $strh){

				$zone = new Zone();
				$transport = new Transport();
				$transportdesc = new Transportdesc();
				$transzone = new Transzone();
								
	 			$json = new Services_JSON();

	 			$tcours = stripslashes($tcours);
	 			$tcours = $json->decode($tcours);

				
				$transport->id = "";
				$transport->actif = "1";
				$transport->classe = $tcours->classe;
				$lastid = $transport->add();
				$transport->charger($lastid);
				
				$transportdesc->transport = $lastid;
				$transportdesc->lang = "1";
				$transportdesc->titre=$tcours->titre;
				$transportdesc->add();
				
				
	 			$strd = stripslashes($strd);
	 			$resd = $json->decode($strd);
	 			
	 			$strh = stripslashes($strh);
	 			$resh = $json->decode($strh);
		
	 				
	 			for($i=0; $i<count($resd); $i++){
	 				$transzone->transport = $lastid;
	 				$transzone->zone = $resd[$i];
	 				$transzone->actif = "1";
					$transzone->add();	 	
				}

	}

	function supprt($tcours){

				$transport = new Transport();
				$transportdesc = new Transportdesc();
				$transzone = new Transzone();					
			
	 			$query = "delete from $transportdesc->table where transport='$tcours'";	
				$resul = mysql_query($query, $transzone->link);	
					 		
	 			$query = "delete from $transzone->table where transport='$tcours'";	
				$resul = mysql_query($query, $transzone->link);	
				
				$transport->charger($tcours);
				$transport->delete();

	}	
?>
<?php
		//$sajax_debug_mode = 1;
      	sajax_init();        
      	sajax_export("chargert");
        sajax_export("chargertdesc");
        sajax_export("chargerz");
        sajax_export("validem");
        sajax_export("validea");
        sajax_export("supprt");

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
//-->
	
	var rest;
	var restdesc;
	var indexCours;
	
	function charger(){

		restdesc = eval(sx_chargertdesc());
		rest = eval(sx_chargert());

		var contenu;
		
		document.getElementById("divt").innerHTML="";

		if(restdesc) {
		
		for(i=0; i<restdesc.length; i++)  {
			var trindex;
			trindex = restdesc[i]['transport'];

			contenu="";
			contenu=contenu + "<div style='border-bottom: solid #CDCDCD 1px;'>";
			
			contenu=contenu + "<span style=' position: absolute;  margin-left: 50px;'>";
			contenu=contenu + restdesc[i]['titre'];
			contenu=contenu + "</span>";

			contenu=contenu + "<span style=' width: 50px;margin-left: 400px;'>";
			contenu=contenu + "<a href='#' onClick=\"chargertdesc('" + i + "')\"><img src='gfx/b_edit.png' width='16' height='16' border='0'></a>";
			contenu=contenu + "</span>";

			contenu=contenu + "<span style='width: 50px;margin-left: 50px;'>";
			contenu=contenu + "<a href='#' onClick=\"supprt('" + trindex + "')\"><img src='gfx/b_drop.png' width='16' height='16' border='0'></a>";
			contenu=contenu + "</span>";

			contenu=contenu + "</div>";
			document.getElementById("divt").innerHTML=document.getElementById("divt").innerHTML + contenu;
			
 		
				
		}	
	}

			var element = document.createElement("option");
			element.text = "liste des zones";
			element.value="0";

         	document.getElementById('selectszoneha').options.length = 0;
         	document.getElementById('selectszoneda').options.length = 0;
        // 	document.getElementById('selectszoneda').options[0] = element;
         	document.getElementById('ntransporta').value='Nom du transport'; 
         	document.getElementById('nclassea').value='Classe'; 


  			var resz = eval(sx_chargerz('', ''));
  		  	for(i=0; i<resz.length; i++){
      		element = document.createElement("option");
      		 element.text=resz[i]['nom'];
      		 element.value=resz[i]['id'];
      		
      		 document.getElementById('selectszoneha').options[document.getElementById('selectszoneha').length]=element;

      		}	                        
                    	     
	}
	

      function chargertdesc(index){

      	indexCours = index;
      	document.getElementById('ntransportm').value=restdesc[index]['titre'];
      	document.getElementById('nclassem').value=rest[index]['classe'];

     	var reszd = eval(sx_chargerz(restdesc[index]['transport'], 'd'));    
  		var reszh = eval(sx_chargerz(restdesc[index]['transport'], 'h'));

		document.getElementById('selectszonedm').options.length = 0;
       	document.getElementById('selectszonehm').options.length = 0;

      	if(reszd)
      		for(i=0; i<reszd.length; i++){
     	 	 var element = document.createElement("option");
      		 element.text=reszd[i]['nom'];
      		 element.value=reszd[i]['id'];

      		document.getElementById('selectszonedm').options[document.getElementById('selectszonedm').length]=element;
      	}
      	
      	if(reszh)

      	for(i=0; i<reszh.length; i++){
      	 var element = document.createElement("option");
      	 element.text=reszh[i]['nom'];
      	 element.value=reszh[i]['id'];
      	document.getElementById('selectszonehm').options[document.getElementById('selectszonehm').length]=element;
      	}

      }	  


	  function ajoutzone(type){
	  		
	      var index = document.getElementById('selectszoneh' + type).selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectszoneh' + type).options[index].text;
      	    element.value=document.getElementById('selectszoneh' + type).options[index].value;
      	    document.getElementById('selectszoned' + type).options[document.getElementById('selectszoned'+type).length]=element;
      	    
	 		document.getElementById('selectszoneh' + type).remove(index); 
	 		
	 		    
	 		
	  }

	  function supprzone(type){
	  		
	      var index = document.getElementById('selectszoned' + type).selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectszoned' + type).options[index].text;
      	    element.value=document.getElementById('selectszoned' + type).options[index].value;
      	    document.getElementById('selectszoneh' + type).options[document.getElementById('selectszoneh'+type).length]=element;
      	    
	 		document.getElementById('selectszoned' + type).remove(index);      
	     
	  }	  
	  
	  
	  function validem(){
	  	var zObj = new Object();
	 	zObj.id =   rest[indexCours]['id'];
	 	zObj.titre = document.getElementById('ntransportm').value;
	 	zObj.classe = document.getElementById('nclassem').value;

	  	var zoned = new Array();
	  	var zoneh = new Array();
	  	
	  	for(i=0; i<document.getElementById('selectszonedm').length; i++)
	  		zoned[i] = document.getElementById('selectszonedm').options[i].value;

	 	for(i=0; i<document.getElementById('selectszonehm').length; i++)
	  		zoneh[i] = document.getElementById('selectszonehm').options[i].value;

	  	 sx_validem(JSON.stringify(zObj), JSON.stringify(zoned), JSON.stringify(zoneh)); 
	 	 charger();
	 	 alert("Modification effectue"); 
}	
	  function validea(){

	  	var zObj = new Object();
	 	zObj.id =  '';	 		 

	 	zObj.titre = document.getElementById('ntransporta').value;
	 	zObj.classe = document.getElementById('nclassea').value;

	  	var zoned = new Array();
	  	var zoneh = new Array();
	  	
	  	for(i=0; i<document.getElementById('selectszoneda').length; i++)
	  		zoned[i] = document.getElementById('selectszoneda').options[i].value;
	
	 	for(i=0; i<document.getElementById('selectszoneha').length; i++)
	  		zoneh[i] = document.getElementById('selectszoneha').options[i].value;

	  	 sx_validea(JSON.stringify(zObj), JSON.stringify(zoned), JSON.stringify(zoneh)); 
	 	 charger();	  
	  	 alert("Modification effectue"); 
	  }
	  
	  
      function supprt(index){
     	 sx_supprt(index);
	 	 charger();	        	
      }	            
</script>
<script>
        <?php
	                sajax_show_javascript();
        ?>
</script>
</head>

<body onLoad="charger()">

<?php
	include("../classes/Boutique.class.php");
?>
<?php
	$menu="livraisons";
	include("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des types de transport </p>
    <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="gestlivraison.php" class="lien04"> Gestion des livraisons</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="#" class="lien04">Gestion des types de transport</a>    </p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES TRANSPORTS </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr class="cellule_sombre_vide">
    <td height="30" colspan="2" class="cellule_sombre_vide"> <div class="geneva11bol_3B4B5B" id="divt">
						Chargement ...
      </div>
    </td>
    </tr>

  </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">MODIFIER UN TRANSPORT </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr class="cellule_sombre_vide">
    <td width="34%" height="30" class="cellule_sombre_vide"> 
	<input name="text" type="text" class="form" id="ntransportm" value="Nom du transport">
	</td>
    <td  height="30">    </td>
    <td width="27%"></td>
  </tr>
 <tr class="cellule_claire_vide">
    <td width="34%" height="30" class="geneva11bol_3B4B5B">
      <div align="center">
        <select name="select" class="form" id="selectszonehm">
          <option selected="selected">listes des zones</option>
        </select>
      </div>
    </td>
    <td width="39%" height="30"> <div align="center">
                            <input name="Submit" type="button" onClick="ajoutzone('m')" class="geneva11bol_3B4B5B" value="Ajouter une zone &gt;&gt; ">
                            <br /><input name="Submit" type="button" onClick="supprzone('m')" class="geneva11bol_3B4B5B" value="&lt;&lt; Supprimer une zone">
    </div></td>
                          <td><div align="center">
                            <select name="select2" size="5" multiple="multiple" class="form" id="selectszonedm">
                              <option selected="selected">liste des zones</option>
                            </select>
                          </div> 
      </td>
    </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_sombre_vide"><span class="geneva11bol_3B4B5B">Definir la classe:</span></td>
   <td height="30" class="cellule_sombre_vide">
     <input name="text2" type="text" class="form" id="nclassem" value="Classe" />
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
      <td width="600" height="30" class="titre_cellule_tres_sombre2">AJOUTER UN TRANSPORT</td>
    </tr>
  </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr class="cellule_sombre_vide">
    <td width="34%" height="30" class="cellule_sombre_vide"><span class="arial11_bold_626262">
      <input name="text3" type="text" class="form" id="ntransporta" onclick="this.value=''" value="Nom du transport" />
    </span></td>
    <td  height="30">    </td>
    <td width="27%"></td>
  </tr>
 <tr class="cellule_claire_vide">
    <td width="34%" height="30" class="geneva11bol_3B4B5B">
      <div align="center">
        <select name="select3" class="form" id="selectszoneha">
                </select>
      </div>
    </td>
    <td width="39%" height="30"> <div align="center">
      <input name="Submit2" type="button" onclick="ajoutzone('a');" class="geneva11bol_3B4B5B" value="Ajouter une zone &gt;&gt; " />
      <br />
      <div align="center">
        <input name="Submit2" type="button" onclick="supprzone('a');" class="geneva11bol_3B4B5B" value="&lt;&lt; Supprimer une zone" />
      </div>
    </div></td>
                          <td><div align="center">
                            <select name="select4" size="5" multiple="multiple" class="form" id="selectszoneda">
                                                        </select>
                          </div> 
      </td>
    </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_sombre_vide"><span class="geneva11bol_3B4B5B">Definir la classe:</span></td>
   <td height="30" class="cellule_sombre_vide">
     <input name="text4" type="text" class="form" id="nclassea" onclick="this.value=''" value="Classe" />
   </td>
   <td class="cellule_sombre_vide"></td>
 </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_claire_vide">&nbsp;</td>
   <td height="30" class="cellule_claire_vide"></td>
   <td class="cellule_claire_vide">
     <input name="Submit3" type="button" onclick="validea();" class="geneva11bol_3B4B5B" value="Ajouter" />
   </td>
 </tr>
  </table>
</div>
</body>
</html>
