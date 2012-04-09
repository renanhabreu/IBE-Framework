<?php

abstract class Skt_Core_Prompt{
    const ALERT = 1;
    const ERROR = 2;
    
    static public function print_($str,$type = NULL){
    
        if(Skt_Core_Request::$request_http){
            $alert   = "<span style='color:yellow'> :: %s</span><br/>";
            $error   = "<span style='color:yellow'> :: %s</span><br/>";
            $default = "<span style='color:yellow'> :: %s</span><br/>";
        }else{            
            $alert   = "\033[1;33m :: %s\033[0m\r\n";
            $error   = "\033[41;32m :: %s\033[0m\r\n";
            $default = "\033[44;40m :: %s\033[0m\r\n";
        }
        
        $retorno = "";
        
        switch($type){
            case Skt_Core_Prompt::ALERT:
                $retorno = sprintf($alert,$str);
                break;
            case Skt_Core_Prompt::ERROR:
                $retorno = sprintf($error,$str);
                break;
            default:
                $retorno = sprintf($default,$str);
                break;
        }
        
        echo $retorno;
        
    }
    
}
