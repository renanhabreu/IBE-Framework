<?php
/**
 * Classe para criacao inicio de transacao SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database
 */
class Ibe_Database_Transaction{
    private $conn_link = null;

    public function __construct($name = null){
        $this->conn_link = isset($name)? $name:Ibe_Database::getConn();
    }

    /**
     * Inicia uma transa��o no banco de dados.
     * As tabelas devem utilizar Inodb
     */
    public function begin(){
        if(isset($this->conn_link)){
            mysql_query('begin',$this->conn_link);
        }else{
            throw new Ibe_Exception(Ibe_Exception::DB_SEM_RESOURCE);
        }
    }

    /**
     * Fecha uma transa��o confirmando que a opera��o n�o executou nenhuma
     * exce��o
     */
    public function commit(){
        mysql_query('commit',$this->conn_link);
    }

    /**
     * Cancela uma transa��o quando houver alguma exce��o ao executar
     * uma consulta/clausura
     */
    public function rollback(){
        mysql_query('rollback',$this->conn_link);
    }
}