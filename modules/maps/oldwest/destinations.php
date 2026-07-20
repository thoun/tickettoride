<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

function getBaseDestinations() {
    return [
        1 => new DestinationCard(4, 23, 10), // Bend Las Vegas
        2 => new DestinationCard(4, 24, 9), // Bend Los Angeles
        3 => new DestinationCard(5, 1, 12), // Billings Albuquerque
        4 => new DestinationCard(5, 12, 11), // Billings Durango
        5 => new DestinationCard(6, 17, 8), // Boise Grand Junction
        6 => new DestinationCard(6, 33, 10), // Boise San Diego
        7 => new DestinationCard(6, 41, 11), // Boise Wolf Point
        8 => new DestinationCard(7, 11, 9), // Bozeman Denver
        9 => new DestinationCard(8, 1, 12), // Carson City Albuquerque
        10 => new DestinationCard(9, 1, 8), // Casper Albuquerque
        11 => new DestinationCard(11, 39, 10), // Denver Tucson
        12 => new DestinationCard(13, 25, 9), // Eugene Missoula
        13 => new DestinationCard(13, 34, 6), // Eugene San Francisco
        14 => new DestinationCard(15, 26, 8), // Fresno Phoenix
        15 => new DestinationCard(18, 26, 14), // Great Falls Phoenix
        16 => new DestinationCard(19, 35, 9), // Green River Santa Fe
        17 => new DestinationCard(20, 22, 15), // Helena Las Cruces
        18 => new DestinationCard(20, 32, 6), // Helena Salt Lake City
        19 => new DestinationCard(21, 3, 11), // Idaho Falls Bakersfield
        20 => new DestinationCard(21, 23, 8), // Idaho Falls Las Vegas
        21 => new DestinationCard(23, 11, 9), // Las Vegas Denver
        22 => new DestinationCard(23, 39, 5), // Las Vegas Tucson
        23 => new DestinationCard(24, 5, 16), // Los Angeles Billings
        24 => new DestinationCard(24, 11, 12), // Los Angeles Denver
        25 => new DestinationCard(24, 32, 9), // Los Angeles Salt Lake City
        26 => new DestinationCard(24, 35, 11), // Los Angeles Santa Fe
        27 => new DestinationCard(25, 10, 10), // Missoula Cheyenne
        28 => new DestinationCard(26, 27, 8), // Phoenix Pueblo
        29 => new DestinationCard(28, 31, 6), // Portland Sacramento
        30 => new DestinationCard(28, 32, 10), // Portland Salt Lake City
        31 => new DestinationCard(31, 22, 15), // Sacramento Las Cruces
        32 => new DestinationCard(32, 10, 5), // Salt Lake City Cheyenne
        33 => new DestinationCard(32, 26, 7), // Salt Lake City Phoenix
        34 => new DestinationCard(33, 1, 10), // San Diego Albuquerque
        35 => new DestinationCard(33, 14, 6), // San Diego Flagstaff
        36 => new DestinationCard(34, 4, 9), // San Francisco Bend
        37 => new DestinationCard(34, 6, 7), // San Francisco Boise
        38 => new DestinationCard(34, 11, 15), // San Francisco Denver
        39 => new DestinationCard(34, 20, 12), // San Francisco Helena
        40 => new DestinationCard(34, 24, 4), // San Francisco Los Angeles
        41 => new DestinationCard(36, 2, 5), // Seattle Ashland
        42 => new DestinationCard(36, 11, 11), // Seattle Denver
        43 => new DestinationCard(36, 14, 16), // Seattle Flagstaff
        44 => new DestinationCard(36, 18, 9), // Seattle Great Falls
        45 => new DestinationCard(36, 24, 12), // Seattle Los Angeles
        46 => new DestinationCard(36, 30, 21), // Seattle Roswell
        47 => new DestinationCard(37, 1, 16), // Spokane Albuquerque
        48 => new DestinationCard(37, 26, 16), // Spokane Phoenix
        49 => new DestinationCard(37, 29, 10), // Spokane Redding
        50 => new DestinationCard(38, 16, 8), // St. George Gillette
        51 => new DestinationCard(40, 9, 8), // Winnemucca Casper
    ];
}

function getAllDestinations() {
    return [
        1 => getBaseDestinations(),
    ];
}
