<?php

class Doct extends Skt_Core_Maker {

    public function create(Skt_Core_Request $req) {
        try {            
            ob_start();
            // inserindo a biblioteca doctrine
            $dir = $this->_dir_app . $this->_configure->path_doctrine_library;
            if (!is_dir($dir)) {
                Skt_Core_Prompt::print_($dir, Skt_Core_Prompt::ERROR);
                $this->copy_directory('_makers/doct/tpls/Doctrine-1.2.4/', $dir);            
                $this->_file->include_tpl("inc_doctrine",$this->_dir_app.'_extensions');
            }
            
            // criando diretorios para o doctrine models, base, schemas
            foreach($this->_configure->doctrine_configure as $directory){
                $this->_directory->create($this->_dir_app.$directory);
            }
            
            // configurando o nome das pastas para o gerador de codigos
            $confs = array();
            foreach ($this->_configure->doctrine_configure as $conf_name => $conf_value) {
                $confs[$conf_name] = $this->_dir_app . $conf_value;
            }
            
            // conectando o doctrine ao banco de dados atraves do dns em configuracao.php
            require_once $this->_dir_app . '_libraries/Doctrine-1.2.4/Doctrine.php';
            spl_autoload_register(array('Doctrine', 'autoload'));
            if(!Doctrine_Manager::connection($this->_configure->doctrine_database_dns)){
                throw new Exception('Not possible connect in database');
            }
            
            // executando os comandos doctrine
            $cli = new Doctrine_Cli($confs);
            $params = array_merge(array('doctrine'), explode('.',$req->getParam('command')));
            $cli->run($params);
            
            // capturando saida do doctrine para enviar como json
            $out = ob_get_contents();
            ob_end_clean();
            
            // inserindo a saida no json
            if(isset($out) && !empty($out)){
                Skt_Core_Prompt::print_($out, Skt_Core_Prompt::ALERT);  
            }
            
        } catch (Exception $e) {
            Skt_Core_Prompt::print_($e->getMessage(), Skt_Core_Prompt::ERROR);
        }
    }

    private function copy_directory($source, $destination) {
        if (is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while (FALSE !== ( $readdirectory = $directory->read() )) {
                if ($readdirectory == '.' || $readdirectory == '..') {
                    continue;
                }
                $PathDir = $source . '/' . $readdirectory;
                if (is_dir($PathDir)) {
                    $this->copy_directory($PathDir, $destination . '/' . $readdirectory);
                    continue;
                }
                copy($PathDir, $destination . '/' . $readdirectory);
            }

            $directory->close();
        } else {
            copy($source, $destination);
        }
    }

}

