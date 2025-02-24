
<?php

require_once(__DIR__.'/../../php/objects/city.php');

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Amsterdam', 537, 327),
    2 => new City('Angora', 1528, 1051),
    3 => new City('Athina', 1165, 1050),
    4 => new City('Barcelona', 345, 986),
    5 => new City('Berlin', 854, 369),
    6 => new City('Brest', 192, 529),
    7 => new City('Brindisi', 948, 914),
    8 => new City('Bruxelles', 498, 410),
    9 => new City('Bucuresti', 1304, 739),
    10 => new City('Budapest', 1039, 616),
    11 => new City('Cadiz', 148, 1099),
    12 => new City('Constantinople', 1393, 956),
    13 => new City('Danzig', 1054, 231),
    14 => new City('Dieppe', 349, 479),
    15 => new City('Edinburgh', 252, 58),
    16 => new City('Erzurum', 1669, 1010),
    17 => new City('Essen', 679, 343),
    18 => new City('Frankfurt', 653, 466),
    19 => new City('Kharkov', 1640, 540),
    20 => new City('Kobenhavn', 805, 158),
    21 => new City('Kyiv', 1405, 447),
    22 => new City('Lisboa', 32, 1007),
    23 => new City('London', 368, 321),
    24 => new City('Madrid', 151, 968),
    25 => new City('Marseille', 590, 817),
    26 => new City('Moskva', 1665, 275),
    27 => new City('München', 754, 543),
    28 => new City('Palermo', 863, 1098),
    29 => new City('Pamplona', 324, 825),
    30 => new City('Paris', 435, 557),
    31 => new City('Petrograd', 1495, 64),
    32 => new City('Riga', 1198, 73),
    33 => new City('Roma', 797, 868),
    34 => new City('Rostov', 1711, 632),
    35 => new City('Sarajevo', 1080, 834),
    36 => new City('Sevastopol', 1546, 764),
    37 => new City('Smolensk', 1516, 318),
    38 => new City('Smyrna', 1315, 1094),
    39 => new City('Sochi', 1702, 790),
    40 => new City('Sofia', 1197, 850),
    41 => new City('Stockholm', 987, 22),
    42 => new City('Venezia', 784, 711),
    43 => new City('Warszawa', 1140, 354),
    44 => new City('Wien', 954, 572),
    45 => new City('Wilno', 1336, 310),
    46 => new City('Zagrab', 932, 733),
    47 => new City('Zürich', 638, 654),
  ];
}

