<?php

/**
 * seta as variaveis globais
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @todo imlementar metodos publicos para setar valores de diretorios e variaveis
 */
final class Ibe_Register {

    /**
     * Array com os valores dos registros
     * @var array
     */
    static public $globalsVariables = null;

    private function __construct() {

    }

    /**
     * Set a  global variable
     * @param string Global name
     * @param $value Value of global
     */
    static public function set($name, $value) {
        self::$globalsVariables[$name] = $value;
    }

    /**
     * Get a global variable
     * @param string Global name
     * @return null || global
     */
    static public function get($name,$default = NULL) {
        $retorno = $default;
        $array = self::$globalsVariables;

        if (isset($array[$name])) {
            $retorno = $array[$name];
        }
        return $retorno;
    }

    /**
     * Check if exists the variable
     * @param string Variable name
     * @return true || false
     */
    static public function isAdded($name) {
        return isset(self::$globalsVariables[$name]);
    }

    /**
     * Will read a configuration file ( type ini )
     * VAR_NAME = value
     * @return null
     */
    static public function load($configFile, $variable = true) {

        $configArray = parse_ini_file($configFile, true);
        if ($configArray) {
            array_merge(self::$globalsVariables, $configArray);
        } else {
            throw new Ibe_Register('ARQUIVO_DE_REG', array($configFile));
        }
    }

}

?>