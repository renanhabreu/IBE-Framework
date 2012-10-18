<?php
/**
 * Extentions
 * @autor Auto-create by SKT
 */
class Ext_A extends Ext_Component {
	protected $label = 'link';
	
	/**
	 * @param string $label
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}

	protected function _print(){
		echo '<a ';
		echo '  href="#" ';
		$this->printConfiguration();
		echo ' >';
		echo $this->label;
		echo '</a> ';
	}

}
