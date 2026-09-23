<?php

use Bga\Games\TicketToRide\Objects\City;

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Akita', 1727, 368),
    2 => new City('Aomori', 1910, 231),
    3 => new City('Asakusa', 1329, 224),
    4 => new City('Fukushima', 1625, 706),
    5 => new City('Ginza', 1085, 426),
    6 => new City('Hakata', 420, 126),
    7 => new City('Hakodate', 1981, 85),
    8 => new City('Hamamatsu', 1043, 1037),
    9 => new City('Hiroshima', 302, 678),
    10 => new City('Ikebukuro', 906, 149),
    11 => new City('Ise', 855, 999),
    12 => new City('Iwaki', 1617, 870),
    13 => new City('Kagoshima-Chuo', 192, 409),
    14 => new City('Kanazawa', 1041, 635),
    15 => new City('Kita-Senju', 1261, 151),
    16 => new City('Kochi', 354, 919),
    17 => new City('Kokura', 26, 644, extraCoordinates: [521, 139]),
    18 => new City('Kumamoto', 348, 264),
    19 => new City('Kyoto', 791, 841),
    20 => new City('Masuda', 240, 586),
    21 => new City('Matsumoto', 1153, 795),
    22 => new City('Matsuyama', 272, 780),
    23 => new City('Miyako', 1963, 500),
    24 => new City('Miyazaki', 334, 475),
    25 => new City('Monzen-Nakacho', 1298, 452),
    26 => new City('Morioka', 1878, 443),
    27 => new City('Nagano', 1239, 736),
    28 => new City('Nagasaki', 262, 186),
    29 => new City('Nagoya', 948, 887),
    30 => new City('Narita', 1450, 1031),
    31 => new City('Niigata', 1463, 582),
    32 => new City('Odawara', 1258, 1042),
    33 => new City('Oita', 510, 291),
    34 => new City('Okayama', 513, 738),
    35 => new City('Osaka', 696, 861),
    36 => new City('Sendai', 1724, 678),
    37 => new City('Shibuya', 866, 471),
    38 => new City('Shinjo', 1680, 527),
    39 => new City('Shinjuku', 772, 324),
    40 => new City('Suitengumae', 1202, 324),
    41 => new City('Takamatsu', 482, 832),
    42 => new City('Takasaki', 1318, 836),
    43 => new City('Tokyo', 1354, 996, extraCoordinates: [1037, 337]),
    44 => new City('Tottori', 626, 625),
    45 => new City('Tsuruga', 865, 757),
    46 => new City('Ueno', 1112, 198),
    47 => new City('Utsunomiya', 1472, 871),
    48 => new City('Yotsuya', 928, 324),
  ];
}
