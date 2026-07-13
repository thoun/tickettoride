<?php

use Bga\Games\TicketToRide\Objects\City;

require_once(__DIR__.'/countries.php');

/**
 * Cities in the UK map (alphabetical order).
 */
function getCities() {
    return [
        // countries
        -1 => new City('France', 773, 1720),

        // cities
        1 => new City('Aberdeen', 1025, 418, UK_COUNTRY_SCOTLAND),
        2 => new City('Aberystwyth', 443, 1117, UK_COUNTRY_WALES),
        3 => new City('Barrow', 684, 865, UK_COUNTRY_ENGLAND),
        4 => new City('Belfast', 464, 670, UK_COUNTRY_IRELAND),
        5 => new City('Birmingham', 689, 1221, UK_COUNTRY_ENGLAND),
        6 => new City('Brighton', 762, 1582, UK_COUNTRY_ENGLAND),
        7 => new City('Bristol', 537, 1375, UK_COUNTRY_ENGLAND),
        8 => new City('Cambridge', 879, 1348, UK_COUNTRY_ENGLAND),
        9 => new City('Cardiff', 460, 1302, UK_COUNTRY_WALES),
        10 => new City('Carlisle', 764, 770, UK_COUNTRY_ENGLAND),
        11 => new City('Carmarthen', 377, 1219, UK_COUNTRY_WALES),
        12 => new City('Cork', 31, 990, UK_COUNTRY_IRELAND),
        13 => new City('Dover', 949, 1582, UK_COUNTRY_ENGLAND),
        14 => new City('Dublin', 319, 854, UK_COUNTRY_IRELAND),
        15 => new City('Dundalk', 365, 744, UK_COUNTRY_IRELAND),
        16 => new City('Dundee', 911, 485, UK_COUNTRY_SCOTLAND),
        17 => new City('Edinburgh', 835, 575, UK_COUNTRY_SCOTLAND),
        18 => new City('Fort William', 712, 343, UK_COUNTRY_SCOTLAND),
        19 => new City('Galway', 51, 715, UK_COUNTRY_IRELAND),
        20 => new City('Glasgow', 718, 541, UK_COUNTRY_SCOTLAND),
        21 => new City('Holyhead', 481, 946, UK_COUNTRY_WALES),
        22 => new City('Hull', 932, 1058, UK_COUNTRY_ENGLAND),
        23 => new City('Inverness', 861, 252, UK_COUNTRY_SCOTLAND),
        24 => new City('Ipswich', 969, 1441, UK_COUNTRY_ENGLAND),
        25 => new City('Leeds', 833, 977, UK_COUNTRY_ENGLAND),
        26 => new City('Limerick', 52, 850, UK_COUNTRY_IRELAND),
        27 => new City('Liverpool', 633, 971, UK_COUNTRY_ENGLAND),
        28 => new City('Llandrindod Wells', 529, 1192, UK_COUNTRY_WALES),
        29 => new City('Londonderry', 382, 507, UK_COUNTRY_IRELAND),
        30 => new City('London', 817, 1456, UK_COUNTRY_ENGLAND),
        31 => new City('Manchester', 724, 1035, UK_COUNTRY_ENGLAND),
        32 => new City('Newcastle', 900, 806, UK_COUNTRY_ENGLAND),
        33 => new City('Northampton', 768, 1312, UK_COUNTRY_ENGLAND),
        34 => new City('Norwich', 1055, 1337, UK_COUNTRY_ENGLAND),
        35 => new City('Nottingham', 794, 1172, UK_COUNTRY_ENGLAND),
        36 => new City('Penzance', 116, 1412, UK_COUNTRY_ENGLAND),
        37 => new City('Plymouth', 296, 1431, UK_COUNTRY_ENGLAND),
        38 => new City('Reading', 702, 1418, UK_COUNTRY_ENGLAND),
        39 => new City('Rosslare', 222, 1013, UK_COUNTRY_IRELAND),
        40 => new City('Sligo', 186, 578, UK_COUNTRY_IRELAND),
        41 => new City('Southampton', 638, 1530, UK_COUNTRY_ENGLAND),
        42 => new City('Stornoway', 733, 70, UK_COUNTRY_SCOTLAND),
        43 => new City('Stranraer', 582, 659, UK_COUNTRY_SCOTLAND),
        44 => new City('Tullamore', 204, 824, UK_COUNTRY_IRELAND),
        45 => new City('Ullapool', 802, 160, UK_COUNTRY_SCOTLAND),
        46 => new City('Wick', 1038, 160, UK_COUNTRY_SCOTLAND),

        // country endpoints
        1001 => new City('France', 532, 1728),
        1002 => new City('France', 1067, 1696),
    ];
}
