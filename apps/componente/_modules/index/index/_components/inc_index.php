<?php

/**
 * Configuracao de componente
 */
class IndexComponent extends Ibe_Component {

	public function configure() {
		/* @var $banner Ibe_Component_Element_Slide */
		$banner = $this->registerComponente('slide','banner',$this->application);		
	}

}
