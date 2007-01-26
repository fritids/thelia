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

	function lireTag($ligne, $tag){
	
		if( ! strstr($ligne, $tag)) return "";
		ereg("$tag=\"([^\"]*)\"", "$ligne", $res);
		return $res[1];
	
	}
	
	function redirige($url){
	
		header("Location: " . $url);
		exit;
	}
	
	function chemin($id){

		$tab ="";
		
		$trubrique = new Rubrique();
		$trubrique->parent = $id;
		$trubriquedesc = new Rubriquedesc();

		
		$i =  0;
 		do {
			$trubrique->charger("$trubrique->parent");	
			$trubriquedesc->charger($trubrique->id);
			$tab[$i++] = $trubriquedesc;	

		} while($trubrique->parent != 0); 
	
		$i--;
	
		return $tab;
		
	
	
	}	

	function chemin_dos($id){

		$tab ="";
		
		$tdossier = new Dossier();
		$tdossier->parent = $id;
		$tdossierdesc = new Dossierdesc();

		
		$i =  0;
 		do {
			$tdossier->charger("$tdossier->parent");	
			$tdossierdesc->charger($tdossier->id);
			$tab[$i++] = $tdossierdesc;	

		} while($tdossier->parent != 0); 
	
		$i--;
	
		return $tab;
		
	
	
	}	

	function rewrite_prod($ref, $lang=0){

		if(! $lang) $lang=$_SESSION['navig']->lang;
		
		$prod = new Produit();
		$prod->charger($ref);
				
		$rubfinal = $prod->rubrique;
		$chem = chemin($rubfinal);
		
		$rubriquedesc = new Rubriquedesc();
		
		$listrub = "";

		$rubriquedesc->charger($chem[count($chem)-1]->rubrique, $lang);
		$listrub .= $rubriquedesc->titre . "_";
		
		$rubriquedesc->charger($chem[0]->rubrique, $lang);
		$listrub .= $rubriquedesc->rubrique . "_";
				
	
					
		for($i=count($chem)-2; $i>=0; $i--){
			$rubriquedesc->charger($chem[$i]->rubrique, $lang);
			$listrub .= $rubriquedesc->titre . "_";
		}
				
				
		$listrub .= "_" . $prod->ref . ".html";		
		
		return eregurl($listrub); 


	}
	
	function rewrite_rub($id, $lang=0){

		if(! $lang) $lang=$_SESSION['navig']->lang;
		
		$rub = new Rubrique();
		$rub->charger($id);

		$chem = chemin($id);

		$rubriquedesc = new Rubriquedesc();
		
		$listrub = "";

		$rubriquedesc->charger($chem[count($chem)-1]->rubrique, $lang);
		$listrub .= $rubriquedesc->titre . "_";

		$rubriquedesc->charger($chem[0]->rubrique, $lang);
		$listrub .= $rubriquedesc->rubrique . "_";
							
		for($i=count($chem)-2; $i>=0; $i--){
			$rubriquedesc->charger($chem[$i]->rubrique, $lang);
			$listrub .= $rubriquedesc->titre . "_";
		}

				
		$listrub .= ".html";		
		
		return eregurl($listrub); 


	}
	

	function rewrite_cont($id, $lang=0){

		if(! $lang) $lang=$_SESSION['navig']->lang;
				
		$cont = new Contenu();
		$cont->charger($id);
				
		$dosfinal = $cont->dossier;
		$chem = chemin_dos($dosfinal);
		
		$dossierdesc = new Dossierdesc();
		
		$listdos = "";

		$dossierdesc->charger($chem[count($chem)-1]->dossier, $lang);
		$listdos .= $dossierdesc->titre . "__";
		
		$dossierdesc->charger($chem[0]->dossier, $lang);
		$listdos .= $dossierdesc->dossier . "_";
				
	
					
		for($i=count($chem)-2; $i>=0; $i--){
			$dossierdesc->charger($chem[$i]->dossier, $lang);
			$listdos .= $dossierdesc->titre . "_";
		}
				
		$contenudesc = new Contenudesc();
		$contenudesc->charger($cont->id, $lang);
				
		$listdos .= $contenudesc->titre . "_" . $cont->id . ".html";		
		
		return eregurl($listdos); 


	}

	function rewrite_dos($id, $lang=0){

		if(! $lang) $lang=$_SESSION['navig']->lang;
				
		$chem = chemin_dos($id);
		
		$dossierdesc = new Dossierdesc();
		
		$listdos = "";

		$dossierdesc->charger($chem[count($chem)-1]->dossier, $lang);
		$listdos .= $dossierdesc->titre . "__";
		
		$dossierdesc->charger($chem[0]->dossier, $lang);
		$listdos .= $dossierdesc->dossier . "_";
				
	
					
		for($i=count($chem)-2; $i>=0; $i--){
			$dossierdesc->charger($chem[$i]->dossier, $lang);
			$listdos .= $dossierdesc->titre . "_";
		}
				
	
		$listdos .= ".html";
		


		return eregurl($listdos); 

	}


