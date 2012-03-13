<?php

/**
 * Classe de excecoes
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @final
 */
class Ibe_Exception extends Exception {
    /**
     * 'A mensagem de excecao nao foi localizada [<em>%s</em>]'
     */
    const SEM_MENSAGEM_DE_EXCECAO = 0;

    /**
     * 'O diretorio para as classes filhas de [<em> %s </em>] nao foi registrado no <em>%s</em>'
     */
    const DIRETORIO_CLASSE_FILHA = 1;

    /**
     * 'Diretorio [ %s ] de arquivos auxiliares herdeiras das classes funcionais nao existe.<br/>Utilize no array do parametro da funcao <em> Ibe_Autoload::directoryRegister</em> a configuracao: <b><em>"pasta"=>"classe_pai"</em></b>'
     */
    const DIRETORIO_AUXILIAR = 2;

    /**
     * 'Nao foi possivel realizar o load do arquivo [<em> %s </em>] porque ele nao foi encontrado'
     */
    const FALHA_EM_LOAD = 3;

    /**
     * 'Erro ao executar SQL. O seguinte erro foi encntrado <b> %s </b><br/>QUERY:<p> %s </p>'
     */
    const FALHA_DE_SQL = 4;

    /**
     *  'O Form [<em> %s </em>] nao existe na localizacao [<em> %s </em>]'
     */
    const FORM_SEM_CLASSE = 5;

    /**
     * 'Variavel $form_fields nao foi setada com as configuracoes da classe descritora de formulario [<em>%s</em>]'
     */
    const FORM_SEM_CONFIGURACAO = 6;

    /**
     * 'Variavel [<em> %s </em>] do layout herdeiro de <em>Ibe_Layout</em> nao foi definida'
     */
    const LAYOUT_VARIAVEL_INEXISTENTE = 7;

    /**
     * 'O nome da tabela não foi definido  no mapa <em> %s </em> '
     */
    const MAPA_TABELA_SEM_NOME = 8;

    /**
     * 'O nome da chave primaria não foi definido  no mapa <em> %s </em>'
     */
    const MAPA_SEM_PK = 9;

    /**
     * 'As colunas nao foram definidas no mapa <em> %s </em>'
     */
    const MAPA_SEM_COLUNA = 10;

    /**
     * 'O Map [<em> %s </em>] nao existe na pasta de mapas <em>%s</em>'
     */
    const MAPA_NAO_ENCONTRADO = 11;

    /**
     * 'O valor da chave primaria nao foi definida  no mapa <em> %s </em>'
     */
    const MAPA_VALOR_PK = 12;

    /**
     * 'Nenhuma chave estrangeira foi encontrada no arquivo de configuracao do mapa <em> %s </em>'
     */
    const MAPA_SEM_FK = 13;

    /**
     * 'A tabela [<em> %s </em>] nao possui o relacionamento com  <em>%s</em>'
     */
    const MAPA_SEM_RELACIONAMENTO = 14;

    /**
     *  'Arquivo de registro [<em> %s </em>] nao foi lido corretamente'
     */
    const ARQUIVO_REG = 15;

    /**
     * 'Nenhum mysql resource foi encontrado'
     */
    const DB_SEM_RESOURCE = 16;

    /**
     * 'A tabela nao foi definida para a clausura SQL [<em>%</em>] em <em>Ibe_Database_Query_[<em>Insert|Delete|Update</em>]</em>'
     */
    const SQL_TABELA_INDEFINIDA = 17;

    /**
     * 'Nenhum campo foi adicionado a clausura SQL [<em>%</em>] na utilizacao de <em>Ibe_Database_Query_Insert</em>'
     */
    const SQL_SEM_CAMPO = 18;

    /**
     * 'Clausura JOIN sem clausura WHERE em <em>Ibe_Database_Query_Join</em>'
     */
    const SQL_JOIN_SEM_WHERE = 19;

    /**
     * 'Tipo de campo [<em> %s </em>] invalido em <em>setType</em> da classe <em>Ibe_Form_Field</em>'
     */
    const FORM_TIPO = 20;

    /**
     * 'Tipo de input [<em> %s </em>] invalido na configuracao do formulario'
     */
    const FORM_INPUT = 21;

