<?php

/**
 * Arquivo de configuracao de modulo
 * 
 * @author Renan Abreu <renanhabreu@gmail.com>
 * @package ibe
 */
class Ibe_Configure{
	
	/**
	 * Lista de filtros executados antes de todas
	 * as acoes
	 * @var array
	 */
    protected $filters = array();
    /**
     * Lista de helpers instanciados para todas acoes
     * @var array
     */
    protected $helpers = array();
    protected $allowComponents = FALSE;
    protected $modules_params = array();
    protected $database_active = FALSE;
    protected $action_return_json = FALSE;
    protected $database_host = "";
    protected $database_user = "";
    protected $database_pass = "";
    protected $database_schm = "";
    
    
    public function getFilters(){
        return $this->filters;
    }
    
    public function getHelpers(){
        return $this->helpers;
    }

    public function isAllowComponents(){
    	return $this->allowComponents;
    }
        
    public function getModulesParams(){
        return $this->modules_params;
    }
    
    public function isDataBaseActive(){
        return $this->database_active;
    }
    
    public function isActionReturnJson(){
        return $this->action_return_json;
    }
    
    public function getDataBaseHost(){
        return $this->database_host;
    }
    
    public function getDataBaseUser(){
        return $this->database_user;
    }
    
    public function getDataBasePass(){
        return $this->database_host;
    }
    
    public function getDataBaseSchm(){
        return $this->database_schm;
    }
    
}
