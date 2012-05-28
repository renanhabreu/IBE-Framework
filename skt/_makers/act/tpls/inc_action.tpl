<?php
/**
 * Ação Index do controlador Index do Modulo Index
 */
class @ACT@Action extends Ibe_Action{
        
    public function execute(Ibe_Request $req){        
        $this->welcome = "Action @ACT@";
        
        return Ibe_View::APPLICATION;
    }
}
