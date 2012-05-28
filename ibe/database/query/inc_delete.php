<?php
/**
 * Classe para criacao de querys DELETE SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database.query
 */
class Ibe_Database_Query_Delete extends Ibe_Database_Query{
    private $table = null;

    public function __construct($table_name) {
        parent::__construct(Ibe_Database_Query::DELETE);
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

    public function getQuery() {
        if(!isset($this->table)){
            throw new Ibe_Exception(Ibe_Exception::TABELA_INDEFINIDA_NO_SQL);
        }

        $where = $this->where->getWhere();
        if(isset($where)){
            $where = ' WHERE '.$where;
        }

        return 'DELETE FROM '.$this->table.$where;
    }
}

