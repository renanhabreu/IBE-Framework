<?php

final class Ibe_Load{
    
    static public function action(){
        $context = Ibe_Context::getInstance();
        $actionName = strtolower($context->getAction()).'Action';
        $actionPath = '_modules'.DS.$context->getModule()
                                .DS.$context->getController()
                                .DS.'_actions'
                                .DS.'inc_'.$context->getAction().'.php';
        //Ibe_Debug::dispatchAlert(__FILE__,$actionPath);
        if(!file_exists($actionPath)){
            throw new Ibe_Exception_Load("Acao requisitada nao existe");
        }else{
            include_once($actionPath);
        }
        
        $objAction = NULL;
        //Instanciando um objeto Action
        $clsAction = new ReflectionClass($actionName);
        if ($clsAction->isSubclassOf('Ibe_Action')) {
            //instancia um novo controlador
            $objAction = $clsAction->newInstanceArgs();
        } else {
            $param =  array($actionName, 'Ibe_Action');
            throw new Ibe_Exception_Load(Ibe_Exception::CLASSE_PAI_INVALIDA,$param);
        }
        
        return $objAction;
    }
    
    static public function plugin($name){
        $name = strtolower($name);
        $pluginName = ucfirst($name)."Plugin";
        $path = "_plugins".DS."inc_".$name.".php";
        
        if(!file_exists($path)){
            throw new Ibe_Exception_Load("O plugin ".$name." nao foi encontrado");
        }else{
            include_once($path);
        }
        
        
       $clsPlugin = new ReflectionClass($pluginName);
       return $clsPlugin->newInstance();
    }
    
    static public function filter($name){
        $name = strtolower($name);
        $filterName = ucfirst($name)."Filter";
        $path = "_filters".DS."inc_".$name.".php";
        
        if(!file_exists($path)){
            throw new Ibe_Exception_Load("O filtro ".$name." nao foi encontrado");
        }else{
            include_once($path);
        }
        
       $clsFilter= new ReflectionClass($filterName);
       return $clsFilter->newInstance();
    }
    
    static public function helper($name){
        $name = strtolower($name);
        $helperName = ucfirst($name)."Helper";
        $path = "_helpers".DS."inc_".$name.".php";
        
        if(!file_exists($path)){
            throw new Ibe_Exception_Load("O helper ".$name." nao foi encontrado");
        }else{
            include_once($path);
        }
        
       $clsHelper= new ReflectionClass($helperName);
       return $clsHelper->newInstance();
    }
    
    static public function configure(){
        
        $configureName = "Configure";
        $path = "_modules".DS."inc_configure.php";
        
        if(file_exists($path)){
           include_once($path);
           $cls= new ReflectionClass($configureName);
           return $cls->newInstance();
        }
        
        return NULL;
    }
    
    static public function map($name){
        $filename = '_maps/'.'inc_' . str_replace("_","",strtolower($name)) . '.php';
        $model_class_name = implode("",array_map("ucfirst",explode("_",strtolower($name)))) . 'Map';
    
        
        if(!file_exists($filename)){
            throw new Ibe_Exception_Load("O mapa ".$model_class_name." nao foi encontrado");
        }
        
        include_once($filename);
        
        $reflection = NULL;
        if (class_exists($model_class_name)) {
            $reflection = new ReflectionClass($model_class_name);
        } else {
            throw new Ibe_Exception_Load("O mapa ".$model_class_name." nao foi encontrado");
        }
        
        return $reflection->newInstance();
    }
}

?>
