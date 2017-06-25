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

        $j = 0;
        for ($i = 0; $i < count($savedData['reviews']); $i++) {
            if ($savedData['reviews'][$i]['lang'] == 'french') {
                $dataFR[$j]['text'] = str_replace($prep, ' ', $savedData['reviews'][$i]['text']);
                $dataFR[$j]['name'] = $savedData['reviews'][$i]['name'];
                $dataFR[$j]['date'] = $savedData['reviews'][$i]['date'];
                $j++;
            }
            if ($savedData['reviews'][$i]['lang'] == 'english') {
                $dataEN[$j]['text'] = str_replace($prep, ' ', $savedData['reviews'][$i]['text']);
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

            for ($i = 0; $i < count($values) - 1; $i = $i + 2) {
                $j = $i + 1;
                $array[$k][$clean->grandNettoyage($values[$i])] = $values[$j];
                if ($values[$j] == "ADJ" AND in_array($values[$i],$negatif)) {
                    var_dump($values[$i]);
                    die();
                }
            }
            $k++;
        }

        var_dump($keywords);
        die();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }
}
