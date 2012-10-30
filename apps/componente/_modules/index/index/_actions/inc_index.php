<?php
/**
 * Acao Index do controlador Index do Modulo Index
 */
class IndexAction extends Ibe_Action{
        
    public function execute(Ibe_Request $req){        
        $this->view_action->welcome = "Action Index";
                
        
        return Ibe_View::APPLICATION;
    }
}
