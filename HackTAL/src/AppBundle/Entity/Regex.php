<?php
/**
 * Created by PhpStorm.
 * User: fanny
 * Date: 24/06/17
 * Time: 21:57
 */

namespace AppBundle\Entity;


class Regex
{
    public function lowerAction($string)
    {
        $string = strtolower($string);
        return $string;
    }

    public  function prepAction($string)
    {
        $pattern = "#^(de|des|le|la|les[ ])|[l|d]'|([l|d]')|([ ](de|des|le|la|les)[ ])#mUis";

       $result = preg_replace($pattern, " ", $string);
       return trim($result);
    }

    public function accentAction($string)
    {
        $accents = Array("/é/", "/è/", "/ê/","/ë/", "/ç/", "/à/", "/â/","/á/","/ä/","/ã/","/å/", "/î/", "/ï/", "/í/", "/ì/", "/ù/", "/ô/", "/ò/", "/ó/", "/ö/");
        $sans = Array("e", "e", "e", "e", "c", "a", "a","a", "a","a", "a", "i", "i", "i", "i", "u", "o", "o", "o", "o");

        $string = preg_replace($accents, $sans,$string);
//        $string = preg_replace('#[^A-Za-z0-9]#','',$string);

        return $string;
    }

    public function grandNettoyage($string)
    {
        $string = strtolower($string);

//        $preposition = "#^(de|des|le|la|les[ ])|[l|d]'|([l|d]')|([ ](de|des|le|la|les)[ ])#mUis";
//
//        $string = preg_replace($preposition, " ", $string);

        $accents = Array("/é/", "/è/", "/ê/","/ë/", "/ç/", "/à/", "/â/","/á/","/ä/","/ã/","/å/", "/î/", "/ï/", "/í/", "/ì/", "/ù/", "/ô/", "/ò/", "/ó/", "/ö/");
        $sans_accents = Array("e", "e", "e", "e", "c", "a", "a","a", "a","a", "a", "i", "i", "i", "i", "u", "o", "o", "o", "o");

        $string = preg_replace($accents, $sans_accents,$string);

        return $string;
    }
}