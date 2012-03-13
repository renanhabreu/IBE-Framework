<?php
/**
 * Classe para ajuda em layouts
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Layout extends Ibe_Object {

    const APPLICATION = 0;
    const ACTION = 1;
    const CONTROLLER = 2;
    const MODULE = 3;
    const NONE = 4;

    /**
     *
     * @var Ibe_Layout
     */
    private static $instance = null;
    /**
     * Variaveis do template
     * @var array
     */
   // private $vars = array();
    /**
     * Lista de arquivos css
     * @var array
     */
    protected $styles = array();
    /**
     * Lista de arquivos js
     * @var array
     */
    protected $scripts = array();

    /**
     * Retorna a instência de Ibe_Layout
     * @return NULL|Ibe_Layout
     */
    static protected function getSelf(){
        return self::$instance;
    }

    static protected function setSelf(Ibe_Layout $layout_object){
        self::$instance = $layout_object;
    }

    /**
     * Adiciona javascript ao arquivo de template
     * @param string $name
     * @param string $dir
     * @param boolean $prepend
     * @return Ibe_Layout
     */
    public function addScript($name, $dir = NULL, $prepend = FALSE) {
        $script = $this->callScript($name, $dir, FALSE);
        
        if (!$prepend) {
            array_push($this->scripts, $script);
        } else {
            array_unshift($this->scripts, $script);
        }
        return $this;
    }

    /**
     * Adiciona um arquivo css ao arquivo de template
     * @param type $name
     * @param type $dir
     * @param type $prepend
     * @return Ibe_Layout
     */
    public function addStyle($name, $dir = NULL, $prepend = FALSE) {
        $script = $this->callStyle($name, $dir, FALSE);
        
        if (!$prepend) {
            array_push($this->styles, $script);
        } else {
            array_unshift($this->styles, $script);
        }
        return $this;
    }
    
    /**
     * Cria a tag de script
     * @param type $name
     * @param type $dir
     * @param type $echo
     * @return string 
     */
    protected function callScript($name, $dir = NULL,$echo = TRUE) {
        $dir = is_null($dir)? Ibe_Source::getPathViewName()."/js/":$dir;
        
        $script = '<script src="' . $dir . $name . '"></script>';
        
        if($echo){
            echo $script;
        }else{
            return $script;
        }
    }
    
    
    /**
     * Cria a tag style
     * @param type $name
     * @param type $dir
     * @param type $echo
     * @return string 
     */
    protected function callStyle($name, $dir = NULL, $echo = TRUE) {
        $dir = is_null($dir)? Ibe_Source::getPathViewName()."/css/":$dir;
        
        $script = '<link type="text/css" rel="stylesheet" href="' . $dir . $name . '" />';
        
        if($echo){
            echo $script;
        }else{
            return $script;
        }
    }
    
    /**
     * Alias para echo $this->script na view
     */
    protected function includeScript(){
        echo $this->scripts;
    }
    
    /**
     * Alias para echo $this->style na view
     */
    protected function includeStyle(){
        echo $this->styles;
    }
    
    /**
     * Mostra o layout
     */
    abstract public function showLayout();

    /**
     * Retorna o layout
     */
    abstract public function getLayout();
}
