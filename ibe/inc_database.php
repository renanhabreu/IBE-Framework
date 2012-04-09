<?php

/**
 * Classe de manipulacao de banco de dados
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 */
abstract class Ibe_Database {

    /**
     * Conexao mysql
     * @var mysql_connection
     */
    private static $conn = false;

    /**
     * Abre uma nova conexao
     * @param string $local
     * @param string $schema
     */
    static public function open($host, $user, $pass, $schema) {
        /* if(!isset (self::$conn)){
          include_once Ibe_Register::get('SIGO_CAMINHO_RAIZ') . "includes/banco.php";
          } */
        @self::$conn = mysql_connect($host,$user,$pass);
        if (!self::$conn) {
            throw new Ibe_Exception_Database(mysql_error(),null,false);
        }
        $selected = mysql_select_db($schema);
        if (!$selected) {
            throw new Ibe_Exception_Database(mysql_error(),null,false);
        }
    }

    /**
     * Fecha uma conexao
     */
    static public function close() {
        if (isset(self::$conn)) {
            mysql_close(self::$conn);
        }
    }

    /**
     * Captura a conexao
     * @return mysql_connection
     */
    static public function getConn() {
        return self::$conn;
    }

    /**
     * Seta uma nova conexao mysql
     * @param resource_mysql $mysql_connection
     */
    static public function setConn($mysql_connection){
        self::$conn = $mysql_connection;
    }

    /**
     * Executa um sql
     * @param string $query
     * @return mysql_result
     */
    static public function execute($query) {


        $result = mysql_query($query);

        if (!$result) {
            throw new Ibe_Exception_Database(Ibe_Exception::FALHA_DE_SQL, array(mysql_error(), $query));
        }

        return $result;
    }

}