function eregurl($url){

		$html = strpos($url, ".html");
		
		$url = substr($url, 0, $html);
		
		
		$url = ereg_caracspec($url);

		return $url . ".html";
}


function eregfic($fichier){

		$fichier = ereg_caracspec($fichier);

		
		return $fichier;
}

function ereg_caracspec($chaine){
		
		$avant = "�������������������������ؖ��ˁ̀��������ͅ���";  
  		$apres = "aaaaaaooooooeeeeciiiiuuuuynaaaaaaceeeeoooooouuuu"; 
		
		$chaine = strtolower($chaine);
 		$chaine = strtr($chaine, $avant, $apres);
  
  		$chaine = ereg_replace("/", "-", $chaine);
		$chaine = ereg_replace(" ", "-", $chaine);
		$chaine = ereg_replace("'", "-", $chaine);
		$chaine = ereg_replace("\&", "-", $chaine);
		$chaine = ereg_replace("\?", "", $chaine);	
		$chaine = ereg_replace("\.", "", $chaine);	
		$chaine = ereg_replace("!", "", $chaine);			
		$chaine = preg_replace('/-+/', '-', $chaine);	
		
		return $chaine;
}
	
function arbreBoucle($depart, $profondeur=0, $i=0){
		$rec="";
		$i++;
		if($i == $profondeur && $profondeur != 0) return;
		$trubrique = new Rubrique();
		
		$query = "select * from $trubrique->table where parent=\"$depart\"";
		$resul = mysql_query($query, $trubrique->link);
		
		while($row=mysql_fetch_object($resul)){
			$rec .= "'" . $row->id . "',";
			$rec .= arbreBoucle($row->id, $profondeur,$i);
			
		}
		
		if(substr($rec, strlen($rec)-1) == ",") $rec = substr($rec, 0, strlen($rec)-1);
		
		return $rec;
}

function arbreOption($depart, $niveau, $prubrique){

		$rec="";
		
		$niveau++;
		$trubrique = new Rubrique();
		$trubriquedesc = new Rubriquedesc();
		
		$query = "select * from $trubrique->table where parent=\"$depart\"";
		$resul = mysql_query($query, $trubrique->link);
		
		for($i=0; $i<$niveau; $i++) $espace .="&nbsp;&nbsp;&nbsp;";

		while($row=mysql_fetch_object($resul)){
			$trubriquedesc->charger($row->id);
			if($prubrique == $trubriquedesc->rubrique) $selected="selected"; else $selected="";
			
			$rec .= "<option value=\"$row->id\" $selected>" . $espace . $trubriquedesc->titre . "</option>";
			
			$rec .= arbreOption($row->id, $niveau, $prubrique);
			
		}
		
		
		return $rec;
}

