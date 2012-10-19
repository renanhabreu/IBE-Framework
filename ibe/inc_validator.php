<?php
/**
 * Validadores para o metodo getParam de Ibe_Request.
 * Estes validadores devem exibir excecoes caso
 * a validacao nao ocorrer
 * 
 * @author Renan Abreu <renanhabreu@gmail.com>
 * @package ibe
 *
 */
abstract class Ibe_Validator{
    
    protected $message = 'Campo invalido';
    
    public function getMessage(){  return $this->message; }
    
    /**
     * Valor passado via GET ou POST
     * @param mixed $value
     */
    abstract public function isValid($value);
        
}