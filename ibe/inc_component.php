<?php

abstract class Ibe_Component extends Ibe_Object{
	protected $application = NULL;
	protected $module = NULL;
	protected $controller = NULL;
	protected $action = NULL;
	    
    /**
	 * @param string $application
	 * @return Ibe_Component
	 */
	public function setApplication($application) {
		$this->application = $application;
		return $this;
	}

	/**
	 * @param string $module
	 * @return Ibe_Component
	 */
	public function setModule($module) {
		$this->module = $module;
		return $this;
	}

	/**
	 * @param string $controller
	 * @return Ibe_Component
	 */
	public function setController($controller) {
		$this->controller = $controller;
		return $this;
	}

	/**
	 * @param string $action
	 * @return Ibe_Component
	 */
	public function setAction($action) {
		$this->action = $action;
		return $this;
	}

	
	/**
	 * @return the $application
	 */
	public function getApplication() {
		return $this->application;
	}

	/**
	 * @return the $module
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * @return the $controller
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * @return the $action
	 */
	public function getAction() {
		return $this->action;
	}

	abstract public function configure();
	
	
	/**
     * Instancia e registra um novo componente 
     * para as telas
     * 
     * @param string $name
     * @param mixed $id
     * @throws Ibe_Exception_Component
     * @return Ibe_Component_Interface
     */
    protected function registerComponente($name,$id,Ibe_Object $context = NULL){
    	$alert = 'O componente requerido na view nao foi registrado.';
    	$name = strtolower($name);
    	if(!isset($context)){
    		$context = $this->action;
    	}
    	
    	if(!$context->isSetVar('component')){
    		$context->component = new Ibe_Object(TRUE,$alert);
    		$context->component->$name = new Ibe_Object(TRUE,$alert);
    	}else if(!$context->component->isSetVar($name)){
    		$context->component->$name = new Ibe_Object(TRUE,$alert);    		
    	}
    	
    	if(!isset($context->component->$name->$id)){
    		$reflection = Ibe_Load::component($name);
    		$context->component->$name->$id = $reflection->newInstanceArgs(array($id));
    	}
    	
    	return $context->component->$name->$id;
    }
    
    /**
     * Instancia um Elemento Componente 
     * @param string $name
     * @throws Ibe_Exception_Component
     * @return Ibe_Component_Interface
     */
    static public function getInstance($name){
    	$className = Ibe_Load::component($name);
    	return $reflection->newInstance();
    }
    
}