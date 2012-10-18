<?php
/**
 * Ação Index do controlador Index do Modulo Index
 */
class ResultadoAction extends Ibe_Action{

    public function execute(Ibe_Request $req){     	
        return Ibe_View::JSON;
    }
}
