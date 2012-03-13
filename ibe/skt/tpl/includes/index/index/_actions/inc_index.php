<?php
/**
 * Ação Index do controlador Index do Modulo Index
 */
class IndexAction extends Ibe_Action{

    public function execute(Ibe_Request $req){
        $this->getViewAction()->welcome = 'Welcome IbeFramework Application';
        return Ibe_Layout::APPLICATION;
    }
}