<?php
/**
 * Layout de tela
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage layout
 */
class Ibe_View extends Ibe_Object {
    
    const APPLICATION = 0;
    const ACTION = 1;
    const CONTROLLER = 2;
    const MODULE = 3;
    const NONE = 4;
    
    private $view_application = NULL;
    private $view_module = NULL;
    private $view_controller = NULL;
    private $view_action = NULL;
    private $context = NULL;
    
    public function __construct(Ibe_Object $app,Ibe_Object $mod,Ibe_Object $ctr, Ibe_Object $act){
        $this->view_application = $app;
        $this->view_module      = $mod;
        $this->view_controller  = $ctr;
        $this->view_action      = $act;
        
        $this->context = Ibe_Context::getInstance();
        
    }
    
    /**
     * Mostra o layout na saida
     */
    public function show($type_show) {
        $app_path = "_modules".DS."inc_views.php";
        $mod_path = "_modules"
                    .DS.$this->context->getModule()
                    .DS."inc_views.php";
        $ctr_path = "_modules"
                    .DS.$this->context->getModule()
                    .DS.$this->context->getController()
                    .DS."inc_views.php";
        $act_path = "_modules"
                    .DS.$this->context->getModule()
                    .DS.$this->context->getController()
                    .DS."_views"
                    .DS."inc_".$this->context->getAction().".php";
        
        
        switch($type_show){
            case Ibe_View::APPLICATION:
                $cts = $this->view_action->__include($act_path);                
                $cts = $this->view_controller->__set("view_action",$cts)->__include($ctr_path);
                $cts = $this->view_module->__set("view_controller",$cts)->__include($mod_path);
                $cts = $this->view_application->__set("view_module",$cts)->__include($app_path);
                break;
                
            case Ibe_View::MODULE:
                $cts = $this->view_action->__include($act_path);                
                $cts = $this->view_controller->__set("view_action",$cts)->__include($ctr_path);
                $cts = $this->view_module->__set("view_controller",$cts)->__include($mod_path);
                break;
                
            case Ibe_View::CONTROLLER:
                $cts = $this->view_action->__include($act_path);                
                $cts = $this->view_controller->__set("view_action",$cts)->__include($ctr_path);
                break;
                
            case Ibe_View::ACTION:                
                $cts = $this->view_action->__include($act_path);
                break;
                
            case Ibe_View::NONE:
                $cts = "";
                break;
                
            default:
                $cts = "";
                break;
        }
        
        echo $cts;
       
    }

}
