<?php

class Ibe_Context{
    static private $instance = NULL;
    
    private $url_base = NULL;
    private $module = NULL;
    private $controller = NULL;
    private $action = NULL;
    
    static public function getInstance($module = NULL,$controller = NULL,$action = NULL){
        if(is_null(self::$instance)){
            self::$instance = new self($module,$controller,$action);
        }
        
        return self::$instance;
    }
    
    private function __construct($module,$controller,$action){
        
        $this->initSession();
        
        
        $url = explode('/', rtrim($_SERVER['REQUEST_URI']," \t\n\r\0/"));
        $exit = false;
        $index = array_search('index.php', $url);
        
        if(($_ = strstr($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],'index.php',true))){
			$this->url_base = 'http://'.$_; 
		}else{
			$this->url_base = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		
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
                    $module = $url[0];
                    break;
                case 2:
                    $module = $url[0];
                    $controller = $url[1];
                    break;
                case 3:
                    $module = $url[0];
                    $controller = $url[1];
                    $action = $url[2];
                    break;
                default:
                    $module = array_shift($url);
                    $controller = array_shift($url);
                    $action = array_shift($url);
                    $size = sizeof($url);

                    for ($i = 0; $i < $size; $i++) {
                        $j = $i;
                        $_GET[$url[$i]] = isset($url[$i++]) ? $url[$i] : null;
                        $_REQUEST[$url[$j]] = $_GET[$url[$j]];
                    }
                    break;
            }
        }
        
        if(!is_null($module)){
            $this->module     = $module;
        }
        
        if(!is_null($controller)){
            $this->controller = $controller;
        }
        
        if(!is_null($action)){
            $this->action     = $action;
        }

        return TRUE;
    }
    
    private function initSession(){
        session_start();
        
        if(!isset($_SESSION['_USER'])){
            $_SESSION['_USER'] = array();
        }
    }
        
    public function getUrlBase(){
        return $this->url_base;
    }
   
    public function getModule(){
        return $this->module;
    }
    
    public function getController(){
        return $this->controller;
    }
    
    public function getAction(){
        return $this->action;
    }
}
