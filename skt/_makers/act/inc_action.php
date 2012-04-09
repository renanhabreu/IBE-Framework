<?php

class Act extends Skt_Core_Maker{

    public function create(Skt_Core_Request $req){
        $var = array("act"=>ucfirst(strtolower($this->_act)));
        $this->_file->include_tpl("inc_view",$this->_dir_ctr."_views",$var,"inc_".$this->_act);
        $this->_file->include_tpl("inc_action",$this->_dir_ctr."_actions",$var,"inc_".$this->_act);
    }
}
