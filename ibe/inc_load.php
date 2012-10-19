<?php
/**
 * Realiza o load dos componentes de nucleo 
 * do framework. Deve ser modificado apenas quando 
 * uma nova funcionalidade do nucleo for criada
 * @author Renan Abreu <renanhabreu@gmail.com>
 * @package ibe
 */
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
        	$core_action = IBE_FRAMEWORK_PATH . 'default'. DS . $actionPath;
        	//Ibe_Debug::error($core_action);
        	if(!file_exists($core_action)){
            	throw new Ibe_Exception_Load("Acao requisitada nao existe");
        	}else{
        		include_once($core_action);
        	}
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
    
    private static $instance_plugin = array();
    static public function plugin($name){
        $name = strtolower($name);
        
        if(!isset(self::$instance_plugin[$name])){
            $pluginName = ucfirst($name)."Plugin";
            $path = "_plugins".DS."inc_".$name.".php";

            if(!file_exists($path)){
                throw new Ibe_Exception_Load("O plugin ".$name." nao foi encontrado");
            }else{
                include_once($path);
            }


            $clsPlugin = new ReflectionClass($pluginName);
            self::$instance_plugin[$name] = $clsPlugin->newInstance();
        }
        
        return self::$instance_plugin[$name];
    }
        
    private static $instance_filter = array();
    static public function filter($name){
        $name = strtolower($name);
        
        if(!isset(self::$instance_filter[$name])){
            $filterName = ucfirst($name)."Filter";
            $path = "_filters".DS."inc_".$name.".php";
            
            if(!file_exists($path)){
                if($name != Ibe_Context::getInstance()->getAction()){
                    throw new Ibe_Exception_Load("O filtro ".$name." nao foi encontrado");
                }
                self::$instance_filter[$name] = NULL;
            }else{
                include_once($path);
                $clsFilter= new ReflectionClass($filterName);
                self::$instance_filter[$name] = $clsFilter->newInstance();
            }
        }
        
        return self::$instance_filter[$name];
    }
    
    
    private static $instance_validator = array();
    static public function validator($name){
        $name = strtolower($name);
        if(!isset(self::$instance_validator[$name])){
            $helperName = ucfirst($name)."Validator";
            $path = "_validators".DS."inc_".$name.".php";

            if(!file_exists($path)){
                return FALSE;
            }else{
                include_once($path);
            }

            $clsHelper= new ReflectionClass($helperName);
            self::$instance_validator[$name] = $clsHelper->newInstance();
        }
        
        return self::$instance_validator[$name];
    }   
    
    private static $instance_helper = array();
    static public function helper($name){
        $name = strtolower($name);
        if(!isset(self::$instance_helper[$name])){
            $helperName = ucfirst($name)."Helper";
            $path = "_helpers".DS."inc_".$name.".php";

            if(!file_exists($path)){
                throw new Ibe_Exception_Load("O helper ".$name." nao foi encontrado");
            }else{
                include_once($path);
            }

            $clsHelper= new ReflectionClass($helperName);
            self::$instance_helper[$name] = $clsHelper->newInstance();
        }
        
        return self::$instance_helper[$name];
    }
    
    private static $instance_configue = NULL;
    static public function configure(){
        
        if(!isset(self::$instance_configue)){
            $configureName = "Configure";
            $path = "_modules".DS."inc_configure.php";

            if(file_exists($path)){
                include_once($path);
                $cls= new ReflectionClass($configureName);
                self::$instance_configue = $cls->newInstance();
            }
        }
        
        return self::$instance_configue;
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
