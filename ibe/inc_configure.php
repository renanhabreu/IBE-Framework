<?php

/**
 * Classe de configuracao de modulos
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Configure {
    /**
     * Registra as permissoes de usuario para o acesso ao modulo/controller/action
     */
    abstract public function registerAuth();

    /**
     * Realiza o include de arquivos de terceiros no aplicativo
     */
    abstract public function registerInclude();

    /**
     * @todo implementar registrador de variaveis globais no arquivo de configuracao
     */
    abstract public function registerVar();

    /**
     *
     * @return Ibe_Config
     */
    static public function get(){

         /**
          *  @todo implementar reflection
          */
         Ibe_Source::load(Ibe_Request_Decode::getModulePath(), 'inc_configure.php');

         if(class_exists('Configure',false)){
             $configure = new Configure();
             if(get_parent_class($configure) != 'Ibe_Configure'){
                 throw new Ibe_Exception(Ibe_Exception::CLASSE_PAI_INVALIDA,array('Configure','Ibe_Configure'));
             }

             $configure->registerInclude();
             $configure->registerVar();
             $configure->registerAuth();

         }else{
             throw new Ibe_Exception(Ibe_Exception::CLASSE_NAO_ENCONTRADA,array('Configure',  Ibe_Request_Decode::getModulePath()));
         }

    }

    private function __construct(){}
}