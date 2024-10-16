<?php

require_once(__DIR__.'/../../php/objects/destination.php');

function getBaseDestinations() {
  return [
      1 => new DestinationCard(-1, [-2, -3, -4], [5, 14, 11]), // France []
      2 => new DestinationCard(-2, [-1, -3, -4], [5, 5, 13]), // Deutschland []
      3 => new DestinationCard(-3, [-1, -2, -4], [14, 5, 6]), // Österreich []
      4 => new DestinationCard(-4, [-1, -2, -3], [11, 13, 6]), // Italia []
      5 => new DestinationCard(4, [-1, -2, -3, -4], [5, 6, 11, 8]), // Bern []
      6 => new DestinationCard(7, [-1, -2, -3, -4], [12, 6, 3, 5]), // Chur []
      7 => new DestinationCard(34, [-1, -2, -3, -4], [7, 3, 7, 11]), // Zurich []
      8 => new DestinationCard(17, [-1, -2, -3, -4], [14, 12, 13, 2]), // Lugano []
      9 => new DestinationCard(2, 4, 5), // Basel  Bern  5
      10 => new DestinationCard(2, 5, 10), // Basel Brig  10
      11 => new DestinationCard(2, 28, 8), // Basel  St Gallen 8
      12 => new DestinationCard(4, 7, 10), // Bern Chur  10
      13 => new DestinationCard(4, 25, 5), // Bern Schwyz  5
      14 => new DestinationCard(4, 17, 12), // Bern  Lugano  12
      15 => new DestinationCard(4, 34, 6), // Bern  Zurich  6
      16 => new DestinationCard(10, 18, 5), // Fribourg  Luzern  5
      17 => new DestinationCard(11, 2, 13), // Geneve  Basel 13
      18 => new DestinationCard(11, 4, 8), // Geneve  Bern  8
      19 => new DestinationCard(11, 26, 10), // Geneve  Sion  10
      20 => new DestinationCard(11, 34, 14), // Geneve  Zurich  14
      21 => new DestinationCard(12, 31, 7), // Interlaken Winterthur  7
      22 => new DestinationCard(13, 34, 3), // Kreuzlingen  Zurich  3
      23 => new DestinationCard(14, 4, 3), // La Chaux-de-Fonds Bern  3
      24 => new DestinationCard(14, 18, 7), // La Chaux-de-Fonds Luzern 7
      25 => new DestinationCard(14, 34, 8), // La Chaux-de-Fonds Zurich 8
      26 => new DestinationCard(15, 12, 7), // Lausanne  Interlaken  7
      27 => new DestinationCard(15, 18, 8), // Lausanne  Luzern  8
      28 => new DestinationCard(15, 28, 13), // Lausanne  St Gallen 13
      29 => new DestinationCard(18, 29, 6), // Luzern  Vaduz 6
      30 => new DestinationCard(17, 7, 10), // Lugano  Chur  10
      31 => new DestinationCard(18, 34, 2), // Luzern  Zurich  2
      32 => new DestinationCard(20, 31, 9), // Neuchatel  Wintherthur 9
      33 => new DestinationCard(21, 24, 5), // Olten  Schaffhausen  5
      34 => new DestinationCard(24, 19, 15), // Schaffhausen Martigny  15
      35 => new DestinationCard(24, 28, 4), // Schaffhausen St Gallen 4
      36 => new DestinationCard(24, 33, 3), // Schaffhausen Zug 3
      37 => new DestinationCard(28, 6, 9), // St Gallen  Brusio  9
      38 => new DestinationCard(31, 25, 3), // Wintherthur  Schwyz  3
      39 => new DestinationCard(34, 2, 4), // Zurich  Basel  4
      40 => new DestinationCard(34, 6 ,11), // Zurich  Brusio  11
      41 => new DestinationCard(34, 17, 9), // Zurich  Lugano  9
      42 => new DestinationCard(34, 29, 6), // Zurich  Vaduz  6
    ];
}

function getAllDestinations() {
  return [
    1 => getBaseDestinations(),
  ];
}
