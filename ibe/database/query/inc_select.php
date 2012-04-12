<?php
/**
 * Classe para criacao de querys SELECT SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database.query
 */
class Ibe_Database_Query_Select extends Ibe_Database_Query{

    /**
     * Campos do select
     * @var array
     */
    private $fields = array();
    /**
     * Tabela do select
     * @var string
     */
    private $table  = null;
    /**
     * Query items
     * @var array
     */
    private $query = array();

    /**
     * Clausuras join
     * @var array
     */
    private $join = array();

    /**
     * order by para selects
     * @var Ibe_Database_Query_Condition_Orderby
     */
    private $order_by = null;

    /**
     * Limite da paginacao
     * @var int
     */
    private $limit = 10000;

    /**
     * Pagina atual da paginacao
     * @var type
     */
    private $page = 0;

    public function __construct($table = null) {
        parent::__construct(Ibe_Database_Query::SELECT);
        $this->table = $table;
    }

    /**
     * Retorna os campos do select
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Seta os campos do select
     * @param array $fields
     * @return Ibe_Database_Query_Select
     */
    public function setFields(array $fields) {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Captura o nome da tabela do select
     * @return string
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Seta o nome da tabela do select
     * @param string $table
     * @return Ibe_Database_Query_Select
     */
    public function setTable($table) {
        $this->table = $table;
        return $this;
    }

    /**
     * Seta o tamanho da paginacao
     * @param int $size
     */
    public function setLimit($size){
        $this->limit = $size;
        return $this;
    }

    /**
     * Seta a pagina atual da paginacao
     * @param int $page
     */
    public function setPage($page){
        $this->page = $page;
        return $this;
    }

    /**
     * Adiciona um campo a tabela do select
     * @param string $field_name
     * @return Ibe_Database_Query_Select
     */
    public function addField($field_name){
        $this->fields[] = $field_name;
        return $this;
    }

    /**
     * Adiciona where
     * @param Ibe_Database_Query_Condition_Where $where
     * @return Ibe_Database_Query_Select
     */
    public function addWhere(Ibe_Database_Query_Condition_Where $where){
        $this->where = $where;
        return $this;
    }

    /**
     * Adiciona JOIN
     * @param Ibe_Database_Query_Join $join
     * @return Ibe_Database_Query_Select
     */
    public function addJoin(Ibe_Database_Query_Join $join){
        $this->join[] = $join;
        return $this;
    }

    /**
     * Adiciona um uma clausura order by
     * @param Ibe_Database_Query_Condition_Orderby $orderby
     * @return Ibe_Database_Query_Select
     */
    public function addOrderBy(Ibe_Database_Query_Condition_Orderby $orderby){
        $this->order_by = $orderby;
        return $this;
    }

    /**
     * Retorna a query montada
     * @return string
     */
    public function getQuery(){
        // Clausura do select
        $this->query['type'] = 'SELECT';


        // Campos do select
        if(sizeof($this->fields)){
            $this->query['fields'] = implode(',', $this->fields);
        }else{
            $this->query['fields'] = '*';
        }

        // From
        $this->query['from'] = 'FROM';

        //procurando nome da tabela
        if(isset($this->table)){
            $this->query['table'] = $this->table;
        }else{
            throw new Ibe_Exception(Ibe_Exception::SQL_TABELA_INDEFINIDA);
        }

        //clausura JOIN
        $this->query['join'] = '';
        foreach($this->join as $join){
            $this->query['join']  .= ' '.$join->getJoin();
        }

        //clausura WHERE
        if(isset($this->where)){
            $where = $this->where->getWhere();

            if(isset($where)){
                $this->query['where'] = 'WHERE'.$where;
            }
        }

        // Order BY
        if(isset($this->order_by)){
            $this->query['order'] = $this->order_by->getOrderby();
        }

        //LIMIT
        $this->query['limit'] = 'LIMIT '.$this->page.','.$this->limit;

        return implode(' ',$this->query);
    }


}

