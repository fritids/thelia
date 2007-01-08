<?php 
  // la fonction ci dessous permet de charger la librairie SP PLUS si elle n'est pas declaree dans le fichier php.ini (rubrique extensions)
   dl('php_spplus.so');
   if (!extension_loaded('SPPLUS')) { echo "extension SP PLUS non chargée<br><br>\n"; }
    
  /* boutique de demo */
  $clent="58 6d fc 9c 34 91 9b 86 3f fd 64 63 c9 13 4a 26 ba 29 74 1e c7 e9 80 79";
  $codesiret="00000000000001-01";
  $siret="00000000000001";
  /* */
  
  // URL d'accès à la servlet d'administration
  $spadminservlet = "https://www.spplus.net/administration/spadm/spadminservlet";

  /* 
   * 3 étapes sont réalisées pour chaque cas :
   * - construction de la chaîne de paramètre en fonction de l'action appelée  
   * - calcul du sceau numérique hmac
   * - construction de l'url d'appel en fonction de l'action appelée  
   */
  switch ($action) {
    case "url" :
      $params = $_REQUEST['action'] . $siret;
      if ($_REQUEST['urlweb'] != "NULL") { $params = $params . $_REQUEST['urlweb']; };
      if ($_REQUEST['urlcdv'] != "NULL") { $params = $params . $_REQUEST['urlcdv']; };
      if ($_REQUEST['urlcheck'] != "NULL") { $params = $params . $_REQUEST['urlcheck']; };
      if ($_REQUEST['urlcheck2'] != "NULL") { $params = $params . $_REQUEST['urlcheck2']; };
      if ($_REQUEST['urlretour'] != "NULL") { $params = $params . $_REQUEST['urlretour']; };
      if ($_REQUEST['nomfenetre'] != "NULL") { $params = $params . $_REQUEST['nomfenetre']; };
      if ($_REQUEST['mailconf'] != "NULL") { $params = $params . $_REQUEST['mailconf']; };
      if ($_REQUEST['mailalerte'] != "NULL") { $params = $params . $_REQUEST['mailalerte']; };
      
      $hmac = nthmac($clent, $params);
      
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret;
      if ($_REQUEST['urlweb'] != "NULL") { $url = $url . "&urlweb=" . $_REQUEST['urlweb']; };
      if ($_REQUEST['urlcdv'] != "NULL") { $url = $url . "&urlcdv=" . $_REQUEST['urlcdv']; };
      if ($_REQUEST['urlcheck'] != "NULL") { $url = $url . "&urlcheck=" . $_REQUEST['urlcheck']; };
      if ($_REQUEST['urlcheck2'] != "NULL") { $url = $url . "&urlcheck2=" . $_REQUEST['urlcheck2']; };
      if ($_REQUEST['urlretour'] != "NULL") { $url = $url . "&urlretour=" . $_REQUEST['urlretour']; };
      if ($_REQUEST['nomfenetre'] != "NULL") { $url = $url . "&nomfenetre=" . $_REQUEST['nomfenetre']; };
      if ($_REQUEST['mailconf'] != "NULL") { $url = $url . "&mailconf=" . $_REQUEST['mailconf']; };
      if ($_REQUEST['mailalerte'] != "NULL") { $url = $url . "&mailalerte=" . $_REQUEST['mailalerte']; };
      $url = $url . "&hmac=" . $hmac;
      break;
    case "image":
      // à faire
      echo("<br><br><b>Cette action n'a pas encore été implémentée dans le script PHP d'exemple dédié à l'administration SP PLUS.</b><br><br>");
      exit();
      break;
    case "delete":
      // à faire
      echo("<br><br><b>Cette action n'a pas encore été implémentée dans le script PHP d'exemple dédié à l'administration SP PLUS.</b><br><br>");
      exit();
      break;
    case "paiement":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'] . $_REQUEST['mnt'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&mnt=" . $_REQUEST['mnt'] . "&hmac=" . $hmac;
      break;
    case "annulation":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&hmac=" . $hmac;
      break;
    case "annulationtout" :
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&hmac=" . $hmac;
      break;
    case "reinitialisation":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&hmac=" . $hmac;
      break;
    case "remboursement":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'] . $_REQUEST['mnt'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&mnt=" . $_REQUEST['mnt'] . "&hmac=" . $hmac;
      break;
    case "prolongation":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'] . $_REQUEST['mnt'] . $_REQUEST['iter'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&mnt=" . $_REQUEST['mnt'] . "&iter=" . $_REQUEST['iter'] . "&hmac=" . $hmac;
      break;
    case "resiliation":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&hmac=" . $hmac;
      break;
    case "suspension" :
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&hmac=" . $hmac;
      break;
    case "reactivation":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'] . $_REQUEST['refsfp'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&reference=" . $_REQUEST['reference'] . "&refsfp=" . $_REQUEST['refsfp'] . "&hmac=" . $hmac;
      break;
    case "etattrans":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&refcde=" . $_REQUEST['reference'] . "&hmac=" . $hmac;
      break;
    case "informationsTransaction":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['reference'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&refcde=" . $_REQUEST['reference'] . "&hmac=" . $hmac;
      break;
    case "echeance":
      $params = $_REQUEST['action'] . $siret . $_REQUEST['refsfp'] . $_REQUEST['echeance'] . $_REQUEST['mnt'];
      $hmac = nthmac($clent, $params);
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret . "&refsfp=" . $_REQUEST['refsfp'];
      if( isset($_REQUEST['echeance']) && $_REQUEST['echeance'] != "" ) {
        $url .= "&echeance=" . $_REQUEST['echeance'];
      }
      if( isset($_REQUEST['mnt']) && $_REQUEST['mnt'] != "" ) {
        $url .= "&mnt=" . $_REQUEST['mnt'];
      }
      $url .= "&hmac=" . $hmac;
      break;
    case "admin":
      $params = $_REQUEST['action'] . $siret;
      if ($_REQUEST['email'] != "NULL") { $params = $params . $_REQUEST['email']; };
      if ($_REQUEST['reference'] != "NULL") { $params = $params . $_REQUEST['reference']; };
      if ($_REQUEST['emailhtml'] != "NULL") { $params = $params . $_REQUEST['emailhtml']; };
      if ($_REQUEST['arguments'] != "NULL") { $params = $params . $_REQUEST['arguments']; };
      if ($_REQUEST['cheque'] != "NULL") { $params = $params . $_REQUEST['cheque']; };
      if ($_REQUEST['2x'] != "NULL") { $params = $params . $_REQUEST['2x'] . "/" . $_REQUEST['montant2x'] . "/" . $_REQUEST['per2x']; };
      if ($_REQUEST['3x'] != "NULL") { $params = $params . $_REQUEST['3x'] . "/" . $_REQUEST['montant3x'] . "/" . $_REQUEST['per3x']; };
      if ($_REQUEST['xx'] != "NULL") { $params = $params . $_REQUEST['xx'] . "/" . $_REQUEST['nper'] . "/" . $_REQUEST['perxx']; };
      if ($_REQUEST['valauto'] != "NULL") { $params = $params . $_REQUEST['valauto']; };
      if ($_REQUEST['delaipaiement'] != "NULL") { $params = $params . $_REQUEST['delaipaiement']; };
      if ($_REQUEST['paiementmanu2auto'] != "NULL") { $params = $params . $_REQUEST['paiementmanu2auto']; };
      if ($_REQUEST['delaipaiementmanu2auto'] != "NULL") { $params = $params . $_REQUEST['delaipaiementmanu2auto']; };
      if ($_REQUEST['remce'] != "NULL") { $params = $params . $_REQUEST['remce']; };
      
      $hmac = nthmac($clent, $params);
      
      $url = $spadminservlet . "?action=" . $_REQUEST['action'] . "&siret=" . $codesiret;
      if ($_REQUEST['email'] != "NULL") { $url = $url . "&email=" . $_REQUEST['email']; };
      if ($_REQUEST['reference'] != "NULL") { $url = $url . "&reference=" . $_REQUEST['reference']; };
      if ($_REQUEST['emailhtml'] != "NULL") { $url = $url . "&emailhtml=" . $_REQUEST['emailhtml']; };
      if ($_REQUEST['arguments'] != "NULL") { $url = $url . "&arguments=" . $_REQUEST['arguments']; };
      if ($_REQUEST['cheque'] != "NULL") { $url = $url . "&cheque=" . $_REQUEST['cheque']; };
      if ($_REQUEST['2x'] != "NULL") { $url = $url . "&2x=" . $_REQUEST['2x'] . "/" . $_REQUEST['montant2x'] . "/" . $_REQUEST['per2x']; };
      if ($_REQUEST['3x'] != "NULL") { $url = $url . "&3x=" . $_REQUEST['3x'] . "/" . $_REQUEST['montant3x'] . "/" . $_REQUEST['per3x']; };
      if ($_REQUEST['xx'] != "NULL") { $url = $url . "&xx=" . $_REQUEST['xx'] . "/" . $_REQUEST['nper'] . "/" . $_REQUEST['perxx']; };
      if ($_REQUEST['valauto'] != "NULL") { $url = $url . "&valauto=" . $_REQUEST['valauto']; };
      if ($_REQUEST['delaipaiement'] != "NULL") { $url = $url . "&delaipaiement=" . $_REQUEST['delaipaiement']; };
      if ($_REQUEST['paiementmanu2auto'] != "NULL") { $url = $url . "&paiementmanu2auto=" . $_REQUEST['paiementmanu2auto']; };
      if ($_REQUEST['delaipaiementmanu2auto'] != "NULL") { $url = $url . "&delaipaiementmanu2auto=" . $_REQUEST['delaipaiementmanu2auto']; };
      if ($_REQUEST['remce'] != "NULL") { $url = $url . "&remce=" . $_REQUEST['remce']; };
      $url = $url . "&hmac=" . $hmac;
      break;
    default : 
      echo("Action inconnue !!!<br><br>\n");
  }
  
  // mode debug ou normal
  if ($_REQUEST['debug'] == "true") {
    // on affiche les différentes traces
    echo("<b>Clé marchand utilisée : " . $clent . "<br><br>");
    echo("Code Siret utilisé : " . $codesiret . "</b><br><br><br>");
    echo("Chaîne de paramètres à signer : " . $params . "<br><br>\n");
    echo("URL d'appel :<br>" . $url . "<br><br>\n");
    echo("<a href=\"" . $url . "\" target=\"_blank\">Tester l'URL</a><br><br>\n");
  } else {
    // on effectue l'appel vers la servlet d'administration
    header("Location: " . $url);
    exit();
  }
    
?>