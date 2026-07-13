<?php

use Bga\Games\TicketToRide\Objects\City;

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
    return [
        // country
        -1 => new City('Ontario', 250, 48),

        1 => new City('Albany', 1698, 101),
        2 => new City('Allentown', 1367, 746),
        3 => new City('Altoona', 629, 768),
        4 => new City('Atlantic City', 1687, 1003),
        5 => new City('Baltimore', 1183, 1088),
        6 => new City('Binghamton', 1288, 254),
        7 => new City('Buffalo', 557, 52),
        8 => new City('Chambersburg', 812, 970),
        9 => new City('Coudersport', 737, 355),
        10 => new City('Cumberland', 638, 1092),
        11 => new City('Dubois', 539, 592),
        12 => new City('Elmira', 1016, 256),
        13 => new City('Erie', 207, 254),
        14 => new City('Gettysburg', 937, 1011),
        15 => new City('Harrisburg', 1013, 844),
        16 => new City('Johnstown', 530, 841),
        17 => new City('Lancaster', 1171, 931),
        18 => new City('Lewiston', 842, 748),
        19 => new City('Morgantown', 242, 1091),
        20 => new City('New York', 1697, 559),
        21 => new City('Oil City', 301, 489),
        22 => new City('Philadelphia', 1486, 975),
        23 => new City('Pittsburgh', 218, 808),
        24 => new City('Reading', 1204, 816),
        25 => new City('Rochester', 936, 52),
        26 => new City('Scranton / Wilkes Barre', 1335, 494),
        27 => new City('Stroudsburg', 1495, 624),
        28 => new City('Syracuse', 1252, 52),
        29 => new City('Towanda', 1128, 377),
        30 => new City('Warren', 435, 352),
        31 => new City('Wheeling', 65, 924),
        32 => new City('Williamsport', 975, 550),
        33 => new City('York', 1064, 963),
        34 => new City('Youngstown', 52, 547),
        // country endpoints
        1001 => new City('Ontario', 407, 48),
        1002 => new City('Ontario', 89, 48),
    ];
}
