<?php

class Skt_Core_Request {
    static public $request_http = FALSE;
    private $params = NULL;
    private $maker   = FALSE;
    
    public function __construct($argv){
    
        $this->params = array();
        
        if(!isset($_SERVER['SHELL'])){            
            Skt_Core_Request::$request_http = TRUE;
            $url = explode('/', rtrim($_SERVER['REQUEST_URI']," \t\n\r\0/"));
            $exit = false;

            $index = array_search('ibe.php', $url);
            
            if ($index) {
                $slices = array_slice($url, 0, $index);
            } else {
                array_pop($url);
                $slices = $url;
            }

            if ($index) {
                $url = array_slice($url, ++$index, sizeof($url));
                $size = sizeof($url);

                switch ($size) {
                    case 0:
                        break;
                    case 1:
                        $this->maker = $url[0];
                        break;
                    default:
                        $this->maker = array_shift($url);
                        $size = sizeof($url);

                        for ($i = 0; $i < $size; $i++) {
                            $j = $i;
                            $_GET[$url[$i]] = isset($url[$i++]) ? $url[$i] : null;
                            $_REQUEST[$url[$j]] = $_GET[$url[$j]];
                        }
                        break;
                    }
                }
                
                
                $this->params = $_REQUEST;
        }else {
            $size = sizeof($argv);
            
            if($size > 1){
                $this->maker = $argv[1];
                for($i = 2; $i < $size; $i++){
                    $param = explode(":",$argv[$i]);
                    $this->params[$param[0]] = $param[1];
                }
            }
        } 
    }
    
    public function getParam($name,$default = NULL){
        if(isset($this->params[$name])){
           $default = $this->params[$name]; 
        }
        
        return $default;
    }
    
    public function getParams(){
        return $this->params;
    }
    
    public function getParamsWithDefaults($defaults){
        
        $vars = array();
        
        foreach($defaults as $var_name=>$default){
            $vars[$var_name] = $this->getParam($var_name,$default);
        }
        
        return $vars;
    }
    
    public function getMaker(){
        return $this->maker;
    }
    
}
