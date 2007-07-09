<?php 
    
  /* boutique de démonstration */
  $clent = "58 6d fc 9c 34 91 9b 86 3f fd 64 63 c9 13 4a 26 ba 29 74 1e c7 e9 80 79";
  $codesiret = "00000000000001-01";

  echo("<b>Clé marchand utilisée : " . $clent . "<br><br>");
  echo("code siret utilisé : " . $codesiret . "</b><br><br><br>");
  
  /* $_SERVER['QUERY_STRING'] : 
   * permet de recuperer telquel la chaine de requete, 
   * si elle existe, qui est utilisee pour acceder a la page. 
   * Valable uniquement pour les parametres passe en GET.
   */
  $chaineParam = $_SERVER['QUERY_STRING']; 
  
  echo("Parametres recus : " . $chaineParam . "<br><br>\n");
  
  // on cherche la position du premier caractere de la chaine '&hmac' 
  $pos = strpos($chaineParam,"&hmac="); 
  
  if($pos == false || !is_integer($pos))  { 
    echo("Erreur : Hmac non present<br><br>\n"); 
  } else { 
    // on supprime le parametre hmac de la chaine
    $chaineCalcul = substr($chaineParam,0,$pos); 
    $chaineCalcul .= substr($chaineParam,$pos+strlen($_GET["hmac"])+6,strlen($chaineParam));
    $chaineParam = "";
      
    // on decoupe la chaine de parametre afin de ne recuperer que les valeurs concatenees
    $tok = strtok($chaineCalcul,"=&");
    while($tok) {
      if($_REQUEST[$tok] != "") {
        echo "Valeur de $tok non nulle<br>";
        $tok = strtok("&=");
        $chaineParam .= $tok;
      } else {
        echo "Valeur de $tok nulle => Non pris en compte<br>";
      }
      $tok = strtok("&=");
      $tok = urldecode($tok);
    }
  }
  
  echo("Chaine de parametre a controler : " . $chaineParam . "<br><br>\n");
  
  // on calcul le sceau numerique hmac
  $hmac_calcule = nthmac($clent, $chaineParam); 
  
  // on controle le hmac obtenu au hmac recu
  if ( strcmp( $hmac_calcule, $_GET["hmac"]) != 0 ) { 
    $message = "erreur_hmac"; 
  } else {
    $message = "hmacok"; 
  }
  
  echo("Hmac reçu : " . $_GET["hmac"] . "<br><br>\n");
  echo("Hmac calculé : " . $hmac_calcule . "<br><br>\n");
  echo("<b>Résultat : " . $message . "</b><br><br>\n");
    
?>