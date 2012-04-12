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
     * Inicia uma transação no banco de dados.
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
     * Fecha uma transação confirmando que a operação não executou nenhuma
     * exceção
     */
    public function commit(){
        mysql_query('commit',$this->conn_link);
    }

    /**
     * Cancela uma transação quando houver alguma exceção ao executar
     * uma consulta/clausura
     */
    public function rollback(){
        mysql_query('rollback',$this->conn_link);
    }
}