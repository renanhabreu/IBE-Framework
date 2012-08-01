<?php

class Flt extends Skt_Core_Maker{

    public function create(Skt_Core_Request $req){        
        $name = $req->getParam('flt', FALSE);
        if(!$name){
            Skt_Core_Prompt::print_('O nome passado esta invalido', Skt_Core_Prompt::ERROR);
        }else{
            $dir = $this->_dir_app.$this->_configure->path;
            $this->_file->include_tpl('filter', $dir, array('flt'=>  ucfirst(strtolower($name))),"inc_".strtolower($name));
        }
    }
}
