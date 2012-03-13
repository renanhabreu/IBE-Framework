<?php

/**
 * Helper for forms
 * Cria um formulario bem como campos e labels
 *
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Form {

    /**
     * Elemento forumalario
     * @var Telement_Form_Element
     */
    private $form = null;
    /**
     * module para envio do formulario
     * @var string
     */
    protected $module = NULL;
    /**
     * controlador para envio do formulario
     * @var string
     */
    protected $controller = NULL;
    /**
     * acao para envio do formulario
     * @var string
     */
    protected $action = NULL;
    /**
     * metodo de envio do formulario. Padrao = POST
     * @var string
     */
    protected $method = 'POST';
    /**
     * Informa se o arquivo receberah arquivo. Padrao = false
     * @var boolean
     */
    protected $file = false;
    /**
     * Rotulo do botao de submit
     * @var string
     */
    protected $label_button_submit = 'ENVIAR';
    /**
     * Rotulo do botao de reset
     * @var string
     */
    protected $label_button_reset = 'LIMPAR';
    /**
     * Attributos para o campo
     * @var string
     */
    protected $attribute = array();
    /**
     *
     * @var Form_Element_Field
     */
    protected $form_fields = array();
    /**
     * Titulo do formulario
     * @var string
     */
    protected $title = 'Formulário';

    /**
     * Exige a implementacao do metodo configure
     */
    public function __construct() {
        $this->form = new Ibe_Form_Element();
        $this->configure();
        if (!isset($this->form_fields)) {
            throw new Ibe_Exception(Ibe_Exception::FORM_SEM_CONFIGURACAO, array(__FILE__));
        }
    }

    /**
     * metodo que contem a configuracao do formulario
     */
    abstract protected function configure();

    /**
     * Retorna uma instancia de um formulario
     * @param string $form_class_name
     * @return Ibe_Form
     */
    static public function getInstance($form_class_name) {

        $filename = '\inc_' . strtolower($form_class_name) . '.php';
        $form_class_name = ucfirst(strtolower($form_class_name)) . 'Form';
        try {
            Ibe_Source::load(Ibe_Source::getPathFormName(), $filename);
        } catch (Ibe_Exception $e) {
            if ($e->getCode() == Ibe_Exception::FALHA_EM_LOAD) {
                Ibe_Source::load(Ibe_Request_Decode::getModulePath() . Ibe_Source::getPathFormName(), $filename);
            }
        }
        // O nome da classe de formulario deve seguir o padrao NomeForm. Exemplo: UsuarioForm
        if (class_exists($form_class_name)) {
            $reflection = new ReflectionClass($form_class_name);
            return $reflection->newInstance();
        } else {
            throw new Ibe_Exception(Ibe_Exception::MAPA_NAO_ENCONTRADO, array($form_class_name, $dir));
        }
    }

    /**
     * Cria o formulario conforme as configuracoes no model
     * @return string
     */
    public function create() {
        $url = Ibe_Response::getCompleteUrl($this->module,$this->controller, $this->action);

        $html = $this->form->open($url, $this->method, $this->file, $this->attribute);
        $html .= '<div class="ibe-formulario-box">';
        $html .= '<div class="ibe-formulario-header"><p>' . strtoupper($this->title) . '</p></div><div style="clear:both"></div>';
        $html .= '<div class="ibe-formulario-body">';
        foreach ($this->form_fields as $conf) {
            $method = $conf->getInput();
            $label = $conf->getLabel();
            if (!empty($label)) {
                $html .= $this->form->label($label, true);
            }

            switch ($method) {
                case 'text':
                case 'textarea':
                case 'textarea':
                case 'hidden':
                    $html .= $this->form->$method($conf->getName(), $conf->getValue(), $conf->getAttributes());
                    break;
                case 'check':
                    $conf->setChecked($conf->getValue());
                    $html .= $this->form->$method($conf->getName(), $conf->getChecked(), $conf->getAttributes());
                    break;
                case 'file':
                    $html .= $this->form->$method($conf->getName(), $conf->getAttributes());
                    break;
                case 'radio':
                    $name = $conf->getName();
                    $checked = $conf->getValue();
                    $attribute = $conf->getAttributes();
                    $html .= $this->form->$method($name, '', $checked, $attribute);
                    break;
                case 'select':
                    $name = $conf->getName();
                    $value = $conf->getValue();
                    $options = $conf->getOptions();
                    $attribute = $conf->getAttributes();
                    $html .= $this->form->$method($name, $value, $options, $attribute);
                    break;

                default:
                    break;
            }


            if (!empty($label)) {
                $html .= $this->form->closeLabel();
            }
        }
        $html .= '</div><div style="clear:both"></div>';
        $html .= '<div class="ibe-formulario-botao">';
        $html .= $this->form->reset($this->label_button_reset);
        $html .= $this->form->submit($this->label_button_submit);
        $html .= '</div><div style="clear:both"></div>';
        $html .= '</div><div style="clear:both"></div>';
        $html .= $this->form->close();
        return $html;
    }

    /**
     * Cria o formulario conforme as configuracoes no model
     * @return string
     */
    public function show() {
        echo $this->create();
    }

    /**
     * Adiciona um valor a um campo de formulario
     * @param string $field
     * @param mixed $value
     * @return Ibe_Form
     */
    public function setValue($field, $value) {
        if (isset($this->form_fields[$field])) {
            $this->form_fields[$field]->setValue($value);
        } /* else {
          throw new Ibe_Exception('Campo ' . $field . ' inexistente no formulario ' . $this->controller);
          } */

        return $this;
    }

    /**
     * Adiciona um valor a um campo de formulario
     * @param array $field field=>value
     * @param mixed $value
     * @return Ibe_Form
     */
    public function setValues($fields) {
        foreach ($fields as $name => $value) {
            $this->setValue($name, $value);
        }
        return $this;
    }

    /**
     * Seta o MAINAPP da URL
     * @param string $controller
     * @return Ibe_Form
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Seta o APP da URL
     * @param string $action
     * @return Ibe_Form
     */
    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * Captura um valor de um campo do formulario
     * @param type $name
     * @return type
     */
    public function getValue($name) {
        $value = NULL;
        if (isset($this->form_fields[$name])) {
            $value = $this->form_fields[$name]->getValue();
        }

        return $value;
    }

}
