<?php

/**
 * Classe  para responder a requisicoes
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Response{

    static private $host = FALSE;

    /**
     * Redirecion para um controlador ou acao específica
     *
     * @param string $controller
     * @param string $action
     * @param array|null $params
     * @param Request $request
     */
    static public function redirecionar($controller, $action, Ibe_Request $req = NULL) {
        ob_clean();
        header('Location: '.self::getCompleteUrl($controller, $action, $req));
        exit();
    }

    /**
     * Captura a URL do Aplicativo
     * @return string
     */
    static public function getHost(){
        if(!self::$host){
            self::$host = self::getCompleteUrl(NULL, NULL);
        }

        return self::$host;
    }

    /**
     * Monta e Retorna a URL Completa do aplicativo
     * @param string $module
     * @param string $controller
     * @param string $action
     * @param Ibe_Request $req
     * @return string
     */
    static public function getCompleteUrl($module,$controller,$action,Ibe_Request $req = NULL){
        $get = array();

        if(isset($req)){
            $params = $request->getParams();
            foreach ($params as $key => $value) {
                $get[] = $key;
                $get[] =  urlencode($value);
            }
        }

        $url = explode('/', $_SERVER['REQUEST_URI']);
        $index = array_search('index.php', $url);
        if ($index) {
            $slices = array_slice($url, 0, $index);
        } else {
            array_pop($url);
            $slices = $url;
        }
        if(isset($controller) && isset ($action)){
            $slices = array_merge($slices,array('index.php',$module,$controller,$action),$get);
        }else{
            $slices = array_merge($slices,array(''));
        }

        $location = 'http://' . $_SERVER['HTTP_HOST'] . implode('/', $slices);

        return $location;
    }

    /**
     * Retorna um tipo de cabecalho Json
     * @param array $array_to_json
     */
    static public function json($json) {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo $json;
        exit();
    }

}