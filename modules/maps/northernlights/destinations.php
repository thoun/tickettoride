<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

function getBaseDestinations() {
  return [
    1 => new DestinationCard(37, 19, 5), // Stockholm København 5
    2 => new DestinationCard(31, 37, 2), // Oslo Stockholm 2
    3 => new DestinationCard(37, 9, 3), // Stockholm Helsinki 3
    4 => new DestinationCard(28, 37, 14), // Murmansk Stockholm 14
    5 => new DestinationCard(5, 37, 5), // Bergen Stockholm 5
    6 => new DestinationCard(37, 34, 4), // Stockholm Riga 4
    7 => new DestinationCard(29, 37, 12), // Narvik Stockholm 12
    8 => new DestinationCard(31, 19, 4), // Oslo København 4
    9 => new DestinationCard(19, 9, 8), // København Helsinki 8
    10 => new DestinationCard(28, 19, 19), // Murmansk København 19
    11 => new DestinationCard(19, 18, 5), // København Klaipėda 5
    12 => new DestinationCard(6, 19, 14), // Boden København 14
    13 => new DestinationCard(31, 9, 5), // Oslo Helsinki 5
    14 => new DestinationCard(28, 31, 16), // Murmansk Oslo 16
    15 => new DestinationCard(31, 39, 6), // Oslo Tallinn 6
    16 => new DestinationCard(45, 31, 8), // Umeå Oslo 8
    17 => new DestinationCard(28, 9, 11), // Murmansk Helsinki 11
    18 => new DestinationCard(9, 7, 8), // Helsinki Gdańsk 8
    19 => new DestinationCard(27, 9, 8), // Mo I Rana Helsinki 8
    20 => new DestinationCard(42, 28, 7), // Tromsø Murmansk 7
    21 => new DestinationCard(28, 25, 6), // Murmansk Lieksa 6
    22 => new DestinationCard(5, 18, 8), // Bergen Klaipėda 8
    23 => new DestinationCard(5, 24, 9), // Bergen Lahti 9
    24 => new DestinationCard(17, 5, 12), // Kiruna Bergen 12
    25 => new DestinationCard(5, 13, 8), // Bergen Karlskrona 8
    26 => new DestinationCard(43, 5, 5), // Trondheim Bergen 5
    27 => new DestinationCard(10, 39, 14), // Honningsvåg Tallinn 14
    28 => new DestinationCard(10, 43, 12), // Honningsvåg Trondheim 12
    29 => new DestinationCard(10, 35, 5), // Honningsvåg Rovaniemi 5
    30 => new DestinationCard(10, 36, 19), // Honningsvåg Stavanger 19
    31 => new DestinationCard(36, 7, 9), // Stavanger Gdańsk 9
    32 => new DestinationCard(38, 36, 8), // Sundsvall Stavanger 8
    33 => new DestinationCard(36, 14, 4), // Stavanger Karlstad 4
    34 => new DestinationCard(8, 34, 7), // Göteborg Riga 7
    35 => new DestinationCard(2, 8, 5), // Åndalsnes Göteborg 5
    36 => new DestinationCard(20, 8, 12), // Kostomuksha Göteborg 12
    37 => new DestinationCard(40, 8, 6), // Tampere Göteborg 6
    38 => new DestinationCard(26, 1, 3), // Lillehammer Ålborg 3
    39 => new DestinationCard(16, 1, 18), // Kirkenes Ålborg 18
    40 => new DestinationCard(3, 30, 5), // Århus Norrköping 5
    41 => new DestinationCard(22, 3, 12), // Kuopio Århus 12
    42 => new DestinationCard(43, 21, 5), // Trondheim Kristiansand 5
    43 => new DestinationCard(21, 11, 9), // Kristiansand Imatra 9
    44 => new DestinationCard(32, 30, 9), // Oulu Norrköping 9
    45 => new DestinationCard(33, 7, 11), // Östersund Gdańsk 11
    46 => new DestinationCard(44, 18, 6), // Turku Klaipėda 6
    47 => new DestinationCard(46, 34, 6), // Vaasa Riga 6
    48 => new DestinationCard(29, 39, 11), // Narvik Tallinn 11
    49 => new DestinationCard(15, 47, 13), // Kemijärvi Visby 13
    50 => new DestinationCard(29, 4, 7), // Narvik Apatity 7
    51 => new DestinationCard(41, 23, 9), // Tornio Kuressaare 9
    52 => new DestinationCard(12, 13, 11), // Kajaani Karlskrona 11
    53 => new DestinationCard(42, 27, 6), // Tromsø Mo I Rana 6
    54 => new DestinationCard(42, 14, 14), // Tromsø Karlstad 14
    55 => new DestinationCard(27, 2, 6), // Mo I Rana Åndalsnes 6
  ];
}

function getAllDestinations() {
  return [
    1 => getBaseDestinations(),
  ];
}
