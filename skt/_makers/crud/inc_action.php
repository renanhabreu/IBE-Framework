<?php 

class Crud extends Skt_Core_Maker{
	
	public function create(Skt_Core_Request $req){
		
        $this->_directory->create($this->_dir_ctr);
        
        foreach($this->_configure->paths as $path){
            $this->_directory->create($this->_dir_ctr.$path);
        }
        
        $var = array('table'=>$req->getParam('table','tbl_name'));
        
        $this->_file->include_tpl("inc_views",$this->_dir_ctr);
        $this->_file->include_tpl("inc_view",$this->_dir_ctr."_views",$var,"inc_index");
        $this->_file->include_tpl("inc_index",$this->_dir_ctr."_actions",$var);
        $this->_file->include_tpl("inc_excluir",$this->_dir_ctr."_actions",$var);
        $this->_file->include_tpl("inc_salvar",$this->_dir_ctr."_actions",$var);
	}
} 
