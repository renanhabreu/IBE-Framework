<?php
/**
 * Acao Index do controlador Index do Modulo Index
 */
class IndexAction extends Ibe_Action{

    public function execute(Ibe_Request $req){  
    	$this->welcome = "Bem vindo ao IBE Framework";   	
        return Ibe_View::MODULE;
    }
}
