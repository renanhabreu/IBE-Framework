<?php


class App extends Skt_Core_Maker{

    public function create(Skt_Core_Request $req){
    	$req->getParam('repository_app', 'ibe-apps');
        $this->_directory->create($this->_dir_app);
        
        foreach($this->_configure->paths as $path){
            $this->_directory->create($this->_dir_app . $path);
        }
        
        $vars = $req->getParamsWithDefaults($this->_configure->index);
        
        $this->_file->include_tpl("index",$this->_dir_app,$vars);
        $this->_file->include_tpl("inc_configure",$this->_dir_app."_modules");
        $this->_file->include_tpl("inc_views",$this->_dir_app."_modules");
    }
}
