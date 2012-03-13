<?php
/**
 * Classe que implementa uma a��o responsavel por retornar um JSON ao inv�s de
 * criar um LAYOUT. Util para cria��o de Web Services
 */
abstract class Ibe_Action_Json extends Ibe_Action{
    protected $json_response = NULL;

    public function execute(Ibe_Request $req) {
        $this->json_response = new Ibe_Object();

        try {
            $this->run($req);
            $this->json_response->success = true;
            $this->json_response->message = 'Acao executada com sucesso';
        } catch (Exception $e) {
            $this->json_response->success = false;
            $this->json_response->message = $e->getMessage();
        }


        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        $array = array(
            'success' => $this->json_response->success,
            'message' => $this->json_response->message,
            'list' => $this->json_response->list
        );

        echo json_encode(array_merge($array, $this->json_response->__getVars()));

        return Ibe_Layout::NONE;
    }

    /**
     * M�todo da a��o que ser� executado ao iniciar uma requisi��o
     */
    abstract protected function run(Ibe_request $req);
}