<?php
/**
 * Classe pai que inplementa os metodos mágigos set e gets
 * Importa  e exporta as variáveis
 *
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 *
 */
class Ibe_Object{


    private $vars = array();

    public function __setVars($vars){

        if(sizeof($this->vars)){
           $this->vars =  array_merge($this->vars,$vars);
        }else{
            $this->vars = $vars;
        }
        return $this;
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
        return $this;
    }

    public function __getVars(){
        $vars = isset($this->vars)? $this->vars:array();
        return $vars;
    }

    public function __get($name) {
        if(isset($this->vars[$name])){
           return $this->vars[$name];
        }
        return NULL;
    }
    
    public function __include($filename){
        if(!file_exists($filename)){
            throw new Ibe_Exception("Arquivo de objeto nao encontrado. [".$filename." ]");
        }
        
        ob_start();
        include_once($filename);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    /**
     * Verifica se alguma variável foi setada através de do metodo mágico: Ex:
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
            throw new Ibe_Exception(Ibe_Exception::TIPO_INVALIDO_DE_VARIAVEL,'string');
        }
        return isset ($this->vars[$name]);
    }

    public function toString(){
        return implode(',', $this->vars);
    }

    public function toJson(){
        return json_encode($this->vars);
    }
}
