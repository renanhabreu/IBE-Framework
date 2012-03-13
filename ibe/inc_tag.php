<?php
/**
 * Classe para criacao de tags HTML
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
class Ibe_Tag{
    /**
     * Elementos filhos da tag html
     * @var array
     */
    private $element = array();
    /**
     * Conteudo da tag html
     * @var mixed
     */
    private $content = NULL;
    /**
     * Atributos da tag html
     * @var array
     */
    private $attributes = array();
    /**
     * Nome da tag
     * @var string
     */
    protected $tag = NULL;

    private function __construct($tag_name) {
        $this->tag = $tag_name;
    }

    /**
     * Retorna uma instancia de uma Tag HTML
     * @param type $tag_name
     * @return Ibe_Tag
     */
    static public function getInstance($tag_name){
        return new self($tag_name);
    }

    /**
     * Adiciona um objeto Ibe_Tag a tag atual
     * @param Ibe_Tag $tag
     * @return Ibe_Tag
     */
    public function addElement(Ibe_Tag $tag) {
        $this->element[] = $tag;
        return $this;
    }

    /**
     * Adiciona atributos a tag
     * @param array|string $att
     * @param mixed $value
     * @return Ibe_Tag
     */
    public function addAttribute($att,$value = null) {
        $att = is_array($att)? $att:array($att=>$value);

        foreach($att as $name=>$value){
            $this->attributes[] = $name.' = "'.$value.'"';
        }

        return $this;
    }

    /**
     * Seta o conteudo da tag html
     * @param mixed $content
     * @return Ibe_Tag
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Cria atag HTML
     * @return string
     */
    public function create(){
        $tag  = "<".$this->tag." ".  implode(' ', $this->attributes)." >";
        $tag .= $this->content;

        foreach($this->element as $element){
            $tag .= $element->create();
        }
        $tag .= "</".$this->tag.">";

        return $tag;
    }

    /**
     * Mostra a tag HTML
     */
    public function show(){
        echo $this->create();
    }

}
