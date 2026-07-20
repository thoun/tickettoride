<?php

use Bga\Games\TicketToRide\Objects\City;

/**
 * Cities on the Germany map (alphabetical order).
 *
 * Negative ids are logical countries used by destination tickets. Their
 * visible border endpoints use the corresponding positive thousand range.
 */
function getCities() {
    return [
        // countries
        -1 => new City('Dänemark', 483, 25),
        -2 => new City('Frankreich', 77, 1446),
        -3 => new City('Niederlande', 41, 561),
        -4 => new City('Österreich', 906, 1637),
        -5 => new City('Schweiz', 305, 1696),

        // cities
        1 => new City('Augsburg', 650, 1473),
        2 => new City('Berlin', 993, 555),
        3 => new City('Bremen', 364, 441),
        4 => new City('Bremerhaven', 355, 337),
        5 => new City('Chemnitz', 969, 929),
        6 => new City('Dortmund', 196, 770),
        7 => new City('Dresden', 1069, 884),
        8 => new City('Düsseldorf', 98, 814),
        9 => new City('Emden', 151, 370),
        10 => new City('Erfurt', 677, 908),
        11 => new City('Frankfurt', 345, 1079),
        12 => new City('Freiburg', 230, 1569),
        13 => new City('Hamburg', 574, 335),
        14 => new City('Hannover', 496, 615),
        15 => new City('Karlsruhe', 292, 1349),
        16 => new City('Kassel', 472, 837),
        17 => new City('Kiel', 539, 175),
        18 => new City('Koblenz', 115, 1040),
        19 => new City('Köln', 92, 928),
        20 => new City('Konstanz', 399, 1609),
        21 => new City('Leipzig', 853, 809),
        22 => new City('Lindau', 499, 1628),
        23 => new City('Magdeburg', 789, 658),
        24 => new City('Mainz', 253, 1142),
        25 => new City('Mannheim', 299, 1245),
        26 => new City('München', 811, 1514),
        27 => new City('Münster', 215, 656),
        28 => new City('Nürnberg', 677, 1193),
        29 => new City('Regensburg', 898, 1319),
        30 => new City('Rostock', 826, 178),
        31 => new City('Saarbrücken', 64, 1271),
        32 => new City('Schwerin', 725, 316),
        33 => new City('Stuttgart', 409, 1375),
        34 => new City('Ulm', 548, 1467),
        35 => new City('Würzburg', 513, 1147),

        // country endpoints
        1001 => new City('Dänemark', 459, 19),
        1002 => new City('Dänemark', 507, 31),
        2001 => new City('Frankreich', 44, 1375),
        2002 => new City('Frankreich', 145, 1439),
        2003 => new City('Frankreich', 65, 1525),
        3001 => new City('Niederlande', 37, 495),
        3002 => new City('Niederlande', 21, 597),
        3003 => new City('Niederlande', 64, 592),
        4001 => new City('Österreich', 1075, 1550),
        4002 => new City('Österreich', 983, 1672),
        4003 => new City('Österreich', 661, 1688),
        5001 => new City('Schweiz', 315, 1675),
        5002 => new City('Schweiz', 243, 1711),
        5003 => new City('Schweiz', 358, 1701),
    ];
}
