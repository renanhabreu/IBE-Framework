<?php

class Model_Controller{
    private $nome = NULL;
    private $unique = NULL;

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function getUnique() {
        return $this->unique;
    }

    public function setUnique($unique) {
        $this->unique = $unique;
        return $this;
    }

    public function criar(){
        if($this->unique == 'on'){
            if(!is_dir('../../../controllers')){
                mkdir('../../../controllers', 777, true);
            }

            $class = file_get_contents('maps/inc_action.php');
            $class = str_replace('____', $this->nome, $class);
            file_put_contents('../../../controllers/inc_'.strtolower($this->nome).'.php', $class);
        }else{
            if(!is_dir('../../../controllers/'.strtolower($this->nome))){
                mkdir('../../../controllers/'.strtolower($this->nome), 777, true);
            }
        }
    }
}