<?php

/**
 * Classe de template para Viwes
 * @author renan.abreu
 */
final class Ibe_Template extends Ibe_Object{
	const LINK = 'link';
	const SCRIPT = 'script';
	const ICON = 'ICON';
	private $type = 0;
	private $js_path = NULL;
	private $css_path = NULL;
	private $img_path = NULL;
	
	
	public function __construct($type = Ibe_View::APPLICATION){
		$rsc = '_rsc';		
		$this->js_path = $rsc'/js/';  
		$this->css_path = $rsc.'/css/';
		$this->img_path = $rsc.'/img/';      

		$this->type = $type;
	}

	/**
	 * Este metodo identifica o nome passado como parametro
	 * e realiza a inclusao da tag necessaria para o html.
	 * O arquivo identificado pelo parametro $filename deve
	 * estar localizado nas sub-pastas de _rsc
	 * A identificacao eh realizada pela extensao do arquivo
	 * passado como parametro à funcao.
	 * 
	 * @param string $filename
	 * @param string $type
	 * @throws Ibe_Exception_Template
	 */
	protected function includeScript($filename,$type = Ibe_Template::LINK){
		$url_base = str_replace('index.php','',Ibe_Context::getInstance()->getUrlBase());
                $url_base = explode('?',$url_base);
		$url_base = $url_base[0];
                
		if($filename){			
			$file = explode('.',$filename);
			$type = $file[sizeof($file) - 1]; 
			
			if($type == 'js' || ($type != 'css' && $type == Ibe_Template::SCRIPT)){
				$path = $this->js_path.$filename;
				if(file_exists($path)){
					echo '<script src="'.$url_base.$path.'"></script>';
				}else{
					throw new Ibe_Exception_Template('Script ['.$filename.'] tipo JS nao encontrado em repositorio');
				}
			}else if($type == 'css'||  $type == Ibe_Template::LINK || $type == Ibe_Template::ICON){			
				$path =  $this->css_path.$filename;
				if(file_exists($path)){
					$rel = ($type == Ibe_Template::ICON)? 'icon':'stylesheet';
					$t = ($type == Ibe_Template::ICON)? 'type="text/css"':'';
					
					echo '<link '.$t.' rel="'.$rel.'" href="'.$url_base.$path.'" />';
				}else{
					echo $path;
					throw new Ibe_Exception_Template('Script ['.$filename.'] tipo CSS  nao encontrado em repositorio');
				}
			}else{
				
			}
			
		}		
	}
	
	
	/**
	 * Realiza a inclusao no template mestre da proxima
	 * camada a ser incluida no arquivo de template.
	 * app->mod->contr->action
	 * 
	 * @throws Ibe_Exception_Template
	 */
	protected function includeNextLayer(){
		$nextLayer = NULL;
		
		switch ($this->type) {
			case Ibe_View::APPLICATION:
				$nextLayer = $this->view_module;
				break;
			case Ibe_View::MODULE:
				$nextLayer = $this->view_controller;
				break;
			case Ibe_View::CONTROLLER:
				$nextLayer = $this->view_action;
				break;			
			default:
				throw new Ibe_Exception_Template('O template de ACTION nao possui uma proxima camada');
			break;
		}
		
		echo $nextLayer;
	}
	
	/**
	 * Inclui uma view particular para o contexto especifico.
	 * dentro da pasta de _modules todas as suas subpastas _views
	 * sao repositorios de views particulares
	 * 
	 * @param string $name
	 * @throws Ibe_Exception_Template
	 */
	protected function includeView($name){
		$view_include = NULL;
		$name = strtolower($name);
		$context = Ibe_Context::getInstance();
		
		$app_path = "_modules"
				.DS."_views"
				.DS."_".$name.".php";;
		$mod_path = "_modules"
				.DS.$context->getModule()
				.DS."_views"
				.DS."_".$name.".php";
		$ctr_path = "_modules"
				.DS.$context->getModule()
				.DS.$context->getController()
				.DS."_views"
				.DS."_".$name.".php";
		$act_path = "_modules"
				.DS.$context->getModule()
				.DS.$context->getController()
				.DS."_views"
				.DS."_".$name.".php";
		
		
		switch ($this->type) {
			case Ibe_View::APPLICATION:
				$view_include = $this->__include($app_path);
				break;
			case Ibe_View::MODULE:
				$view_include = $this->__include($mod_path);
				break;
			case Ibe_View::CONTROLLER:
				$view_include = $this->__include($ctr_path);
				break;	
			case Ibe_View::ACTION:
				$view_include = $this->__include($act_path);
				break;			
			default:
				throw new Ibe_Exception_Template('A view '.$name.' nao foi encontrada no repositorio _views.');
			break;
		}
		
		echo $view_include;
	}
}