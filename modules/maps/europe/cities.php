<?php

require_once(__DIR__.'/../../php/objects/city.php');

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Amsterdam', 1395, 726),
    2 => new City('Angora', 1701, 197),
    3 => new City('Athina', 378, 99),
    4 => new City('Barcelona', 1564, 739),
    5 => new City('Berlin', 1214, 442),
    6 => new City('Brest', 973, 908),
    7 => new City('Brindisi', 666, 622),
    8 => new City('Bruxelles', 991, 330),
    9 => new City('Bucuresti', 644, 950),
    10 => new City('Budapest', 559, 340),
    11 => new City('Cadiz', 1049, 980),
    12 => new City('Constantinople', 973, 590),
    13 => new City('Danzig', 327, 764),
    14 => new City('Dieppe', 1101, 755),
    15 => new City('Edinburgh', 209, 871),
    16 => new City('Erzurum', 1624, 1025),
    17 => new City('Essen', 1571, 90),
    18 => new City('Frankfurt', 1302, 663),
    19 => new City('Kharov', 1223, 966),
    20 => new City('Kobenhavn', 1606, 333),
    21 => new City('Kyiv', 937, 747),
    22 => new City('Lisboa', 937, 497),
    23 => new City('London', 428, 884),
    24 => new City('Madrid', 1452, 414),
    25 => new City('Marseille', 94, 322),
    26 => new City('Moskva', 1514, 619),
    27 => new City('München', 1133, 592),
    28 => new City('Palermo', 430, 564),
    29 => new City('Pamplona', 1223, 209),
    30 => new City('Paris', 69, 682),
    31 => new City('Petrograd', 653, 787),
    32 => new City('Riga', 133, 230),
    33 => new City('Roma', 1421, 251),
    34 => new City('Rostov', 140, 132),
    35 => new City('Sarajevo', 1619, 498),
    36 => new City('Sevastopol', 786, 119),
    37 => new City('Smolensk', 786, 119),
    38 => new City('Smyrna', 786, 119),
    39 => new City('Sochi', 786, 119),
    40 => new City('Sofia', 786, 119),
    41 => new City('Stockolm', 786, 119),
    42 => new City('Venezia', 786, 119),
    43 => new City('Warszawa', 786, 119),
    44 => new City('Wien', 786, 119),
    45 => new City('Wilno', 786, 119),
    46 => new City('Zagrab', 786, 119),
    47 => new City('Zürich', 786, 119),
  ];
}
