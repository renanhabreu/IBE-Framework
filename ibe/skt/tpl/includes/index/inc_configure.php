<?php

/**
 * Esta configuracao sera sempre lida antes de ser executado a qualquer acao
 * deste modulo.
 * � utilizado para realizar includes de arquivos de terceiros,
 * Registrar vari�veis principalmente vari�veis de usu�rios e finalmente
 * registrar a autoriza��o dos usu�rios
 */
class Configure extends Ibe_Configure{

    public function registerInclude() {
    }

    public function registerVar() {
    }

    public function registerAuth() {
        // Ibe_User::getInstance()->allowAccess('index');
    }
}
