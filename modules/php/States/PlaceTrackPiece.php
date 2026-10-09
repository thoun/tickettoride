<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\UserException;
use Bga\Games\TicketToRide\Game;

class PlaceTrackPiece extends GameState {
    public function __construct(protected Game $game) {
        parent::__construct($game,
            id: ST_PLAYER_PLACE_TRACK_PIECE,
            type: StateType::ACTIVE_PLAYER,
            name: 'PlaceTrackPiece',
            description: clienttranslate('${actplayer} must place a Track Piece'),
            descriptionMyTurn: clienttranslate('${you} must place a Track Piece'),
        );
    }

    function getArgs(): array {
        $remainingTrackPieces = $this->bga->globals->get('REMAINING_TRACK_PIECES', []);
        $placedTrackPieces = $this->bga->globals->get('PLACED_TRACK_PIECES', []);
        $claimedRouteIds = array_map(fn($route) => $route->routeId, array_values($this->game->getClaimedRoutes()));
        $routes = $this->game->mapManager->getCurrentStateRoutes(includeTrackbed: true);
        $possibleRouteIds = [];
        if ($this->game->getMap()->useTrackBedPieces) {
            foreach ($routes as $route) {
                if ($route->color !== TRACKBED || isset($placedTrackPieces[$route->id]) || in_array($route->id, $claimedRouteIds)) {
                    continue;
                }
                if (!array_filter($remainingTrackPieces, fn($counts) => ($counts[$route->number] ?? 0) > 0)) {
                    continue;
                }
                // At low player counts, building also locks the parallel tracks.
                $twins = array_filter($routes, fn($twin) => $twin->id !== $route->id && $twin->from === $route->from && $twin->to === $route->to);
                $maximum = count($twins) > 1
                    ? ($this->game->getMap()->maximumPlayerForTripleRoutes[$this->game->getPlayerCount()] ?? 3)
                    : ($this->game->getMap()->maximumPlayerForDoubleRoutes[$this->game->getPlayerCount()] ?? 2);
                $builtTwins = count(array_filter($twins, fn($twin) => isset($placedTrackPieces[$twin->id]) || in_array($twin->id, $claimedRouteIds)));
                if ($builtTwins < $maximum) {
                    $possibleRouteIds[] = $route->id;
                }
            }
        }
        return [
            'possibleRouteIds' => $possibleRouteIds,
            'remainingTrackPieces' => $remainingTrackPieces,
        ];
    }

    function onEnteringState() {
        if (empty($this->getArgs()['possibleRouteIds'])) {
            return NextPlayer::class;
        }
    }

    #[PossibleAction]
    public function actPlaceTrackPiece(int $routeId, int $color, int $activePlayerId) {
        $args = $this->getArgs();
        if (!in_array($routeId, $args['possibleRouteIds'], true)) {
            throw new UserException('You cannot place a Track Piece on this route');
        }
        $route = $this->game->mapManager->getCurrentStateRoutes(includeTrackbed: true)[$routeId];
        $remainingTrackPieces = $args['remainingTrackPieces'];
        if (($remainingTrackPieces[$color][$route->number] ?? 0) <= 0) {
            throw new UserException('This Track Piece is not available');
        }
        $remainingTrackPieces[$color][$route->number]--;
        $placedTrackPieces = $this->bga->globals->get('PLACED_TRACK_PIECES', []);
        $placedTrackPieces[$routeId] = $color;
        $this->bga->globals->set('REMAINING_TRACK_PIECES', $remainingTrackPieces);
        $this->bga->globals->set('PLACED_TRACK_PIECES', $placedTrackPieces);
        $pieceColor = match ($color) {
            0 => clienttranslate('Gray'),
            1 => clienttranslate('Pink'),
            2 => clienttranslate('White'),
            3 => clienttranslate('Blue'),
            4 => clienttranslate('Yellow'),
            5 => clienttranslate('Orange'),
            6 => clienttranslate('Black'),
            7 => clienttranslate('Red'),
            8 => clienttranslate('Green'),
        };

        $this->notify->all('trackPiecePlaced', clienttranslate('${player_name} places a ${piece_color} Track Piece between ${from} and ${to}'), [
            'playerId' => $activePlayerId,
            'player_name' => $this->game->getPlayerNameById($activePlayerId),
            'from' => $this->game->getMap()->cities[$route->from]->name,
            'to' => $this->game->getMap()->cities[$route->to]->name,
            'piece_color' => $pieceColor,
            'i18n' => ['piece_color'],
            'routeId' => $routeId,
            'color' => $color,
            'length' => $route->number,
            'remainingCount' => $remainingTrackPieces[$color][$route->number],
        ]);
        return NextPlayer::class;
    }

    function zombie(int $playerId, array $args) {
        if (empty($args['possibleRouteIds'])) {
            return NextPlayer::class;
        }
        $routeId = $this->getRandomZombieChoice($args['possibleRouteIds']);
        $length = $this->game->mapManager->getCurrentStateRoutes(includeTrackbed: true)[$routeId]->number;
        $colors = array_keys(array_filter($args['remainingTrackPieces'], fn($counts) => ($counts[$length] ?? 0) > 0));
        return $this->actPlaceTrackPiece($routeId, $this->getRandomZombieChoice($colors), $playerId);
    }
}
