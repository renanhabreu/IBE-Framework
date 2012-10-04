<?php
include_once 'cls/inc_maker.php';
/**
 * Description of inc_menu
 *
 * @author renan.abreu
 */
class Menu{
    private $makers = array();
    
    static public function get(){
        return new Menu();
    }
    
    public function __construct() {
        $this->getMakers();        
    }
    
    public function show(){
        foreach($this->makers as $maker){
            echo $maker->getHtml();
        }
    }
    
    private function getMakers(){
        $makers_path  = "../skt/_makers/";
        $makers = array();
        
        if(!($makers = scandir($makers_path))){
            throw new Exception("Nao foi possivel realizar a leitura do diretorio de makers");
        }
        
        foreach($makers as $maker){
            if($maker != "." && $maker != ".."){
            	$_name = null;
            	$_description = null;
            	$_example = null;
            	$_params = null;
            	$_position = null;
                $help_file = $makers_path.$maker."/help.php";
                
                if(file_exists($help_file)){
                    include_once $help_file;
                    $mk = new Maker();
                    $mk->setName($_name);
                    $mk->setDescription($_description);
                    $mk->setExample($_example);
                    $mk->setParams($_params);
                    $mk->setPath($maker);
                    
                    $position = Maker::registerId($_position);
                    $mk->setId($position);
                    $this->makers[$position] = $mk;                
                }
            }
        }
        
        ksort($this->makers);
    }
}

?>
