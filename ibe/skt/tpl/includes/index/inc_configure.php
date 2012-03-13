<?php

/**
 * Esta configuracao sera sempre lida antes de ser executado a qualquer acao
 * deste modulo.
 * É utilizado para realizar includes de arquivos de terceiros,
 * Registrar variáveis principalmente variáveis de usuários e finalmente
 * registrar a autorização dos usuários
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
