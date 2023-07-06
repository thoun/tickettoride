<?php

require_once(__DIR__.'/../../php/objects/city.php');

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Atlanta', 1395, 726),
    2 => new City('Boston', 1701, 197),
    3 => new City('Calgary', 378, 99),
    4 => new City('Charleston', 1564, 739),
    5 => new City('Chicago', 1214, 442),
    6 => new City('Dallas', 973, 908),
    7 => new City('Denver', 666, 622),
    8 => new City('Duluth', 991, 330),
    9 => new City('El Paso', 644, 950),
    10 => new City('Helena', 559, 340),
    11 => new City('Houston', 1049, 980),
    12 => new City('Kansas City', 973, 590),
    13 => new City('Las Vegas', 327, 764),
    14 => new City('Little Rock', 1101, 755),
    15 => new City('Los Angeles', 209, 871),
    16 => new City('Miami', 1624, 1025),
    17 => new City('Montréal', 1571, 90),
    18 => new City('Nashville', 1302, 663),
    19 => new City('New Orleans', 1223, 966),
    20 => new City('New York', 1606, 333),
    21 => new City('Oklahoma City', 937, 747),
    22 => new City('Omaha', 937, 497),
    23 => new City('Phoenix', 428, 884),
    24 => new City('Pittsburgh', 1452, 414),
    25 => new City('Portland', 94, 322),
    26 => new City('Raleigh', 1514, 619),
    27 => new City('Saint Louis', 1133, 592),
    28 => new City('Salt Lake City', 430, 564),
    29 => new City('Sault St. Marie', 1223, 209),
    30 => new City('San Francisco', 69, 682),
    31 => new City('Santa Fe', 653, 787),
    32 => new City('Seattle', 133, 230),
    33 => new City('Toronto', 1421, 251),
    34 => new City('Vancouver', 140, 132),
    35 => new City('Washington', 1619, 498),
    36 => new City('Winnipeg', 786, 119),
  ];
}
