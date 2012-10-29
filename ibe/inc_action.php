<?php

/**
 * Controlador de acoes
 * @author Renan Abreu <renanhabreu@gmail.com>
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
    protected $response = NULL;
    
    private $configure = NULL;
    
    public function __construct() {        
		$this->setContext(Ibe_Context::getInstance());
		$this->filters[] = $this->action;

		$this->loadConfigure();
		$this->executeFilters();
		$this->configureDatabase();
		$this->configureResponse();
		$this->configureHelpers();
    }
    
    /**
     * Configura o contexto da aplicacao para acao
     * @param Ibe_Context $context 
     */
    public function setContext(Ibe_Context $context){
        $this->module = $context->getModule();
        $this->controller = $context->getController();
        $this->action = $context->getAction();
    }
    
    /**
     * Retorna a view da aplicacao
     * @return Ibe_Template 
     */
    public function getViewApplication(){
        return $this->view_application;
    }
    
    /**
     * Retorna a view do modulo
     * @return Ibe_Template 
     */
    public function getViewModule(){
        return $this->view_module;
    }
    
    /**
     * Retorna a view do controller
     * @return Ibe_Template
     */
    public function getViewController(){
        return $this->view_controller;
    }

    /**
     * Retorna o template da acao
     * @return Ibe_Template
     */
    public function getViewAction(){
    	return $this->view_action;
    }
    
    /**
     * Retorna um objeto padrao para resposta de JSON
     * @return stdClass 
     */
    public function getResponse(){
        return $this->response;
    }
    
    /**
     * Redireciona uma requisicao a outra acao
     * @param string $mod
     * @param string $ctr
     * @param string $action
     * @param array $get 
     */
    public function redirect($mod,$ctr,$action,array $get = array()){
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
		$this->__setVars($req->getAllParams());
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
     * Metodo que implementa a acao logica do aplicativo
     * Este metodo serah executado quando o usuario
     * realizar uma requisicao via URL ao aplicativo.
     * é identificado como o terceiro parametro da URL ou o valor padrao
     *
     * http://localhost/index.php/module/controller/action
     *
     * @return int
     */
    abstract public function execute(Ibe_request $req);
    
	/**
	 * Realiza a leitura do arquivo de configuracao
	 */
    private function loadConfigure() {
    	/* Realiza a leitura do arquivo de configuracao */
    	$this->configure = Ibe_Load::configure();
    	if (!isset($this->configure)) {
    		$this->modules_params = $this->configure->getModulesParams();
    		$this->filters = array_merge($this->configure->getFilters(),
    				$this->filters);
    	}
    }
    
    private function configureDatabase() {
    
    	if ($this->configure->isDataBaseActive()) {
    		$host = $this->configure->getDataBaseHost();
    		$user = $this->configure->getDataBaseUser();
    		$pass = $this->configure->getDataBasePass();
    		$schema = $this->configure->getDataBaseSchm();
    
    		Ibe_Database::open($host, $user, $pass, $schema);
    	}
    }
    
    /**
     * Configura as views caso a resposta nao seja json
     * no arquivo de configuracao. Caso seja jSON eh
     * apenas instanciado uma classe padrao para objetos
     *
     */
    private function configureResponse() {
    
    	if (!$this->configure->isActionReturnJson()) {
			$this->view_application = new Ibe_Template(Ibe_View::APPLICATION);
			$this->view_module = new Ibe_Template(Ibe_View::MODULE);
			$this->view_controller = new Ibe_Template(Ibe_View::CONTROLLER);
			$this->view_action = new Ibe_Template(Ibe_View::ACTION);

			$this->view_application->helper = new Ibe_Object();
			$this->view_module->helper = new Ibe_Object();
			$this->view_controller->helper = new Ibe_Object();
			$this->view_action->helper = new Ibe_Object();

			$this->view_application->modules_params = $this->modules_params;
			$this->view_module->modules_params = $this->modules_params;
			$this->view_controller->modules_params = $this->modules_params;
			$this->view_action->modules_params = $this->modules_params;
    	} else {
    		$this->response = new Ibe_Object();
    	}
    }
    
    /**
     * Configura os helpers registrados no arquivo de configuracao
     * do modulo
     */
    private function configureHelpers() {
    
    	$this->helper = new Ibe_Object();
    	$helpers = $this->configure->getHelpers();
    
    	/**
    	 * Helpers simples $helpers = array("x","xx","xxxx")
    	 *  sao inclusos em todos os controladores
    	 * Helpers limitados Helpers = array("x"=>"controlador1|controlador2")
    	 *  sao inclusos apenas nos controladores indicados
    	*/
    	foreach ($helpers as $helper => $all) {
    		$helper_name = $all;
    		if (is_string($helper)) {
    			$helper_name = FALSE;
    			$controllers = explode("|", $all);
    
    			if (array_search($this->controller, $controllers) !== FALSE) {
    				$helper_name = $helper;
    			}
    		}
    		if ($helper_name != FALSE) {
    			$hp = Ibe_Helper::get($helper_name);
    			if (!$this->configure->isActionReturnJson()) {
    				$this->view_application->helper->__set($helper, $hp);
    				$this->view_module->helper->__set($helper, $hp);
    				$this->view_controller->helper->__set($helper, $hp);
    			}
    			$this->helper->__set($helper, $hp);
    		}
    	}
    }
    
    /**
     * Executa os filtros registrados no arquivo de configuracao
     * e o filtro com o nome da classe action por padrao
     */
    private function executeFilters() {
    	foreach ($this->filters as $filter) {
    		$instance = Ibe_Load::filter($filter);
    
    		if (isset($instance)) {
    			$instance->execute();
    		}
    	}
    }
    
    
}
