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
    const JSON = -999;
    
    private $view_application = NULL;
    private $view_module = NULL;
    private $view_controller = NULL;
    /**
     * @var Ibe_Action 
     */
    private $view_action = NULL;
    private $context = NULL;
    
    public function __construct(Ibe_Object $app = NULL,Ibe_Object $mod = NULL,Ibe_Object $ctr = NULL, Ibe_Action $act = NULL){
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
        
        $configure = Ibe_Load::configure();    
            
        if(!$configure->isActionReturnJson()){
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
                	$act_path = "_modules"
                	          .DS.$this->context->getModule()
                			  .DS.$this->context->getController()
                			  .DS."_views"
                			  .DS."inc_".rtrim(str_replace(' ','_', strtolower($type_show))).".php";
                    $cts = $this->view_action->__include($act_path);
                    break;
            }

            echo $cts;
        }else{
            
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');

            $value = new stdClass();
            if(!is_object($type_show) && isset($type_show) && $type_show != Ibe_View::JSON){
                $response = $type_show;
                $value->response = $response; 
            }else{
                $value->response = $this->view_action->getResponse();
                if(isset($value->response->error)){
                    $value->error = (bool)$value->response->error;
                    $value->messageError = (string)$value->response->messageError;
                    unset($value->response->error);
                    unset($value->response->messageError);
                }
            }
            echo json_encode($value, JSON_FORCE_OBJECT);
            exit();
        }
       
       
    }

    static public function showJson($value){
        
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        
        if(!is_object($value)){
            $response = $value;
            $value = new stdClass();
            $value->response = $response; 
        }
        echo json_encode($value, JSON_FORCE_OBJECT);
        exit();
    }
}
