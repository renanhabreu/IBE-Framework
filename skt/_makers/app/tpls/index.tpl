<?php

//incluindo o arquivo que contem a classe de autoload
include '@DIR_FRM@/inc_autoload.php';

try{
	//Registra o diretorio das funcionalidades sigo
	Ibe_Autoload::frameworkDirectoryRegister('@DIR_FRM@/');

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


