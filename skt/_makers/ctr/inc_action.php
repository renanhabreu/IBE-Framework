<?php

class Ctr extends Skt_Core_Maker{

    public function create(Skt_Core_Request $req){        
        $this->_directory->create($this->_dir_ctr);
        
        foreach($this->_configure->paths as $path){
            $this->_directory->create($this->_dir_ctr.$path);
        }
        
        $this->_file->include_tpl("inc_views",$this->_dir_ctr);
    }
}