    /**
     * 'Atributos nao estao em um array. Exemplo: <em><b>array</b>("class"=>"css-class")</em>'
     */
    const FORM_ATRIBUTOS = 22;

    /**
     * 'A tela [<em> %s </em>] nao foi localizada em [<em> %s </em>]'
     */
    const LAYOUT_SEM_TELA = 23;

    /**
     * 'A tela nao possui um nome. Incapaz de realizar um load de uma tela sem nome. Use o metodo <em>setScreenName</em>(<b>$name</b>) para isto'
     */
    const LAYOUT_SEM_NOME = 24;

    /**
     * 'Objeto acao nao foi criado em Ibe_Request_Decode'
     */
    const REQUISICAO_ACAO_INEXISTENTE = 25;

    /**
     * 'A requisicao nao pode ser realizada verifique se a acao realmente esta disponivel [<em>%s</em>][<em>%s</em>][<em>%s</em>]'
     */
    const REQUISICAO_INVALIDA = 26;

    /**
     * 'A classe [<em>%s</em>] nao extende a classe [<em>%s</em>]'
     */
    const CLASSE_PAI_INVALIDA = 27;

    /**
     * 'Nao foi aberto uma conexao com o banco de dados MYSQL. Utilize a classe [<em>Ibe_Database::open(<b>$host</b>,<b>$user</b>,<b>$pass</b>,<b>$schema</b>)</em>]'
     */
    const ERRO_MYSQL = 28;

    /**
     * 'A acao deve retornar um dos resultados [<b><em>Ibe_Layout::ACTION</em>, <em>Ibe_Layout::CONTROLLER</em>, <em>Ibe_Layout::APPLICATION</em>, <em>Ibe_Layout::NONE</em></b>]'
     */
    const LAYOUT_INDEFINIDO = 29;

    /**
     * 'O metodo [ <b><em>$s</em> ] nao esta disponivel para este objeto'
     */
    const CLASSE_SEM_METODO = 30;

    /**
     * 'O diretorio [ <b><em>$s</em></b> ] nao existe'
     */
    const DIRETORIO_NAO_EXISTE = 31;

    /**
     * 'A variavel [<b><em>%s</em></b>] nao pode ser sobrecrita com o valor [<b><em>%s</em></b>]'
     */
    const VARIAVEL_NAO_SOBRESCRITA = 32;

    /**
     * 'O modulo [<b><em>%s</em></b>] nao foi encontrado'
     */
    const MODULO_NAO_ENCONTRADO = 33;

    /**
     * 'O controlador [<b><em>%s</em></b>] nao foi encontrado'
     */
    const CONTROLADOR_NAO_ENCONTRADO = 34;

    /**
     * 'A acao [<b><em>%s</em></b>] nao foi encontrada'
     */
    const ACAO_NAO_ENCONTRADO = 35;

    /**
     * 'Voce nao tem permissao de acesso a area requisitada. Contate o administrador !'
     */
    const PERMISSAO_DE_ACESSO_NEGADA = 36;

    /**
     * 'A classe [<em><b>%s</b></em>] nao foi encontrada em [<em>%s</em>]'
     */
    const CLASSE_NAO_ENCONTRADA = 37;

    /**
     * 'Erro de tipagem de variavel! A variavel deve se do tipo [<em><b>%s</b></em>]'
     */
    const TIPO_INVALIDO_DE_VARIAVEL= 38;


    /**
     * Constroi uma Excecao IBE
     * @param int|string $mes_index
     * @param array $params
     * @param int $code
     */
    public function __construct($mes_index = self::SEM_MENSAGEM_DE_EXCECAO, $params = array(), $code = NULL) {

        $message = (is_int($mes_index)) ? $this->get($mes_index,$params) : $mes_index;
        $code = (is_int($mes_index)) ? $mes_index:$code ;

        parent::__construct($message, $code);
        Ibe_Log::save('request_error', $message);
    }

