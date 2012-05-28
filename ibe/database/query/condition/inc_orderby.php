<?php
/**
 * Classe para criacao de condicoes ORDER BY de querys SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database.query.condition
 */
class Ibe_Database_Query_Condition_Orderby{

    private $fields = array();
    private $order  = 'ASC';

    /**
     * Adiciona campos ao order BY
     * @param string $field_name
     * @return Ibe_Database_Query_Condition_Orderby
     */
    public function addField($field_name){
        $this->fields[] = $field_name;
        return $this;
    }

    /**
     * seta a ordenacao
     * @param boolean $ative
     * @return Ibe_Database_Query_Condition_Orderby
     */
    public function setAsc($ative = true){
        $this->order = ($ative)? 'ASC':'DESC';
        return $this;
    }

    /**
     * Retorna a clausura order by
     * @return string
     */
    public function getOrderby(){

        $order = '';
        if(sizeof($this->fields)){
            $order = 'ORDER BY '.implode(' ,',$this->fields).' '.$this->order;
        }
        return $order;
    }



}
