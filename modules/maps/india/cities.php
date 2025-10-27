<?php

require_once(__DIR__.'/../../php/objects/city.php');

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Agra', 475, 643),
    2 => new City('Ahmadabad', 219, 762),
    3 => new City('Ambala', 470, 301),
    4 => new City('Bareilly', 565, 448),
    5 => new City('Bezwada', 588, 1103),
    6 => new City('Bhatinda', 295, 305),
    7 => new City('Bhopal', 472, 749),
    8 => new City('Bilaspur', 641, 749),
    9 => new City('Bombay', 242, 1000),
    10 => new City('Calcutta', 917, 778),
    11 => new City('Calicut', 350, 1373),
    12 => new City('Chittagong', 1086, 753),
    13 => new City('Delhi', 454, 477),
    14 => new City('Dhubri', 969, 620),
    15 => new City('Erode', 584, 1394),
    16 => new City('Guntakal', 430, 1169),
    17 => new City('Indur', 569, 935),
    18 => new City('Jacobabad', 120, 340),
    19 => new City('Jaipur', 363, 615),
    20 => new City('Jarhat', 1097, 520),
    21 => new City('Jodhpur', 236, 534),
    22 => new City('Karachi', 29, 609),
    23 => new City('Katni', 642, 647),
    24 => new City('Khandwa', 350, 853),
    25 => new City('Lahore', 240, 138),
    26 => new City('Lucknow', 612, 550),
    27 => new City('Madras', 598, 1216),
    28 => new City('Mangalore', 324, 1268),
    29 => new City('Manmad', 343, 956),
    30 => new City('Mormugau', 256, 1111),
    31 => new City('Patna', 806, 619),
    32 => new City('Peshawar', 130, 113),
    33 => new City('Poona', 342, 1058),
    34 => new City('Quilon', 417, 1534),
    35 => new City('Raipur', 621, 852),
    36 => new City('Ratiam', 315, 708),
    37 => new City('Rohri', 183, 434),
    38 => new City('Wadi', 447, 1063),
    39 => new City('Waltair', 756, 1032),
  ];
}
