<?php
namespace App\Utility;

class DictionaryRegex
{

    public static function mask($incoming)
    {
        $result = self::hide_email($incoming);
        $result = self::hide_phone($result);
        return $result;
    }

    private static function hide_email($incoming)
    {
        $pattern = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
        // to do: check for variations

        $replacement = ' ADRESA E-MAIL ASCUNSA ';

        $result = preg_replace($pattern, $replacement, $incoming);

        return $result;
    }

    private static function hide_phone($incoming)
    {
        // $pattern1 = '^[\.-)( ]*([0-9]{3})[\.-)( ]*([0-9]{3})[\.-)( ]*([0-9]{4})$';
        $pattern1 = '/^([0-9]+( [0-9]+)+)$/i';
        // $pattern2 = '/(\+4|)?(07[0-8]{1}[0-9]{1}|02[0-9]{2}|03[0-9]{2}){1}?(\s|\.|\-|\_|)?([0-9]{3}(\s|\.|\-|)){2}/';
        $pattern2 = '/(\+4|)?(07[0-8]{1}[0-9]{1}|02[0-9]{2}|03[0-9]{2}){1}?(\s|\.|\-)?([0-9]{3}(\s|\.|\-|,|)){2}|([0-9]{2}(\s|\.|\-|,|)){3}|([0-9]{2}(\s|\.|\-|,|)){4}/';

        $pattern = '/' . $pattern1 . '|' . $pattern2 . '/';
        // to do: check for variations

        $replacement = ' ******** ';

        $result = preg_replace($pattern2, $replacement, $incoming);

        return $result;
    }
}
