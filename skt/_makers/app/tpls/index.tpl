<?php

//incluindo o arquivo que contem a classe de autoload
include '@DIR_FRM@/ibe/inc_autoload.php';

try{

    //Ativa o Log
    Ibe_Debug::enable();
    
    //Configura requisicao padrao
    Ibe_Request::setDefaultModule('@MOD@');
    Ibe_Request::setDefaultController('@CTR@');
    Ibe_Request::setDefaultAction('@ACT@');
    // Dispara a requisicao
    Ibe_Request::dispatch();

}catch (Ibe_Exception $e) {
	$e->alert();
}catch (Exception $e) {
	Ibe_Debug::dispatchError(__FILE__, $e->getMessage());
}


