<?php
/**
 * Created by PhpStorm.
 * User: wilder12
 * Date: 26/06/17
 * Time: 02:26
 */

namespace AppBundle\Controller;


class json
{
    public function createJson($totaux, $recap, $average, $nom, $json)
    {
        $jsonD = json_decode($json, true);
        $j = 0;
        for ($i = 0; $i <= count($jsonD['reviews']); $i++) {
            if ($jsonD['reviews'][$i]['lang'] == "french") {
                $jsonD['reviews'][$i]['neg'] = $recap[$j]['tag'];
                $j++;
            };
        }
        $jsonD['p_proprete'] = $totaux[1];
        $jsonD['p_communication'] = $totaux[2];
        $jsonD['p_emplacement'] = $totaux[3];
        $jsonD['p_arrivee'] = $totaux[4];
        $jsonD['p_qualite-prix'] = $totaux[5];
        $jsonD['p_precision'] = $totaux[6];
        $jsonD['p_averageRanking'] = $average;

        $contenu_json = json_encode($jsonD, JSON_UNESCAPED_UNICODE);

// Ouverture du fichier
        $fichier = fopen($nom, 'w+');

// Ecriture dans le fichier
        fwrite($fichier, $contenu_json);

// Fermeture du fichier
        fclose($fichier);
    }
}