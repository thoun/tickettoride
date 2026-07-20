<?php

use Bga\Games\TicketToRide\Objects\DestinationCard;

function getBaseDestinations() {
    return [
        1 => new DestinationCard(1, 25, 13), // Amiens - Montpellier
        2 => new DestinationCard(2, 9, 12), // Angers - Briançon
        3 => new DestinationCard(-1, 10, 12), // Belgique - Brive-la-Gaillarde
        4 => new DestinationCard(-1, -3, 12), // Belgique - Suisse
        5 => new DestinationCard(5, 6, 12), // Besançon - Bordeaux
        6 => new DestinationCard(6, 13, 6), // Bordeaux - Clermont-Ferrand
        7 => new DestinationCard(6, 23, 10), // Bordeaux - Marseille
        8 => new DestinationCard(8, -1, 12), // Brest - Belgique
        9 => new DestinationCard(8, 13, 11), // Brest - Clermont-Ferrand
        10 => new DestinationCard(8, 31, 9), // Brest - Paris
        11 => new DestinationCard(11, -2, 9), // Calais - Allemagne
        12 => new DestinationCard(11, 28, 9), // Calais - Nantes
        13 => new DestinationCard(12, 20, 9), // Cherbourg - Limoges
        14 => new DestinationCard(12, 31, 5), // Cherbourg - Paris
        15 => new DestinationCard(14, 15, 5), // Dijon - Grenoble
        16 => new DestinationCard(14, 25, 9), // Dijon - Montpellier
        17 => new DestinationCard(-2, 3, 12), // Allemagne - Avignon
        18 => new DestinationCard(-2, -5, 18), // Allemagne - Espagne
        19 => new DestinationCard(16, 25, 11), // La Rochelle - Montpellier
        20 => new DestinationCard(16, 41, 7), // La Rochelle - Toulouse
        21 => new DestinationCard(17, 7, 5), // Le Havre - Bourges
        22 => new DestinationCard(17, 23, 16), // Le Havre - Marseille
        23 => new DestinationCard(18, 6, 7), // Le Mans - Bordeaux
        24 => new DestinationCard(18, -3, 10), // Le Mans - Suisse
        25 => new DestinationCard(19, 14, 9), // Lille - Dijon
        26 => new DestinationCard(19, 34, 8), // Lille - Poitiers
        27 => new DestinationCard(19, 39, 8), // Lille - Saint-Malo
        28 => new DestinationCard(21, 37, 10), // Lorient - Rodez
        29 => new DestinationCard(21, -5, 12), // Lorient - Espagne
        30 => new DestinationCard(22, 23, 6), // Lyon - Marseille
        31 => new DestinationCard(22, 41, 7), // Lyon - Toulouse
        32 => new DestinationCard(24, 2, 8), // Metz - Angers
        33 => new DestinationCard(24, 33, 16), // Metz - Perpignan
        34 => new DestinationCard(26, 22, 6), // Mulhouse - Lyon
        35 => new DestinationCard(27, 23, 13), // Nancy - Marseille
        36 => new DestinationCard(28, 29, 15), // Nantes - Nice
        37 => new DestinationCard(28, 32, 8), // Nantes - Pau
        38 => new DestinationCard(31, 23, 14), // Paris - Marseille
        39 => new DestinationCard(31, 4, 11), // Paris - Bayonne
        40 => new DestinationCard(31, 10, 7), // Paris - Brive-la-Gaillarde
        41 => new DestinationCard(31, 22, 8), // Paris - Lyon
        42 => new DestinationCard(31, 29, 14), // Paris - Nice
        43 => new DestinationCard(31, 40, 7), // Paris - Strasbourg
        44 => new DestinationCard(35, 30, 3), // Reims - Orléans
        45 => new DestinationCard(35, 41, 13), // Reims - Toulouse
        46 => new DestinationCard(36, 4, 10), // Rennes - Bayonne
        47 => new DestinationCard(36, 22, 11), // Rennes - Lyon
        48 => new DestinationCard(36, 27, 10), // Rennes - Nancy
        49 => new DestinationCard(38, 15, 11), // Rouen - Grenoble
        50 => new DestinationCard(-5, -4, 12), // Espagne - Italie
        51 => new DestinationCard(40, 3, 11), // Strasbourg - Avignon
        52 => new DestinationCard(40, -6, 15), // Strasbourg - Corse
        53 => new DestinationCard(40, -4, 13), // Strasbourg - Italie
        54 => new DestinationCard(40, 20, 13), // Strasbourg - Limoges
        55 => new DestinationCard(-3, 23, 9), // Suisse - Marseille
        56 => new DestinationCard(41, -6, 8), // Toulouse - Corse
        57 => new DestinationCard(41, 29, 9), // Toulouse - Nice
        58 => new DestinationCard(42, 14, 6), // Tours - Dijon
    ];
}

function getAllDestinations() {
    return [
        1 => getBaseDestinations(),
    ];
}
