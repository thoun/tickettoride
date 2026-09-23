<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

function getBaseDestinations() {
  return [
    1 => new DestinationCard(43, 7, 8), // Tokyo Hakodate 8
    2 => new DestinationCard(35, 43, 5), // Osaka Tokyo 5
    3 => new DestinationCard(43, 36, 3), // Tokyo Sendai 3
    4 => new DestinationCard(44, 43, 9), // Tottori Tokyo 9
    5 => new DestinationCard(13, 43, 13), // Kagoshima-Chuo Tokyo 13
    6 => new DestinationCard(43, 39, 4), // Tokyo Shinjuku 4
    7 => new DestinationCard(43, 3, 6), // Tokyo Asakusa 6
    8 => new DestinationCard(11, 43, 6), // Ise Tokyo 6
    9 => new DestinationCard(6, 43, 10), // Hakata Tokyo 10
    10 => new DestinationCard(9, 35, 3), // Hiroshima Osaka 3
    11 => new DestinationCard(13, 35, 8), // Kagoshima-Chuo Osaka 8
    12 => new DestinationCard(35, 27, 3), // Osaka Nagano 3
    13 => new DestinationCard(35, 4, 7), // Osaka Fukushima 7
    14 => new DestinationCard(35, 1, 9), // Osaka Akita 9
    15 => new DestinationCard(35, 37, 13), // Osaka Shibuya 13
    16 => new DestinationCard(24, 19, 12), // Miyazaki Kyoto 12
    17 => new DestinationCard(20, 19, 5), // Masuda Kyoto 5
    18 => new DestinationCard(16, 19, 6), // Kochi Kyoto 6
    19 => new DestinationCard(19, 30, 6), // Kyoto Narita 6
    20 => new DestinationCard(19, 31, 5), // Kyoto Niigata 5
    21 => new DestinationCard(19, 2, 9), // Kyoto Aomori 9
    22 => new DestinationCard(19, 10, 12), // Kyoto Ikebukuro 12
    23 => new DestinationCard(29, 39, 10), // Nagoya Shinjuku 10
    24 => new DestinationCard(22, 29, 6), // Matsuyama Nagoya 6
    25 => new DestinationCard(28, 29, 10), // Nagasaki Nagoya 10
    26 => new DestinationCard(29, 12, 8), // Nagoya Iwaki 8
    27 => new DestinationCard(29, 38, 7), // Nagoya Shinjo 7
    28 => new DestinationCard(44, 29, 4), // Tottori Nagoya 4
    29 => new DestinationCard(6, 33, 3), // Hakata Oita 3
    30 => new DestinationCard(6, 16, 8), // Hakata Kochi 8
    31 => new DestinationCard(6, 11, 14), // Hakata Ise 14
    32 => new DestinationCard(6, 23, 19), // Hakata Miyako 19
    33 => new DestinationCard(6, 14, 8), // Hakata Kanazawa 8
    34 => new DestinationCard(5, 15, 7), // Ginza Kita-Senju 7
    35 => new DestinationCard(48, 25, 7), // Yotsuya Monzen-Nakacho 7
    36 => new DestinationCard(39, 3, 10), // Shinjuku Asakusa 10
    37 => new DestinationCard(13, 17, 3), // Kagoshima-Chuo Kokura 3
    38 => new DestinationCard(28, 24, 5), // Nagasaki Miyazaki 5
    39 => new DestinationCard(28, 31, 16), // Nagasaki Niigata 16
    40 => new DestinationCard(18, 41, 7), // Kumamoto Takamatsu 7
    41 => new DestinationCard(34, 21, 6), // Okayama Matsumoto 6
    42 => new DestinationCard(32, 30, 1), // Odawara Narita 1
    43 => new DestinationCard(42, 26, 5), // Takasaki Morioka 5
    44 => new DestinationCard(8, 47, 3), // Hamamatsu Utsunomiya 3
    45 => new DestinationCard(9, 7, 14), // Hiroshima Hakodate 14
    46 => new DestinationCard(17, 45, 8), // Kokura Tsuruga 8
    47 => new DestinationCard(36, 2, 4), // Sendai Aomori 4
    48 => new DestinationCard(20, 36, 16), // Masuda Sendai 16
    49 => new DestinationCard(21, 4, 5), // Matsumoto Fukushima 5
    50 => new DestinationCard(1, 23, 2), // Akita Miyako 2
    51 => new DestinationCard(37, 46, 7), // Shibuya Ueno 7
    52 => new DestinationCard(27, 40, 6), // Nagano Suitengumae 6
    53 => new DestinationCard(10, 40, 7), // Ikebukuro Suitengumae 7
    54 => new DestinationCard(39, 10, 4), // Shinjuku Ikebukuro 4
  ];
}

function getAllDestinations() {
  return [
    1 => getBaseDestinations(),
  ];
}
