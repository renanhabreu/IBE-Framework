<?php

/**
 * Classe de tratamento das requisicoes
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
class Ibe_Request {

    static private $default_model = 'public';
    static private $default_controller = 'public';
    static private $default_action = 'public';

    /**
     * Parametros enviados pela requisicao
     * @var array
     */
    private $params = array();
    /**
     * Usuario atual do sistema
     * @var Ibe_User
     */
    private $user = null;

    const IS_BOOLEAN = 'boolean';
    const IS_INTEGER = 'integer';
    const IS_FLOAT   = 'float';
    const IS_STRING  = 'string';
    const IS_ARRAY   = 'array';
    const IS_OBJECT  = 'object';
    const IS_NULL    = 'null';


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
    static public function setDefaultModule($name){ self::$default_model = $name;}

    /**
     * Seta o nome do controlador padrao quando este nao for citado na URL
     * @param type $name
     */
    static public function setDefaultController($name){ self::$default_controller = $name;}

    /**
     * Seta o nome da ação padrao quando este nao for citado na URL
     * @param type $name
     */
    static public function setDefaultAction($name){ self::$default_action = $name;}

    /**
     * Captura o nome do módulo padrao do aplicativo
     * @param type $name
     */
    static public function getDefaultModule(){ return self::$default_model;}

    /**
     * Captura o nome do controlador padrao do aplicativo
     * @param type $name
     */
    static public function getDefaultController(){ return self::$default_controller;}

    /**
     * Captura o nome da ação padrao do aplicativo
     * @param type $name
     */
    static public function getDefaultAction(){ return self::$default_action;}

    /**
     * Inicia a execucao de uma nova requisicao HTTP ao aplicativo
     * @return Ibe_Request
     */
    static public function dispatch($user_session = 'all_user') {

        //decodifica a url para identificar a requisicao
        Ibe_Request_Decode::url();

        //Cria uma nova requisicao
        $request = new Ibe_Request();
        $request->setUser(Ibe_User::getInstance($user_session));

        //executa a acao
        Ibe_Request_Execute::action($request);
    }

    /**
     * Seta o usuario que realizou a requisicao
     * @param Ibe_User $user
     * @return Ibe_Request
     */
    public function setUser(Ibe_User $user) {
        $this->user = $user;
        return $this;
    }

    /**
     * Retorna o usuario atual do sistema
     * @return Ibe_User
     */
    public function getUser() {
        return $this->user;
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

        if(!settype($valor, $type)){
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
    public function clearParams(){
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
        $value = strip_tags($value);
        $value = addslashes($value);

        return urldecode($value);
    }

}