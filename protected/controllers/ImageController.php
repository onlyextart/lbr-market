<?php

class ImageController extends Controller 
{
    public static function adapt($src) 
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
