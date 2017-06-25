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
        $path = $this->get('kernel')->getRootDir() . '/../web/hackatal2017-resume-data/train/test.json';
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
            'agressif' => 2,
            'amateur' => 2,
            'angoisse' => 2,
            'arrogant' => 2,
            'associable' => 2,
            'asocial' => 2,
            'assiste' => 2,
            'autoritaire' => 2,
            'avare' => 2,
            'baratineur' => 2,
            'bavard' => 2,
            'blase' => 2,
            'blessant' => 2,
            'borne' => 2,
            'brute' => 2,
            'bruyant' => 3,
            'cachottier' => 2,
            'calculateur' => 2,
            'capricieux' => 2,
            'caricatural' => 6,
            'cassant' => 2,
            'casse' => 2,
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
//            'vieux' => 3,
            'humide' => 3,
            'degoutant' => 1,
            'vetuste' => 3,
            'minuscule' => 3,
            'cher' => 5,
            'couteux' => 5,
            'onereux' => 5,
            'exorbitant' => 5,
            'inabordable' => 5,
            'glauque' => 3,
            'abime' => 1,
            'fissure' => 1,
            'lugubre' => 3,
            'friable' => 1,
            'erreur' => 6,
            'mauvais' => 1,
            'humidite' => 1,
            'immonde' => 1,
            'malpropre' => 1,
            'laid' => 3,
            'repugant' => 1,
            'saccage' => 3,
            'desagrable' => 2,
            'chaud' => 3,
            'inhabitable' => 3,
            'indecent' => 3,
            'insalubre' => 1,
            'degrade' => 1,
            'defectueux' => 1,
            'defectueuse' => 1,
            'dangereuse' => 3,
            'dangereux' => 3,
            'inutilisable' => 3,
            'bruyante' => 3,
            'degueux' => 1,
        ];

        $negatifNom =[
            'infiltration' => 1,
            'moisissure' => 1,
            'insecte' => 1,
            'cafard' => 1,
            'hygiene' => 1,
            'fumee' => 1,
            'fumeur' => 1,
            'odeur' => 1,
            'tabac' => 1,
            'salete' => 1,
            'poussiere' => 1,
            'ordure' => 1,
            'fuite' => 1,
            'bruit' => 3,
            'wifi' => 3,
            'wi-fi' => 3,
            'humidite' => 1,
            'insalubrite' => 1,
            'cheveux' => 1,
            'poils' => 1,
       ];

        $positif = [
            'lumineux' => 3,
            'lumineuse' => 3,
            'degagee' => 3,
            'spacieux' => 3,
            'spacieuse' => 3,
            'eclaire' => 3,
            'eclairee' => 3,
            'agreable' => 3,
            'confortable' => 3,
            'pratique' => 3,
            'impeccable' => 3,
            'tranquille' => 3,
            'calme' => 3,
            'irreprochable' => 1,
            'raffine' => 3,
            'raffinee' => 3,
            'ensoleille' => 3,
            'illumine' => 3,
            'radieux' => 3,
            'grand' => 3,
            'immense' => 3,
            'doux' => 3,
            'douce' => 3,
            'clair' => 3,
            'claire' => 3,
            'conforme' => 6,
            'accueillant' => 2,
            'accueillante' => 2,
            'serviable' => 2,
            'arrangeant' => 2,
            'incroyable' => 3,
            'gentille' => 2,
            'gentil' => 2,
            'communicant' => 2,
            'silencieux' => 3,
            'silencieuse' => 3,
            'disponible' => 2,
            'parfait' => 3,
            'fonctionnel' => 3,
            'fonctionnelle' => 3,
            'satisfaisant' => 3,
            'aeree' => 3,
            'aere' => 3,
        ];

        $double = [
            'cher' => 5,
            'chere' => 5,
            'propre' => 1,
            'agencee' => 3,
            'agence' => 3,
            'structure' => 3,
            'structuree' => 3,
        ];

        $adv = [
            'absolument' => 2,
            'assez' => 4,
            'beaucoup' => 3,
            'completement' => 2,
            'extremement' => 1,
            'fort' => 3,
            'fortement' => 2,
            'grandement' => 2,
            'moins' => 4,
            'passablement' => 3,
            'peu' => 3,
            'plus' => 3,
            'plutot' => 4,
            'presque' => 4,
            'quasi' => 4,
            'quasiment' => 4,
            'quelque' => 4,
            'tellement' => 3,
            'terriblement' => 2,
            'totalement' => 2,
            'tout' => 3,
            'tres' => 3,
            'trop' => 2
        ];

        $type = 0;
        for ($word = 0; $word < count($savedData['reviews']); $word++) {
            if ($savedData['reviews'][$word]['lang'] == 'french') {
                $dataFR[$type]['text'] = str_replace($prep, ' ', $savedData['reviews'][$word]['text']);
                $dataFR[$type]['name'] = $savedData['reviews'][$word]['name'];
                $dataFR[$type]['date'] = $savedData['reviews'][$word]['date'];
                $type++;
            }
//            if ($savedData['reviews'][$word]['lang'] == 'english') {
//                $dataEN[$type]['text'] = str_replace($prep, ' ', $savedData['reviews'][$word]['text']);
//                $dataEN[$type]['name'] = $savedData['reviews'][$word]['name'];
//                $dataEN[$type]['date'] = $savedData['reviews'][$word]['date'];
//                $type++;
//            }
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

                if (array_key_exists($values[$word], $negatif) || in_array($values[$word], $negatifNom)) {
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
                        // Analyse des mot précédent le tag
                        if ($values[$typePrev] == 'N' || $values[$typePrev] == 'NC'
                            || $values[$typePrev] == 'NPP' || $values[$typePrev] == 'VINF'
                            || $values[$typePrev] == 'V' || $values[$typePrev] == 'ADV'
                        ) {
                            $array[$k][$nbWords][2] = $values[$wordPrev] . ' ' . $array[$k][$nbWords][2];
                        }

                        // Analyse des mots suivant le tag
//                        if ($values[$typeNext] == 'N' || $values[$typeNext] == 'NC'
//                            || $values[$typeNext] == 'NPP' || $values[$typeNext] == 'VINF'
//                            || $values[$typeNext] == 'V' || $values[$typeNext] == 'ADV'
//                        ) {
//                            $array[$k][$nbWords][2] .= ' ' . $values[$wordNext];
//                        }
                    }
                    $comNeg[$k][$m][0] = $array[$k][$nbWords][2];
                    if (isset($negatif[$values[$word]])) {
                        $comNeg[$k][$m][1] = $negatif[$values[$word]];
                    } else {
                        $comNeg[$k][$m][1] = $negatifNom[$values[$word]];
                    }

                    $m++;
                }
                $nbWords++;
            }
            $k++;
        }
//        var_dump($comNeg);
//        die();
        // replace this example code with whatever you need
        return $this->render('AppBundle::index.html.twig', [
            'comNeg' => $comNeg,
        ]);
    }
}
