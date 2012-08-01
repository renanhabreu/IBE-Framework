<?php

abstract class Ibe_Validator{
    
    protected $message = 'Campo invalido';
    
    public function getMessage(){  return $this->message; }
    
    abstract public function isValid($value);
        
}