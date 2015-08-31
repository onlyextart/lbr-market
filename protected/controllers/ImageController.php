<?php

class ImageController extends Controller 
{    
    public static function saveImage($image, $folder = '/images/bestoffer/') 
    {
        $filePath = '';
        if (isset($image)) {
            $filePath = $folder.$image->name;
            // save and adapt image size
            $image->saveAs(Yii::getPathOfAlias('webroot').$filePath);
            ImageController::adapt($filePath);
        }
        
        return $filePath;
    }
    
    public function adapt($src) 
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . $src;
        if ((exif_imagetype($imagePath) == IMAGETYPE_JPEG)) {
            $image = imagecreatefromjpeg($imagePath);
            if ($image === false) {
                return false;
            }
            
            imagejpeg($image, $imagePath);
            imagedestroy($image);
        }
    }
}
