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
        $path = $this->get('kernel')->getRootDir() . '/../web/hackatal2017-resume-data/train/75.json';
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
        $negatif = [
            'accro' => 2,
            'accusateur' => 2,
            'acerbe' => 2,
            'agressif' => 2,
            'aigri' => 2,
            'amateur' => 2,
            'amorphe' => 2,
            'angoisse' => 2,
            'anxieux' => 2,
            'arrogant' => 2,
            'associable' => 2,
            'asocial' => 2,
            'assiste' => 2,
            'autoritaire' => 2,
            'avare' => 2,
            'bagarreur' => 2,
            'baratineur' => 2,
            'bavard' => 2,
            'blase' => 2,
            'blessant' => 2,
            'borne' => 2,
            'boudeur' => 2,
            'brouillon' => 6,
            'brute' => 2,
            'bruyant' => 3,
            'cachottier' => 2,
            'calculateur' => 2,
            'capricieux' => 2,
            'caracteriel' => 2,
            'caricatural' => 6,
            'carrieriste' => 2,
            'cassant' => 2,
            'catastrophiste' => 1,
            'confus' => 6,
            'deborde' => 4,
            'desordonne' => 4,
            'desorganise' => 4,
            'douteux' => 1,
            'douteuse' => 1,
            'envahissant' => 2,
            'excentrique' => 3,
            'excessif' => 5,
            'froid' => 1,
            'inaccessible' => 3,
            'inapproprie' => 3,
            'insatisfait' => 1,
            'interesse' => 3,
            'irrespectueux' => 2,
            'lent' => 4,
            'maniaque' => 1,
            'mediocre' => 3,
            'mou' => 3,
            'negatif' => 1,
            'negligeant' => 1,
            'poussiereux' => 1,
            'radin' => 2,
            'raleur' => 2,
            'renferme' => 1,
            'rigide' => 3,
            'ringard' => 3,
            'sale' => 1,
            'sombre' => 3,
            'superficiel' => 1,
            'approximatif' => 6,
            'bof' => 3,
            'merdique' => 3,
            'minable' => 3,
            'moyen' => 3,
            'neglige' => 1,
            'nul' => 3,
            'pietre' => 1,
            'passable' => 1,
            'degueulasse' => 1,
            'epouvantable' => 1,
            'quelconque' => 3,
            'horrible' => 3,
            'infernal' => 3,
            'innommable' => 1,
            'insuffisant' => 3,
            'vulgaire' => 2,
            'delabre' => 3,
            'climatise' => 3,
            'vieux' => 3,
            'humide' => 3,
            'degoutant' => 1,
            'vetuste' => 3,
            'minuscule' => 3,
            'cher' => 5,
            'couteux' => 5,
            'onereux' => 5,
            'exorbitant' => 5,
            'inabordable' => 5,
        ];

        $type = 0;
        for ($word = 0; $word < count($savedData['reviews']); $word++) {
            if ($savedData['reviews'][$word]['lang'] == 'french') {
                $dataFR[$type]['text'] = str_replace($prep, ' ', $savedData['reviews'][$word]['text']);
                $dataFR[$type]['name'] = $savedData['reviews'][$word]['name'];
                $dataFR[$type]['date'] = $savedData['reviews'][$word]['date'];
                $type++;
            }
            if ($savedData['reviews'][$word]['lang'] == 'english') {
                $dataEN[$type]['text'] = str_replace($prep, ' ', $savedData['reviews'][$word]['text']);
                $dataEN[$type]['name'] = $savedData['reviews'][$word]['name'];
                $dataEN[$type]['date'] = $savedData['reviews'][$word]['date'];
                $type++;
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
        $nbWords = 0;

        foreach ($keywords as $values) {
            $m = 0;
            for ($word = 0; $word <= count($values) - 1; $word = $word + 2) {
                $type = $word + 1;
                $array[$k][$nbWords][0] = $values[$type];
                $array[$k][$nbWords][1] = $clean->grandNettoyage($values[$word]);

                if ($values[$type] == 'ADJ' && array_key_exists($values[$word], $negatif)) {
                    $array[$k][$nbWords][2] = $values[$word];

                    for ($l = 2; $l <= 4; $l = $l + 2) {
                        if ($word == count($values) - 2) {
                            $typeNext = $word + 1;
                        } elseif ($word == count($values) - 4) {
                            $typeNext = $word + 1;
                        } else {
                            $typeNext = $type + $l;
                        }
                        $typePrev = $type - $l;
                        $wordPrev = $typePrev - 1;
                        $wordNext = $typeNext - 1;
                        if ($values[$typePrev] == 'N' || $values[$typePrev] == 'NC'
                            || $values[$typePrev] == 'NPP' || $values[$typePrev] == 'VINF'
                            || $values[$typePrev] == 'V' || $values[$typePrev] == 'ADV'
                        ) {
                            $array[$k][$nbWords][2] = $values[$wordPrev] . ' ' . $array[$k][$nbWords][2];
                        }
                        if ($values[$typeNext] == 'N' || $values[$typeNext] == 'NC'
                            || $values[$typeNext] == 'NPP' || $values[$typeNext] == 'VINF'
                            || $values[$typeNext] == 'V' || $values[$typeNext] == 'ADV'
                        ) {

                            $array[$k][$nbWords][2] .= ' ' . $values[$wordNext];
                        }
                    }
                    $comNeg[$k][$m] = $array[$k][$nbWords][2];
                    $m++;
                }
                $nbWords++;
            }
            $k++;
        }
        var_dump($comNeg);
        die();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }
}
