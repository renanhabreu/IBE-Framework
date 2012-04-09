<?php

class Skt_Core_File{
    private $dir_tpls = NULL;
    
    public function __construct($dir_tpls){
        $this->dir_tpls = $dir_tpls;
    }
    
    public function include_configure($dir){
        $dir = "_makers/".$dir.DS;
        
        if(!file_exists($dir."configure.php")){
            Skt_Core_Prompt::print_("arquivo de configuracao nao existe [".$dir."]", Skt_Core_Prompt::ERROR);  
            exit();
        }
        
        include_once($dir."configure.php");
        return new Configure();
        
    }
    
    public function include_tpl($name,$local,array $params = array(),$alias = NULL){
        
        if(is_null($alias)){
            $alias = $name;
        }
        
        $dir = "_makers/".$this->dir_tpls.DS."tpls".DS.$name.".tpl";
        
        if(!file_exists($dir)){
            Skt_Core_Prompt::print_("template nao existe [".$dir."]", Skt_Core_Prompt::ERROR);  
            exit();
        }
        
        $str = file_get_contents($dir);
        
        foreach($params as $param_name=>$param_value){            
            $str = str_replace(strtoupper("@".$param_name."@"),$param_value,$str);
        }
        
        if(file_put_contents($local.DS.$alias.".php",$str)){            
            Skt_Core_Prompt::print_("Arquivo baseado em template inserido [".$name.".php]");  
        }
    }
    
}
