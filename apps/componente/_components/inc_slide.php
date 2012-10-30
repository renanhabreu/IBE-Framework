<?php
/**
 * Componente
 * 
 * @method Ibe_Component_Element_Slide setXXXXX() setXXXXX(string $name)
 * @autor Auto-create by SKT
 */
class SlideComponent extends Ibe_Component_Interface {

	protected function printMe() {
		// echo componente
		echo '<div id="slides"';
		$this->printConfiguration();
		echo '>';
		echo '<p>slides</p>';
		echo '</div>';

	}
	/* (non-PHPdoc)
	 * @see Ibe_Component_Interface::configureMe()
	 */protected function configureMe() {
		// TODO Auto-generated method stub
	}

}
