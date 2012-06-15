<?php

/**
 * Classe para ajuda no debug da aplicacao
 * 
 * @author Renan Abreu
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
    static public function enable() {
        self::$debug_mode = true;
    }

    /**
     * Desabililta o debug
     */
    static public function disable() {
        self::$debug_mode = false;
    }

    /**
     * Verifica se o debug esta ativo
     * @return boolean
     */
    static public function isEnable() {
        return self::$debug_mode;
    }

    /**
     * Mostra na tela a quantidade de memoriautilizada na aplicacao
     */
    static public function getSizeMemory() {
        $size = memory_get_usage();
        $unit = array('B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
        $memory = @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
        Ibe_Debug::warn('APP IBE SIZE MEMORY', $memory);
    }

    /**
     * Salva o resultado do debug de uma variavel em um arquivo de log nas pasta
     * especificada pela variavel de classe log_path
     * 
     * @param string $title {Para facilitar o encontro utilize __FILE__ }
     * @param mixed $content 
     */
    static public function save($title, $content, $type = 'txt') {

        $log = str_repeat('-', strlen($title) + 8);
        $log .= "\n--- $title \n";
        $log .= "--- " . date("d/m/Y  H:i:s") . " \n";
        $log .= str_repeat('-', strlen($title) + 8) . "\n";
        $log .= $content;
        $log .= "\n\n";

        $file_path = '_logs/' . md5($title) . "__" . date("dmy") . "." . $type;

        file_put_contents($file_path, $log, FILE_APPEND);
    }

    /**
     * Inicia a contagem de tempo onde $name é o identificador para inicio da contagem
     * 
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
        Ibe_Debug::warn('TEMPO DE EXECUCAO', $time);
    }

    /**
     * Mensagem padrao do ibe de alerta para excecoes, error ou debugs
     * 
     * @param string $title
     * @param mixed $content
     */
    static public function error($title, $content) {
        self::printMe( $content, false, "#c91616");
        exit();
    }

    /**
     * Mensagem padrao do ibe de alerta para excecoes, error ou debugs
     * 
     * @param string $title
     * @param mixed $content
     */
    static public function warn($title, $content) {
        self::printMe($content, false, "#E0CD00");
    }
    
    /**
     * Imprime uma mensagem generica de debug
     * 
     * @param string $title <padrao __FILE__ >
     * @param mixed $content
     * @param boolean $var_dump <FALSE>
     * @param string $color  <#C90000>
     */
    static public function printMe($content, $var_dump = FALSE, $color = "#C90000") {
        if(is_bool($content)){
            $content = ($content)? 'TRUE':'FALSE';
        }
        
        $calls = debug_backtrace();
        $call_list = "<b>FILE:</b> ".$calls[1]['file']." <b>LINE:</b> ".$calls[1]['line'];
       
        $style = implode(";",array(
            "position:relative",
            "z-index:9999999",
            "background-color:#2f2f2f",
            "color:#fff", 
            "padding:4px"
        ));
        
        echo "<div style='$style'>";
        echo "<pre style='padding-left:10px; font-size:12px; margin:10px;>";
        echo "<div style='margin:20px; border:1px solid black;'>";
        echo "<div style='background:$color; padding:5px;color:#fff;font-size:14px'>";
        echo "::: DEBUG :::";
        echo "</div>";
        echo '<div style="padding:10px; overflow:scroll; text-align:left; background-color:#fff; color:#000 ">';
        if (!$var_dump)
            print_r($content);
        else
            var_dump($content);
        echo '<div style="margin-top:10px; border-top:1px solid #000;">';
        echo $call_list;
        echo '</div>';
        echo '</div>';
        echo "</div>";
        echo "</pre>";
        echo "</div>";
    }

}

