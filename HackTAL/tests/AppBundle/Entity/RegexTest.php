<?php
/**
 * Created by PhpStorm.
 * User: fanny
 * Date: 24/06/17
 * Time: 21:58
 */

namespace Tests\AppBundle\Entity;



use AppBundle\Entity\Regex;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RegexTest extends TestCase
{
    public function testLower()
    {
        $chaine = new Regex;

        $this->assertEquals('test', $chaine->lowerAction('TEST'));
        $this->assertEquals('test de ma super chaine', $chaine->lowerAction('TesT De Ma SuPeR ChaiNe'));
    }


//    public function testPrep()
//    {
//        $chaine = new Regex;
//
//        $this->assertEquals('chaton trop mignon est couleur noire', $chaine->prepAction('le chaton trop mignon est de couleur noire'));
//    }

    public function testAccent()
    {
        $chaine = new Regex;

        $this->assertEquals('e e a a o', $chaine->accentAction('é è à a ô'));
        $this->assertEquals('mon chateau est genereusement installe a cote d\'un hopital', $chaine->accentAction('mon château est généreusement installé à côté d\'un hôpital'));
    }

    public  function testGrandNettoyage()
    {
        $chaine = new Regex;

        $this->assertEquals('le lion est mort se soir', $chaine->grandNettoyage('Le lion est môrt se soir'));
    }
}