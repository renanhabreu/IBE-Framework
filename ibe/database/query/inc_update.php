<?php
/**
 * Classe para criacao de querys UPDATE SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database.query
 */
class Ibe_Database_Query_Update extends Ibe_Database_Query{
    private $table = null;
    private $fields = array();

    /**
     *
     * @param string $table_name
     */
    public function __construct($table_name) {
        parent::__construct(Ibe_Database_Query::UPDATE);
        $this->table = $table_name;
    }

    /**
     * Adiciona where para clausuras delete
     * @param Ibe_Database_Query_Condition_Where $where
     * @return Ibe_Database_Query_Delete
     */
    public function addWhere(Ibe_Database_Query_Condition_Where $where){
        $this->where = $where;
        return $this;
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

        $this->fields[$field] = $value;
        return $this;
    }

    public function getQuery() {
        if(!isset($this->table)){
            throw new Ibe_Exception(Ibe_Exception::SQL_TABELA_INDEFINIDA);
        }
        if(!sizeof($this->fields)){
            throw new Ibe_Exception(Ibe_Exception::SQL_SEM_CAMPO);
        }

        $set = array();
        foreach($this->fields as $field=>$value){
            $set[] = $field.'='.$value;
        }
        $query   = array('UPDATE '.$this->table.' SET');
        $query[] = implode(',', $set);

        if(isset($this->where)){
            $query[] = 'WHERE'.$this->where->getWhere();
        }

        return implode(' ', $query);
    }
}