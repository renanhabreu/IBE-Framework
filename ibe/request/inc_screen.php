<?php

/**
 * Classe que dmonta a tela de uma requisicao
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage request
 */
class Ibe_Request_Screen {

    /**
     * Captura o retorno final da acao e baseado nela Monta a screen.
     * Esté metodo precisa do objeto Ibe_action para que seja possível
     * capturar os atributos de ação do aplicativo para seta-los na tela
     * que será criada
     * 
     * @param type $tpl
     * @param Ibe_Controller $action
     * @return NULL
     */
    static public function show( $screen, Ibe_Action $action) {
        $layout = NULL;
        if ($screen == Ibe_Layout::APPLICATION) {
            $layout = $action->getViewApplication();
        }else if ($screen == Ibe_Layout::ACTION) {
            $layout = $action->getViewAction();
        } else if ($screen == Ibe_Layout::CONTROLLER) {
            $layout = $action->getViewController();
        } else if ($screen == Ibe_Layout::MODULE) {
            $layout = $action->getViewModule();
        } else if (is_object ($screen) && is_a($screen, 'Ibe_Layout')) {
            $layout = $screen;
        } else if ($screen == Ibe_Layout::NONE) {
            return;
        } else {
            throw new Ibe_Exception(Ibe_Exception::LAYOUT_INDEFINIDO);
        }

        $layout->__setVars($action->__getVars());
        $layout->showLayout();
    }

}