<?php

class Map extends Skt_Core_Maker {

    public function create(Skt_Core_Request $req) {
       $this->_directory->create($this->_dir_app.$this->_configure->base);
        $vars = $req->getParamsWithDefaults($this->_configure->database);
        $host = $vars['host'];
        $user = $vars['user'];
        $pass = $vars['pass'];
        $schema = $vars['schm'];
        @$conn = mysql_connect($host, $user, $pass);
        if($conn){
            $sch = mysql_select_db($schema);
            if(!$sch){                
                Skt_Core_Prompt::print_('not select schema', Skt_Core_Prompt::ERROR);
                return;
            };

            $show_tables = 'SHOW TABLES';
            $table = array();
            $result = mysql_query($show_tables);

            if ($result) {
                while (@$row = mysql_fetch_array($result)) {

                    //Tabelas
                    $table[$row[0]] = array();

                    Skt_Core_Prompt::print_("lendo tabela ". $row[0]);
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
                            $table[$row[0]]['foreign_key'][$row_tbl['Column_name']] = implode("",array_map("ucfirst",array_map("strtolower",$foreign_table)));// ucfirst(strtolower(implode('',$foreign_table)));
                        }
                    }
                }
            }else{
                Skt_Core_Prompt::print_(mysql_error(), Skt_Core_Prompt::ERROR);
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
                $file_php .= " * Mapeamento Objeto Relacional \n *\n";

                //Configurando os metodos campos
                if (isset($tbl_confs['field'])) {
                    foreach ($tbl_confs['field'] as $f_name => $f_type) {
                        $file_php .= " * @method " . ucfirst(strtolower($name)) . "Map set" . ucfirst(strtolower($f_name)) . "(\$value) metodo set\n";
                        $file_php .= " * @method " . ucfirst(strtolower($name)) . "Map get" . ucfirst(strtolower($f_name)) . "() metodo get\n";
                    }
                }

                $file_php .= " * @date " . date('d/m/Y') . "\n";
                $file_php .= " * @autor Renan Henrique Abreu<renanhabreu@gmail.com> SKT\n";
                $file_php .= " */\n";

                /// Criacao da classe
                $file_php .= "class " .$name. "Table extends Ibe_Map{\n\n";
                $file_php .= "\tprotected function configure(){\n";
                $file_php .= "\t\t\$this->table_name  = '" . $schema. "." . $tbl_name . "';\n";
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
                $dir = $this->_dir_app."_maps".DS."tables".DS;
                if(is_dir($dir)){
                    file_put_contents($dir . 'tbl_'.strtolower($name) . '.php', $file_php);
                    
                    $map = "_maps".DS.'inc_'.strtolower($name) . '.php';
                    if(!file_exists($this->_dir_app.$map)){
                        $include = "_maps".DS."tables".DS;
                        $file_php = "<?php\n\n";
                        $file_php .= "include_once('". $include.'tbl_'.strtolower($name) . '.php'."');\n\n\n";
                        $file_php .= "/**\n";
                        $file_php .= " * Classe de regras de negocio \n *\n";
                        $file_php .= " * @date " . date('d/m/Y') . "\n";
                        $file_php .= " * @autor Renan Henrique Abreu<renanhabreu@gmail.com> SKT\n";
                        $file_php .= " */\n";
                        $file_php .= "class " .$name. "Map extends " .$name. "Table {\n\n\n}";
                        file_put_contents($this->_dir_app.$map, $file_php);
                    }
                    
                    Skt_Core_Prompt::print_("map ". ucfirst(strtolower($name))." criado com sucesso");
                }else{
                    Skt_Core_Prompt::print_("application not exists", Skt_Core_Prompt::ERROR);
                }
            }
            mysql_close();
        }else{
            Skt_Core_Prompt::print_("not permition", Skt_Core_Prompt::ERROR);
        }
        
    }

    private function getType($type) {
        $t = explode('(', $type);
        return $this->_configure->type[$t[0]];
    }

}

