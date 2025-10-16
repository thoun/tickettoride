<?php

require_once(__DIR__.'/../../php/objects/destination.php');

function getBaseDestinations() {
  return [
    1 => new DestinationCard(1, 20, 8), // Agra Jarhat 8
    2 => new DestinationCard(2, 11, 7), // Ahmadabad Calicut 7
    3 => new DestinationCard(3, 30, 10), // Ambala Mormugau 10
    4 => new DestinationCard(3, 36, 5), // Ambala Ratiam 5
    5 => new DestinationCard(3, 39, 9), // Ambala Waltair 9
    6 => new DestinationCard(4, 2, 5), // Bareilly Ahmadabad 5
    7 => new DestinationCard(4, 10, 6), // Bareilly Calcutta 6
    8 => new DestinationCard(4, 16, 10), // Bareilly Guntakal 10
    9 => new DestinationCard(6, 5, 13), // Bhatinda Bezwada 13
    10 => new DestinationCard(6, 8, 7), // Bhatinda Bilaspur 7
    11 => new DestinationCard(6, 29, 9), // Bhatinda Manmad 9
    12 => new DestinationCard(7, 10, 6), // Bhopal Calcutta 6
    13 => new DestinationCard(7, 30, 5), // Bhopal Mormugau 5
    14 => new DestinationCard(8, 11, 10), // Bilaspur Calicut 10
    15 => new DestinationCard(8, 14, 5), // Bilaspur Dhubri 5
    16 => new DestinationCard(9, 5, 5), // Bombay Bezwada 5
    17 => new DestinationCard(9, 34, 6), // Bombay Quilon 6
    18 => new DestinationCard(10, 15, 9), // Calcutta Erode 9
    19 => new DestinationCard(10, 17, 8), // Calcutta Indur 8
    20 => new DestinationCard(12, 38, 11), // Chittagong Wadi 11
    21 => new DestinationCard(13, 11, 11), // Delhi Calicut 11
    22 => new DestinationCard(13, 12, 9), // Delhi Chittagong 9
    23 => new DestinationCard(13, 17, 9), // Delhi Indur 9
    24 => new DestinationCard(14, 28, 12), // Dhubri Mangalore 12
    25 => new DestinationCard(18, 9, 10), // Jacobabad Bombay 10
    26 => new DestinationCard(18, 10, 13), // Jacobabad Calcutta 13
    27 => new DestinationCard(18, 36, 9), // Jacobabad Ratiam 9
    28 => new DestinationCard(19, 31, 5), // Jaipur Patna 5
    29 => new DestinationCard(19, 33, 5), // Jaipur Poona 5
    30 => new DestinationCard(19, 35, 5), // Jaipur Raipur 5
    31 => new DestinationCard(20, 9, 13), // Jarhat Bombay 13
    32 => new DestinationCard(21, 16, 9), // Jodhpur Guntakal 9
    33 => new DestinationCard(21, 24, 5), // Jodhpur Khandwa 5
    34 => new DestinationCard(21, 26, 5), // Jodhpur Lucknow 5
    35 => new DestinationCard(22, 2, 6), // Karachi Ahmadabad 6
    36 => new DestinationCard(22, 13, 7), // Karachi Delhi 7
    37 => new DestinationCard(22, 33, 7), // Karachi Poona 7
    38 => new DestinationCard(23, 24, 5), // Katni Khandwa 5
    39 => new DestinationCard(23, 38, 8), // Katni Wadi 8
    40 => new DestinationCard(23, 39, 5), // Katni Waltair 5
    41 => new DestinationCard(24, 12, 10), // Khandwa Chittagong 10
    42 => new DestinationCard(25, 13, 5), // Lahore Delhi 5
    43 => new DestinationCard(25, 14, 12), // Lahore Dhubri 12
    44 => new DestinationCard(25, 38, 13), // Lahore Wadi 13
    45 => new DestinationCard(26, 9, 7), // Lucknow Bombay 7
    46 => new DestinationCard(26, 15, 11), // Lucknow Erode 11
    47 => new DestinationCard(29, 15, 7), // Manmad Erode 7
    48 => new DestinationCard(27, 34, 6), // Madras Quilon 6
    49 => new DestinationCard(31, 27, 9), // Patna Madras 9
    50 => new DestinationCard(31, 30, 10), // Patna Mormugau 10
    51 => new DestinationCard(32, 7, 9), // Peshawar Bhopal 9
    52 => new DestinationCard(32, 27, 17), // Peshawar Madras 17
    53 => new DestinationCard(35, 16, 7), // Raipur Guntakal 7
    54 => new DestinationCard(35, 29, 5), // Raipur Manmad 5
    55 => new DestinationCard(36, 5, 8), // Ratiam Bezwada 8
    56 => new DestinationCard(37, 1, 7), // Rohri Agra 7
    57 => new DestinationCard(37, 28, 12), // Rohri Mangalore 12
    58 => new DestinationCard(39, 28, 6), // Waltair Mangalore 6
    ];
}

function getAllDestinations() {
  return [
    1 => getBaseDestinations(),
  ];
}
