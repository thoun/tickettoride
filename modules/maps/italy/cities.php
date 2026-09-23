<?php

use Bga\Games\TicketToRide\Objects\City;

const ITALY_REGION_ABRUZZO = 1;
const ITALY_REGION_CALABRIA = 2;
const ITALY_REGION_CAMPANIA = 3;
const ITALY_REGION_EMILIA_ROMAGNA = 4;
const ITALY_REGION_FRIULI_VENEZIA_GIULIA = 5;
const ITALY_REGION_LAZIO = 6;
const ITALY_REGION_LIGURIA = 7;
const ITALY_REGION_LOMBARDIA = 8;
const ITALY_REGION_MARCHE = 9;
const ITALY_REGION_PIEMONTE = 10;
const ITALY_REGION_PUGLIA = 11;
const ITALY_REGION_SARDEGNA = 12;
const ITALY_REGION_SICILIA = 13;
const ITALY_REGION_TOSCANA = 14;
const ITALY_REGION_TRENTINO_ALTO_ADIGE = 15;
const ITALY_REGION_UMBRIA = 16;
const ITALY_REGION_VENETO = 17;

/**
 * Cities in the map (by alphabetical order).
 */
function getCities() {
  return [
    1 => new City('Agrigento', 166, 1835, region: ITALY_REGION_SICILIA),
    2 => new City('Ancona', 761, 848, region: ITALY_REGION_MARCHE),
    3 => new City('Bari', 934, 1473, region: ITALY_REGION_PUGLIA),
    4 => new City('Bergamo', 562, 258, region: ITALY_REGION_LOMBARDIA),
    5 => new City('Bologna', 606, 565, region: ITALY_REGION_EMILIA_ROMAGNA),
    6 => new City('Bolzano', 815, 238, region: ITALY_REGION_TRENTINO_ALTO_ADIGE),
    7 => new City('Cagliari', 198, 1194, region: ITALY_REGION_SARDEGNA),
    8 => new City('Catania', 364, 1912, region: ITALY_REGION_SICILIA),
    9 => new City('Cosenza', 675, 1713, region: ITALY_REGION_CALABRIA),
    10 => new City('Firenze', 522, 663, region: ITALY_REGION_TOSCANA),
    11 => new City('Foggia', 797, 1304, region: ITALY_REGION_PUGLIA),
    12 => new City('Genova', 343, 402, region: ITALY_REGION_LIGURIA),
    13 => new City('Grosseto', 412, 816, region: ITALY_REGION_TOSCANA),
    14 => new City('Lecce', 1038, 1682, region: ITALY_REGION_PUGLIA),
    15 => new City('Messina', 501, 1841, region: ITALY_REGION_SICILIA),
    16 => new City('Milano', 461, 258, region: ITALY_REGION_LOMBARDIA),
    17 => new City('Napoli', 578, 1319, region: ITALY_REGION_CAMPANIA),
    18 => new City('Olbia', 237, 877, region: ITALY_REGION_SARDEGNA),
    19 => new City('Palermo', 225, 1672, region: ITALY_REGION_SICILIA),
    20 => new City('Parma', 517, 427, region: ITALY_REGION_EMILIA_ROMAGNA),
    21 => new City('Perugia', 587, 838, region: ITALY_REGION_UMBRIA),
    22 => new City('Pescara', 745, 1081, region: ITALY_REGION_ABRUZZO),
    23 => new City('Pisa', 433, 598, region: ITALY_REGION_TOSCANA),
    24 => new City('Ravenna', 699, 626, region: ITALY_REGION_EMILIA_ROMAGNA),
    25 => new City('Roma', 481, 1041, region: ITALY_REGION_LAZIO),
    26 => new City('Salerno', 630, 1399, region: ITALY_REGION_CAMPANIA),
    27 => new City('Sassari', 78, 931, region: ITALY_REGION_SARDEGNA),
    28 => new City('Siracusa', 342, 2003, region: ITALY_REGION_SICILIA),
    29 => new City('Taranto', 914, 1587, region: ITALY_REGION_PUGLIA),
    30 => new City('Tarvisio', 1038, 400, region: ITALY_REGION_FRIULI_VENEZIA_GIULIA),
    31 => new City('Torino', 252, 199, region: ITALY_REGION_PIEMONTE),
    32 => new City('Trieste', 997, 553, region: ITALY_REGION_FRIULI_VENEZIA_GIULIA),
    33 => new City('Venezia', 790, 476, region: ITALY_REGION_VENETO),
    34 => new City('Verona', 653, 394, region: ITALY_REGION_VENETO),

    // countries end point
    1001 => new City('Francia', 66, 170),
    2001 => new City('Monaco', 77, 379),
    3001 => new City('Svizzera', 400, 29),
    3002 => new City('Svizzera', 694, 122),
    4001 => new City('Austria', 1010, 271),
    5001 => new City('Croazia', 1075, 722),
    5002 => new City('Croazia', 1058, 848),
    5003 => new City('Croazia', 1071, 1005),
  ];
}
