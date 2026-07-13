<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

function getBaseBigDestinations() {
    return [
        1 => new DestinationCard(2, 9, 18), // Ankara - Colombo
        2 => new DestinationCard(3, 11, 18), // Astrakhan - Hanoi
        3 => new DestinationCard(15, 13, 17), // Khabarovsk - Karachi
        4 => new DestinationCard(17, 33, 17), // Krasnoyarsk - Singapore
        5 => new DestinationCard(22, 7, 16), // Moscow - Calcutta
        6 => new DestinationCard(23, 16, 16), // Omsk - Kobe
    ];
}

function getBaseSmallDestinations() {
    return [
        1 => new DestinationCard(3, 13, 10), // Astrakhan - Karachi
        2 => new DestinationCard(8, 31, 8), // Chita - Shanghai
        3 => new DestinationCard(10, 20, 10), // Dihua - Mandalay
        4 => new DestinationCard(10, 30, 9), // Dihua - Seoul
        5 => new DestinationCard(12, 10, 6), // Irkutsk - Dihua
        6 => new DestinationCard(12, 15, 7), // Irkutsk - Khabarovsk
        7 => new DestinationCard(14, 7, 5), // Kathmandu - Calcutta
        8 => new DestinationCard(16, 19, 7), // Kobe - Macau
        9 => new DestinationCard(17, 24, 9), // Krasnoyarsk - Peking
        10 => new DestinationCard(17, 29, 8), // Krasnoyarsk - Samarkand
        11 => new DestinationCard(18, 5, 12), // Lhasa - Bangkok
        12 => new DestinationCard(19, 33, 6), // Macau - Singapore
        13 => new DestinationCard(22, 2, 7), // Moscow - Ankara
        14 => new DestinationCard(22, 21, 10), // Moscow - Mecca
        15 => new DestinationCard(23, 3, 6), // Omsk - Astrakhan
        16 => new DestinationCard(23, 4, 10), // Omsk - Baghdad
        17 => new DestinationCard(24, 28, 9), // Peking - Saigon
        18 => new DestinationCard(25, 12, 9), // Perm - Irkutsk
        19 => new DestinationCard(25, 35, 6), // Perm - Tehran
        20 => new DestinationCard(26, 9, 7), // Rangoon - Colombo
        21 => new DestinationCard(27, 6, 5), // Rawalpindi - Bombay
        22 => new DestinationCard(29, 1, 6), // Samarkand - Agra
        23 => new DestinationCard(29, 21, 8), // Samarkand - Mecca
        24 => new DestinationCard(30, 11, 9), // Seoul - Hanoi
        25 => new DestinationCard(36, 32, 7), // Tbilisi - Shiraz
        26 => new DestinationCard(35, 14, 11), // Tehran - Kathmandu
        27 => new DestinationCard(37, 38, 9), // Ulan Bator - Vladivostok
        28 => new DestinationCard(38, 18, 12), // Vladivostok - Lhasa
        29 => new DestinationCard(39, 34, 5), // Xi'an - Taipei
        30 => new DestinationCard(39, 6, 9), // Xi'an - Bombay
    ];
}

function getAllDestinations() {
    return [
        1 => getBaseSmallDestinations(),
        2 => getBaseBigDestinations(),
    ];
}

