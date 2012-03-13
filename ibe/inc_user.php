<?php
/**
 * Classe para criacao de tags HTML
 * @author Renan Abreu
 * @version 21102011
 * @package ibe
 */
final class Ibe_User{

    /**
     * Instancias da classe Ibe_User
     * @var Ibe_User
     */
    static private $instances = array();

    /**
     * Atributos do usuario
     * @var array
     */
    private $attributes = array();

    private $access = array();

    /**
     * Captura uma nova instancia da classe Ibe_User
     * @param string $name
     * @return Ibe_User
     */
    static public function getInstance($name = 'all_user') {
        $name = (isset($name))? $name:'all_user';

        if(!isset(self::$instances[$name])){
            self::$instances[$name] = new self();
        }

        return self::$instances[$name];
    }

    /**
     * Retorna o array contendo as regras de acesso do permitidas ao usuario
     * @return array
     */
    public function getAccess(){
        return $this->access;
    }

    /**
     * Adiciona uma permissao ao usuario. Os parametros são as localidades
     * dentro do aplicativo que o usuario poderá ter acesso
     *
     * @param string $module
     * @param string $controller
     * @param string $action
     */
    public function allowAccess($module = TRUE,$controller = TRUE,$action = TRUE){
        if($module !== TRUE){
            $this->access[$module]  = TRUE;
            if($controller !== TRUE){
                $this->access[$module] = array($controller=>TRUE);
                if($action !== TRUE){
                    $this->access[$module][$controller] = array($action => TRUE);
                }
            }
        }
    }

    /**
     * Seta atributos do usuario
     * @param string $name
     * @param mixed $value
     * @return Ibe_User
     */
    public function setAttribute($name,$value){
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Captura um atributo de um usuario
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name){
        $val = NULL;

        if(isset($this->attributes[$name])){
            $val = $this->attributes[$name];
        }

        return $val;
    }


}