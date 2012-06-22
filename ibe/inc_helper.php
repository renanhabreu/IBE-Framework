<?php
/**
 * Funcionalidades passíveis de serem utilizados na camada de visão, 
 * estas porporcionam facilidades em tarefas repetitivas e que não 
 * demandam grandes lógicas. Uma class helper precisa estar no 
 * pacote _helpers, extender a class Ibe_Helper e implementar 
 * o método execute
 * 
 * @author Renan Abreu
 * @package ibe
 */
abstract class Ibe_Helper extends Ibe_Object {

    static private $instances = array();
    protected $params = array();
    
    /**
     * O metodo a ser executado quando o metodo "_" for chamado
     */
    abstract public function execute();

    /**
     * Instancia ou busca um objeto helper ja instanciado
     * @param string $name
     * @return Ibe_Helper 
     */
    static public function get($name) {

        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = Ibe_Load::helper($name);
        }

        return self::$instances[$name];
    }

    /**
     * Metodo magico que executa o helper
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed 
     */
    public function __call($name, $arguments) {

        if ($name == "_") {
            if (sizeof($this->params) > 0) {
                $i = 0;
                foreach ($this->params as $key => $val) {
                    if (isset($arguments[$i])) {
                        $this->params[$key] = $arguments[$i];
                    }
                    $i++;
                }
            }

            return $this->execute();
        }
    }

}
