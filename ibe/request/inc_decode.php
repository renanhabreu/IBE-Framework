<?php
/**
 * Classe que decodifica uma requisicao
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage request
 */
abstract class Ibe_Request_Decode {

    static private $module = NULL;
    static private $controller = NULL;
    static private $action = NULL;

    static private $module_path = NULL;
    static private $controller_path = NULL;
    static private $action_path = NULL;

    /**
     * Decodifica a URL a fim de identificar qual o modulo, controlador e acao a
     * ser executada.
     * Ex:
     * http://www.ibeframyapp.com.br/public/post/list
     *
     * O aplicativo ira buscar o Modulo Plublic, o Controlador Post e o
     * Arquivo/Classe/Aчуo list executando o seu mщtodo execute
     *
     * @return boolean
     */
    static public function url() {
        $module     = Ibe_Request::getDefaultModule();
        $controller = Ibe_Request::getDefaultController();
        $action     = Ibe_Request::getDefaultAction();

        $url = explode('/', $_SERVER['REQUEST_URI']);
        $exit = false;

        $index = array_search('index.php', $url);
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

        self::$module     = self::checkModule($module);
        self::$controller = self::checkController($controller);
        self::$action     = self::checkAction($action);

        return TRUE;
    }

    /**
     * Retorna o nome do modulo identificado na URL ou o modulo padrao
     * caso na url nao tenha sido passado nenhum parametro contendo o modulo
     * @return string
     */
    static public function getModule(){ return self::$module; }
    /**
     * Retorna o nome do controlador identificado na URL ou o controlador padrao
     * caso na url nao tenha sido passado nenhum parametro contendo o controlador
     * @return string
     */
    static public function getController(){ return self::$controller; }
    /**
     * Retorna o nome da acao identificada na URL ou a acao padrao
     * caso na url nao tenha sido passado nenhum parametro contendo o acao.
     * Este metodo adiciona uma string no inicio, final ou no inicio e final
     * do nome da acao
     * @return string
     */
    static public function getAction($append = '',$prepend = ''){ return $append.self::$action.$prepend; }

    /**
     * Retorna o nome da pasta que contem os modulos do aplicativo
     * @return string
     */
    static public function getModulePath(){ return self::$module_path; }

    /**
     * Retorna o nome da pasta que contem os controladores do aplicativo
     * @return string
     */
    static public function getControllerPath(){ return self::$controller_path;}
    /**
     * Retorna o nome da pasta que contem as acao a serem executadas ao inciar uma requisicao
     * @return string
     */
    static public function getActionPath(){ return self::$action_path; }

    /**
     * Verifica se o modulo existea
     * @param string $module
     * @return boolean
     */
    static private function checkModule($module){
        $paths_name = Ibe_Source::getPathModuleName();
        $module = strtolower($module);

        self::$module_path = $paths_name.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR;

        if(!is_dir(self::$module_path)){
            throw new Ibe_Exception(Ibe_Exception::MODULO_NAO_ENCONTRADO,$module);
        }else{
            Ibe_Configure::get();
        }

        return $module;
    }
    /**
     * Verifica se o controlador existe
     * @param string $controller
     * @return boolean
     */
    static private function checkController($controller){
        $paths_name = Ibe_Source::getPathModuleName();
        $controller = strtolower($controller);

        self::$controller_path = $paths_name
                               .DIRECTORY_SEPARATOR
                               .self::$module
                               .DIRECTORY_SEPARATOR.$controller
                               .DIRECTORY_SEPARATOR;

        if(!is_dir(self::$controller_path)){
            throw new Ibe_Exception(Ibe_Exception::CONTROLADOR_NAO_ENCONTRADO,$controller);
        }

        return $controller;
    }
    /**
     * Verifica se uma acao existe
     * @param string $action
     * @return boolean
     */
    static private function checkAction($action){
        $paths_name = Ibe_Source::getPathModuleName();
        $ctr_paths_name = Ibe_Source::getPathActionName();

        $action = strtolower($action);
        self::$action_path   = $paths_name.DIRECTORY_SEPARATOR.self::$module
                             .DIRECTORY_SEPARATOR.self::$controller
                             .DIRECTORY_SEPARATOR.$ctr_paths_name
                             .DIRECTORY_SEPARATOR;

        if(!file_exists(self::$action_path.'inc_'.$action.'.php')){
            throw new Ibe_Exception(Ibe_Exception::ACAO_NAO_ENCONTRADO,self::$action_path.'inc_'.$action.'.php');
        }

        return $action ;
    }
}