<?php

/**
 * Classe de tratamento das requisicoes
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
class Ibe_Request {

    static private $_module = 'public';
    static private $_controller = 'public';
    static private $_action = 'public';

    /**
     * Parametros enviados pela requisicao
     * @var array
     */
    private $params = array();

    const IS_BOOLEAN = 'boolean';
    const IS_INTEGER = 'integer';
    const IS_FLOAT = 'float';
    const IS_STRING = 'string';
    const IS_ARRAY = 'array';
    const IS_OBJECT = 'object';
    const IS_NULL = 'null';

    private function __construct() {
        $params = array();

        foreach ($_REQUEST as $field => $value) {
            if (!is_array($value)) {
                $params[$field] = $this->clearParam($value);
            } else {
                $array_tmp = array();
                foreach ($value as $v) {
                    $array_tmp[] = $this->clearParam($v);
                }
                $params[$field] = $array_tmp;
            }
        }
        $this->params = $params;
    }

    /**
     * Seta o nome do modulo padrao quando este nao for citado na URL
     * @param type $name
     */
    static public function setDefaultModule($name) {
        self::$_module = $name;
    }

    /**
     * Seta o nome do controlador padrao quando este nao for citado na URL
     * @param type $name
     */
    static public function setDefaultController($name) {
        self::$_controller = $name;
    }

    /**
     * Seta o nome da ação padrao quando este nao for citado na URL
     * @param type $name
     */
    static public function setDefaultAction($name) {
        self::$_action = $name;
    }

    /**
     * Inicia a execucao de uma nova requisicao HTTP ao aplicativo
     * @return Ibe_Request
     */
    static public function dispatch() {

        $ctx = Ibe_Context::getInstance(self::$_module, self::$_controller, self::$_action);
        $request = new self();

        $action = Ibe_Load::action();
        $action->setContext($ctx);
        $action->preAction($request);
        $template = $action->execute($request);
        $action->posAction($request);
        
        $view_app = $action->getViewApplication();
        $view_mod = $action->getViewModule();
        $view_ctr = $action->getViewController();

        $view = new Ibe_View($view_app, $view_mod, $view_ctr, $action);
        $view->show($template);
    }

    static public function initSession() {
        session_start();

        if (!isset($_SESSION['_IBE'])) {
            $_SESSION['_IBE'] = array();
        }
    }

    static public function finalizeSession() {
        session_destroy();
    }

    /**
     * Retorna um array com todos os parametros passados como post, get e cookies
     * tratados
     *
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Retorna o valor de um parametro passado como post,get ou cookie,
     * caso o parametro não exista sera devolvido um valor_padrao
     * @param string $nome
     * @param mixed $valor_padrao
     * @return mixed
     */
    public function getParam($nome, $valor_padrao = null, $type = Ibe_Request::IS_STRING) {
        $params = $this->params;
        $valor = null;
        if (!isset($params[$nome]) || empty($params[$nome])) {
            $valor = $valor_padrao;
        } else {
            $valor = $params[$nome];
        }

        if (!settype($valor, $type)) {
            $valor = $valor_padrao;
        }

        return $valor;
    }

    /**
     * Seta um novo parametro para a requisicao atual
     * @param string $nome
     * @param mixed $value
     * @return Request
     */
    public function setParam($nome, $value) {
        $this->params[$nome] = $this->clearParam($value);
        return $this;
    }

    /**
     * Compara se o valor de um parametro passado como post,get,cookie é igual ao
     * passado como parametro $valor
     *
     * @param string $nome
     * @param mixed $valor
     * @return bool
     */
    public function isParamEquals($nome, $valor) {
        $param = $this->getParam($nome);
        return ($valor == $param);
    }

    /**
     * Limpa todos os parametros da requisicao
     * @return Ibe_Request
     */
    public function clearParams() {
        $this->params = array();
        return $this;
    }

    /**
     * Realiza a limpeza do parametro para evitar codigo malicioso pela url
     * @param mixed $value
     * @return string
     */
    private function clearParam($value) {

        $value = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $value);
        $value = trim($value);
        $value = htmlentities($value);
        $value = addslashes($value);

        return urldecode($value);
    }

}
