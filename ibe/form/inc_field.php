<?php
/**
 * Classe para criacao elementos de formularios
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage form
 */
class Ibe_Form_Field{

    protected $type = null;
    protected $allow_blank = true;
    protected $name = null;
    protected $allow_value = null;
    protected $reg_validation = array();
    protected $label = 'label';
    protected $input = 'text';
    protected $value = null;
    protected $options = array();
    protected $checked = false;
    protected $attributes = array();

    private function __construct(){

    }

    /**
     *
     * @return Ibe_Form_Field
     */
    static public function getInstance(){
        return new self();
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $type = strtolower($type);

        switch ($type){
            case 'int':
            case 'float':
            case 'double':
            case 'string':
            case 'date':
            case 'boolean':
                break;
            default:
                throw new Ibe_Exception(Ibe_Exception::FORM_TIPO);
                break;
        }
        $this->type = $type;
        return $this;
    }

    public function getAllowBlank() {
        return $this->allow_blank;
    }

    public function setAllowBlank($allow_blank) {
        $this->allow_blank = $allow_blank;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getAllowValue() {
        return $this->allow_value;
    }

    public function setAllowValue($allow_value) {
        $this->allow_value = $allow_value;
        return $this;
    }

    public function getRegValidation() {
        return $this->reg_validation;
    }

    public function setRegValidation($reg_validation) {
        $this->reg_validation = $reg_validation;
        return $this;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function setAttributes(array $attributes) {
        $this->attributes = $attributes;
        return $this;
    }

    public function addAttribute($name,$value){
        $this->attributes[$name] = $value;
        return $this;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function getInput() {
        return $this->input;
    }

    public function setInput($input) {
        $array = array(
            'text'
            ,'textarea'
            ,'select'
            ,'check'
            ,'radio'
            ,'file'
            ,'hidden'
        );

        if(!in_array($input, $array)){
            throw new Ibe_Exception(Ibe_Exception::FORM_INPUT,array($input));
        }
        $this->input = $input;
        return $this;
    }

    public function getOptions() {
        return $this->options;
    }

    public function setOptions(array $options) {
        $this->options = $options;
        return $this;
    }

    public function getChecked() {
        return $this->checked;
    }

    public function setChecked($checked) {
        $this->checked = $checked;
        return $this;
    }

}