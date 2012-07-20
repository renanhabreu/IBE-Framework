<?php

class Configure extends stdClass {
    
    public $path_doctrine_library   = '_maps/doctrine';
    
    public $doctrine_configure = array(
        'data_fixtures_path' => '_maps/data/fixtures',
        'models_path' => '_maps/models',
        'migrations_path' => '_maps/migrations',
        'sql_path' => '_maps/data/sql',
        'yaml_schema_path' => '_maps/schema'
    );
    
    public $doctrine_database_dns = 'mysql://root:123@localhost/teste'; 
    
    
}

?>
