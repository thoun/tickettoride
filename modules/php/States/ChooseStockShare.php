<?php

namespace Bga\Games\TicketToRide\States;

use Bga\GameFramework\States\GameState;
use Bga\GameFramework\States\PossibleAction;
use Bga\GameFramework\StateType;
use Bga\GameFramework\UserException;
use Bga\Games\TicketToRide\Game;

use function Bga\Games\TicketToRide\debug;

class ChooseStockShare extends GameState {
    public function __construct(protected Game $game, bool $dummy = false)
    {
        parent::__construct($game,
            id: $dummy ? ST_PLAYER_CHOOSE_STOCK_SHARE_DUMMY : ST_PLAYER_CHOOSE_STOCK_SHARE,
            type: StateType::ACTIVE_PLAYER,
            description: $dummy ? clienttranslate('${actplayer} must choose a stock share for the dummy player (${stockShares})') : clienttranslate('${actplayer} must choose a stock share (${stockShares})'),
            descriptionMyTurn: $dummy ? clienttranslate('${you} must choose a stock share for the dummy player (${stockShares})') : clienttranslate('${you} must choose a stock share (${stockShares})'),
        );
    }

    function getArgs() {
        $routeId = (int) $this->game->bga->globals->get('STOCK_SHARE_ROUTE');
        $route = $this->game->mapManager->getAllRoutes()[$routeId];

        return [
            'routeId' => $routeId,
            'stockShares' => $route->stockShares,
        ];
    }

    private function getPossibleStockShares(): array {
        $routeId = (int) $this->game->bga->globals->get('STOCK_SHARE_ROUTE');
        $route = $this->game->mapManager->getAllRoutes()[$routeId];
         $remainingStockShareCards = $this->bga->globals->get('REMAINING_STOCK_SHARE_CARDS');

        return array_values(array_filter($route->stockShares, fn($type) => !empty($remainingStockShareCards[$type])));
    }

    #[PossibleAction]
    public function actChooseStockShare(int $type, int $activePlayerId) {
        $this->takeStockShare($type, "STOCK_SHARE_CARDS_{$activePlayerId}", $activePlayerId);

        return count($this->game->getPlayersIds()) === 2 && !empty($this->getPossibleStockShares())
            ? ChooseStockShareDummy::class
            : NextPlayer::class;
    }

    function zombie(int $playerId, array $args) {
        return $this->actChooseStockShare($this->getRandomZombieChoice($this->getPossibleStockShares()), $playerId);
    }

    protected function takeStockShare(int $type, string $stockKey, ?int $playerId): void {
        if (!in_array($type, $this->getArgs()['stockShares'])) {
            throw new UserException("Stock share type is not available on this route");
        }

        $remainingStockShareCards = $this->bga->globals->get("REMAINING_STOCK_SHARE_CARDS");
        if (!array_key_exists($type, $remainingStockShareCards)) {
            throw new UserException("Invalid stock share type");
        }
        if (empty($remainingStockShareCards[$type])) {
            throw new UserException("Empty deck for stock share type");
        }

        $stockShareCard = array_shift($remainingStockShareCards[$type]);
        $this->bga->globals->set("REMAINING_STOCK_SHARE_CARDS", $remainingStockShareCards);

        $playerStock = $this->bga->globals->get($stockKey);
        $playerStock[$type][] = $stockShareCard;
        $this->bga->globals->set($stockKey, $playerStock);

        $this->notify->all('stockShareTaken', $playerId === null
            ? clienttranslate('The dummy player takes a ${company_name} stock share')
            : clienttranslate('${player_name} takes a ${company_name} stock share'), [
            'playerId' => $playerId,
            'company_name' => $this->game->getMap()->shareStockCompanyNames[$type],
            'type' => $type,
            'cardNumber' => $stockShareCard,
            'nextCardNumber' => $remainingStockShareCards[$type][0] ?? null,
            'remainingCount' => count($remainingStockShareCards[$type]),
            'ownerTypeCount' => count($playerStock[$type]),
            'ownerTotalCount' => array_sum(array_map('count', $playerStock)),
        ]);
    }
}
