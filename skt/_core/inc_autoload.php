<?php

// DIRECTORY_SEPARATOR alias
define("DS",DIRECTORY_SEPARATOR);

abstract class Skt_Core_Autoload{

    static private $registred = false;

    /**
     * Procura por uma classe
     * @param string $class_name
     * @return Object
     */
    static public function search($class_name) {
    
        $n = $class_name;
        if (!class_exists($class_name)) {
            $directory = false;

            //configurando o nome
            $class_name = strtolower($class_name);
            $class_dir =  explode('_', $class_name);
            $class_dir_make = $class_dir;
            //verifica se a classe pertence as funcinalidades do framework
            $skt_dir = ($class_dir[0] == 'skt');
            
            
            if ($skt_dir) {
            
                array_shift($class_dir);
                $size = sizeof($class_dir) - 1;
                $class_dir[$size] = 'inc_' . $class_dir[$size].'.php';
                
                $dir_file_class = "_".implode(DS, $class_dir);

                if(file_exists($dir_file_class)){
                    $directory = $dir_file_class;
                }
                
            } else {
                $size = sizeof($class_dir) - 1;
                $class_dir[$size] = 'inc_' . $class_dir[$size].'.php';
                $_makers = '_makers'. DS . implode(DS, $class_dir);
                $_maker = '_makers'. DS . implode(DS, $class_dir_make).DS.'inc_action.php';
                
                if (file_exists($_makers)) {
                    $directory = $_makers;
                } else if(file_exists($_maker)){
                    $directory = $_maker;
                }else{
                    throw new Exception('Fabricante nao encontrado');
                }
            }
            
            if ($directory) {                
                include_once $directory;
                return;
            } else {
                throw new Exception("Nao foi possivel encontrar a classe ".$class_name);
            }
        }
    }

    /**
     * Inicializa as configuracoes de load
     */
    static private function activeAutoload() {
        if (!self::$registred) {
            spl_autoload_register(array(__CLASS__, 'search'));
            self::$registred = true;
        }
    }

    /**
     * Registra o diretorio das funcionalidades de ajuda
     * @param string $dir
     */
    static public function appDirectoryRegister($dir = "../apps") {
        if (!defined('APP_DIR')) {
            if(!is_dir($dir)){
                mkdir($dir,777,true);
            }
            
            define('APP_DIR', $dir.DS);
        }
        
        self::activeAutoload();
    }

}
