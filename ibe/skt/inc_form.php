<?php

class Model_Form {

    private $host;
    private $user;
    private $pass;
    private $schema;
    private $variable_types = array(
        "int"       => "int",
        "text"      => "string",
        "bool"      => "bool",
        "date"      => "int",
        "blob"      => "int",
        "float"     => "int",
        "double"    => "int",
        "bigint"    => "int",
        "tinyint"   => "int",
        "longint"   => "int",
        "varchar"   => "string",
        "string"    => "string",
        "smallint"  => "int",
        "datetime"  => "int",
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
                while ($result_show_table && ($row_tbl = mysql_fetch_array($result_show_table))) {
                    $table[$row[0]]['field'][$row_tbl['Field']] = $this->getType($row_tbl['Type']);
                }

                //Configurando chaves
                $result_show_keys = mysql_query('SHOW KEYS FROM ' . $row[0]);
                while ($result_show_table && ($row_tbl = mysql_fetch_array($result_show_keys))) {
                    if ($row_tbl['Key_name'] == 'PRIMARY') {
                        $table[$row[0]]['primary_key'][] = $row_tbl['Column_name'];
                    } else {
                        $foreign_table = explode('_', $row_tbl['Column_name']);
                        $table[$row[0]]['foreign_key'][$row_tbl['Column_name']] = ucfirst(strtolower($foreign_table[0]));
                    }
                }
            }
        }

        foreach ($table as $tbl_name => $tbl_conf) {
            $table_name = $tbl_name;
            $clsss_name = implode('',array_map('ucfirst', explode('_',strtolower($tbl_name))));
            $tbl_name = str_replace('_', '', strtolower($tbl_name));
            $file_php = "<?php\n\n";
            $file_php .= "class " . $clsss_name . "Form extends Ibe_Form{\n\n";
            $file_php .= "\tprotected function configure(){\n";
            $file_php .= "\t\t\$this->controller  = '" . $tbl_name . "';\n";
            $file_php .= "\t\t\$this->action      = 'salvar';\n";
            $file_php .= "\t\t\$this->form_fields = array(\n";

            $field = array();
            //if(is_array($tbl)){
                foreach ($tbl_conf['field'] as $name => $type) {
                    $field[] = "\t\t\t'".$name."'=>Ibe_Form_Field::getInstance()\n"
                            . "\t\t\t\t\t->setType('" . $this->getType($type) . "')\n"
                            . "\t\t\t\t\t->setInput('text')\n"
                            . "\t\t\t\t\t->setName('" . $name . "')\n"
                            . "\t\t\t\t\t->setLabel('" . ucfirst(strtolower($name)) . "')";
                }
            //}
            $file_php .= implode(",\n", $field);
            $file_php .= "\n\t\t);\n";
            $file_php .= "\t\t\$this->configureSelects();\n";
            $file_php .= "\t}\n";

            $file_php .= $this->getSelects($table_name);

            $file_php .= "}";

            //Criando o arquivo
            $dir = '../../../application/_forms/';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($dir .'inc_'.strtolower($tbl_name) . '.php', $file_php);
            Model_Application::show($dir .'inc_'.strtolower($tbl_name) . '.php', 'FORMULARIO');
        }

        mysql_close();
    }

    private function getType($type) {
        $t = explode('(', $type);
        $retorno = isset ($this->variable_types[$t[0]])? $this->variable_types[$t[0]]:'int';
        return $retorno;
    }

    private function getSelects($tbl_name){
        $file = "\n\t/**\n";
        $file .= "\t * Criando Options dos campos selects que se referem a outra tabela: Fks\n";
        $file .= "\t */\n";
        $file .= "\tprotected  function configureSelects(){\n";
        $sql = "
         SELECT * FROM  information_schema.KEY_COLUMN_USAGE
         WHERE table_name = '{$tbl_name}'
            AND table_schema = '{$this->schema}'
            AND referenced_table_schema is not NULL
         ";


        $result = mysql_query($sql);
        if ($result) {
            while (($row = mysql_fetch_object($result))) {
                $table  = (string)$row->REFERENCED_TABLE_NAME;
                $column = (string)$row->COLUMN_NAME;

                $file .= "\t\t/**\n";
                $file .= "\t\t * Select para a tabela $table\n";
                $file .= "\t\t */\n";
                $file .= "\t\t\$objs = Ibe_Map::getTable('$table')->findAll();\n";
                $file .= "\t\t\$select = array('Selecione'=>'');\n";

                $file .= "\t\tforeach(\$objs as \$obj){\n";
                $file .= "\t\t\t\$select[\$obj->getId()] = \$obj->getId();\n";
                $file .= "\t\t}\n";
                $file .= "\t\t\$this->form_fields['$column']->setOptions(\$select);\n\n";
            }
        }

        $file .= "\t}\n";

        return $file;
    }

}