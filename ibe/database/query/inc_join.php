<?php
/**
 * Classe para criacao de querys JOIN SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database.query
 */
class Ibe_Database_Query_Join{

    protected $table_a  = false;
    protected $table_b  = false;
    protected $where    = false;
    protected $type     = false;

    /**
     * Instanciando um novo join
     * @param string $table_a
     * @param string $table_b
     */
    public function __construct($table_a,$table_b){
        $this->table_a = $table_a;
        $this->table_b = $table_b;
    }

    /**
     * Right join
     * @return Ibe_Database_Query_Join
     */
    public function setRight(){
        $this->type = 'RIGHT';
        return $this;
    }

    /**
     * Left join
     * @return Ibe_Database_Query_Join
     */
    public function setLeft(){
        $this->type = 'LEFT';
        return $this;
    }

    /**
     * Adiciona clausuras where
     * @param Ibe_Database_Query_Condition_Where $where
     * @return Ibe_Database_Query_Join
     */
    public function addWhere(Ibe_Database_Query_Condition_Where $where){
        $this->where = $where;
        return $this;
    }

    /**
     * Monta a clausura join
     * @return string
     */
    public function getJoin(){
        $join = '';

        if($this->table_a && $this->table_b){
            if($this->where){
                $where = $this->where->getWhere();
            }else{
                throw new Ibe_Exception(Ibe_Exception::SQL_JOIN_SEM_WHERE);
            }

            $type = ($this->type)? $this->type:'INNER';
            $join = $type.' JOIN '.$this->table_a. ' ON'.$where;
        }

        return $join;
    }
}