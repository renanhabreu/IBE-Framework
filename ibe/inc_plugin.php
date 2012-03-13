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
            $plugin_class = strtolower($plugin_name) . 'Plugin';
            try {
                Ibe_Source::load(Ibe_Source::getPathPluginName(), '\inc_' . strtolower($plugin_name) . '.php');
            } catch (Ibe_Exception $e) {
                Ibe_Source::load(Ibe_Request_Decode::getModulePath() . Ibe_Source::getPathPluginName(), '\inc_' . strtolower($plugin_name) . '.php');
            }
            $clsPlugin = new ReflectionClass($plugin_class);
            $objPlugin = $clsPlugin->newInstance();
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
     * Passa um parametro, que foi configurado na construção do plugin
     * @param string $plugin_name
     * @param string $name
     * @param mixed $value
     */
    static public function setParam($plugin_name, $name, $value) {
        if (isset(self::$instances[$plugin_name])) {
            self::$instances[$plugin_name]->setParam($name, $value);
        }
    }

    /**
     * Captura o valor de um parametro do plugin
     * @param string $plugin_name
     * @param string $name
     * @return mixed
     */
    static public function getParam($plugin_name, $name) {
        $value = NULL;
        if (isset(self::$instances[$plugin_name])) {
            $value = self::$instances[$plugin_name]->getParam($name);
        }

        return $value;
    }

    /**
     * Executa o plugin
     * @param type $plugin_name
     */
    static public function execute($plugin_name) {
        if (isset(self::$instances[$plugin_name])) {
            self::$instances[$plugin_name]->dispatch();
        }
    }

    /**
     * Seta uma variavel do plugin
     * @param string $name
     * @param mixed $value
     */
    public function setParam($name, $value) {
        if (isset($this->param_conf[$name])) {
            settype($value, $this->param_conf);
            $this->$name = $value;
        }
    }

    /**
     * Retorna o valor de uma variavel do plugin
     * @param string $name
     * @return mixed
     */
    public function getParam($name) {
        $value = NULL;
        if ($this->isSetVar($name)) {
            $value = $this->$name;
        }

        return $value;
    }

    /**
     * Funcao disparada ao inicializar o plugin
     */
    abstract public function initialize();

    /**
     * Dispara o plugin
     */
    abstract public function dispatch();

    /**
     * Funcao disparada ao destruir um plugin
     */
    abstract public function finalize();
}