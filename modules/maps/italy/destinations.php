<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

function getBaseDestinations() {
  return [
    1 => new DestinationCard(10, 25, 5), // Firenze Roma 5
    2 => new DestinationCard(27, 25, 7), // Sassari Roma 7
    3 => new DestinationCard(33, 25, 8), // Venezia Roma 8
    4 => new DestinationCard(12, 25, 9), // Genova Roma 9
    5 => new DestinationCard(25, 19, 13), // Roma Palermo 13
    6 => new DestinationCard(25, 29, 10), // Roma Taranto 10
    7 => new DestinationCard(25, -5, 10), // Roma Croazia 10
    8 => new DestinationCard(16, 17, 14), // Milano Napoli 14
    9 => new DestinationCard(16, 5, 4), // Milano Bologna 4
    10 => new DestinationCard(16, 3, 17), // Milano Bari 17
    11 => new DestinationCard(16, 7, 15), // Milano Cagliari 15
    12 => new DestinationCard(16, 15, 23), // Milano Messina 23
    13 => new DestinationCard(16, 32, 8), // Milano Trieste 8
    14 => new DestinationCard(21, 17, 7), // Perugia Napoli 7
    15 => new DestinationCard(32, 17, 13), // Trieste Napoli 13
    16 => new DestinationCard(17, 28, 14), // Napoli Siracusa 14
    17 => new DestinationCard(23, 17, 10), // Pisa Napoli 10
    18 => new DestinationCard(18, 17, 9), // Olbia Napoli 9
    19 => new DestinationCard(-2, 17, 17), // Monaco Napoli 17
    20 => new DestinationCard(31, -4, 9), // Torino Austria 9
    21 => new DestinationCard(31, 10, 7), // Torino Firenze 7
    22 => new DestinationCard(31, 8, 28), // Torino Catania 28
    23 => new DestinationCard(31, 27, 14), // Torino Sassari 14
    24 => new DestinationCard(31, 22, 13), // Torino Pescara 13
    25 => new DestinationCard(19, 28, 5), // Palermo Siracusa 5
    26 => new DestinationCard(4, 19, 25), // Bergamo Palermo 25
    27 => new DestinationCard(3, 19, 13), // Bari Palermo 13
    28 => new DestinationCard(2, 19, 16), // Ancona Palermo 16
    29 => new DestinationCard(18, 19, 13), // Olbia Palermo 13
    30 => new DestinationCard(30, 5, 6), // Tarvisio Bologna 6
    31 => new DestinationCard(5, 14, 16), // Bologna Lecce 16
    32 => new DestinationCard(5, 7, 11), // Bologna Cagliari 11
    33 => new DestinationCard(5, 29, 14), // Bologna Taranto 14
    34 => new DestinationCard(5, 8, 22), // Bologna Catania 22
    35 => new DestinationCard(12, 30, 10), // Genova Tarvisio 10
    36 => new DestinationCard(12, 9, 18), // Genova Cosenza 18
    37 => new DestinationCard(12, 22, 7), // Genova Pescara 7
    38 => new DestinationCard(6, 10, 6), // Bolzano Firenze 6
    39 => new DestinationCard(10, 11, 9), // Firenze Foggia 9
    40 => new DestinationCard(10, 1, 20), // Firenze Agrigento 20
    41 => new DestinationCard(2, 3, 9), // Ancona Bari 9
    42 => new DestinationCard(29, 8, 10), // Taranto Catania 10
    43 => new DestinationCard(-3, 8, 27), // Svizzera Catania 27
    44 => new DestinationCard(-1, 33, 10), // Francia Venezia 10
    45 => new DestinationCard(33, 23, 5), // Venezia Pisa 5
    46 => new DestinationCard(33, 15, 22), // Venezia Messina 22
    47 => new DestinationCard(-3, 34, 4), // Svizzera Verona 4
    48 => new DestinationCard(34, 13, 5), // Verona Grosseto 5
    49 => new DestinationCard(34, 26, 13), // Verona Salerno 13
    50 => new DestinationCard(24, 9, 15), // Ravenna Cosenza 15
    51 => new DestinationCard(-2, 24, 9), // Monaco Ravenna 9
    52 => new DestinationCard(20, 18, 11), // Parma Olbia 11
    53 => new DestinationCard(14, 1, 15), // Lecce Agrigento 15
    54 => new DestinationCard(13, -5, 8), // Grosseto Croazia 8
    55 => new DestinationCard(1001, -4, 11), // Francia Austria 11
    56 => new DestinationCard(6, -5, 8), // Bolzano Croazia 8
    ];
}

function getAllDestinations() {
  return [
    1 => getBaseDestinations(),
  ];
}
