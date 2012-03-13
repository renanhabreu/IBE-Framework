<?php
/**
 * Layout de tela
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage layout
 */
class Ibe_Layout_Screen extends Ibe_Layout {
    /**
     * Nome do arquivo de layout
     * @var string
     */
    private $name = null;

    /**
     * Nome do subdiretorio da pasta de templates
     * @var string
     */
    private $directory = array();

    /**
     *
     * @return Ibe_Layout_Screen
     */
    static public function getInstance() {
        return new self();
    }

    /**
     * Seta o nome do arquivo de layout. Sempre sera adicionado a substring inc_
     * no layout
     *
     * @param string $name
     * @return Ibe_Layout_Screen
     */
    public function setScreenName($name){

        if(substr($name,0,1) != '_'){
            $name = '_'.$name;
        }
        $this->name = 'inc'.strtolower($name);
        return $this;
    }

    /**
     * Seta a subpasta do diretorio de layouts que contem o layout a ser exibido
     * @param string $name
     * @return Ibe_Layout_Screen
     */
    public function addPathName($name){
        $this->directory[] = $name;
        return $this;
    }

    /**
     * Mostra o layout na saida
     */
    public function showLayout() {
        $dir = array();
        if(isset($this->name)){

            // Nome do layout
            $this->directory[] = $this->name.'.php';
            // Construindo o  diretorio final do layout
            $tela = implode('/', $this->directory);
            // Inserindo o layout na aplicacao

            if(file_exists($tela)){
                include_once $tela ;
            }else{
                throw new Ibe_Exception(Ibe_Exception::LAYOUT_SEM_TELA,array($this->name,$tela));
            }

        }else{
            throw new Ibe_Exception(Ibe_Exception::LAYOUT_SEM_NOME);
        }
    }

    /**
     * Retorna o conteudo do layout sem imprimir o mesmo
     * @return string
     */
    public function getLayout() {
        ob_start();
        $this->showLayout();
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }
}
