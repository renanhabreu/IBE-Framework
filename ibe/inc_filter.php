<?php

/**
 * São sempre disparados antes da execução de uma action, realizam sua tarefa 
 * e em seguida são finalizados. Um classe de filtro precisa apenas de estar 
 * localida no pacote _filtres, extender a class Ibe_Filter e implementar o 
 * método execute 
 */
interface Ibe_Filter {

    /**
     * Acao do filtro 
     */
    public function execute();
}
