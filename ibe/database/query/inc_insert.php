<?php
/**
 * Classe para criacao de querys INSERT SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database.query
 */
class Ibe_Database_Query_Insert extends Ibe_Database_Query{
    private $table = null;
    private $fields = array();

    public function __construct($table_name){
        parent::__construct(Ibe_Database_Query::INSERT);
        $this->table = $table_name;
    }

    /**
     * Adiciona um campo para o field
     * @param string $field
     * @param mixed $value
     * @return Ibe_Database_Query_Insert
     */
    public function addField($field,$value,$type = 'string'){

        if($type == 'string' || $type == 'date'){
            $value = '"'.$value.'"';
        }

        if($value === NULL){
            $value = 'NULL';
        }

        $this->fields[$field] = $value;
        return $this;
    }

    public function getQuery() {
        if(!isset($this->table)){
            throw new Ibe_Exception(Ibe_Exception::TABELA_INDEFINIDA_NO_SQL,'');
        }
        if(!sizeof($this->fields)){
            throw new Ibe_Exception(Ibe_Exception::SQL_SEM_CAMPO,'');
        }

        $query   = array('INSERT INTO '.$this->table);
        $query[] = '('.  implode(',', array_keys($this->fields)).')';
        $query[] = 'VALUES';
        $query[] = '('.  implode(',', $this->fields).')';

        return implode(' ', $query);
    }
}