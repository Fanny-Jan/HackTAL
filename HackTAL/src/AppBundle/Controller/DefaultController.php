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
        $path = $this->get('kernel')->getRootDir() . '/../web/hackatal2017-resume-data/train/49.json';
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
            'abrupt',
            'accro',
            'accusateur',
            'acerbe',
            'agressif',
            'aigri',
            'amateur',
            'amorphe',
            'angoissé',
            'anxieux',
            'arbitraire',
            'arriviste',
            'arrogant',
            'associable',
            'asocial',
            'assisté',
            'autoritaire',
            'avare',
            'bagarreur',
            'baratineur',
            'bavard',
            'blasé',
            'blessant',
            'borné',
            'boudeur',
            'brouillon',
            'brute',
            'bruyant',
            'cachottier',
            'calculateur',
            'capricieux',
            'caractériel',
            'caricatural',
            'carriériste',
            'cassant',
            'casse-cou',
            'catastrophiste',
            'caustique',
            'censeur',
            'coléreux',
            'colérique',
            'complexé',
            'compliqué',
            'confus',
            'crédule',
            'cruel',
            'cynique',
            'débordé',
            'défaitiste',
            'dépensier',
            'désinvolte',
            'désobéissant',
            'désordonné',
            'désorganisé',
            'diabolique',
            'distrait',
            'douteux',
            'douteuse',
            'docile',
            'dominateur',
            'dragueur',
            'égocentrique',
            'égoïste',
            'emotif',
            'enigmatique',
            'entêté',
            'envahissant',
            'envieux',
            'étourdi',
            'excentrique',
            'excessif',
            'fainéant',
            'familier',
            'fantasque',
            'fataliste',
            'froid',
            'grossier',
            'hautain',
            'hésitant',
            'humiliant',
            'hypocrite',
            'imbu de lui-même',
            'immature',
            'impatient',
            'imprudent',
            'impulsif',
            'inaccessible',
            'inattentif',
            'incompétent',
            'inconstant',
            'inculte',
            'indécis',
            'indiscret',
            'indomptable',
            'influençable',
            'insatisfait',
            'insignifiant',
            'insouciant',
            'instable',
            'intéressé',
            'intolérant',
            'intransigeant',
            'introverti',
            'ironique',
            'irréaliste',
            'irrespectueux',
            'irresponsable',
            'jaloux',
            'joueur',
            'laxiste',
            'lent',
            'lunatique',
            'macho',
            'magnanime',
            'mal à l’aise',
            'mal élevé',
            'maladroit',
            'malhonnête',
            'maniaque',
            'maniéré',
            'manipulateur',
            'méchant',
            'médiocre',
            'médisant',
            'méfiant',
            'mégalomane',
            'menteur',
            'méprisant',
            'mesquin',
            'misogyne',
            'moqueur',
            'mou',
            'muet',
            'mystérieux',
            'mythomane',
            'naïf',
            'narcissique',
            'négatif',
            'négligeant',
            'nerveux',
            'nonchalant',
            'obstiné',
            'obtus',
            'odieux',
            'opiniâtre',
            'orgueilleux',
            'paresseux',
            'passif',
            'pédant',
            'persécuteur',
            'pervers',
            'pessimiste',
            'peureux',
            'plaintif',
            'possessif',
            'présomptueux',
            'prétentieux',
            'procrastinateur',
            'profiteur',
            'provocateur',
            'puéril',
            'raciste',
            'radin',
            'râleur',
            'rancunier',
            'rebelle',
            'renfermé',
            'réservé',
            'résigné',
            'rétrograde',
            'revanchard',
            'revêche',
            'révolté',
            'rigide',
            'ringard',
            'routinier',
            'sale',
            'sans gêne',
            'sarcastique',
            'secret',
            'sensible',
            'solitaire',
            'sombre',
            'soupçonneux',
            'sournois',
            'stressé',
            'strict',
            'stupide',
            'suffisant',
            'superficiel',
            'susceptible',
            'tatillon',
            'tempétueux',
            'têtu',
            'timide',
            'triste',
            'vaniteux',
            'versatile',
            'vulgaire',
            'approximatif',
            'banal',
            'bas',
            'bof',
            'bouleversant',
            'boute-en-train',
            'captivant',
            'cataclysmique',
            'catastrophique',
            'commun',
            'médiocre',
            'merdique',
            'minable',
            'moyen',
            'négligeable',
            'nul',
            'ordinaire',
            'original',
            'pietre',
            'passable',
            'passionnant',
            'percutant',
            'correct',
            'cynique',
            'dégueulasse',
            'délectable',
            'disjoncté',
            'ennuyant',
            'enragé',
            'épouvantable',
            'quelconque',
            'horrible',
            'infernal',
            'innommable',
            'insignifiant',
            'insuffisant',
            'insupportable',
            'intolérable',
            'tragique',
            'trépidant',
            'troublant',
            'vulgaire',
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
            for ($word = 0; $word <= count($values) - 1; $word = $word + 2) {
                $type = $word + 1;
                $array[$k][$nbWords][0] = $values[$type];
                $array[$k][$nbWords][1] = $clean->grandNettoyage($values[$word]);
                if ($values[$type] == 'ADJ' && in_array($values[$word], $negatif)) {
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
                            || $values[$typePrev] == 'V'
                        ) {
                            $array[$k][$nbWords][2] = $values[$wordPrev] . ' ' .$array[$k][$nbWords][2];
                        }

                        if ($values[$typeNext] == 'N' || $values[$typeNext] == 'NC'
                            || $values[$typeNext] == 'NPP' || $values[$typeNext] == 'VINF'
                            || $values[$typeNext] == 'V'
                        ) {

                            $array[$k][$nbWords][2] .= ' ' . $values[$wordNext];
                        }
                    }
                }
                $nbWords++;
            }
            $k++;
        }


        var_dump($array);
        die();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }
}
