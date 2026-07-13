<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

/**
 * Destination tickets for the Pennsylvania map.
 *
 * City -1 is the logical Ontario location.  It has two displayed endpoints
 * on the map (1001 and 1002), but destinations treat them as one location.
 */
function getBaseDestinations() {
    return [
        1 => new DestinationCard(3, 6, 9), // Altoona - Binghamton
        2 => new DestinationCard(5, 1, 16), // Baltimore - Albany
        3 => new DestinationCard(5, 20, 10), // Baltimore - New York
        4 => new DestinationCard(5, 22, 4), // Baltimore - Philadelphia
        5 => new DestinationCard(7, 5, 16), // Buffalo - Baltimore
        6 => new DestinationCard(7, 15, 13), // Buffalo - Harrisburg
        7 => new DestinationCard(7, 16, 10), // Buffalo - Johnstown
        8 => new DestinationCard(7, 20, 18), // Buffalo - New York
        9 => new DestinationCard(7, 22, 19), // Buffalo - Philadelphia
        10 => new DestinationCard(8, 26, 8), // Chambersburg - Scranton / Wilkes Barre
        11 => new DestinationCard(9, 6, 7), // Coudersport - Binghamton
        12 => new DestinationCard(10, 15, 4), // Cumberland - Harrisburg
        13 => new DestinationCard(11, 10, 6), // Dubois - Cumberland
        14 => new DestinationCard(11, 27, 12), // Dubois - Stroudsburg
        15 => new DestinationCard(13, 1, 20), // Erie - Albany
        16 => new DestinationCard(13, 3, 8), // Erie - Altoona
        17 => new DestinationCard(14, 24, 3), // Gettysburg - Reading
        18 => new DestinationCard(15, 5, 3), // Harrisburg - Baltimore
        19 => new DestinationCard(15, 20, 11), // Harrisburg - New York
        20 => new DestinationCard(15, 22, 6), // Harrisburg - Philadelphia
        21 => new DestinationCard(16, 12, 10), // Johnstown - Elmira
        22 => new DestinationCard(18, 28, 9), // Lewiston - Syracuse
        23 => new DestinationCard(19, 8, 7), // Morgantown - Chambersburg
        24 => new DestinationCard(19, 32, 13), // Morgantown - Williamsport
        25 => new DestinationCard(20, 4, 6), // New York - Atlantic City
        26 => new DestinationCard(21, 16, 6), // Oil City - Johnstown
        27 => new DestinationCard(21, 26, 14), // Oil City - Scranton / Wilkes Barre
        28 => new DestinationCard(-1, 23, 10), // Ontario - Pittsburgh
        29 => new DestinationCard(-1, 28, 10), // Ontario - Syracuse
        30 => new DestinationCard(-1, 30, 5), // Ontario - Warren
        31 => new DestinationCard(22, 4, 2), // Philadelphia - Atlantic City
        32 => new DestinationCard(22, 20, 6), // Philadelphia - New York
        33 => new DestinationCard(23, 15, 9), // Pittsburgh - Harrisburg
        34 => new DestinationCard(23, 5, 13), // Pittsburgh - Baltimore
        35 => new DestinationCard(23, 7, 10), // Pittsburgh - Buffalo
        36 => new DestinationCard(23, 20, 20), // Pittsburgh - New York
        37 => new DestinationCard(23, 22, 15), // Pittsburgh - Philadelphia
        38 => new DestinationCard(25, 12, 3), // Rochester - Elmira
        39 => new DestinationCard(25, 24, 13), // Rochester - Reading
        40 => new DestinationCard(26, 2, 3), // Scranton / Wilkes Barre - Allentown
        41 => new DestinationCard(28, 2, 8), // Syracuse - Allentown
        42 => new DestinationCard(29, 17, 9), // Towanda - Lancaster
        43 => new DestinationCard(30, 33, 10), // Warren - York
        44 => new DestinationCard(31, 1, 22), // Wheeling - Albany
        45 => new DestinationCard(31, 2, 15), // Wheeling - Allentown
        46 => new DestinationCard(31, 13, 9), // Wheeling - Erie
        47 => new DestinationCard(32, 1, 10), // Williamsport - Albany
        48 => new DestinationCard(34, 19, 7), // Youngstown - Morgantown
        49 => new DestinationCard(34, 25, 14), // Youngstown - Rochester
        50 => new DestinationCard(34, 26, 17), // Youngstown - Scranton / Wilkes Barre
    ];
}

function getAllDestinations() {
    return [
        1 => getBaseDestinations(),
    ];
}
