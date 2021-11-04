<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * TicketToRide implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * tickettoride.action.php
 *
 * TicketToRide main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/tickettoride/tickettoride/myAction.html", ...)
 *
 */
  
  
  class action_tickettoride extends APP_GameAction { 
    // Constructor: please do not modify
   	public function __default() {
      if (self::isArg('notifwindow')) {
          $this->view = "common_notifwindow";
          $this->viewArgs['table'] = self::getArg("table", AT_posint, true);
      } else {
          $this->view = "tickettoride_tickettoride";
          self::trace("Complete reinitialization of board game");
      }
  	} 
  	
    public function chooseInitialDestinations() {
        self::setAjaxMode();

        $destinationsIds = self::getArg("destinationsIds", AT_numberlist, true);

        $this->game->chooseInitialDestinations($destinationsIds);

        self::ajaxResponse();
    }
  	
    public function chooseAdditionalDestinations() {
        self::setAjaxMode();

        $destinationsIds = self::getArg("destinationsIds", AT_numberlist, true);

        $this->game->chooseAdditionalDestinations($destinationsIds);

        self::ajaxResponse();
    }

  }
  

