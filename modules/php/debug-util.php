<?php

trait DebugUtilTrait {

//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

    function debugSetup() {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        }

        $this->gamestate->changeActivePlayer(2343492);
    }

    function debug($debugData) {
        if ($this->getBgaEnvironment() != 'studio') { 
            return;
        }die('debug data : '.json_encode($debugData));
    }
}
