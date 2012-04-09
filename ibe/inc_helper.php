<?php

abstract class Ibe_Helper extends Ibe_Object{

    static private $instances = array();
    protected $params = array();
        
        
    abstract public function execute();
    
    static public function get($name){
    
        if(!isset(self::$instances[$name])){            
            self::$instances[$name] =  Ibe_Load::helper($name);
        }
        
        return self::$instances[$name];
    }
    
    public function __call($name,$arguments){
        
        if($name == "_"){
            
            if(sizeof($this->params) > 0){
                $len = sizeof($arguments);
                for($i = 0; $i < $len; $i++ ){
                    $this->__set($this->params[$i],$arguments[$i]);
                }
            }
            
            return $this->execute();
        }
        
    }
    
}
