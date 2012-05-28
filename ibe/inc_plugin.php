<?php

/**
 * Interface para plugins
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Plugin extends Ibe_Object {

    protected $param_conf = array();
    static private $instances = array();

    /**
     * Inicializa um plugin
     * @param string $plugin_name
     */
    static public function init($plugin_name) {

        if (!isset(self::$instances[$plugin_name])) {
            $objPlugin = Ibe_Load::plugin();
            $objPlugin->initialize();
            self::$instances[$plugin_name] = $objPlugin;
        }
    }
    /**
     * Finaliza um plugin
     * @param type $plugin_name
     */
    static public function end($plugin_name) {
        if (isset(self::$instances[$plugin_name])) {
            self::$instances[$plugin_name]->finalize();
            unset(self::$instances[$plugin_name]);
        }
    }


    /**
     * Executa o plugin
     * @param type $plugin_name
     */
    static public function run($plugin_name) {
        if (isset(self::$instances[$plugin_name])) {
            self::$instances[$plugin_name]->execute();
        }
    }

  

    /**
     * Funcao disparada ao inicializar o plugin
     */
    abstract public function initialize();

    /**
     * Dispara o plugin
     */
    abstract public function execute();

    /**
     * Funcao disparada ao destruir um plugin
     */
    abstract public function finalize();
}
