<?php
/**
 * Ação Index do controlador Index do Modulo Index
 */
class IndexAction extends Ibe_Action{
        
    public function execute(Ibe_Request $req){        
        $this->welcome = "Action Index";
        
        return Ibe_View::APPLICATION;
    }
}
