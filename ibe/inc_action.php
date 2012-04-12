<?php

/**
 * Controlador de acoes
 * @author Renan Abreu
 * @version 21102011
 * @package ibe
 */
abstract class Ibe_Action extends Ibe_Object {
    protected $module;
    protected $controller;
    protected $action;
    protected $filters = array();
    protected $modules_params = array();
    
    protected $view_application = NULL;
    protected $view_module = NULL;
    protected $view_controller = NULL;
    
    private $configure = NULL;
    
    public function __construct() {
        $this->view_application = new Ibe_Object(); 
        $this->view_module      = new Ibe_Object();
        $this->view_controller  = new Ibe_Object();
        
        $this->configure = Ibe_Load::configure();
        
        if(!is_null($this->configure)){
            $this->modules_params = $this->configure->getModulesParams();
            $this->filters = array_merge($this->configure->getFilters(),$this->filters);
            
            
            if($this->configure->isDataBaseActive()){
                $host   = $this->configure->getDataBaseHost();
                $user   = $this->configure->getDataBaseUser();
                $pass   = $this->configure->getDataBasePass();
                $schema = $this->configure->getDataBaseSchm();
                
                Ibe_Database::open($host,$user,$pass,$schema);
            }        
        }
        
        $helpers = $this->configure->getHelpers();
        foreach($helpers as $helper){
            $hp = Ibe_Helper::get($helper);
            
            $this->view_application->helper = new Ibe_Object();
            $this->view_application->helper->__set($helper,$hp);
            
            $this->view_module->helper = new Ibe_Object();
            $this->view_module->helper->__set($helper,$hp);
            
            $this->view_controller->helper = new Ibe_Object();
            $this->view_controller->helper->__set($helper,$hp);
            
            $this->helper = new Ibe_Object();
            $this->helper->__set($helper,$hp);
        }
        
        foreach($this->filters as $filter){
            Ibe_Load::filter($filter)->execute();
        }
        
    }
    
    
    public function setContext(Ibe_Context $context){
        $this->module = $context->getModule();
        $this->controller = $context->getController();
        $this->action = $context->getAction();
    }
    
    public function getViewApplication(){
        return $this->view_application;
    }
    
    public function getViewModule(){
        return $this->view_module;
    }
    
    public function getViewController(){
        return $this->view_controller;
    }
    
    public function redirect($mod,$ctr,$action){
		
	}
	
	public function forward($app,$mod,$ctr,$act, Ibe_Request $req){
		
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
        if(!is_null($this->configure)){
            if($this->configure->isDataBaseActive()){
                Ibe_Database::close();
            }
        }
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
