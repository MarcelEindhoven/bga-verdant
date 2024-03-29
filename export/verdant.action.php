<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Verdant implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * verdant.action.php
 *
 * Verdant main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/verdant/verdant/myAction.html", ...)
 *
 */
  
  
  class action_verdant extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "verdant_verdant";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there
    function playerPlacesInitialPlant() {
      self::setAjaxMode();

      $selected_id = self::getArg( "selected_id", AT_alphanum, true );

      $this->game->playerPlacesInitialPlant($selected_id);

      self::ajaxResponse( );
    }

    function playerPlacesPlant() {
      self::setAjaxMode();

      $selected_market_card = self::getArg( "selected_market_card", AT_alphanum, true );
      $selected_home_id = self::getArg( "selected_home_id", AT_alphanum, true );

      $this->game->playerPlacesPlant($selected_market_card, $selected_home_id);

      self::ajaxResponse( );
    }

    function playerPlacesRoom() {
      self::setAjaxMode();

      $selected_market_card = self::getArg( "selected_market_card", AT_alphanum, true );
      $selected_home_id = self::getArg( "selected_home_id", AT_alphanum, true );

      $this->game->playerPlacesRoom($selected_market_card, $selected_home_id);

      self::ajaxResponse( );
    }

    function playerPlacesItemOnPlant() {
      self::setAjaxMode();

      $selected_market_card = self::getArg( "selected_market_card", AT_alphanum, true );
      $selected_home_id = self::getArg( "selected_home_id", AT_alphanum, true );

      $this->game->playerPlacesItemOnPlant($selected_market_card, $selected_home_id);

      self::ajaxResponse( );
    }

    function playerPlacesItemOnRoom() {
      self::setAjaxMode();

      $selected_market_card = self::getArg( "selected_market_card", AT_alphanum, true );
      $selected_home_id = self::getArg( "selected_home_id", AT_alphanum, true );

      $this->game->playerPlacesItemOnRoom($selected_market_card, $selected_home_id);

      self::ajaxResponse( );
    }

    function playerPlacesItemOnStorage() {
      self::setAjaxMode();

      $selected_market_card = self::getArg( "selected_market_card", AT_alphanum, true );
      $selected_home_id = self::getArg( "selected_home_id", AT_alphanum, true );

      $this->game->playerPlacesItemOnStorage($selected_market_card, $selected_home_id);

      self::ajaxResponse( );
    }
    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

  }
  

