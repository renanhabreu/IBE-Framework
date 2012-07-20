<?php

// DIRECTORY_SEPARATOR alias
define("DS", DIRECTORY_SEPARATOR);
define("IBE_FRAMEWORK_PATH", __DIR__ . DS);

/**
 * Classe de includes automatico da classes do aplicativo.
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Autoload {

    /**
     * Procura por uma classe
     * @param string $class_name
     * @return Object
     */
    static public function search($class_name) {
        $n = $class_name;

        if (!class_exists($class_name)) {

            if (0 === stripos($class_name, 'Ibe_') || 0 === stripos($class_name, 'Ext_')) {
                $directory = false;

                //configurando o nome
                $class_name = strtolower($class_name);
                $class_dir = explode('_', $class_name);
                //verifica se a classe pertence as funcinalidades do framework
                $ibe_dir = ($class_dir[0] == 'ibe');
                $ext_dir = ($class_dir[0] == 'ext');

                array_shift($class_dir);
                $size = sizeof($class_dir) - 1;
                $class_dir[$size] = 'inc_' . $class_dir[$size];

                if ($ibe_dir) {

                    $framework_dir = IBE_FRAMEWORK_PATH . implode('/', $class_dir) . '.php';

                    if (file_exists($framework_dir)) {
                        $directory = $framework_dir;
                    }
                } else if ($ext_dir) {
                    $_extension = '_extensions/' . implode('/', $class_dir) . '.php';
                    //Ibe_Debug::dispatchAlert(__FILE__,$_extension);
                    if (file_exists($_extension)) {
                        $directory = $_extension;
                    } else {
                        throw new Exception('A extensao ' . $n . ' nao foi encontrada. Deve ser implementada em  [' . $_extension . ']');
                    }
                }

                if ($directory) {
                    require $directory;
                    return;
                } else {
                    $msg = ($ibe_dir) ? "Classe " . $class_name . " nao foi localizada no repositorio do framework.<br />Esta classe nao foi implementada." : "A classe " . $class_name . " nao foi encontrada.<br/>Verifique a exist�ncia dela nos diretorios do aplicativo!";
                    throw new Exception($msg);
                }
            }
        }
    }

    /**
     * Inicializa as configuracoes de load
     */
    static public function activeAutoload() {
        spl_autoload_register(array(__CLASS__, 'search'));
    }

    /**
     * Registra o diretorio das funcionalidades de ajuda
     * @param string $dir
     * @deprecated
     */
    static public function frameworkDirectoryRegister($dir = NULL) {
        self::activeAutoload();
    }

}

Ibe_Autoload::activeAutoload();
