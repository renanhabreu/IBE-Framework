<?php

class Skt_Core_Directory{
   
   public function create($directory){
        
        if(!is_dir($directory)){         
            if(mkdir($directory,0777,true)){
                Skt_Core_Prompt::print_("diretorio [".$directory."] criado com sucesso");   
            }else{
                Skt_Core_Prompt::print_("erro ao criar o diretorio [".$directory."]", Skt_Core_Prompt::ERROR);                  
            }
        }else{
            Skt_Core_Prompt::print_("diretorio [".$directory."] existente",Skt_Core_Prompt::ALERT); 
        }
    }
    
    public function dirApp($name){
        return APP_DIR.$name.DS;
    }
    
    public function dirMod($app,$name){
        return $this->dirApp($app)."_modules".DS.$name.DS;
    }
    
    public function dirCtr($app,$mod,$name){
        return $this->dirMod($app,$mod).$name.DS;
    }
    
    public function dirAct($app,$mod,$ctr,$name){
        return $this->dirCtr($app,$mod,$ctr)."_actions".DS;
    }
    
    public function dirActView($app,$mod,$ctr,$name){
        return $this->dirCtr($app,$mod,$ctr)."_views".DS;
    }
}
