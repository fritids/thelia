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

// redimensionnement des images + effets

include_once("divers.php");

// Déclaration des variables
if(isset($_REQUEST['nomorig'])) 
	$nomorig = $_REQUEST['nomorig'] ; else  $nomorig = "";
if(isset($_REQUEST['height'])) 
	$height = $_REQUEST['height'] ; else  $height = "";
if(isset($_REQUEST['width'])) 
	$width=$_REQUEST['width']; else $width="";
if(isset($_REQUEST['opacite'])) 
	$opacite=$_REQUEST['opacite']; else $opacite="";
if(isset($_REQUEST['nb'])) 
	$nb=$_REQUEST['nb']; else $nb="";
if(isset($_REQUEST['miroir'])) 
	$miroir=$_REQUEST['miroir']; else $miroir="";

// function de retournement d'image	
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


 if (file_exists($nomorig) || url_exists($nomorig))
 {
 		$extension = substr($nomorig, strlen($nomorig)-3);
 
 		if(strstr($nomorig, "client/gfx/photos/rubrique")) $type = "rubrique";
 		else if(strstr($nomorig, "client/gfx/photos/produit")) $type = "produit";
 		else if(strstr($nomorig, "client/gfx/photos/dossier")) $type = "dossier";
 		else if(strstr($nomorig, "client/gfx/photos/contenu")) $type = "contenu";
  		
        preg_match("/([^\/]*.(jpg|gif|png))/", $nomorig, $nsimple);
 		
  		$nomcache = "client/cache/" . $type . "/" . $width . "_" . $height . "_" . $opacite . "_" . $nb . "_" . $miroir . "_" . $nsimple[1];
 		
 		if(file_exists("../$nomcache")) { header("Location: ../$nomcache"); exit; }
 		
  	   // Cacul des nouvelles dimensions
	      list($width_orig, $height_orig) = getimagesize($nomorig);


			 // si l'image est plus grande
			$image_p_largeur = "";
			$image_p_hauteur = "";

			if ( ($width_orig > $width) || ($height_orig > $height) )
			{
				if (($width_orig > $width) && $width!="")
				{
					# Calcul 1 : la largeur
					$facteur_div = $width_orig / $width ;

					$image_p_largeur = $width ; /* Nouvelle largeur */
					$image_p_hauteur = $height_orig / $facteur_div ; /* Nouvelle hauteur */
				}
				else {
					$image_p_largeur = $width_orig ; /* Nouvelle largeur */
					$image_p_hauteur = $height_orig ; /* Nouvelle hauteur */
				}

				if (($image_p_hauteur > $height) && $height!="")
				{
					# Calcul 2 : la hauteur
					$facteur_div = $image_p_hauteur / $height ;

					$image_p_largeur = $image_p_largeur / $facteur_div ; /* Nouvelle largeur */
					$image_p_hauteur = $height ; /* Nouvelle hauteur */			
				}
				$width = $image_p_largeur ;
				$height = $image_p_hauteur ;
			}

			else
			{
				$width = "";
				$height = "";
			}

			if($width=="") $width=$width_orig;
			if($height=="") $height=$height_orig;

	         $image_new = imagecreatetruecolor($width, $height);
     

   // chargement img origine
   if(strtolower($extension) == "gif"){
        $image_orig = imagecreatefromgif($nomorig);
   }
   else if(strtolower($extension) == "jpg" || strtolower($extension) == "png"){
       
        if(strtolower($extension) == "jpg") 
			$image_orig = imagecreatefromjpeg($nomorig);
		else if(strtolower($extension) == "png") {
			$image_orig = imagecreatefrompng($nomorig);
		
            $trnprt_indx = imagecolortransparent($image_orig);

            // If we have a specific transparent color
            if ($trnprt_indx >= 0) {

                // Get the original image's transparent color's RGB values
                $trnprt_color    = imagecolorsforindex($image_orig, $trnprt_indx);

                // Allocate the same color in the new image resource
                $trnprt_indx    = imagecolorallocate($image_new, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

                // Completely fill the background of the new image with allocated color.
                imagefill($image_new, 0, 0, $trnprt_indx);

                // Set the background color for new image to transparent
                imagecolortransparent($image_new, $trnprt_indx);


            }

			else {
             // Turn off transparency blending (temporarily)
                imagealphablending($image_new, false);

                // Create a new transparent color for image
                $color = imagecolorallocatealpha($image_new, 0, 0, 0, 127);

                // Completely fill the background of the new image with allocated color.
                imagefill($image_new, 0, 0, $color);

                // Restore transparency blending
                imagesavealpha($image_new, true);				
				
			}
		 }

		}

		
		if($opacite != ""){
			$opac_img = imagecreatetruecolor($width_orig, $height_orig);
			$white = ImageColorAllocate ($opac_img, 255, 255, 255);

			imagefill($opac_img, 0, 0, $white);

			imagecopymerge($opac_img, $image_orig, 0,0,0,0, $width_orig, $height_orig, $opacite);
        
        	$image_orig = $opac_img;
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
         imagepng($image_new, "../$nomcache");
         imagepng($image_new);
     }   
    
  }

 ?>