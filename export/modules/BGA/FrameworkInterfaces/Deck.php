<?php
namespace NieuwenhovenGames\BGA\FrameworkInterfaces;
/**
 * @see https://boardgamearena.com/doc/Deck
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface Deck {
    const STANDARD_DECK = 'deck';
    const PLAYER_HAND = 'hand';
    const DISCARD_PILE = 'discard';

    public function init( $table_name );
    public function createCards( $cards, $location=Deck::STANDARD_DECK, $location_arg=null );

    public function pickCard( $location, $player_id );
    public function pickCards( $nbr, $location, $player_id );
    public function pickCardForLocation( $from_location, $to_location, $location_arg=0 );
    public function pickCardsForLocation( $nbr, $from_location, $to_location, $location_arg=0, $no_deck_reform=false );

    public function moveCard(int $cardID, string $location, int $locationIndex = 0) : void;
    public function moveCards( $cards, $location, $location_arg=0 );
    public function insertCard( $card_id, $location, $location_arg );
    public function insertCardOnExtremePosition( $card_id, $location, $bOnTop );
    public function moveAllCardsInLocation( $from_location, $to_location, $from_location_arg=null, $to_location_arg=0 );
    public function moveAllCardsInLocationKeepOrder( $from_location, $to_location );
    public function playCard( $card_id );

    // Returns database entry associated with card ID including the mandatory keys id, type, type_arg, location, location arg
    public function getCard($card_id) : array;
    // Return array of card IDs
    public function getCards( $cards_array ) : array;
    // Returns an empty array if the location is empty
    public function getCardsInLocation( $location, $location_arg = null, $order_by = null ) : array;
    public function countCardInLocation( $location, $location_arg=null ) : int;
    public function countCardsInLocations() : int;
    // Note that locations without cards are not in the output array
    public function countCardsByLocationArgs( $location ) : array;
    public function getPlayerHand( $player_id ) : array;
    public function getCardOnTop( $location );
    public function getCardsOnTop( $nbr, $location );
    public function shuffle( $location );
}
?>
