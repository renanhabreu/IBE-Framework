<?php

class Model_Map {

    private $host;
    private $user;
    private $pass;
    private $schema;
    private $variable_types = array(
        "int" => "int",
        "text" => "string",
        "bool" => "bool",
        "date" => "int",
        "blob" => "int",
        "float" => "int",
        "double" => "int",
        "bit" => "int",
        "bigint" => "int",
        "tinyint" => "int",
        "longint" => "int",
        "varchar" => "string",
        "smallint" => "int",
        "datetime" => "int",
        "timestamp" => "int"
    );


    public function __construct($host,$user,$pass,$schema) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->schema = $schema;
    }

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function getPass() {
        return $this->pass;
    }

    public function setPass($pass) {
        $this->pass = $pass;
        return $this;
    }

    public function getSchema() {
        return $this->schema;
    }

    public function setSchema($schema) {
        $this->schema = $schema;
        return $this;
    }

    public function criar() {

        mysql_connect($this->host, $this->user, $this->pass);
        mysql_select_db($this->schema);

        $show_tables = 'SHOW TABLES';
        $table = array();
        $result = mysql_query($show_tables);
        if ($result) {
            while ($result && ($row = mysql_fetch_array($result))) {
                //Tabelas
                $table[$row[0]] = array();

                //Configuracao das colunas
                $result_show_table = mysql_query('DESC ' . $row[0]);
                while ( $result_show_table && ($row_tbl = mysql_fetch_array($result_show_table))) {
                    $table[$row[0]]['field'][$row_tbl['Field']] = $this->getType($row_tbl['Type']);
                }

                //Configurando chaves
                $result_show_keys = mysql_query('SHOW KEYS FROM ' . $row[0]);
                while ($result_show_keys && ($row_tbl = mysql_fetch_array($result_show_keys))) {
                    if ($row_tbl['Key_name'] == 'PRIMARY') {
                        $table[$row[0]]['primary_key'][] = $row_tbl['Column_name'];
                    } else {
                        $foreign_table = explode('_', $row_tbl['Column_name']);
                        $foreign_table[sizeof($foreign_table) - 1] = ""; 
                        $table[$row[0]]['foreign_key'][$row_tbl['Column_name']] = ucfirst(strtolower(implode('',$foreign_table)));
                    }
                }
            }
        }

        foreach ($table as $tbl_name => $tbl_confs) {
            $class = explode('_', $tbl_name);
            $name = '';
            foreach ($class as $n) {
                $name .= ucfirst(strtolower($n));
            }

            /// Comentario inicial
            $file_php = "<?php\n\n";
            $file_php .= "/**\n";
            $file_php .= " * Mapeamento Objeto Relacional \n *\n *\n *\n";

            //Configurando os metodos campos
            if (isset($tbl_confs['field'])) {
                foreach ($tbl_confs['field'] as $f_name => $f_type) {
                    $file_php .= " * @method " . ucfirst(strtolower($name)) . "Map set" . ucfirst(strtolower($f_name)) . "(\$value) metodo set\n";
                    $file_php .= " * @method " . ucfirst(strtolower($name)) . "Map get" . ucfirst(strtolower($f_name)) . "() metodo get\n";
                }
            }

            $file_php .= " * @date " . date('d/m/Y') . "\n";
            $file_php .= " * @autor Renan Henrique Abreu<renanhabreu@gmail.com>\n";
            $file_php .= " */\n";

            /// Criacao da classe
            $file_php .= "class " . ucfirst(strtolower($name)) . "Map extends Ibe_Map{\n\n";
            $file_php .= "\tprotected function configure(){\n";
            $file_php .= "\t\t\$this->table_name  = '" . $this->schema. "." . $tbl_name . "';\n";
            $file_php .= "\t\t\$this->primary_key = '" . $tbl_confs['primary_key'][0] . "';\n";

            /// Configurando chave estrangeira
            if (isset($tbl_confs['foreign_key'])) {
                $file_php .= "\t\t\$this->foreign_key = array(";
                $fks = array();
                foreach ($tbl_confs['foreign_key'] as $fk_name => $fk_table) {
                    $fks[] = "\n\t\t\t'" . $fk_name . "'=>'" . $fk_table . "'";
                }
                $file_php .= implode(',', $fks);
                $file_php .= "\n\t\t);\n";
            }

            //Configurando campos
            if (isset($tbl_confs['field'])) {
                $file_php .= "\t\t\$this->columns_conf = array(";
                $f = array();
                foreach ($tbl_confs['field'] as $f_name => $f_type) {
                    $f[] = "\n\t\t\t'" . $f_name . "'=>'" . $f_type . "'";
                }
                $file_php .= implode(',', $f);
                $file_php .= "\n\t\t);\n";
            }

            //Fechando funcao configure
            $file_php .= "\t}\n\n\n";

            //Fechando classe
            $file_php .='}';

            //Criando o arquivo
            $dir = '../../../application/_maps/';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($dir . 'inc_'.strtolower($name) . '.php', $file_php);
            Model_Application::show($dir . 'inc_'.strtolower($name) . '.php', 'MAPA');
        }

        mysql_close();
    }

    private function getType($type) {
        $t = explode('(', $type);
        return $this->variable_types[$t[0]];
    }

}

