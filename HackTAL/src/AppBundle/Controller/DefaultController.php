<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Regex;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StanfordTagger\POSTagger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //séparation des comments
        $path = $this->get('kernel')->getRootDir() . '/../web/hackatal2017-resume-data/train/43.json';
        $save = file_get_contents($path);
        $savedData = json_decode($save, true);
        $clean = new Regex();
        $prep = [
            ' a ',
            ' dans ',
            ' par ',
            ' pour ',
            ' en ',
            ' vers ',
            ' avec ',
            ' de ',
            ' sans ',
            ' sous ',
            ' un ',
            ' et ',
            ' ou ',
            ' le ',
            ' une ',
            ' au ',
            '.',
            '...',
            '!'
        ];

        $j = 0;
        for ($i = 0; $i < count($savedData['reviews']); $i++) {
            if ($savedData['reviews'][$i]['lang'] == 'french') {
                $dataFR[$j]['text'] = str_replace($prep,' ',$savedData['reviews'][$i]['text']);
                $dataFR[$j]['name'] = $savedData['reviews'][$i]['name'];
                $dataFR[$j]['date'] = $savedData['reviews'][$i]['date'];
                $j++;
            }
            if ($savedData['reviews'][$i]['lang'] == 'english') {
                $dataEN[$j]['text'] = str_replace($prep,' ',$savedData['reviews'][$i]['text']);
                $dataEN[$j]['name'] = $savedData['reviews'][$i]['name'];
                $dataEN[$j]['date'] = $savedData['reviews'][$i]['date'];
                $j++;
            }
        }

        $pos = new POSTagger();
        $pos->setModel('../vendor/patrickschur/stanford-postagger-full-2016-10-31/models/french.tagger');
        $pos->setJarArchive('../vendor/patrickschur/stanford-postagger-full-2016-10-31/stanford-postagger.jar');

        foreach ($dataFR as $key => $value) {
//            $test = explode(' ',$pos->tag($value['text']));
            $keywords[] = preg_split("/[_]+|[\s,]+/", $pos->tag($value['text']));
        }
        $k = 0;

        foreach ($keywords as $values) {

            for ($i = 0; $i < count($values) -1; $i = $i + 2) {
                $j = $i + 1;
                $array[$k][$clean->grandNettoyage($values[$i])] = $values[$j];
            }
            $k++;
        }

        var_dump($keywords);
        die();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }
}
