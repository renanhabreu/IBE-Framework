<?php

/**
 * Classe de tratamento das requisicoes
 * @author Renan Abreu <renanhabreu@gmail.com>
 * @package ibe
 */
class Ibe_Request {
	static private $_is_https = FALSE;
	static private $_module = 'public';
	static private $_controller = 'public';
	static private $_action = 'public';

	/**
	 * Parametros enviados pela requisicao
	 * @var array
	 */
	private $params = array();

	/* Data Types */

	const IS_BOOLEAN = 'boolean';
	const IS_INTEGER = 'integer';
	const IS_FLOAT = 'float';
	const IS_STRING = 'string';
	const IS_ARRAY = 'array';
	const IS_OBJECT = 'object';
	const IS_NULL = 'null';
	const IS_ANY = "/.*/";
	const IS_EMAIL = "/^[\w\-\+\&\*]+(?:\.[\w\-\_\+\&\*]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/";
	const IS_DATE = "/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/";
	const IS_NUMBER = "/^.*[0-9]$/";
	const IS_ALFA = "/^.*[a-zA-Z]$/";
	const IS_NO_EMPTY = "/^.+$/";

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
	 * @param string $name
	 */
	static public function setDefaultModule($name) {
		self::$_module = $name;
		return self;
	}

	/**
	 * Seta o nome do controlador padrao quando este nao for citado na URL
	 * @param string $name
	 */
	static public function setDefaultController($name) {
		self::$_controller = $name;
		return self;
	}

	/**
	 * Seta o nome da ação padrao quando este nao for citado na URL
	 * @param string $name
	 */
	static public function setDefaultAction($name) {
		self::$_action = $name;
		return self;
	}

	/**
	 * Seta a descricao para o framework se as requisições serão HTTPS
	 * @param boolean $is
	 * @return string
	 */
	static public function setIsHttps($is = TRUE) {
		self::$_is_https = $is;
		return self;
	}

	/**
	 * Inicia a execucao de uma nova requisicao HTTP ao aplicativo
	 * @return Ibe_Request 
	 */
	static public function dispatch($init_session = FALSE) {

		if ($init_session) {
			self::initSession();
		}

		$ctx = Ibe_Context::getInstance(self::$_module, self::$_controller,
				self::$_action, self::$_is_https);
		$request = new self();

		$action = Ibe_Load::action();
		$action->setContext($ctx);
		$action->preAction($request);
		$template = $action->execute($request);
		$action->posAction($request);

		$view_app = $action->getViewApplication();
		$view_mod = $action->getViewModule();
		$view_ctr = $action->getViewController();
		$view_act = $action->getViewAction();

		$view = new Ibe_View($view_app, $view_mod, $view_ctr, $view_act);
		$view->show($template);
	}
	
	/**
	 * Anicia a sessao IBE
	 */
	static private function initSession() {
		session_start();

		if (!isset($_SESSION['_IBE'])) {
			$_SESSION['_IBE'] = array();
		}
	}

	/**
	 * Finaliza e descrio a sessao IBE
	 */
	static public function finalizeSession() {
		session_destroy();
	}

	/**
	 * Retorna um array com todos os parametros passados como post, get e cookies
	 * tratados
	 *
	 * @return array
	 */
	public function getAllParams() {
		return $this->params;
	}

	/**
	 * Retorna o valor de um parametro passado como post,get ou cookie,
	 * caso o parametro nao exista sera devolvido um valor_padrao
	 * @param string $nome
	 * @param mixed $valor_padrao
	 * @return mixed
	 */
	public function getParam($nome, $valor_padrao = NULL, $types = FALSE, array $related = array()) {
		$params = $this->params;
		$valor = null;
		if (!isset($params[$nome]) || empty($params[$nome])) {
			$valor = $valor_padrao;
		} else {
			$valor = $params[$nome];
		}

		if ($types !== FALSE) {

			if (!is_array($types)) {
				$types = array($types);
			}

			foreach ($types as $type) {
				switch ($type) {
				case Ibe_Request::IS_ARRAY:
				case Ibe_Request::IS_BOOLEAN:
				case Ibe_Request::IS_FLOAT:
				case Ibe_Request::IS_INTEGER:
				case Ibe_Request::IS_NULL:
				case Ibe_Request::IS_OBJECT:
				case Ibe_Request::IS_STRING:
					if (!settype($valor, $type)) {
						$valor = $valor_padrao;
					}
					break;
				default:
					$this->isExternalValidated($nome,$valor,$type);
					break;
				}
			}
		}
		
		if(sizeof($related)){
			$this->isParamRelated($nome,$related);
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
	 * Compara se o valor de um parametro passado como post,get,cookie eh igual ao
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

		$value = preg_replace(
				"/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/",
				"", $value);
		$value = trim($value);
		$value = htmlentities($value);
		$value = addslashes($value);

		return urldecode($value);
	}

	/**
	 * Realiza a validacao do parametro atraves atraves a verificacao
	 * nos arquivos do repositorio _validators
	 * @param string $validator
	 * @throws Ibe_Exception
	 */
	private function isExternalValidated($nome,$valor,$type) {

		$ibe_validator = Ibe_Load::validator($type);
		if ($ibe_validator === FALSE) {
			if (!preg_match($type, $valor)) {
				throw new Ibe_Exception(
						'Valor de ' . $nome . ' [' . $valor . '] invalido');
			}
		} else if (!$ibe_validator->isValid($valor)) {
			throw new Ibe_Exception($ibe_validator->getMessage());
		}
		
	}
	/**
	 * Este metodo verifica a dependencia relacionada ao campo $nome.
	 * Quando o campo $nome eh diferente de NULL todos os parametros
	 * dentro do array $related devem obrigatoriamente ser diferentes
	 * de NULL
	 * 
	 * @param string $nome
	 * @param array $related
	 * @throws Ibe_Exception
	 */
	private function isParamRelated($nome,array $related){
		
		if(!$this->isParamEquals($nome, NULL)){
			foreach($related as $field){
				if($this->isParamEquals($field, NULL)){
					throw new Ibe_Exception('Campo '.$field.' obrigatorio');
				}
			}
		}
	}

}
