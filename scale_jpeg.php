<?PHP
/**
 * um alle Bilder die über den Medienmanager hochgeladen wurde im Nachgang in einem rutsch zu verkleinern (Dimension) ist folgendes Script.
 * Es werden nur Bilder vom Typ JPEG gewandelt.
 * Man könnte die maximale Breite des neuen Bildes ändern. Es werden nur Bilder bearbeitet die größer als dieser Wert sind und nicht vom Type {filename}@2x.jpg -> das sind nämlich Bilder die für hochauflösende Displays bereit gestellt werden und diese wurden vom System generiert und sollten so bleiben. 
 * 
 * das Script wird einfach in diesem Ordner hier per
 *          php -f scale_jpeg.php 
 * aufgerufen
 * Je nach dem wie viele Bilder betroffen sind kann das eine ganze Weile dauern. Einfach laufen lassen.
 * 
 */

$image_dir = '../media/image/';
$width = 1980; // maximale Breite des neuen Bildes

function scandirs($subdir){
    global $width;
    foreach( scandir($subdir) as $dir){
        if( $dir !="." and $dir!=".."){
            if(is_dir($subdir.'/'.$dir)){
                    scandirs($subdir.'/'.$dir);
                    }else{
                        $image = @getimagesize($subdir.'/'.$dir);
                        if( $image and $image[0] > $width and $image['mime'] == 'image/jpeg' and strpos($dir,"2x.jpg" ) === false){
                         
                                $new_image = $subdir.'/'.$dir;
                                copy($subdir.'/'.$dir,$subdir.'/bak_'.$dir);
                            
                                $height = ($image[1]/$image[0]) * $width;
                                $old_image = imagecreatefromjpeg($subdir.'/bak_'.$dir); 
                                $new_imag = imagecreatetruecolor($width, $height);
                                imagecopyresampled($new_imag, $old_image, 0, 0, 0, 0, $width, $height, $image[0], $image[1]);
                                imagejpeg($new_imag, $new_image);
                                imagedestroy($new_imag); 
                                unlink($subdir.'/bak_'.$dir);
                                echo $subdir.'/'.$dir ."(".$image[0]."x".$image[1]." neu ". $width."x".round($height) ." )\n"; 
                            
                        }
                   
                    }
        }
       
    }
}

$scan_dir = scandir($image_dir);
foreach( $scan_dir as $dir){ if( $dir !="." and $dir!=".."){
      if(is_dir($image_dir.$dir)){ 
            scandirs($image_dir.$dir);
        }
}
  
}





?>