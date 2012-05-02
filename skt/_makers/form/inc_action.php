<?php


class Form extends Skt_Core_Maker{

    public function create(Skt_Core_Request $req){
        $vars = $req->getParamsWithDefaults($this->_configure->database);
        $host = $vars['host'];
        $user = $vars['user'];
        $pass = $vars['pass'];
        $schema = $vars['schm'];
        
        mysql_connect($host, $user, $pass);
        mysql_select_db($schema);

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
            $file_php .= "class " . $clsss_name . "Form extends Ext_Form{\n\n";
            $file_php .= "\tprotected function configure(){\n";
            $file_php .= "\t\t\$this->url  = '#';\n";
            $file_php .= "\t\t\$this->form_fields = array(\n";

            $field = array();
            //if(is_array($tbl)){
                foreach ($tbl_conf['field'] as $name => $type) {
                    
                    $t = ($name ==  'id')? 'hidden':'text';
                    
                    $field[] = "\t\t\t'".$name."'=>Ext_Form_Field::getInstance()\n"
                            . "\t\t\t\t\t->setType('" . $this->getType($type) . "')\n"
                            . "\t\t\t\t\t->setInput('{$t}')\n"
                            . "\t\t\t\t\t->setName('" . $name . "')\n"
                            . "\t\t\t\t\t->setLabel('" . ucfirst(strtolower($name)) . "')\n"
                            . "\t\t\t\t\t->setValidation(array(
						Ext_Form_Field::IS_EMPTY
					))";
                }
            //}
            $file_php .= implode(",\n", $field);
            $file_php .= "\n\t\t);\n";
            $file_php .= "\t\t\$this->configureSelects();\n";
            $file_php .= "\t}\n";

            $file_php .= $this->getSelects($table_name,$schema);

            $file_php .= "}";

            if (!is_dir($this->_dir_app.'_forms/')) {
                mkdir($this->_dir_app.'_forms/', 0777, true);
            }
            file_put_contents($this->_dir_app.'_forms/'.'inc_'.strtolower($tbl_name) . '.php', $file_php);          
            Skt_Core_Prompt::print_("formulario  ". ucfirst(strtolower($tbl_name))." criado com sucesso");
        }

        mysql_close();
    }

    private function getType($type) {
        $t = explode('(', $type);
        $retorno = isset ($this->_configure->types[$t[0]])? $this->_configure->types[$t[0]]:'int';
        return $retorno;
    }

    private function getSelects($tbl_name,$schm){
        $file = "\n\t/**\n";
        $file .= "\t * Criando Options dos campos selects que se referem a outra tabela: Fks\n";
        $file .= "\t */\n";
        $file .= "\tprotected  function configureSelects(){\n";
        $sql = "
         SELECT * FROM  information_schema.KEY_COLUMN_USAGE
         WHERE table_name = '{$tbl_name}'
            AND table_schema = '{$schm}'
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
