<?php

class Mod extends Skt_Core_Maker{

    public function create(Skt_Core_Request $req){
        $this->_directory->create($this->_dir_mod);                
        $this->_file->include_tpl("inc_views",$this->_dir_mod);
    }
}
