<?php
/**
 * Contexto eh o ambiente que possui todo o mundo do aplicativo. Eh responsavel
 * por retorna a url_base da aplicacao, controlador, modulo e a aplicacao. 
 * Elem disso, eh responsavel por capturar todos os dados via GET e POST e 
 * configura-los para para o objeto Ibe_Request 
 * @author Renan Abreu <renanhabreu@gmail.com>
 * @package ibe
 */
class Ibe_Context {

    static private $instance = NULL;
    private $url_base = NULL;
    private $module = NULL;
    private $controller = NULL;
    private $action = NULL;
    private $is_https = FALSE;

    /**
     * Retorna a instancia do contexto da aplicacao
     * @param type $module
     * @param type $controller
     * @param type $action
     * @return Ibe_Context
     */
    static public function getInstance($module = NULL, $controller = NULL, $action = NULL, $is_https = FALSE) {
        if (is_null(self::$instance)) {
            self::$instance = new self($module, $controller, $action);
        }

        return self::$instance;
    }

    private function __construct($module, $controller, $action) {

        $url = explode('/', rtrim($_SERVER['REQUEST_URI'], " \t\n\r\0/"));
        $index = array_search('index.php', $url);
        $PORT = '';
        if($_SERVER['SERVER_PORT'] != 80){
        	$PORT = ':' . $_SERVER['SERVER_PORT'];
        }
		$https = ($this->is_https)? 'https://':'http://';
		
		$url_complete = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$url_ = strstr($url_complete, 'index.php', true);
		
		if(!$url_){
			$url_ = $url_complete;
		}
		
	    $url_ = $url_ . $PORT;
        if (($_ = strstr($url_, '?', true))) {
             $url_ = $_;
        }
     	$this->url_base = $https . $url_ ;
		//Dev_debug::error($this->url_base);
		
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

        if (!is_null($module)) {
            $this->module = $module;
        }

        if (!is_null($controller)) {
            $this->controller = $controller;
        }

        if (!is_null($action)) {
            $this->action = $action;
        }

        return TRUE;
    }

    public function getUrlBase() {
        return $this->url_base;
    }

    public function getModule() {
        return $this->module;
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    /**
     * Registra uma variavel na sessao _IBE
     * @param string $name
     * @param mixed $value 
     * @todo validar variavel de sessao
     */
    public function setParam($name, $value) {
        $name = strtoupper($name);
        $_SESSION['_IBE'][$name] = $value;
        
    }

    /**
     * Retorna um parametro da sessao _IBE, 
     * @param string $name
     * @return mixed FALSE em caso de variavel nao registrada 
     */
    public function getParam($name) {
        $name = strtoupper($name);
        $value = FALSE;
        
        if (isset($_SESSION['_IBE'][$name])) {
            $value = $_SESSION['_IBE'][$name];
        }

        return $value;
    }

    /**
     * Retorna todas as variaveis de contexto
     * @return mixed
     */
    public function getAllParam() {
        return $_SESSION['_IBE'];
    }


}
