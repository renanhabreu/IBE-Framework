<?php
//[[../../../index.php]]
//incluindo o arquivo que contem a classe de autoload
include '../libraries/ibe/inc_autoload.php';

try{
	//Registra o diretorio das funcionalidades sigo
	Ibe_Autoload::frameworkDirectoryRegister('../libraries/ibe/');

        //Ativa o Log
        Ibe_Log::enable();
        Ibe_Debug::enable();

        //Setando o nome padrao das pastas
        Ibe_Source::setPathModuleName('includes');

        Ibe_Request::setDefaultModule('index');
        Ibe_Request::setDefaultController('index');
        Ibe_Request::setDefaultAction('index');

	//Conectando ao banco de dados
	//Ibe_Database::open($host,$user,$pass,$schema);

	// Dispara a requisicao
	Ibe_Request::dispatch();

	//Fechando a conexao
	//Ibe_Database::close();


}catch (Ibe_Exception $e) {
	$e->alert();
}catch (Exception $e) {
	Ibe_Debug::dispatchError('In Ibe_Layout Class', $e->getMessage());
}

