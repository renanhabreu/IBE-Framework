<?php
/**
 * Extentions
 * @autor Auto-create by SKT
 */
class Ext_Component extends Ibe_Object{
	protected $complete = NULL;
	protected $before = NULL;
	protected $success = NULL;
	protected $event = 'click';
	protected $action = NULL;
	protected $context = 'body';
	
	
	
	static public function init(Ibe_Object $view){
				
		$componentList = Ibe_load::configure()->getComponentList();
		
		foreach($componentList as $component){
			$className = 'Ext_'.ucfirst(strtolower($component));
			$reflection = NULL;
			
			if (class_exists($className)) {
				$reflection = new ReflectionClass($className);
			} else {
				throw new Ibe_Exception_Load("O componente ".$className." nao foi encontrado");
			}
			
			$field = "c".ucfirst(strtolower($component));
			$view->$field = $reflection->newInstance();
		}
		
	}
	
	/**
	 * @param NULL $complete
	 */
	public function setComplete($complete) {
		$this->complete = $complete;
		return $this;
	}

	/**
	 * @param NULL $before
	 */
	public function setBefore($before) {
		$this->before = $before;
		return $this;
	}

	/**
	 * @param NULL $success
	 */
	public function setSuccess($success) {
		$this->success = $success;
		return $this;
	}

	/**
	 * @param string $event
	 */
	public function setEvent($event) {
		$this->event = $event;
		return $this;
	}

	/**
	 * @param NULL $action
	 */
	public function setAction($action) {
		$this->action = $action;
		return $this;
	}

	/**
	 * @param string $context
	 */
	public function setContext($context) {
		$this->context = $context;
		return $this;
	}
	
	protected  function _print(){}
	
	protected function printConfiguration(){
		echo '  ibe-comp="true" ';
		echo '  ibe-complete="'.$this->complete.'"';
		echo '  ibe-before="'.$this->before.'"';
		echo '	ibe-success="'.$this->success.'"';
		echo '  ibe-event="'.$this->event.'"';
		echo '	ibe-action="'.$this->action.'"';
		echo '  ibe-context="'.$this->context.'"';
	}
	
	public function fix(){
		$this->_print();
	}
}
