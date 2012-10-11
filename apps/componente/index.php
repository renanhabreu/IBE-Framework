<?php

//incluindo o arquivo que contem a classe de autoload
include dirname(__FILE__).'/../../ibe/inc_autoload.php';

try{

    //Ativa o Log
    Ibe_Debug::enable();
    
    //Configura requisicao padrao
    Ibe_Request::setDefaultModule('index');
    Ibe_Request::setDefaultController('index');
    Ibe_Request::setDefaultAction('index');
    
    // Dispara a requisicao 
    // param == TRUE to session_start 
    // param == FALSE default
    Ibe_Request::dispatch(FALSE);

}catch (Exception $e) {
	Ibe_Debug::error($e->getMessage());
}


