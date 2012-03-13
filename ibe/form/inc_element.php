<?php

/**
 * Helper for forms
 * Cria um formulario bem como campos, labels e fieldsets
 *
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage form
 */
class Ibe_Form_Element {

    private $open_form = FALSE;

    /**
     * Open the form tag
     *
     * @param array $url
     * @param string $method
     * @param boolean $upload
     * @param array $attributes key=>value
     *
     * @return boolean|string return form tag or FALSE
     */
    public function open($url = '#', $method = 'post', $upload = false, $attributes = array()) {
        $form_tag = FALSE;
        $tag_attr = '';
        $true = ($url == '#');

        if ($upload) {
            $attributes['enctype'] = 'multipart/form-data';
        }
        $attributes['method'] = $method;
        $attributes['action'] = $url;

        $tag_attr = $this->buildAttributes($attributes);
        $form_tag = '<form ' . $tag_attr . '>';
        $this->open_form = TRUE;

        return $form_tag;
    }

    /**
     * Close Form tag
     */
    public function close() {
        if ($this->open_form == TRUE) {
            return '</form>';
        }
    }

    /**
     * Input tag. Type = text
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     */
    public function text($name, $value = null, $attributes = array()) {
        $attributes['name'] = $name;
        return $this->buildType('text', $value, $attributes);
    }

    /**
     * Textarea tag.
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     */
    public function textarea($name, $value = null, $attributes = array()) {
        $attributes['name'] = $name;
        $tag_textarea = '<textarea ' . $this->buildAttributes($attributes) . '>';

        if (isset($value))
            $tag_textarea .= $value;

        return $tag_textarea . '</textarea>';
    }

    /**
     * Input tag. Type = hidden
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     */
    public function hidden($name, $value = null, $attributes = array()) {
        $attributes['name'] = $name;
        return $this->buildType('hidden', $value, $attributes);
    }

    /**
     * Input tag. Type = check
     *
     * @param string $name
     * @param boolean $checked
     * @param array $attributes
     */
    public function check($name, $checked, $attributes = array()) {
        $attributes['name'] = $name;

        if (is_string($checked))
            $checked = (strtolower($checked) == 'false'  || $checked == 0) ? false : true;

        if ($checked)
            $attributes['checked'] = 'checked';

        return $this->buildType('checkbox', null, $attributes);
    }

    /**
     * Input tag. Type = radio
     *
     * @param string $name
     * @param string $label
     * @param boolean $checked
     * @param array $attributes
     */
    public function radio($name,$label = '', $checked = 'false', $attributes = array()) {
        $attributes['name'] = $name;

        if (is_string($checked))
            $checked = (strtolower($checked) == 'false' || $checked == 0) ? false : true;

        if ($checked)
            $attributes['checked'] = 'checked';

        return $this->buildType('radio', null, $attributes) . $label;
    }

    /**
     * Input tag. Type = file
     *
     * @param string $name
     * @param array $attributes
     */
    public function file($name, $attributes = array()) {
        $attributes['name'] = $name;
        return $this->buildType('file', null, $attributes);
    }

    /**
     * Select tag
     *
     * @param string $name
     * @param string $value
     * @param array $options label=>valor
     * @param array $attributes
     */
    public function select($name, $value = null, $options = null, $attributes = array()) {
        $tag_select = '<select name="' . $name . '" ' . $this->buildAttributes($attributes) . '>';

        if (is_array($options)) {
            $tag_opt = '';

            foreach ($options as $label => $opt_value) {
                if ($value != $opt_value) {
                    $selected = '';
                } else {
                    $selected = ' selected="selected" ';
                }
                $tag_opt .= '<option value="' . $opt_value . '" ' . $selected . '>' . $label . '</option>';
            }
        }

        return $tag_select . $tag_opt . '</select>';
    }

    /**
     * Input tag. Type = submit
     *
     * @param string $value
     * @param array $attributes
     */
    public function submit($value, $attributes = array()) {
        return $this->buildType('submit', $value, $attributes);
    }

    /**
     * Input tag. Type = reset
     *
     * @param string $value
     * @param array $attributes
     */
    public function reset($value, $attributes = array()) {
        return $this->buildType('reset', $value, $attributes);
    }


    /**
     * Input tag. Type = button
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     */
    public function button($name, $value, $attributes = array()) {
        $attributes['name'] = $name;
        return $this->buildType('button', $value, $attributes);
    }

    /**
     * Open Fieldset tag
     *
     * @param string $legend
     * @param array $legend_attr
     * @param array $attributes
     */
    public function openFieldset($legend = null, $legend_attr = array(), $attributes = array()) {
        $tag_fieldset = '<fieldset ' . $this->buildAttributes($attributes) . '>';
        if (isset($legend)) {
            $tag_fieldset .= '<legend ' . $this->buildAttributes($legend_attr) . '>' . $legend . '</legend>';
        }
        return $tag_fieldset;
    }

    /**
     * Close Fieldset tag
     */
    public function closeFieldset() {
        return '</fieldset>';
    }

    /**
     * Build tag label. If $opened equals true then label will be opened
     *
     * @param string $label
     * @param boolean $opened
     * @param array $attributes
     */
    public function label($label, $opened = false, $attributes = array()) {

        $tag_label  = '<label ' . $this->buildAttributes($attributes) . '>';
        $tag_label .= (!empty ($label))? '<p>' . $label.'</p>':'';
        if (!$opened) {
            $tag_label .= '</label>';
        }

        return $tag_label;
    }

    /**
     * Close the label tag
     */
    public function closeLabel() {
        return '</label>';
    }

    /**
     * Create the attributes for tags
     *
     * @param array $attributes
     */
    private function buildAttributes($attributes) {
        $tag_attr = '';
        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $tag_attr .= $key . '="' . $value . '"  ';
            }
        } else {
            throw new Ibe_Exception(Ibe_Exception::FORM_ATRIBUTOS);
        }

        return $tag_attr;
    }

    /**
     * Build the type input tag
     *
     * @param string $type
     * @param string $value
     * @param array $attributes
     */
    private function buildType($type, $value, $attributes) {
        $attributes['type'] = $type;
        if (!$value == null)
            $attributes['value'] = $value;
        return '<input	' . $this->buildAttributes($attributes) . ' >';
    }

}