import { TicketToRideGame } from "./tickettoride.d";

/**
 * Animation to move a card to a player's counter (the destroy animated card).
 */ 
export function animateCardToCounterAndDestroy(game: TicketToRideGame, cardOrCardId: string | HTMLElement, destinationId: string) {
    const card = typeof(cardOrCardId) === 'string' ? document.getElementById(cardOrCardId) : cardOrCardId;
    card.classList.add('animated', 'transform-origin-top-left');
    const cardBR = card.getBoundingClientRect();

    const toBR = document.getElementById(destinationId).getBoundingClientRect();
        
    const zoom = game.getZoom();
    const x = (toBR.x - cardBR.x) / zoom;
    const y = (toBR.y - cardBR.y) / zoom;

    card.style.transform = `translate(${x}px, ${y}px) scale(${0.15 / zoom})`;
    setTimeout(() => card.parentElement?.removeChild(card), 500);
}