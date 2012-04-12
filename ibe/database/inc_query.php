<?php
/**
 * Classe para criacao de querys SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database
 */
abstract class Ibe_Database_Query{
    const INSERT = 1;
    const DELETE = 2;
    const UPDATE = 3;
    const SELECT = 4;
    private $type = 0;

    /**
     * Clausura where
     * @var Ibe_Database_Query_Condition_Where
     */
    protected $where = null;

    public function __construct($type){
        $this->type = $type;
    }

    /**
     * Captura o SQL
     */
    abstract public function getQuery();

    /**
     * Cria uma clausura select
     * @param type $table
     * @return Ibe_Database_Query_Select
     */
    static public function newSelect($table = null){
        return new Ibe_Database_Query_Select($table);
    }

    /**
     * Cria uma nova clausura query
     * @return Ibe_Database_Query_Condition_Where
     */
    static public function newWhere($field = null, $value = null, $sinal = '=', $type = 'string'){
        $where = new Ibe_Database_Query_Condition_Where();

        if(isset($field) && isset($value)){
            $where = $where->where($field, $value, $sinal, $type);
        }

        return $where;
    }

    /**
     * Cria uma nova clausura Order By
     * @return Ibe_Database_Query_Condition_Orderby
     */
    static public function newOrderby(){
        return new Ibe_Database_Query_Condition_Orderby();
    }

    /**
     * Cria uma nova clausura join
     * @param string $table_a
     * @param string $table_b
     * @return Ibe_Database_Query_Join
     */
    static public function newJoin($table_a,$table_b){
        return new Ibe_Database_Query_Join($table_a, $table_b);
    }

    /**
     * Cria uma nova consulta do tipo delete
     * @param string $table_name
     * @return Ibe_Database_Query_Delete
     */
    static public function newDelete($table_name){
        return new Ibe_Database_Query_Delete($table_name);
    }

    /**
     * Cria uma nova clausura insert
     * @param string $table_name
     * @return Ibe_Database_Query_Insert
     */
    static public function newInsert($table_name){
        return new Ibe_Database_Query_Insert($table_name);
    }

    /**
     * Cria uma nova clausura update
     * @param string $table_name
     * @return Ibe_Database_Query_Insert
     */
    static public function newUpdate($table_name){
        return new Ibe_Database_Query_Update($table_name);
    }

    /**
     * Captura uma where
     * @return Ibe_Database_Query_Condition_Where
     */
    public function getWhere(){
        return $this->where;
    }

    /**
     * Executa a query
     * @return array de resultados
     */
    public function execute($mount_array = true,$query = NULL){

        if(Ibe_Database::getConn()){
            if(!isset($query) || !is_string($query)){
                $query = $this->getQuery();
            }

            $result = mysql_query($query);
            if (!$result) {
                $query = (Ibe_Register::get('SHOW_SQL_IN_EXCEPTION',false))? $query:'';
                throw new Ibe_Exception(Ibe_Exception::FALHA_DE_SQL,array(mysql_error(),$query));
            }

            if($mount_array){
                $array_results = array();
                while (($obj = mysql_fetch_object($result))) {
                    $array_results[] = $obj;
                }
            }else{
                $array_results = $result;
            }

        }else{
            throw new Ibe_Exception(Ibe_Exception::ERRO_MYSQL);
        }

        return $array_results;
    }

    /**
     * Retorna o tipo de query  criada
     * @return string
     */
    public function getType(){
        return $this->type;
    }
}
