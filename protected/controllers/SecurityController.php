<?php

class SecurityController extends Controller {

    public static function encrypt($text, $key = '!@#$%^&*') {
        /* $data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
          return strtr($data, array(
          '+' => '.',
          '=' => '-',
          '/' => '~'
          )); */
        return strtr(base64_encode($text), array(
            '+' => '.',
            '=' => '-',
            '/' => '~'
        ));
    }

    public static function decrypt($text, $key = '!@#$%^&*') {
        /*$data = strtr($text, array(
            '.' => '+',
            '-' => '=',
            '~' => '/'
        ));
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND));
        */
        return base64_decode(strtr($text, array(
            '.' => '+',
            '-' => '=',
            '~' => '/'
        )));
    }
}
