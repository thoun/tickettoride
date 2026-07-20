<?php

use Bga\Games\TicketToRide\Objects\City;

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
    return [
        // countries
        -1 => new City('Belgique', 1120, 60),
        -2 => new City('Allemagne', 1430, 470),
        -3 => new City('Suisse', 1240, 1060),
        -4 => new City('Italie', 1120, 1480),
        -5 => new City('Espagne', 260, 1450),
        -6 => new City('Corse', 1030, 1720),

        // cities
        1 => new City('Amiens', 892, 258),
        2 => new City('Angers', 416, 561),
        3 => new City('Avignon', 841, 1380),
        4 => new City('Bayonne', 31, 1194),
        5 => new City('Besançon', 1182, 853),
        6 => new City('Bordeaux', 249, 1024),
        7 => new City('Bourges', 715, 750),
        8 => new City('Brest', 68, 191),
        9 => new City('Briançon', 1102, 1303),
        10 => new City('Brive-la-Gaillarde', 506, 1058),
        11 => new City('Calais', 896, 55),
        12 => new City('Cherbourg', 460, 113),
        13 => new City('Clermont-Ferrand', 735, 1011),
        14 => new City('Dijon', 1031, 803),
        15 => new City('Grenoble', 1018, 1225),
        16 => new City('La Rochelle', 254, 743),
        17 => new City('Le Havre', 636, 202),
        18 => new City('Le Mans', 514, 489),
        19 => new City('Lille', 1048, 162),
        20 => new City('Limoges', 512, 928),
        21 => new City('Lorient', 132, 385),
        22 => new City('Lyon', 970, 1060),
        23 => new City('Marseille', 875, 1568),
        24 => new City('Metz', 1327, 509),
        25 => new City('Montpellier', 656, 1438),
        26 => new City('Mulhouse', 1355, 799),
        27 => new City('Nancy', 1269, 602),
        28 => new City('Nantes', 272, 553),
        29 => new City('Nice', 1103, 1555),
        30 => new City('Orléans', 741, 569),
        31 => new City('Paris', 836, 452),
        32 => new City('Pau', 133, 1279),
        33 => new City('Perpignan', 513, 1535),
        34 => new City('Poitiers', 453, 762),
        35 => new City('Reims', 1059, 432),
        36 => new City('Rennes', 334, 379),
        37 => new City('Rodez', 583, 1243),
        38 => new City('Rouen', 753, 277),
        39 => new City('Saint-Malo', 327, 262),
        40 => new City('Strasbourg', 1416, 676),
        41 => new City('Toulouse', 373, 1324),
        42 => new City('Tours', 584, 606),

        // country endpoints
        1001 => new City('Belgique', 1086, 37),
        1002 => new City('Belgique', 1149, 95),
        1003 => new City('Belgique', 1207, 162),
        2001 => new City('Allemagne', 1420, 440),
        2002 => new City('Allemagne', 1516, 750),
        3001 => new City('Suisse', 1280, 1017),
        3002 => new City('Suisse', 1387, 992),
        3003 => new City('Suisse', 1246, 1102),
        4001 => new City('Italie', 1330, 1202),
        4002 => new City('Italie', 1356, 1302),
        4003 => new City('Italie', 1313, 1397),
        5001 => new City('Espagne', 68, 1436),
        5002 => new City('Espagne', 156, 1463),
        5003 => new City('Espagne', 190, 1591),
        5004 => new City('Espagne', 362, 1650),
        6001 => new City('Corse', 854, 1668),
        6002 => new City('Corse', 1080, 1709),
        6003 => new City('Corse', 1200, 1699),
    ];
}
