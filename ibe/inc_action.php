<?php

/**
 * Controlador de acoes
 * @author Renan Abreu
 * @version 21102011
 * @package ibe
 */
abstract class Ibe_Action extends Ibe_Object {

    private $view_application = NULL;

    public function __construct() {

        $this->view_application = Ibe_Layout_Screen::getInstance();
        $this->view_application->addPathName(Ibe_Source::getPathModuleName())
                                ->setScreenName(Ibe_Source::getPathViewName());

        $this->view_application->view_module = Ibe_Layout_Screen::getInstance();
        $this->view_application->view_module->addPathName(Ibe_Source::getPathModuleName())
                            ->addPathName(Ibe_Request_Decode::getModule())
                            ->setScreenName(Ibe_Source::getPathViewName());


        $this->view_application->view_module->view_controller = Ibe_Layout_Screen::getInstance();
        $this->view_application->view_module->view_controller->addPathName(Ibe_Source::getPathModuleName())
                                ->addPathName(Ibe_Request_Decode::getModule())
                                ->addPathName(Ibe_Request_Decode::getController())
                                ->setScreenName(Ibe_Source::getPathViewName());


        $this->view_application->view_module->view_controller->view_action = Ibe_Layout_Screen::getInstance();
        $this->view_application->view_module->view_controller->view_action
                               ->addPathName(Ibe_Source::getPathModuleName())
                               ->addPathName(Ibe_Request_Decode::getModule())
                               ->addPathName(Ibe_Request_Decode::getController())
                               ->addPathName(Ibe_Source::getPathViewName())
                               ->setScreenName(Ibe_Request_Decode::getAction());

    }

    /**
     * Retorna o layout da aplicação
     * @return Ibe_Layout_Screen
     */
    public function getViewApplication() {
        return $this->view_application;
    }

    /**
     * Seta um novo Layout para o aplicativo
     * @param Ibe_Layout_Screen $view_application
     */
    public function setViewApplication(Ibe_Layout_Screen $view_application) {
        $this->view_application = $view_application;
    }

    /**
     * Retorna o layout do modulo
     * @return Ibe_Layout_Screen
     */
    public function getViewModule() {
        return $this->view_application->view_module;
    }

    /**
     * Seta um novo Layout para o modulo
     * @param Ibe_Layout_Screen $view_application
     */
    public function setViewModule(Ibe_Layout_Screen $view_module) {
        $this->view_application->view_module = $view_module;
    }

    /**
     * Retorna o layout do controlador
     * @return Ibe_Layout_Screen
     */
    public function getViewController() {
        return $this->view_application->view_module->view_controller;
    }

    /**
     * Seta um novo Layout para o controlador
     * @param Ibe_Layout_Screen $view_application
     */
    public function setViewController(Ibe_Layout_Screen $view_controller) {
        $this->view_application->view_module->view_controller = $view_controller;
    }

    /**
     * Retorna o layout da acao
     * @return Ibe_Layout_Screen
     */
    public function getViewAction() {
        return $this->view_application->view_module->view_controller->view_action;
    }

    /**
     * Seta um novo Layout para a acao
     * @param Ibe_Layout_Screen $view_application
     */
    public function setViewAction(Ibe_Layout_Screen $view_action) {
        $this->view_application->view_module->view_controller->view_action = $view_action;
    }

    /**
     * Verifica se o usuario tem permissao de acesso ao construtor
     * @param Ibe_User $user
     */
    public function isAllowed(Ibe_Request $req) {
        $userAccess = $req->getUser()->getAccess();
        $allowed = FALSE;

        if(($allowed = $userAccess[Ibe_Request_Decode::getModule()]) !== TRUE){
            if(($allowed = $allowed[Ibe_Request_Decode::getController()]) !== TRUE){
                $allowed = $allowed[Ibe_Request_Decode::getAction()];
            }
        }

        return $allowed;
    }

    /**
     * Acao disparada antes de realizar a chamada a acao
     * @param Ibe_Request $req
     */
    public function preAction(Ibe_Request $req) {

    }

    /**
     * Acao disparada depois de realizar a chamada a acao
     * @param Ibe_Request $req
     */
    public function posAction(Ibe_Request $req) {

    }

    /**
     * Método que implementa a acao lógica do aplicativo
     * Este metodo será executado quando o usuário
     * realizar uma requisicao via URL ao aplicativo.
     * É identificado como o terceiro parametro da URL ou o valor padrao
     *
     * http://localhost/index.php/module/controller/action
     *
     * @return int
     */
    abstract public function execute(Ibe_request $req);
}