<?php
/**
 * Classe pai que inplementa os metodos magigos set e gets
 * Importa  e exporta as variaveis
 *
 * @author Renan Abreu <renanhabreu@gmail.com>
 * @package ibe
 */
class Ibe_Object{
	private $check_var_msg = 'Variavel dinamica nao foi encontrada no objeto';
	private $check_var = FALSE;
    private $vars = array();
	
    public function __construct($check_var = FALSE,$msg = NULL){
    	$this->check_var = $check_var;
    	if(isset($msg)){
    		$this->check_var_msg = $msg;
    	}
    }
    
    /**
     * Inicializa as variaveis dinamicas de classe
     * @param array $vars
     * @return Ibe_Object
     */
    public function __setVars($vars,$over = FALSE){

        if(sizeof($this->vars) || $over){
           $this->vars =  array_merge($this->vars,$vars);
        }
        return $this;;
    }
	
    public function __set($name, $value) {
        $this->vars[$name] = $value;
        return $this;
    }

    /**
     * Retorna a lista das variaveis dinamicas
     * @return multitype:
     */
    public function __getVars(){
        $vars = isset($this->vars)? $this->vars:array();
        return $vars;
    }

    public function __get($name) {
    	
        if($this->check_var && !isset($this->vars[$name])){
        	throw new Ibe_Exception_Object($this->check_var_msg.' #VAR_NAME: $'.$name);
        }
		
        return $this->vars[$name];
    }
    
    /**
     * Inclui um arquivo php no contexto da classe
     * @param string $filename
     * @throws Ibe_Exception_Object
     * @return string
     */
    public function __include($filename){
        if(!file_exists($filename)){
        	$core = IBE_FRAMEWORK_PATH . 'default'. DS . $filename;
        	if(!file_exists($core)){
            	throw new Ibe_Exception_Object("Arquivo de objeto nao encontrado. [".$filename." ]");
        	}else{
        		$filename = $core;
        	}
        }
        
        ob_start();
        include_once($filename);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    /**
     * Verifica se alguma vari�vel foi setada atrav�s de do metodo m�gico: Ex:
     * <code>
     *
     *  class Home{
     *     public $color;
     *  }
     *  $obj = new Home();
     *  $obj->color = 'red';
     *  $obj->size  = 100;  /// Utilizacao do metodo magigo __set
     *
     *  $obj->isSetVar('color'); // FALSE
     *  $obj->isSetVar('size');  // TRUE
     *
     * </code>
     * @param type $name
     * @return type
     */
    public function isSetVar($name){
        if(!is_string($name)){
            throw new Ibe_Exception_Object(Ibe_Exception::TIPO_INVALIDO_DE_VARIAVEL,'string');
        }
        return isset($this->vars[$name]);
    }

    public function toString(){
        return implode(',', $this->vars);
    }
	
    /**
     * Transforma as variaveis dinamicas em 
     * formato jSON
     * @return string
     */
    public function toJson(){
        return json_encode($this->vars,JSON_FORCE_OBJECT);
    }
}
