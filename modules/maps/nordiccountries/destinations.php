<?php

require_once(__DIR__.'/../../php/objects/destination.php');

function getBaseDestinations() {
  return [
    1 => new DestinationCard(1, 23, 5), // Alborg Norrkoping 5
    2 => new DestinationCard(1, 39, 11), // Alborg Umea 11
    3 => new DestinationCard(2, 18, 6), // Arhus Lillehammer 6
    4 => new DestinationCard(4, 14, 8), // Bergen Kobenhavn 8
    5 => new DestinationCard(4, 22, 16), // Bergen Narvik 16
    6 => new DestinationCard(4, 37, 17), // Bergen Tornio 17
    7 => new DestinationCard(4, 33, 7), // Bergen Trondheim 7
    8 => new DestinationCard(6, 3, 6), // Goteborg Andalsnes 6
    9 => new DestinationCard(6, 25, 12), // Goteborg Oulu 12
    10 => new DestinationCard(6, 36, 7), // Goteborg Turku 7
    11 => new DestinationCard(7, 4, 12), // Helsinki Bergen 12
    12 => new DestinationCard(7, 12, 13), // Helsinki Kirkenes 13
    13 => new DestinationCard(7, 13, 10), // Helsinki Kiruna 10
    14 => new DestinationCard(7, 14, 10), // Helsinki Kobenhavn 10
    15 => new DestinationCard(7, 19, 5), // Helsinki Lieksa 5
    16 => new DestinationCard(7, 27, 8), // Helsinki Ostersund 8
    17 => new DestinationCard(14, 21, 24), // Kobenhavn Murmansk 24
    18 => new DestinationCard(14, 22, 18), // Kobenhavn Narvik 18
    19 => new DestinationCard(14, 25, 14), // Kobenhavn Oulu 14
    20 => new DestinationCard(15, 20, 12), // Kristiansand Mo I Rana 12
    21 => new DestinationCard(22, 21, 12), // Narvik Murmansk 12
    22 => new DestinationCard(22, 35, 13), // Narvik Tallinn 13
    23 => new DestinationCard(23, 5, 11), // Norrkoping Boden 11
    24 => new DestinationCard(26, 16, 10), // Orebro Kuopio 10
    25 => new DestinationCard(24, 7, 8), // Oslo Helsinki 8
    26 => new DestinationCard(24, 8, 21), // Oslo Honningsvag 21
    27 => new DestinationCard(24, 14, 4), // Oslo Kobenhavn 4
    28 => new DestinationCard(24, 20, 10), // Oslo Mo I Rana 10
    29 => new DestinationCard(24, 29, 4), // Oslo Stavanger 4
    30 => new DestinationCard(24, 30, 4), // Oslo Stockholm 4
    31 => new DestinationCard(24, 38, 9), // Oslo Vaasa 9
    32 => new DestinationCard(29, 11, 8), // Stavanger Karlskrona 8
    33 => new DestinationCard(29, 28, 18), // Stavanger Rovaniemi 18
    34 => new DestinationCard(30, 4, 8), // Stockholm Bergen 8
    35 => new DestinationCard(30, 9, 7), // Stockholm Imatra 7
    36 => new DestinationCard(30, 10, 10), // Stockholm Kajaani 10
    37 => new DestinationCard(30, 14, 6), // Stockholm Kobenhavn 6
    38 => new DestinationCard(30, 34, 17), // Stockholm Tromso 17
    39 => new DestinationCard(30, 39, 7), // Stockholm Umea 7
    40 => new DestinationCard(31, 17, 6), // Sundsvall Lahti 6
    41 => new DestinationCard(32, 5, 6), // Tampere Boden 6
    42 => new DestinationCard(32, 15, 10), // Tampere Kristiansand 10
    43 => new DestinationCard(32, 35, 3), // Tampere Tallinn 3
    44 => new DestinationCard(37, 9, 6), // Tornio Imatra 6
    45 => new DestinationCard(34, 38, 11), // Tromso Vaasa 11
    46 => new DestinationCard(36, 33, 10), // Turku Trondheim 10
    ];
}

function getAllDestinations() {
  return [
    1 => getBaseDestinations(),
  ];
}
