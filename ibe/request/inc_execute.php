<?php

/**
 * Classe que executa uma requisicao
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage request
 */
class Ibe_Request_Execute {

    static public function action(Ibe_Request $request) {
        $actionPath = Ibe_Request_Decode::getActionPath();
        $actionFileName = Ibe_Request_Decode::getAction('inc_', '.php');

        Ibe_Source::load($actionPath,$actionFileName);

        // busca uma classe controladora e verifica se e filha de Ibe_Controller
        $actionName = Ibe_Request_Decode::getAction('', 'Action');

        //Instanciando um objeto Action
        $clsAction = new ReflectionClass($actionName);
        if ($clsAction->isSubclassOf('Ibe_Action')) {
            //instancia um novo controlador
            $objAction = $clsAction->newInstanceArgs();

            if ($objAction->isAllowed($request)) {

                //executa uma funcao geral anterior a todas as acoes
                $objAction->preAction($request);

                //executa a acao e posteriormente mostra a tela
                $screen = $objAction->execute($request);
                Ibe_Request_Screen::show($screen, $objAction);

                //executa uma funcao geral posterior a todas as acoes
                $objAction->posAction($request);

            } else {
                throw new Ibe_Exception(Ibe_Exception::PERMISSAO_DE_ACESSO_NEGADA);
            }

        } else {
            $param =  array($actionName, 'Ibe_Action');
            throw new Ibe_Exception(Ibe_Exception::CLASSE_PAI_INVALIDA,$param);
        }
    }

}
