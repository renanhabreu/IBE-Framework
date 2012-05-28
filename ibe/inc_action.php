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
        
        $this->view_application->helper = new Ibe_Object();
        $this->view_module->helper = new Ibe_Object();
        $this->view_controller->helper = new Ibe_Object();
        $this->helper = new Ibe_Object();
        
        
        $this->view_application->modules_params = $this->modules_params;            
        $this->view_module->modules_params = $this->modules_params;  
        $this->view_controller->modules_params = $this->modules_params;  
        
        foreach($helpers as $helper){
            $hp = Ibe_Helper::get($helper);
            
            $this->view_application->helper->__set($helper,$hp);            
            $this->view_module->helper->__set($helper,$hp);            
            $this->view_controller->helper->__set($helper,$hp);            
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
    
    public function redirect($mod,$ctr,$action,$get = array()){
		$url = Ibe_Context::getInstance()->getUrlBase();
		$param = '/';
		
		foreach($get as $name=>$value){
				$param .= $name.'/'.$value.'/';
		}
		
		header('location:'.$url.'index.php/'.$mod.'/'.$ctr.'/'.$action.$param);
		exit();
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
     * M�todo que implementa a acao l�gica do aplicativo
     * Este metodo ser� executado quando o usu�rio
     * realizar uma requisicao via URL ao aplicativo.
     * � identificado como o terceiro parametro da URL ou o valor padrao
     *
     * http://localhost/index.php/module/controller/action
     *
     * @return int
     */
    abstract public function execute(Ibe_request $req);
    
    
}
