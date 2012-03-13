<?php
/**
 * Classe para ajuda no debug da aplicacao
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Debug {

    static private $debug_mode = false;

    /**
     * Identificador de consulta a tempos
     * @var array
     */
    static private $times = array();

    /**
     * Habilita o debug
     */
    static public function enable(){
        self::$debug_mode = true;
    }

    /**
     * Desabililta o debug
     */
    static public function disable(){
        self::$debug_mode = false;
    }

    /**
     * Verifica se o debug esta ativo
     * @return boolean
     */
    static public function isEnable(){
        return self::$debug_mode;
    }

    /**
     * Mostra na tela a quantidade de memoriautilizada na aplicacao
     */
    static public function getSizeMemory() {
        $size = memory_get_usage();
        $unit = array('B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
        $memory = @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
        Ibe_Debug::dispatchAlert('APP IBE SIZE MEMORY', $memory);
    }

    /**
     * Salva $data no arquivo de log chamado log.log na pasta logs do framework
     * @param mixed $data
     */
    static public function log($data) {
        file_put_contents('logs/log.log', $data);
    }

    /**
     * Inicia a contagem de tempo onde $name é o identificador para inicio da contagem
     * @param string $name
     */
    static public function timeExecutionInit($name) {
        self::$times[$name]['init'] = microtime();
    }

    /**
     * Finaliza a contagem de tempo onde $name é o identificador para inicio da contagem
     * Caso $log_file seja true um arquivo de log chamado $name_time.log sera criado.
     *
     * @param string $name
     * @param bool $log_file
     */
    static public function timeExecutionEnd($name) {
        $tempo = microtime();

        if (!isset(self::$times[$name]['init'])) {
            throw new Ibe_Exception('O timeExecution ' . $name . ' nao foi inicializado');
        }
        $time = number_format($tempo - self::$times[$name]['init'], 5, ',', ' ');
        Ibe_Debug::dispatchAlert('TEMPO DE EXECUCAO', $time);
        Ibe_Log::save('time_execution_application', $time);
    }

    /**
     * Mensagem padrao do ibe de alerta para excecoes, error ou debugs
     * @param string $title
     * @param mixed $content
     * @param bool $var_dump
     */
    static public function dispatchAlert($title, $content, $var_dump = false) {

        echo "<pre style='padding-left:10px; font-size:12px; text-align:left'>";
        echo "<div style='margin:20px; border:1px solid black;'>";
        echo "<div style='background:#E0CD00; padding:5px;color:#000;font-size:14px'>";
        echo "::: ALERT ! " . strtoupper($title) . " :::";
        echo "</div>";
        echo '<div style="padding:10px; overflow:scroll" >';
        if (!$var_dump)
            print_r($content);
        else
            var_dump($content);
        echo '</div>';
        echo "</div>";
        echo "</pre>";
    }

    /**
     * Mensagem padrao do ibe de alerta para excecoes, error ou debugs
     * @param string $title
     * @param mixed $content
     * @param bool $var_dump
     */
    static public function dispatchError($title, $content, $var_dump = false) {

        echo "<pre style='padding-left:10px; font-size:12px'>";
        echo "<div style='margin:20px; border:1px solid black;'>";
        echo "<div style='background:#C90000; padding:5px;color:#fff;font-size:14px'>";
        echo "::: ERROR ! " . strtoupper($title) . " :::";
        echo "</div>";
        echo '<div style="padding:10px; overflow:scroll">';
        if (!$var_dump)
            print_r($content);
        else
            var_dump($content);
        echo '</div>';
        echo "</div>";
        echo "</pre>";
        exit();
    }

    /**
     * Salva um arquivo txt.log como conteudo de $log_content
     * @param string $log_dir
     * @param string $log_name
     * @param string $log_content
     */
    static public function saveLog($log_dir, $log_name, $log_content) {
        file_put_contents($log_dir . '_sigo_app.txt.log', $log_content . '::' . date('d-m-Y H:i:s') . chr(13), FILE_APPEND);
    }

}


