<?php

namespace Helpers;

use DateTime;

class DateHelper
{
    /**
     * @throws \DateMalformedStringException
     */
    public static function formatTimeslotDate(string $timestamp): array
    {
        $date = new DateTime($timestamp);

        $jours = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
        $mois = ['', 'Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'];

        return [
            'date_texte' => $jours[$date->format('w')] . '. ' . $date->format('j') . ' ' . $mois[$date->format('n')],
            'heure' => $date->format('H:i')
        ];
    }
}