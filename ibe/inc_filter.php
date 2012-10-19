<?php

/**
 * Saoo sempre disparados antes da execucao de uma action, realizam sua tarefa 
 * e em seguida sao finalizados. Um classe de filtro precisa apenas de estar 
 * localida no pacote _filtres, extender a class Ibe_Filter e implementar o 
 * metodo execute 
 * @author Renan Abreu <renanhabreu@gmail.com>
 * @package ibe
 */
interface Ibe_Filter {

    /**
     * Acao do filtro 
     */
    public function execute();
}
