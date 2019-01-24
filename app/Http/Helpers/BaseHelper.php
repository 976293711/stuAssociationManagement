<?php


namespace App\Http\Helpers;


class BaseHelper
{
    public static function urandom($len){
        $fp = @fopen('/dev/urandom', 'rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        } else {
            trigger_error('Can not open /dev/urandom.');
        }

        // convert from binary to string
        $result = base64_encode($result);
        // remove none url chars
        $result = strtr($result, '+/', '-_');
        return substr($result, 0, $len);
    }
}