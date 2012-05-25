<?php


abstract class Skt_Core_Maker{
    protected $_configure = NULL;
    protected $_directory = NULL;
    protected $_file = NULL;
    protected $_dir_app = NULL;
    protected $_dir_mod = NULL;
    protected $_dir_ctr = NULL;
    protected $_dir_act = NULL;
    
    public function __construct(Skt_Core_Directory $_directory,Skt_Core_File $_file ){
        $this->_directory = $_directory;
        $this->_file = $_file;
    }
    
    private function dispatch(Skt_Core_Request $req){        
        $this->_configure = $this->_file->include_configure(strtolower($req->getMaker()));
        
        
        $this->_app = $req->getParam("app","application");         
        $this->_mod = $req->getParam("mod","module");        
        $this->_ctr = $req->getParam("ctr","controller");         
        $this->_act = $req->getParam("act","action"); 
        
        $this->_dir_app = $this->_directory->dirApp($this->_app);         
        $this->_dir_mod = $this->_directory->dirMod($this->_app,$this->_mod);        
        $this->_dir_ctr = $this->_directory->dirCtr($this->_app,$this->_mod,$this->_ctr);         
        $this->_dir_act = $this->_directory->dirAct($this->_app,$this->_mod,$this->_ctr,$this->_act); 
        
        $this->create($req);
    }
    
        
    static public function run($argv){
        $req = new Skt_Core_Request($argv);
        
        if($req->getMaker()){
            $mk_class = implode("_",array_map("ucfirst",explode("_",strtolower($req->getMaker()))));
            
            $_directory = new Skt_Core_Directory();
            $_file = new Skt_Core_File($req->getMaker());
            
            $maker = new $mk_class($_directory,$_file);
            $maker->dispatch($req);
            
            Skt_Core_Prompt::responseIfHttp();
        }

    }
    
    
    abstract public function create(Skt_Core_Request $req);
}
