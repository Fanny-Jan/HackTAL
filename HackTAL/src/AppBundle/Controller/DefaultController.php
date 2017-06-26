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
        $path = $this->get('kernel')->getRootDir() . '/../web/hackatal2017-resume-data/validation/68.json';

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
            'bruyant' => 5,
            'bruyante' => 5,
            'bruyantes' => 5,
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
            'poussiereuses' => 1,
            'poussiereuse' => 1,
            'radin' => 2,
            'raleur' => 2,
            'renferme' => 1,
            'rigide' => 3,
            'ringard' => 3,
            'sale' => 1,
            'sales' => 1,
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
            'vieilles' => 6,
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
            'desagreable' => 2,
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
            'degueux' => 1,
            'dechire' => 1,
            'crasseux' => 1,
            'crasseuses' => 1,
            'crasseuse' => 1,
            'déconseille' => 5,
            'reveille' => 5,
            'probleme' => 6
        ];

        $negatifNom = [
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
            'bruit' => 5,
            'wifi' => 5,
            'wi-fi' => 3,
            'humidite' => 1,
            'insalubrite' => 1,
            'cheveux' => 1,
            'poils' => 1,
            'taches' => 1,
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
            'assez' => 3,
            'beaucoup' => 2,
            'completement' => 2,
            'extremement' => 1,
            'fort' => 1,
            'fortement' => 1,
            'grandement' => 1,
            'moins' => 2,
            'passablement' => 2,
            'peu' => 3,
            'plus' => 2,
            'plutot' => 3,
            'presque' => 3,
            'quasi' => 3,
            'quasiment' => 3,
            'quelque' => 3,
            'tellement' => 2,
            'terriblement' => 2,
            'totalement' => 2,
            'tout' => 3,
            'tres' => 1,
            'trop' => 1
        ];
        $categories = [
            1 => 'propreté',
            2 => 'communication',
            3 => 'emplacement',
            4 => 'arrivée',
            5 => 'qualité / prix',
            6 => 'précision',
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
            $nbCom = count($keywords);
            $m = 0;
            for ($word = 0; $word <= count($values) - 1; $word = $word + 2) {
                $type = $word + 1;
                $array[$k][$nbWords][0] = $values[$type];
                $values[$word] = $clean->grandNettoyage($values[$word]);
                $array[$k][$nbWords][1] = $values[$word];

                if (array_key_exists($clean->grandNettoyage($values[$word]), $negatif)
                    || array_key_exists($clean->grandNettoyage($values[$word]), $negatifNom)
                ) {
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
                    $comNeg[$k][$m]['tag'] = $clean->grandNettoyage($array[$k][$nbWords][2]);
                    if (isset($negatif[$values[$word]])) {
                        $comNeg[$k][$m]['cat'] = $negatif[$values[$word]];
                    } else {
                        $comNeg[$k][$m]['cat'] = $negatifNom[$values[$word]];
                    }

                    $expl = array_flip(explode(' ', $comNeg[$k][$m]['tag']));
                    $result = array_intersect_key($expl, $adv);

                    if ($result != null) {
                        $result = array_flip($result);
                        $comNeg[$k][$m]['rating'] = floatval($adv[current($result)]);
                    } else {
                        $comNeg[$k][$m]['rating'] = 3.5;
                    }

                    $m++;
                }
                $nbWords++;
            }

            $total1 = '';
            $nb1 = 0;
            $total2 = '';
            $nb2 = 0;
            $total3 = '';
            $nb3 = 0;
            $total4 = '';
            $nb4 = 0;
            $total5 = '';
            $nb5 = 0;
            $total6 = '';
            $nb6 = 0;


            for ($o = 0; $o <= count($comNeg[$k]); $o++) {
                if ($comNeg[$k][$o]['cat'] == 1) {
                    $total1 += floatval($comNeg[$k][$o]['rating']);
                    $nb1++;
                } elseif ($comNeg[$k][$o]['cat'] == 2) {
                    $total2 += floatval($comNeg[$k][$o]['rating']);
                    $nb2++;
                } elseif ($comNeg[$k][$o]['cat'] == 3) {
                    $total3 += floatval($comNeg[$k][$o]['rating']);
                    $nb3++;
                } elseif ($comNeg[$k][$o]['cat'] == 4) {
                    $total4 += floatval($comNeg[$k][$o]['rating']);
                    $nb4++;
                } elseif ($comNeg[$k][$o]['cat'] == 5) {
                    $total5 += floatval($comNeg[$k][$o]['rating']);
                    $nb5++;
                } elseif ($comNeg[$k][$o]['cat'] == 6) {
                    $total6 += floatval($comNeg[$k][$o]['rating']);
                    $nb6++;
                }
            }

            if ($total1 != 0) {
                $total1 = ($total1 / $nb1);
            } else {
                $total1 = '5';
            };
            if ($total2 != 0) {
                $total2 = $total2 / $nb2;
            } else {
                $total2 = '5';
            };
            if ($total3 != 0) {
                $total3 = $total3 / $nb3;
            } else {
                $total3 = '5';
            };
            if ($total4 != 0) {
                $total4 = $total4 / $nb4;
            } else {
                $total4 = '5';
            };
            if ($total5 != 0) {
                $total5 = $total5 / $nb5;
            } else {
                $total5 = '5';
            };
            if ($total6 != 0) {
                $total6 = $total6 / $nb6;
            } else {
                $total6 = '5';
            };

            $tag = '';
            if (isset($comNeg[$k])) {
                for ($p = 0; $p <= count($comNeg[$k]); $p++) {
                    $tag .= $comNeg[$k][$p]['tag'] . ',';
                }
            }
            $penality = 0;
            if (count($comNeg[$k]) >= 4 AND count($comNeg[$k]) < 6) {
                $penality = 0.5;
            } elseif (count($comNeg[$k]) >= 6) {
                $penality = 1;
            }

            $p1 = $p2 = $p3 = $p4 = $p5 = $p6 = 0;
            if ($nb1 >= 2 AND $nb1 < 4) {
                $p1 = 1;
            } elseif ($nb1 >= 4 AND $nb1 < 6) {
                $p1 = 1.5;
            } elseif ($nb1 >= 6) {
                $total1 = 0;
            }

            if ($nb2 >= 2 AND $nb2 < 4) {
                $p2 = 1;
            } elseif ($nb2 >= 4 AND $nb2 < 6) {
                $p2 = 1.5;
            } elseif ($nb2 >= 6) {
                $total2 = 0;
            }
            if ($nb3 >= 2 AND $nb3 < 4) {
                $p3 = 0.5;
            } elseif ($nb3 >= 4 AND $nb3 < 6) {
                $p3 = 1.0;
            } elseif ($nb3 >= 6) {
                $total3 = 0;
            }
            if ($nb4 >= 2 AND $nb4 < 4) {
                $p4 = 0.5;
            } elseif ($nb4 >= 4 AND $nb4 < 6) {
                $p4 = 1.0;
            } elseif ($nb4 >= 6) {
                $total4 = 4;
            }
            if ($nb5 >= 2 AND $nb5 < 4) {
                if ($penality == 0.5) {
                    $total5 = 3;
                    $p5 = 0.5;
                } elseif ($penality == 1) {
                    $total5 = 2;
                    $p5 = 0.5;
                }
            } elseif ($nb5 >= 4 AND $nb5 < 6) {
                if ($penality == 0.5) {
                    $total5 = 3;
                    $p5 = 1;
                } elseif ($penality == 1) {
                    $total5 = 2;
                    $p5 = 1;
                }
            } elseif ($nb5 >= 6) {
                $total5 = 0;
            } else {
                if ($penality == 0.5) {
                    $total5 = 3;
                } elseif ($penality == 1) {
                    $total5 = 2;
                }
            }
            if ($nb6 >= 2 AND $nb6 < 4) {
                $p6 = 0.5;
            } elseif ($nb6 >= 4 AND $nb6 < 6) {
                $p6 = 1.0;
            } elseif ($nb6 >= 6) {
                $total6 = 0;
            }
            $prop = 6;
            if ($tag != '') {
                for ($pr = 0; $pr <= $prop; $pr++) {
                    $kk = $k - $pr;
                    $recap[$k] = [
                        'id' => $comNeg[$k],
                        'name' => $dataFR[$kk]['name'],
                        'tag' => trim($tag, ','),
                        1 => $total1 - $p1 - $penality,
                        2 => $total2 - $p2 - $penality,
                        3 => $total3 - $p3 - $penality,
                        4 => $total4 - $p4 - $penality,
                        5 => $total5 - $p5,
                        6 => $total6 - $p6 - $penality,
                        'penality' => $penality,
                        'p1' => $p1,
                        'p2' => $p2,
                        'p3' => $p3,
                        'p4' => $p4,
                        'p5' => $p5,
                        'p6' => $p6,
                    ];
                    $k++;
                }
            } else {
                $recap[$k] = [
                    'name' => $dataFR[$k]['name'],
                    'tag' => trim($tag, ','),
                    1 => $total1 - $p1 - $penality,
                    2 => $total2 - $p2 - $penality,
                    3 => $total3 - $p3 - $penality,
                    4 => $total4 - $p4 - $penality,
                    5 => $total5 - $p5,
                    6 => $total6 - $p6 - $penality,
                    'penality' => $penality,
                    'p1' => $p1,
                    'p2' => $p2,
                    'p3' => $p3,
                    'p4' => $p4,
                    'p5' => $p5,
                    'p6' => $p6,
                ];
                $k++;
            }
        }

        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;
        $tot5 = 0;
        $tot6 = 0;
        for ($q = 0; $q < count($recap); $q++) {
            $tot1 += $recap[$q][1];
            $tot2 += $recap[$q][2];
            $tot3 += $recap[$q][3];
            $tot4 += $recap[$q][4];
            $tot5 += $recap[$q][5];
            $tot6 += $recap[$q][6];
        }
        $totaux = [
            1 => floor($tot1 / count($recap) * 2) / 2,
            2 => floor($tot2 / count($recap) * 2) / 2,
            3 => floor($tot3 / count($recap) * 2) / 2,
            4 => floor($tot4 / count($recap) * 2) / 2,
            5 => floor($tot5 / count($recap) * 2) / 2,
            6 => floor($tot6 / count($recap) * 2) / 2
        ];

        var_dump($totaux);
        die();
        $nbNeg = count($comNeg);
        $ratioCom = ($nbNeg / $nbCom);

//        $json = new json();
//        $json->createJson($totaux,);

        $dossier = '../web/hackatal2017-resume-data/test/';
        $dir = opendir($dossier);

//        $select_values[] = '';

        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..' && !is_dir($dossier . $file)) {
                $selectValues[] = $file;
            }
        }

        return $this->render('AppBundle::index.html.twig', [
            'comNeg' => $comNeg,
            'selectValues' => $selectValues,
        ]);
    }
}
