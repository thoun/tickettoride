<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

/**
 * Destination tickets for the UK map.
 * City -1 is the logical France location; its visible endpoints are 1001
 * and 1002, just as with country destinations on the Switzerland map.
 */
function getBaseDestinations() {
    return [
        1 => new DestinationCard(1, 20, 5), // Aberdeen - Glasgow
        2 => new DestinationCard(2, 9, 2), // Aberystwyth - Cardiff
        3 => new DestinationCard(4, 14, 4), // Belfast - Dublin
        4 => new DestinationCard(4, 31, 9), // Belfast - Manchester
        5 => new DestinationCard(5, 8, 2), // Birmingham - Cambridge
        6 => new DestinationCard(5, 30, 4), // Birmingham - London
        7 => new DestinationCard(7, 41, 2), // Bristol - Southampton
        8 => new DestinationCard(8, 30, 3), // Cambridge - London
        9 => new DestinationCard(9, 30, 8), // Cardiff - London
        10 => new DestinationCard(9, 38, 4), // Cardiff - Reading
        11 => new DestinationCard(12, 25, 13), // Cork - Leeds
        12 => new DestinationCard(14, 12, 6), // Dublin - Cork
        13 => new DestinationCard(14, 30, 15), // Dublin - London
        14 => new DestinationCard(15, 10, 7), // Dundalk - Carlisle
        15 => new DestinationCard(17, 5, 12), // Edinburgh - Birmingham
        16 => new DestinationCard(17, 30, 15), // Edinburgh - London
        17 => new DestinationCard(18, 17, 3), // Fort William - Edinburgh
        18 => new DestinationCard(19, 3, 12), // Galway - Barrow
        19 => new DestinationCard(19, 14, 5), // Galway - Dublin
        20 => new DestinationCard(20, 14, 9), // Glasgow - Dublin
        21 => new DestinationCard(20, -1, 19), // Glasgow - France
        22 => new DestinationCard(20, 31, 11), // Glasgow - Manchester
        23 => new DestinationCard(21, 9, 4), // Holyhead - Cardiff
        24 => new DestinationCard(23, 4, 10), // Inverness - Belfast
        25 => new DestinationCard(23, 25, 13), // Inverness - Leeds
        26 => new DestinationCard(25, -1, 10), // Leeds - France
        27 => new DestinationCard(25, 30, 6), // Leeds - London
        28 => new DestinationCard(25, 31, 1), // Leeds - Manchester
        29 => new DestinationCard(26, 9, 12), // Limerick - Cardiff
        30 => new DestinationCard(27, 22, 3), // Liverpool - Hull
        31 => new DestinationCard(27, 28, 6), // Liverpool - Llandrindod Wells
        32 => new DestinationCard(27, 41, 6), // Liverpool - Southampton
        33 => new DestinationCard(30, 6, 3), // London - Brighton
        34 => new DestinationCard(30, -1, 7), // London - France
        35 => new DestinationCard(29, 5, 15), // Londonderry - Birmingham
        36 => new DestinationCard(29, 14, 6), // Londonderry - Dublin
        37 => new DestinationCard(29, 43, 4), // Londonderry - Stranraer
        38 => new DestinationCard(31, 30, 6), // Manchester - London
        39 => new DestinationCard(31, 34, 6), // Manchester - Norwich
        40 => new DestinationCard(31, 37, 8), // Manchester - Plymouth
        41 => new DestinationCard(32, 22, 3), // Newcastle - Hull
        42 => new DestinationCard(32, 41, 7), // Newcastle - Southampton
        43 => new DestinationCard(33, 13, 3), // Northampton - Dover
        44 => new DestinationCard(34, 24, 1), // Norwich - Ipswich
        45 => new DestinationCard(35, 24, 3), // Nottingham - Ipswich
        46 => new DestinationCard(36, 30, 10), // Penzance - London
        47 => new DestinationCard(37, 38, 5), // Plymouth - Reading
        48 => new DestinationCard(39, 2, 4), // Rosslare - Aberystwyth
        49 => new DestinationCard(39, 11, 6), // Rosslare - Carmarthen
        50 => new DestinationCard(40, 21, 9), // Sligo - Holyhead
        51 => new DestinationCard(41, 30, 4), // Southampton - London
        52 => new DestinationCard(42, 1, 5), // Stornoway - Aberdeen
        53 => new DestinationCard(42, 20, 7), // Stornoway - Glasgow
        54 => new DestinationCard(43, 44, 6), // Stranraer - Tullamore
        55 => new DestinationCard(45, 16, 4), // Ullapool - Dundee
        56 => new DestinationCard(46, 16, 4), // Wick - Dundee
        57 => new DestinationCard(46, 17, 5), // Wick - Edinburgh
    ];
}

function getAllDestinations() {
    return [
        1 => getBaseDestinations(),
    ];
}