function arbreBoucle_dos($depart, $profondeur=0, $i=0){

		$rec="";
		
		$i++;
		if($i == $profondeur && $profondeur != 0) return;
		$tdossier = new Dossier();
		
		$query = "select * from $tdossier->table where parent=\"$depart\"";
		$resul = mysql_query($query, $tdossier->link);
		
		while($row=mysql_fetch_object($resul)){
			$rec .= "'" . $row->id . "',";
			$rec .= arbreBoucle_dos($row->id, $profondeur,$i);
			
		}
		
		if(substr($rec, strlen($rec)-1) == ",") $rec = substr($rec, 0, strlen($rec)-1);
		
		return $rec;
}

function arbreOption_dos($depart, $niveau, $pdossier){
		$niveau++;
		$tdossier = new Dossier();
		$tdossierdesc = new Dossierdesc();
		
		$query = "select * from $tdossier->table where parent=\"$depart\"";
		$resul = mysql_query($query, $tdossier->link);
		
		for($i=0; $i<$niveau; $i++) $espace .="&nbsp;&nbsp;&nbsp;";

		while($row=mysql_fetch_object($resul)){
			$tdossierdesc->charger($row->id);
			if($pdossier == $tdossierdesc->dossier) $selected="selected"; else $selected="";
			
			$rec .= "<option value=\"$row->id\" $selected>" . $espace . $tdossierdesc->titre . "</option>";
			
			$rec .= arbreOption_dos($row->id, $niveau, $pdossier);
			
		}

		
		return $rec;
}

 function resize($nomorig, $width){


 if (file_exists($nomorig))
 {
        $extension = substr($nomorig, strlen($nomorig)-3);

   // chargement img origine
   if(strtolower($extension) == "gif"){
        $image_orig = imagecreatefromgif($nomorig);
    // Cacul des nouvelles dimensions
         list($width_orig, $height_orig) = getimagesize($nomorig);

          if($width_orig>$width)
              $height = ($width * $height_orig) / $width_orig;
          else {
                   $height = ceil($height_orig);
                   $width = ceil($width_orig);
          }


        $image_new = imagecreatetruecolor($width, $height);
    //    $src_tc_idx = imagecolortransparent($image_orig);
        $src_tc = imagecolorsforindex($image_orig, $src_tc_idx);
        $tgt_tc_idx = imagecolorallocate($image_new, $src_tc['red'], $src_tc['green'], $src_tc['blue']);
        imagefill($image_new, 0, 0, $tgt_tc_idx);
        imagecolortransparent($image_new, $tgt_tc_idx);

   }
   else if(strtolower($extension) == "jpg"){
        $image_orig = imagecreatefromjpeg($nomorig);
    // Cacul des nouvelles dimensions
      list($width_orig, $height_orig) = getimagesize($nomorig);
        if($width_orig>$width)
            $height = ($width * $height_orig) / $width_orig;
         else {
                $height = ceil($height_orig);
                $width = ceil($width_orig);
          }

           $image_new = imagecreatetruecolor($width, $height);

   }

   else if(strtolower($extension) == "png"){
        $image_orig = imagecreatefrompng($nomorig);
    // Cacul des nouvelles dimensions
      list($width_orig, $height_orig) = getimagesize($nomorig);
        if($width_orig>$width)
            $height = ($width * $height_orig) / $width_orig;
         else {
                $height = ceil($height_orig);
                $width = ceil($width_orig);
          }

           $image_new = imagecreatetruecolor($width, $height);

   }
  // Redimensionnement

  imagecopyresampled($image_new, $image_orig, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);


     if(strtolower($extension) == "gif")
                 imagegif($image_new, "$nomorig");
        else if(strtolower($extension) == "jpg")
                 imagejpeg($image_new, "$nomorig", 100);
        else if(strtolower($extension) == "png")
                 imagepng($image_new, "$nomorig", 100);

  }

 }

	
	function genpass($size){ 
		 $key_g = ""; 
 		 $letter = "abcdefghijklmnopqrstuvwxyz"; 
		 $letter .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
 		 $letter .= "0123456789"; 
  
 		 srand((double)microtime()*date("YmdGis")); 
  
		 for($cnt = 0; $cnt < $size; $cnt++) 
  		{ 
  			$key_g .= $letter[rand(0, 61)]; 
  		} 
  
  		return $key_g; 
}
 
	function gencode($size){ 
		 $key_g = ""; 
		 $letter="";
 		 $letter .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
 		 $letter .= "0123456789"; 
  
 		 srand((double)microtime()*date("YmdGis")); 
  
		 for($cnt = 0; $cnt < $size; $cnt++) 
  		{ 
  			$key_g .= $letter[rand(0, 36)]; 
  		} 
  
  		return $key_g; 
}
  
 
	function mail_fichier($to , $sujet , $message , $fichier , $typemime , $nom , $reply , $from){ 
  $limite = "_parties_".md5(uniqid (rand())); 
  
  $mail_mime = "Date: ".date("l j F Y, G:i")."\n"; 
  $mail_mime .= "MIME-Version: 1.0\n"; 
  $mail_mime .= "Content-Type: multipart/mixed;\n"; 
  $mail_mime .= " boundary=\"----=$limite\"\n\n"; 
  
  //Le message en texte simple pour les navigateurs qui n'acceptent pas le HTML

  $texte = "This is a multi-part message in MIME format.\n"; 
  $texte .= "Ceci est un message est au format MIME.\n"; 
  $texte .= "------=$limite\n"; 
  $texte .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n"; 
  $texte .= "Content-Transfer-Encoding: 32bit\n\n"; 
  $texte .= $message; 
  $texte .= "\n\n"; 
  
  //le fichier 
  $attachement = "------=$limite\n"; 
  $attachement .= "Content-Type: $typemime; name=\"$nom\"\n"; 
  $attachement .= "Content-Transfer-Encoding: base64\n"; 
  $attachement .= "Content-Disposition: attachment; filename=\"$nom\"\n\n"; 
  
  $fp = fopen($fichier, "rb"); 
  $buff = fread($fp, filesize($fichier)); 
  
  fclose($fp); 
  $attachement .= chunk_split(base64_encode($buff)); 
  
  $attachement .= "\n\n\n------=$limite\n"; 
  return mail($to, $sujet, $texte.$attachement, "Reply-to:
$reply\nFrom:$from\n".$mail_mime); 
}

   function tabSerialise($tab){
 	  	$res = "[";
   	
  	 	for($i=0; $i<count($tab); $i++)
   			$res .= $tab[$i]->serialise_js() . ",";
   
   	 $res = substr($res, 0, strlen($res)-1);
  	 $res .= "]";
   
   	return $res;
  } 
  
  function supprAccent($texte) {
  
  	return strtr( $texte,"�����������������������������������������������������",
"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn" );
  
  }
  
  
function port($type=0){
	
	if($_SESSION['navig']->commande->transport == "" && !$type) return -1;
	
	if( $_SESSION['navig']->adresse != 0) $chadr=1;
	else $chadr=0;
	
	$transport = new Transport();
	
	if(!$type) $transport->charger($_SESSION['navig']->commande->transport);
	else $transport->charger($type);
		
	$p = new Pays();
	if($chadr){
		 $adr = new adresse();
		 $adr->charger($_SESSION['navig']->adresse);
		 $p->charger($adr->pays);
		 $cpostal = $adr->cpostal;
	}
	else {
		$p->charger($_SESSION['navig']->client->pays);
		$cpostal = $_SESSION['navig']->client->cpostal;
	}
	

	$zone = new Zone();
	$zone->charger($p->zone);
	$classe = $transport->classe;

	if(! file_exists("client/transports/$classe" . ".class.php")){
		return -1;
	}	

	include_once("client/transports/$classe" . ".class.php");
	$port = new $classe();
		
	$port->nbart = $_SESSION['navig']->panier->nbart();
	$port->poids = $_SESSION['navig']->panier->poids();
	$port->total = $_SESSION['navig']->panier->total();
	$port->zone = $p->zone; 
	$port->pays = $p->id;
	$port->unitetr = $zone->unite;
	$port->cpostal = $cpostal;
	 
	return $port->calcule();
} 

function url_exists($url)
{
 $handle = @fopen($url, "r");
 if ($handle === false)
  return false;
 fclose($handle);
 return true;
}
 
?>