       /**
     * Envia um alerta de erro inesperado ao usuario
     */
    public function alert() {

        echo '<pre style="padding-left:10px; font-size:12px">';
        echo "<div style='margin:20px; border:1px solid black;'>";
        echo "<div style='background:black; padding:5px;color:#fff;font-size:14px'>";
        echo "::: ERRO INESPERADO :::";
        echo "</div>";
        echo '<div style="padding:10px">';
        if (Ibe_Debug::isEnable()) {
            echo "<h4>FILE:" . $this->getFile() . "</h4>";
            echo "<h4>LINE:" . $this->getLine() . "</h4>";
            echo $this->getMessage();
        } else {
            echo 'Desculpe-nos o transtorno.<br/>';
            echo 'Tente mais tarde.<br/>';
            echo 'Verifique sua permissao de acesso.<br/>';
            echo 'Se o problema persistir contate o administrador do sistema.<br />';
            echo 'Dados da requisicao gravados em '.date('d/m/Y H:i:s');
        }
        echo '</div>';
        echo '</div>';
        echo '</pre>';

        if (!Ibe_Debug::isEnable()) {
            exit();
        }
    }

    /**
     * Inicia o array de mensagens de erro
     */
    private function get($index, $params) {
        $msgs[self::SEM_MENSAGEM_DE_EXCECAO] = 'A mensagem de excecao nao foi localizada [<em>%s</em>]';
        $msgs[self::DIRETORIO_CLASSE_FILHA] = 'O diretorio para as classes filhas de [<em> %s </em>] nao foi registrado no <em>%s</em>';
        $msgs[self::DIRETORIO_AUXILIAR] = 'Diretorio [ %s ] de arquivos auxiliares herdeiras das classes funcionais nao existe.<br/>Utilize no array do parametro da funcao <em> Ibe_Autoload::directoryRegister</em> a configuracao: <b><em>"pasta"=>"classe_pai"</em></b>';
        $msgs[self::FALHA_EM_LOAD] = 'Nao foi possivel realizar o load do arquivo [<em> %s </em>] porque ele nao foi encontrado';
        $msgs[self::FALHA_DE_SQL] = 'Erro ao executar SQL. O seguinte erro foi encntrado <b> %s </b><br/>QUERY:<p> %s </p>';
        $msgs[self::FORM_SEM_CLASSE] = 'O Form [<em> %s </em>] nao existe na localizacao [<em> %s </em>]';
        $msgs[self::FORM_SEM_CONFIGURACAO] = 'Variavel $form_fields nao foi setada com as configuracoes da classe descritora de formulario [<em>%s</em>]';
        $msgs[self::LAYOUT_VARIAVEL_INEXISTENTE] = 'Variavel [<em> %s </em>] do layout herdeiro de <em>Ibe_Layout</em> nao foi definida';
        $msgs[self::MAPA_TABELA_SEM_NOME] = 'O nome da tabela não foi definido  no mapa <em> %s </em> ';
        $msgs[self::MAPA_SEM_PK] = 'O nome da chave primaria não foi definido  no mapa <em> %s </em>';
        $msgs[self::MAPA_SEM_COLUNA] = 'As colunas nao foram definidas no mapa <em> %s </em>';
        $msgs[self::MAPA_NAO_ENCONTRADO] = 'O Map [<em> %s </em>] nao existe na pasta de mapas <em>%s</em>';
        $msgs[self::MAPA_VALOR_PK] = 'O valor da chave primaria nao foi definida  no mapa <em> %s </em>';
        $msgs[self::MAPA_SEM_FK] = 'Nenhuma chave estrangeira foi encontrada no arquivo de configuracao do mapa <em> %s </em>';
        $msgs[self::MAPA_SEM_RELACIONAMENTO] = 'A tabela [<em> %s </em>] nao possui o relacionamento com  <em>%s</em>';
        $msgs[self::ARQUIVO_REG] = 'Arquivo de registro [<em> %s </em>] nao foi lido corretamente';
        $msgs[self::DB_SEM_RESOURCE] = 'Nenhum mysql resource foi encontrado';
        $msgs[self::SQL_TABELA_INDEFINIDA] = 'A tabela nao foi definida para a clausura SQL [<em>%</em>] em <em>Ibe_Database_Query_[<em>Insert|Delete|Update</em>]</em>';
        $msgs[self::SQL_SEM_CAMPO] = 'Nenhum campo foi adicionado a clausura SQL [<em>%</em>] na utilizacao de <em>Ibe_Database_Query_Insert</em>';
        $msgs[self::SQL_JOIN_SEM_WHERE] = 'Clausura JOIN sem clausura WHERE em <em>Ibe_Database_Query_Join</em>';
        $msgs[self::FORM_TIPO] = 'Tipo de campo [<em> %s </em>] invalido em <em>setType</em> da classe <em>Ibe_Form_Field</em>';
        $msgs[self::FORM_INPUT] = 'Tipo de input [<em> %s </em>] invalido na configuracao do formulario';
        $msgs[self::FORM_ATRIBUTOS] = 'Atributos nao estao em um array. Exemplo: <em><b>array</b>("class"=>"css-class")</em>';
        $msgs[self::LAYOUT_SEM_TELA] = 'A tela [<em> %s </em>] nao foi localizada em [<em> %s </em>]';
        $msgs[self::LAYOUT_SEM_NOME] = 'A tela nao possui um nome. Incapaz de realizar um load de uma tela sem nome. Use o metodo <em>setScreenName</em>(<b>$name</b>) para isto';
        $msgs[self::REQUISICAO_ACAO_INEXISTENTE] = 'Objeto acao nao foi criado em Ibe_Request_Decode';
        $msgs[self::REQUISICAO_INVALIDA] = 'A requisicao nao pode ser realizada verifique se a acao realmente esta disponivel [<em>%s</em>][<em>%s</em>][<em>%s</em>]';
        $msgs[self::CLASSE_PAI_INVALIDA] = 'A classe [<em>%s</em>] nao extende a classe [<em>%s</em>]';
        $msgs[self::ERRO_MYSQL] = 'Nao foi aberto uma conexao com o banco de dados MYSQL. Utilize a classe [<em>Ibe_Database::open(<b>$host</b>,<b>$user</b>,<b>$pass</b>,<b>$schema</b>)</em>]';
        $msgs[self::LAYOUT_INDEFINIDO] = 'A acao deve retornar um dos resultados [<b><em>Ibe_Layout::ACTION</em>, <em>Ibe_Layout::CONTROLLER</em>, <em>Ibe_Layout::APPLICATION</em>, <em>Ibe_Layout::NONE</em></b>]';
        $msgs[self::CLASSE_SEM_METODO] = 'O metodo [ <b><em>$s</em> ] nao esta disponivel para este objeto';
        $msgs[self::DIRETORIO_NAO_EXISTE] = 'O diretorio [ <b><em>$s</em></b> ] nao existe';
        $msgs[self::VARIAVEL_NAO_SOBRESCRITA] = 'A variavel [<b><em>%s</em></b>] nao pode ser sobrecrita com o valor [<b><em>%s</em></b>]';
        $msgs[self::MODULO_NAO_ENCONTRADO] = 'O modulo [<b><em>%s</em></b>] nao foi encontrado';
        $msgs[self::CONTROLADOR_NAO_ENCONTRADO] = 'O controlador [<b><em>%s</em></b>] nao foi encontrado';
        $msgs[self::ACAO_NAO_ENCONTRADO] = 'A acao [<b><em>%s</em></b>] nao foi encontrada';
        $msgs[self::PERMISSAO_DE_ACESSO_NEGADA] = 'Voce nao tem permissao de acesso a area requisitada. Contate o administrador !';
        $msgs[self::CLASSE_NAO_ENCONTRADA] = 'A classe [<em><b>%s</b></em>] nao foi encontrada em [<em>%s</em>]';
        $msgs[self::TIPO_INVALIDO_DE_VARIAVEL] = 'Erro de tipagem de variavel! A variavel deve se do tipo [<em><b>%s</b></em>]';

        if (is_null($msgs[$index])) {
            throw new Ibe_Exception(Ibe_Exception::SEM_MENSAGEM_DE_EXCECAO);
        }

        $params = !is_array($params) ? array($params) : $params;
        $frase = array_merge(array($msgs[$index]), $params);
        $message = call_user_func_array('sprintf', $frase);
        return $message;
    }

}

