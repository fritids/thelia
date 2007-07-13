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
foreach ($_POST as $key => $value) $$key = $value;
foreach ($_GET as $key => $value) $$key = $value;
?>
<?php

// redimensionnement des images + effets

include_once("divers.php");

function imageflip(&$dest, &$src) {
        $w = imagesx($src);
        $h = imagesy($src);
        $alpha = 127;
        for($y=0; $y<$h; $y++) {
                for ($x=0; $x<$w; $x++) {
                        $couleur = imagecolorsforindex($src, imagecolorat($src, $x, $y));
                        $couleurAlpha = imagecolorallocatealpha($dest, $couleur['red'], $couleur['green'], $couleur['blue'], $alpha);
                        imagesetpixel($dest, $x, ($h-$y), $couleurAlpha);
                }
                if($alpha > 1) $alpha--;
        }
}


if(!isset($height)) $height="";
if(!isset($opacite)) $opacite="";
if(!isset($nb)) $nb="";
if(!isset($miroir)) $miroir="";


 if (file_exists($nomorig) || url_exists($nomorig))
 {
 		$extension = substr($nomorig, strlen($nomorig)-3);
 
 		if(strstr($nomorig, "client/gfx/photos/rubrique")) $type = "rubrique";
 		else if(strstr($nomorig, "client/gfx/photos/produit")) $type = "produit";
 		else if(strstr($nomorig, "client/gfx/photos/dossier")) $type = "dossier";
 		else if(strstr($nomorig, "client/gfx/photos/contenu")) $type = "contenu";
  		
		ereg("/([^\/]*.jpg)", $nomorig, $nsimple);
 		
  		$nomcache = "client/cache/" . $type . "/" . $width . "_" . $height . "_" . $opacite . "_" . $nb . "_" . $miroir . "_" . $nsimple[1];
 		
 		if(file_exists("../$nomcache")) { header("Location: ../$nomcache"); exit; }
 		
       

   // chargement img origine
   if(strtolower($extension) == "gif"){
        $image_orig = imagecreatefromgif($nomorig);
    // Cacul des nouvelles dimensions
         list($width_orig, $height_orig) = getimagesize($nomorig);

         if($width!="" && $width_orig>$width) $height = ($width * $height_orig) / $width_orig;
		 else $width="";
		 if($height!="" && $height_orig>$height) $width = ($height * $width_orig) / $height_orig;
		 else $height="";
		
		if($width=="") $width=$width_orig;
		if($height=="") $height=$height_orig;
		
        $image_new = imagecreatetruecolor($width, $height);
        $src_tc_idx = imagecolortransparent($image_orig);
      //  $src_tc = imagecolorsforindex($image_orig, $src_tc_idx);
        $tgt_tc_idx = imagecolorallocate($image_new, $src_tc['red'], $src_tc['green'], $src_tc['blue']);
        imagefill($image_new, 0, 0, $tgt_tc_idx);
        imagecolortransparent($image_new, $tgt_tc_idx);

   }
   else if(strtolower($extension) == "jpg" || strtolower($extension) == "png"){
       
        if(strtolower($extension) == "jpg") $image_orig = imagecreatefromjpeg($nomorig);
		else if(strtolower($extension) == "png") $image_orig = imagecreatefrompng($nomorig);


   
     // Cacul des nouvelles dimensions
      list($width_orig, $height_orig) = getimagesize($nomorig);
		
		if($opacite != ""){
			$opac_img = imagecreatetruecolor($width_orig, $height_orig);
			$white = ImageColorAllocate ($opac_img, 255, 255, 255);

			imagefill($opac_img, 0, 0, $white);

			imagecopymerge($opac_img, $image_orig, 0,0,0,0, $width_orig, $height_orig, $opacite);
        
        	$image_orig = $opac_img;
		}



		
         if($width!="" && $width_orig>$width) $height = ($width * $height_orig) / $width_orig;
		 else $width="";
		 if($height!="" && $height_orig>$height) $width = ($height * $width_orig) / $height_orig;
		 else $height="";
		
		if($width=="") $width=$width_orig;
		if($height=="") $height=$height_orig;
		
         $image_new = imagecreatetruecolor($width, $height);


   }


  // Redimensionnement

  imagecopyresampled($image_new, $image_orig, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

	// Noir et blanc
	
		if($nb != ""){
		
  			imagetruecolortopalette($image_new, false, 256);

 			$total = ImageColorsTotal($image_new); 
			 for( $i=0; $i<$total; $i++){ 
	 	    	 $old = ImageColorsForIndex($image_new, $i); 
 	 	    	 $commongrey = (int)(($old[red] + $old[green] + $old[blue]) / 3);
 	 	     	ImageColorSet($image_new, $i, $commongrey, $commongrey, $commongrey); 
 	    	 } 		
		
		}
		
		
		
		if($miroir != ""){

			$largeur = imagesx($image_new);
			$hauteur = imagesy($image_new);

			$temporaireUn = imagecreatetruecolor($largeur, 50);
			$temporaireDeux = imagecreatetruecolor($largeur, 50);
			$resultat = imagecreatetruecolor($largeur, $hauteur+50);

			$blancUn = imagecolorallocate($resultat, 255, 255, 255);
			imagefill($resultat, 1, 1, $blancUn);
			$blancDeux = imagecolorallocate($temporaireDeux, 255, 255, 255);
			imagefill($temporaireDeux, 1, 1, $blancDeux);

			imagecopy ($resultat, $image_new, 0, 0, 0, 0, $largeur, $hauteur);
			imagecopy ($temporaireUn, $image_new, 0, 0, 0, $hauteur-50, $largeur, 50);
			imageflip($temporaireDeux, $temporaireUn);
			imagecopy ($resultat, $temporaireDeux, 0, $hauteur, 0, 0, $largeur, 50);
			$image_new = $resultat;
			imagejpeg($resultat, null, 100);
		
		}
		
		
     if(strtolower($extension) == "gif"){
     			 imagegif($image_new, "../$nomcache", 100);
                 imagegif($image_new, null, 100);
     }            
     
     else if(strtolower($extension) == "jpg"){
           		 imagejpeg($image_new, "../$nomcache", 100);
                 imagejpeg($image_new, null, 100);
     }            

        else if(strtolower($extension) == "png"){
           		 imagepng($image_new, "../$nomcache", 100);
                 imagepng($image_new, null, 100);
     }   
     
  }
 
 ?>