<?php

abstract class Skt_Core_Prompt{
    const ALERT = 1;
    const ERROR = 2;
    static private $alert_command_json = array();
    static public function print_($str,$type = NULL){
    
        if(Skt_Core_Request::$request_http){
            $alert   = "<span style='padding:2px;background:#000; color:#ffaa00'> :: %s :: in %s</span><br/>";
            $error   = "<span style='padding:2px;background:#000; color:#b05454'> :: %s :: in %s</span><br/>";
            $default = "<span style='padding:2px;background:#000; color:#fff'> :: %s :: in %s</span><br/>";
        }else{            
            $alert   = "\033[1;33m :: %s\033[0m\r\n";
            $error   = "\033[41;32m :: %s\033[0m\r\n";
            $default = "\033[44;40m :: %s\033[0m\r\n";
        }
        
        $retorno = "";
        
        switch($type){
            case Skt_Core_Prompt::ALERT:
                $retorno = sprintf($alert,$str,date('H:i:s'));
                break;
            case Skt_Core_Prompt::ERROR:
                $retorno = sprintf($error,$str,date('H:i:s'));
                break;
            default:
                $retorno = sprintf($default,$str,date('H:i:s'));
                break;
        }
        
        self::show($retorno);
        
    }
    
    static private function show($str){
        if(Skt_Core_Request::$request_http){  
            self::$alert_command_json["message"][] = $str;
        }else{
            echo $str;
        }
    }
    
    static public function responseIfHttp(){
        if(Skt_Core_Request::$request_http){
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            echo json_encode(self::$alert_command_json);
        }
    }
    
}
