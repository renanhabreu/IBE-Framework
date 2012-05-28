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
                $i = 0;
				foreach($this->params as $key=>$val){					
					if(isset($arguments[$i])){
						$this->params[$key] = $arguments[$i];
					}					
					$i++;
				}
            }
            
            return $this->execute();
        }
        
    }
    
}
