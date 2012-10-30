<?php
/**
 * Extentions
 * 
 * @method Ibe_Component_Interface setComplete() setComplete(string $name) 
 * @method Ibe_Component_Interface setBefore() setBefore(string $name)  
 * @method Ibe_Component_Interface setSuccess() setSuccess(string $name)  
 * @method Ibe_Component_Interface setError() setError(string $name) 
 * @method Ibe_Component_Interface setEvent() setEvent(string $name) 
 * @method Ibe_Component_Interface setAction() setAction(string $name) 
 * @method Ibe_Component_Interface setContext() setContext(string $name) 
 * 
 * @autor Renan Abreu
 * 
 */
abstract class Ibe_Component_Interface extends Ibe_Object{
	static public $next = 1;
	static private $componentes = array();
	//private
	private $component_val = array(
			'component'=>'true'
	);
	//protected
	protected $component_vars = array();
	protected $id = 0;
	
	
	
	public function __construct($id = NULL){
		if(!isset($id)){
			$id = Ibe_Component_Interface::$next;
			Ibe_Component_Interface::$next++;
		}
		$this->id = $id;
		$this->configureMe();
		$this->component_val = array_merge($this->component_vars,$this->component_val);	
	}
	
	/**
	 * Configura os parametros do componente
	 */
	abstract protected function configureMe();

	/**
	 * Monta o HTML do componente
	 */
	abstract protected function printMe(); 
	
	/**
	 * Configura os parametros gerais de componente
	 */
	protected function printConfiguration(){
		echo '  ibe-component="'.$this->getComponent().'" ';
		echo '  ibe-component-id="'.$this->id.'"';
	}
	
	
	/**
	 * Fixa ou imprime o componente na tela
	 */
	public function fix(){
		$this->printMe();
	}
	
	/**
	 * Copia as variaveis de um componente
	 * @param Ibe_Component_Interface $component
	 */
	public function cloneAttributes(Ibe_Component_Interface $component){
		$this->component_vars = array_merge($this->component_vars,$component->getComponentVars());
		$this->component_val = array_merge($this->component_val,$component->getComponentVals());
		return $this;
	}
	
	/**
	 * Retorna o array de valores dos parametros de componentes
	 * setados
	 * @return array
	 */
	public function getComponentVals(){
		return $this->component_val;
	}
	
	/**
	 * Retorna os atributos de componentes configurados
	 * 
	 * @return array:
	 */
	public function getComponentVars(){
		return $this->component_vars;
	}
	
	/**
	 * Chamada dos metodos sets e gets da classe
	 *
	 * @param string $method
	 * @param string $parameter
	 * @return mixed
	 */
	public function __call($method, $parameter) {
		$size = strlen($method);
		$varName = strtolower(substr($method, 3, $size));
		$method = substr($method, 0, 3);
		
		if(!isset($this->component_val[$varName]) && $this->component_val[$varName] !== NULL){
			throw new Ibe_Exception_Component('Variavel $'.$varName.' de componente nao definida');
		}
		
		if ($method == 'set') {
			$val = $parameter[0];
			$this->component_val[$varName] = $val;
		} elseif ($method == 'get') {
			$val = $this->component_val[$varName];
			return $val;
		}
		
	
		return $this;
	}
	
	
}
