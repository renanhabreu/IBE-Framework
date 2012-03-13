<?php

/**
 * Classe de criacao e manutencao de logs
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
class Ibe_Log {

    static private $active = false;
    /**
     * Instancias de logs
     * @var array
     */
    static private $instances = array();
    /**
     * Mensagem a ser gravada no log
     * @var string
     */
    private $msg = NULL;
    /**
     * Nome do arquivo de log
     * @var string
     */
    private $filename = NULL;
    /**
     * Caso o arquivo de log seja maior que 34Mb subdividi em partes. Numero de partes
     * @var int
     */
    private $part = 0;

    /**
     * Habilita o log
     */
    static public function enable() {
        self::$active = true;
    }

    /**
     * Desabilita o Log
     */
    static public function desable() {
        self::$active = false;
    }

    /**
     * Verifica se o log esta habilitado ou nao
     * @return boolean
     */
    static public function isEnable() {
        return self::$active;
    }

    /**
     * Desabilita a criacao de LOGS no arquivo $filename
     * @param string $filename
     */
    static public function notCreate($filename){
        self::$instances[$filename] = FALSE;
    }

    /**
     * Salva uma nova string no arquivo de log
     * @param string $filename
     * @param string $msg
     */
    static public function save($filename = 'ibeframework_log', $msg = NULL) {
        if (self::$active) {
            if (!isset(self::$instances[$filename])) {
                $log = new self();
                $log->setFilename($filename);
                self::$instances[$filename] = $log;
            }
            //Verifica se a escrita no arquivo foi desabilitada atraves do metodo notCreate
            if(self::$instances[$filename] !== FALSE){
                self::$instances[$filename]->setMessage($msg)->create();
            }
        }
    }

    /**
     * Seta a mensagem do arquivo de log
     * @param string $msg
     * @return Ibe_Log
     */
    public function setMessage($msg) {
        $this->msg = $msg;
        return $this;
    }

    /**
     * Seta o nome do arquivo de log
     * @param string $filename
     * @return Ibe_Log
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Cria o arquivo de log
     */
    public function create() {
        if (!is_dir('./_logs')) {
            mkdir('./_logs', 777);
        }
        $string = date('d-m-Y') . "\t" . date('H:i:s') . "\t";
        $string .= strip_tags($this->msg) . "\n";

        $filename = './_logs/' . $this->filename;

        /**
         * Quebrando o arquivo em partes quando o tamanho do atingir 34 Mb
         */
        if (file_exists($filename . '.log')) {
            $size = filesize($filename . '.log');
            if ($size >= 31457280) {
                $this->part++;
                for (;;) {
                    if (!file_exists($filename . '.part_' . $this->part . '.log')) {
                        $filename .= '.part_' . $this->part;
                        break;
                    } else {
                        $this->part++;
                    }
                };
            }
        }

        file_put_contents($filename . '.log', $string, FILE_APPEND);
    }

}
