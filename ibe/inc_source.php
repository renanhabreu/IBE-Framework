<?php

/**
 * Classe para manutencao e gerenciamento de codigos fontes de terceiros
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Source {
    static private $path = array(
        'module'=>FALSE,
        '_action'=>FALSE,
        '_view'=>FALSE,
        '_plugin'=>FALSE,
        '_map'=>FALSE,
        '_form'=>FALSE
    );

    /**
     * Nome da pasta que contem os modulos
     * Padrao: module
     * @param string $name
     */
    static public function setPathModuleName($name){
        self::set($name, 'module');
    }

    /**
     * Nome da pasta que contem os actions
     * Padrao: _action
     * @param string $name
     */
    static public function setPathActionName($name){
        self::set($name, '_action');
    }

    /**
     * Nome da pasta que contem as views
     * Padrao: _view
     * @param string $name
     */
    static public function setPathViewName($name){
        self::set($name, '_view');
    }

    /**
     * Nome da pasta que contem os plugins
     * Padrao: _plugin
     * @param string $name
     */
    static public function setPathPluginName($name){
        self::set($name, '_plugin');
    }

    /**
     * Nome da pasta que contem os mapas
     * Padrao: _map
     * @param string $name
     */
    static public function setPathMapName($name){
        self::set($name, '_map');
    }

    /**
     * Nome da pasta que contem os formularios
     * Padrao: _form
     * @param string $name
     */
    static public function setPathFormName($name){
        self::set($name, '_form');
    }

    /**
     * Retorna o nome da pasta que contém os modulos
     * @return string
     */
    static public function getPathModuleName(){
        return self::get('module');
    }

    /**
     * Retorna o nome da pasta que contém as ações
     * @return string
     */
    static public function getPathActionName(){
        return self::get('_action');
    }

    /**
     * Retorna o nome da pasta que contém as views
     * @return string
     */
    static public function getPathViewName(){
        return self::get('_view');
    }

    /**
     * Retorna o nome da pasta que contém os plugins
     * @return string
     */
    static public function getPathPluginName(){
        return self::get('_plugin');
    }

    /**
     * Retorna o nome da pasta que contém os mapas
     * @return string
     */
    static public function getPathMapName(){
        return self::get('_map');
    }

    /**
     * Retorna o nome da pasta que contém os formularios
     * @return string
     */
    static public function getPathFormName(){
        return self::get('_form');
    }


    /**
     * Realiza a inclusao de arquivos
     * @param string $caminho_sigo
     * @param array $arquivos
     */
    static public function load($caminho, $arquivos) {
        $arquivos = (is_array($arquivos)) ? $arquivos : array($arquivos);

        foreach ($arquivos as $arq) {
            self::includeFile($caminho . $arq);
        }
    }

    /**
     * Inclui um arquivo
     * @param boolean $filename
     */
    static private function includeFile($filename) {

        if (file_exists($filename)) {
            include_once $filename;
        } else {
            throw new Ibe_Exception(Ibe_Exception::FALHA_EM_LOAD, array($filename));
        }
    }

    /**
     * Seta um nome de uma pasta
     * @param string $name
     * @param string $type
     */
    static private function set($name,$type){
       // Ibe_Debug::dispatchAlert(__FILE__, self::$path[$type],true);
        if(self::$path[$type] === FALSE){
            self::$path[$type] = $name;
        }else{
            throw new Ibe_Exception(Ibe_Exception::VARIAVEL_NAO_SOBRESCRITA, array('PATH_'.  strtoupper($name) .'_NAME',$name));
        }
    }

    /**
     * Retorna o nome de uma pasta
     * @param string $type
     * @return string
     */
    static private function get($type){
        $path = $type.'s';
        if(self::$path[$type] !== FALSE){
            $path = self::$path[$type];
        }
        return $path;
    }
}
