<?php

class Configure extends stdClass{

    public $database = array(
        "host"=>"localhost",
        "user"=>"root",
        "pass"=>"",
        "schm"=>"test"
    );
    
    public $type = array(
        "int" => "int",
        "text" => "string",
        "longtext" => "string",
        "bool" => "bool",
        "date" => "int",
        "blob" => "int",
        "float" => "int",
        "double" => "int",
        "bit" => "int",
        "bigint" => "int",
        "tinyint" => "int",
        "longint" => "int",
        "varchar" => "string",
        "smallint" => "int",
        "datetime" => "int",
        "timestamp" => "int"
    );
}
?>
