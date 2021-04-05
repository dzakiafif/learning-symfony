<?php

namespace App\Libraries;

class GeneralLib
{
    public static function convertPhoneNumber($number = '',$code='62')
    {
        //remove space ( ) . +
        $number = trim(str_replace([" ", "(", ")", ".", "+"], "", $number));

        //check if number without code $code
        if (substr($number, 0, strlen($code)) != $code) 
        {
            //check if first number '0'
            if (substr($number, 0, 1) == '0')
                $number = $code . substr($number, 1);
        }
        
        return $number;
        
    }
}