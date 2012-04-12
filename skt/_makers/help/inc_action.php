<?php

class Help extends Skt_Core_Maker{
    
    public function create(Skt_Core_Request $req){
        $command = $req->getParam("cmd",FALSE);
        
        if(!$command){
            Skt_Core_Prompt::print_("help cmd:[PARAM]");
            Skt_Core_Prompt::print_("Abaixo a lista de parametros válidos:");
            foreach($this->_configure->helpers as $helper){
                Skt_Core_Prompt::print_(" help cmd:".$helper);
            }
        }else{
            $path = "_makers".DS.$command.DS."help.php";
            if(file_exists($path)){
                include_once($path);
                
                Skt_Core_Prompt::print_($_description,Skt_Core_Prompt::ALERT);
                Skt_Core_Prompt::print_("",Skt_Core_Prompt::ALERT);
                Skt_Core_Prompt::print_("PARAMETROS: ",Skt_Core_Prompt::ALERT);
                foreach($_params as $param=>$desc){
                    Skt_Core_Prompt::print_("       [ ".$param ." ] ".$desc,Skt_Core_Prompt::ALERT);
                }
                Skt_Core_Prompt::print_("",Skt_Core_Prompt::ALERT);
                Skt_Core_Prompt::print_("EXEMPLO:  ".$_example,Skt_Core_Prompt::ALERT);
            }
        }
    }
}
