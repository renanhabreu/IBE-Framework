<?php

/**
 * O pacote mapa é o único pacote que o framework da o suporte nativamente. 
 * Ele é a parte ORM das aplicações, porém, o desenvolvedor não precisa se 
 * limitar a tal pacote. As classes aqui encontradas podem são criadas 
 * automaticamente pelo Fabricante de códigos map
 * 
 * @author Renan Abreu
 * @package ibe
 */
abstract class Ibe_Map {

    /**
     * Nome da tabela no banco de dados
     * @var string
     */
    protected $table_name = null;
    /**
     * Nome da chave primaria
     * @var string
     */
    protected $primary_key = null;
    /**
     * Nome da chave estrangeira
     * @var string
     */
    protected $foreign_key = null;
    /**
     * Colunas da tabela
     * @var array
     */
    protected $columns = array();
    /**
     * Configuracao das colunas do banco de dados nome_da_col=>tipo
     * @var array
     */
    protected $columns_conf = array();
    /**
     * Chave primaria auto incremental
     * @var boolean
     */
    protected $primary_key_auto_increment = true;
    /**
     * Verifica se a tabela tem muitas tabelas filhas. utilizada para a busca
     * com o metodo getChild
     * @var boolean
     */
    protected $has_many_table = false;
    /**
     * Valores da colunas do registro
     * @var array
     */
    private $columns_val = array();

    public function __construct() {
        $this->configure();
        if (!isset($this->table_name)) {
            throw new Ibe_Exception_Map(Ibe_Exception::MAPA_TABELA_SEM_NOME);
        }

        if (!isset($this->primary_key) && !$this->has_many_table) {
            throw new Ibe_Exception_Map(Ibe_Exception::MAPA_SEM_PK);
        }

        if (!sizeof($this->columns_conf)) {
            throw new Ibe_Exception_Map(Ibe_Exception::MAPA_SEM_COLUNA);
        } else {
            $this->columns = array_keys($this->columns_conf);
        }
    }

    /**
     * Metodo obrigatorio para configuracao de modelos
     */
    abstract protected function configure();

    /**
     * Retorna uma instancia de um modelo
     * @param type $model_class_name
     * @return Ibe_Map
     */
    static public function getTable($model_class_name) {
        return Ibe_Load::map($model_class_name);
    }

    /**
     * Captura o nome da tabela representada pelo model
     * @return string
     */
    public function getTableName() {
        return $this->table_name;
    }

    /**
     * Captura o nome da chave primaria
     * @return string
     */
    public function getPrimaryKey() {
        return $this->primary_key;
    }

    /**
     * Retorna o array de chaves estrangeiras conforme a configuracao do model
     * @return array
     */
    public function getForeignKey() {
        return $this->foreign_key;
    }

    /**
     * Retorna um array de colunas  conforme a configuracao do model
     * @return array
     */
    public function getColumns() {
        return $this->columns;
    }

    /**
     * Retorna um array de colunas  conforme a configuracao do model
     * @return array
     */
    public function getColumnsConf() {
        return $this->columns_conf;
    }

    /**
     * Verifica se a tabela eh de muitos pra muitos conforme a configuracao do model
     * @return bool
     */
    public function isHasManyTable() {
        return $this->has_many_table;
    }

    /**
     * Verifica se a chave primaria eh verdadeira conforme a configuracao do model
     * @return bool
     */
    public function isPrimaryKeyAutoIncrement() {
        return $this->primary_key_auto_increment;
    }

    /**
     * Salva o objeto
     * @return Model
     */
    public function save() {
        $this->preSave();
        //$colunas = '';

        //$cols = array();


        /**
         * Ibe_Database_Query
         */
        $query = null;
        $insert = false;

        if (!isset($this->columns_val[$this->primary_key]) || empty ($this->columns_val[$this->primary_key])) {
            $query = Ibe_Database_Query::newInsert($this->table_name);
            $insert = true;
        } else {
            $query = Ibe_Database_Query::newUpdate($this->table_name)
                    ->addWhere(
                            Ibe_Database_Query::newWhere($this->primary_key, $this->columns_val[$this->primary_key])
            );
        }

        foreach ($this->columns_val as $key => $param) {
            if ($key != $this->primary_key) {
                $query->addField($key, $param);
            } else if (!$this->primary_key_auto_increment && $query->getType() != Ibe_Database_Query::UPDATE) {
                $query->addField($key, $param);
            }
        }


        //Ibe_Debug::dispatchError(__FILE__, $query->getQuery());
        $query->execute(false);
        if ($insert && $this->primary_key_auto_increment) {
            $this->columns_val[$this->primary_key] = mysql_insert_id();
        }

        $this->posSave();
        return $this;
    }

    /**
     * Deleta o objeto
     */
    public function delete() {

        $this->preDelete();
        if (!isset($this->columns_val[$this->primary_key])) {
            throw new Ibe_Exception_Map(Ibe_Exception::MAPA_VALOR_PK, array($this->table_name));
        }
        $query = Ibe_Database_Query::newDelete($this->table_name)
                ->addWhere(
                        Ibe_Database_Query::newWhere($this->primary_key, $this->columns_val[$this->primary_key])
        );

        $executou = $query->execute(false);
        $this->posDelete();

        if($executou){
            unset($this);
        }else{
            return $this;
        }
    }

    /**
     * Atualiza o objeto
     * @return Model
     */
    public function update() {
        return $this->save();
    }

    /**
     * Procura por todos os modelos existentes da classe
     * @param int $start
     * @param int $limit
     * @return Model
     */
    public function findAll($order = array(), $start = 0, $limit = 10000) {
        return $this->findAllBy(null, null, $order, $start, $limit);
    }

    /**
     * Procura todos os objetos por um determinado campo
     *
     * @param string|array $field
     * @param mixed|array $value
     * @param int $start
     * @param int $limit
     * @return Ibe_Map
     */
    public function findAllBy($fields, $values,$orders = array(), $page = 0, $size = 10000) {

        if (!is_array($orders)) {
            $orders = array($orders=>true);
        }

        //Clausura order by
        $order = Ibe_Database_Query::newOrderby();
        foreach ($orders as $field=>$asc) {
            $order->addField($field);
            $order->setAsc($asc);
        }

        //Monta o SELECT
        $query = Ibe_Database_Query::newSelect($this->table_name)
                ->setFields($this->columns)
                ->addWhere($this->getWhere($fields, $values))
                ->addOrderBy($order)
                ->setPage($page)
                ->setLimit($size);
        
        //Ibe_Debug::warn(__FILE__, $query->getQuery());
        return $this->mountObjectsToFinds($query);
    }

    /**
     * Procura o objeto por um id
     * @param mixed $id
     * @return Ibe_Map
     */
    public function findById($id) {
        return $this->findBy($this->primary_key, $id);
    }

    /**
     * Encontra o objeto por um campo baseado na configura��o da classe model
     * @param string|array $field
     * @param mixed|array $value
     * @return Ibe_Map
     */
    public function findBy($fields, $values) {

        //Monta o SELECT
        $query = Ibe_Database_Query::newSelect($this->table_name)
                ->setFields($this->columns)
                ->addWhere($this->getWhere($fields, $values))
                ->setPage(0)
                ->setLimit(1);

        //Ibe_Debug::error(__FILE__." - ".__LINE__, $query->getQuery());
        
        $executou = $query->execute(false);
        if ($executou) {
            $registro = mysql_fetch_array($executou);

            foreach ($this->columns as $val) {
                $metodo = 'set' . ucfirst($val);
                $this->$metodo($registro[$val]);
            }
        }

        return $this;
    }

    /**
     * Procura por todos os modelos existentes da classe
     * @param int $start
     * @param int $limit
     * @return Model
     */
    public function findAllWithJoin($order = null, $start = null, $limit = null) {
        return $this->findAllByWithJoin(null, null, $order, $start, $limit);
    }

    /**
     * Procura todos os objetos por um determinado campo, realizando a juncao das chaves estrangeira
     * configuradas no mapa
     *
     * @param string $field
     * @param mixed $value
     * @param int $order
     * @param int $start
     * @param int $limit
     * @return Ibe_Map
     */
    public function findAllByWithJoin($fields, $values, $order = array(), $start = 0, $limit = 10000) {
        //Ibe_Debug::show($this,__FILE__,true);
        $select = Ibe_Database_Query::newSelect($this->table_name)->addWhere($this->getWhere($fields, $values));

        foreach ($this->columns as $col) {
            $select->addField($this->getTableName() . '.' . $col);
        }

        if (isset($this->foreign_key)) {

            foreach ($this->foreign_key as $key => $tbl) {
                $table = Ibe_Map::getTable($tbl);
                $as = str_replace('.',"_", $table->getTableName());
                foreach ($table->getColumns() as $col) {
                    $select->addField($table->getTableName() . '.' . $col . ' AS ' . $as . '_' . $col);
                }

                $on = $key ;
                $field_on = $table->getTableName() . '.' . $table->getPrimaryKey();
                $field_on_right = $this->getTableName() . '.' . $on;

                $select->addJoin(
                        Ibe_Database_Query::newJoin($table->getTableName(), $this->getTableName())
                                ->addWhere(
                                        Ibe_Database_Query::newWhere()
                                        ->andWhere($field_on, $field_on_right, '=', 'column')
                                )
                );
            }
        } /*else {
            throw new Ibe_Exception(Ibe_Exception::MAPA_SEM_FK, array($this->table_name));
        }*/

        return $this->mountObjectsToFinds($select, true);
    }

    /**
     * Busca os objetos do banco de dados, conforme o objeto
     * Ibe_Database_query montar a query a ser buscada
     *
     * @param Ibe_Database_Query $obj
     * @return array
     */
    public function findByQuery(Ibe_Database_Query $obj, $all_columns = false) {
        return $this->mountObjectsToFinds($obj, $all_columns);
    }

    /**
     * Para um relacionamento de um para muitos o retorno sera apenas 1 objeto
     * do tipoIbe_Map. Para relacionamendos n-n sera retornado um array
     * de objetos Ibe_Map
     *
     * @param string $class_name
     * @return Ibe_Map
     */
    public function getChild($class_name) {
        $child_obj = NULL;
        if (is_array($this->foreign_key)) {
            $class_name = implode("",array_map("ucfirst",explode("_",$class_name)));
            $key = array_search($class_name, $this->foreign_key);
            if ($key) {
                $child_obj = Ibe_Map::getTable($class_name);
                if (!$this->has_many_table) {
                    $child_obj->findById($this->columns_val[$key]);
                } else {
                    $child_obj = $child_obj->findAllBy($child_obj->getPrimaryKey(), $this->columns_val[$key]);
                }
            } else {
                throw new Ibe_Exception_Map(Ibe_Exception::MAPA_SEM_RELACIONAMENTO, array($this->table_name, $class_name));
            }
        } else {
            throw new Ibe_Exception_Map(Ibe_Exception::MAPA_SEM_RELACIONAMENTO, array($this->table_name, $class_name));
        }
        return $child_obj;
    }

    /**
     * Retorna o total de registros
     *
     * @param string $where
     * @return int
     */
    public function getCount($where = '') {
        $count = 0;
        $query = 'SELECT COUNT(' . $this->primary_key . ') as total FROM ' . $this->table_name . ' ';
        $query .= $where;

        $result = mysql_query($query);

        if (!$result) {
            throw new Ibe_Exception_Map(mysql_errno());
        } else {
            $return = mysql_fetch_array($result);
            $count = (int) $return['total'];
        }

        return $count;
    }

    /**
     * Verifica se o objeto � existe no banco de dados
     * @return boolean
     */
    public function isEmpty() {
        return (isset($this->columns_val[$this->primary_key]) && !empty($this->columns_val[$this->primary_key]));
    }

    /**
     * captura um objeto where
     * @param mixed $fields
     * @param mixed $values
     * @return Ibe_Database_Query_Condition_Where
     */
    private function getWhere($fields, $values) {
        // Montando a opcao de campos para arrays ou string unica
        if (!is_array($fields) && !is_array($values) && isset($fields) && isset($values)) {
			$fd = $fields; //bug fixed
            $fields = array($fields => '=');
            // Ibe_Debug::warn(__FILE__,$fields);
            $values = array($fd=>array($values,'AND'));
           // Ibe_Debug::warn(__FILE__,$values);
        } else if(!is_array($fields) || !is_array($values)){
            $fields = array();
        }

        //Clausura where
        $where = Ibe_Database_Query::newWhere();
        $i = 0;
        foreach ($fields as $field => $sinal) {
            $val  = $values[$field][0];
            $type = strtoupper($values[$field][1]);

            switch ($type) {
                case 'AND':
                    $where->andWhere($field, $val , $sinal, $this->columns_conf[$field]);
                    break;
                case 'OR':
                    $where->orWhere($field, $val , $sinal, $this->columns_conf[$field]);
                    break;
                case 'NOT':
                    $where->notWhere($field, $val , $sinal, $this->columns_conf[$field]);
                    break;
                default:
                    break;
            }
            $i++;
        }

        return $where;
    }

    /**
     * Metodo executado antes de salvar os itens
     */
    protected function preSave() {

    }

    /**
     * Metodo executado apos savar os itens
     */
    protected function posSave() {

    }

    /**
     * Metodo executado antes de deletar itens
     */
    protected function preDelete() {

    }

    /**
     * Metodo executado depois de deletar itens
     */
    protected function posDelete() {

    }

    /**
     * MOnta os objetos achados na execucao de consultas selects
     * @param string $sql
     * @param boolean $ignore_columns
     * @return array
     */
    private function mountObjectsToFinds(Ibe_Database_Query_Select $query, $ignore_columns = false) {
        $items = array();

        $resultado = $query->execute(false);
        while ((@$row = mysql_fetch_array($resultado))) {

            $class = get_class($this);
            $cl = new ReflectionClass($class);
            $item = $cl->newInstance();

            if (!$ignore_columns) {
                foreach ($this->columns as $cols) {
                    $metodo = 'set' . ucfirst($cols);
                    $item->$metodo($row[$cols]);
                }
            } else {
                foreach ($row as $col => $val) {
                    if (!is_int($col)) {
                        $item->$col = $val;
                    }
                }
            }
            $items[] = $item;
        }


        return $items;
    }

    /**
     * Retorna os valores em forma de array
     * @return array
     */
    public function toArray() {
        return $this->columns_val;
    }

    /**
     * transforma modelos em arrays simples
     * @param array $models
     */
    static public function toArrayModels(array $models) {
        $array = array();
        foreach ($models as $model) {
            $array[] = $model->toArray();
        }

        return $array;
    }

    /**
     * Chamada dos metodos sets e gets da classe
     *
     * @param string $method
     * @param string $parameter
     * @return mixed
     */
    public function __call($method, $parameter) {
        $size = strlen($method);
        $varName = strtolower(substr($method, 3, $size));
        $method = substr($method, 0, 3);

        //if( array_search($varName, $this->columns) || $this->primary_key == $varName){
        if ($method == 'set') {
            $val = $parameter[0];
            if ($this->columns_conf[$varName] == 'date') {
                if (isset($parameter[1]) && strtolower($parameter[1]) == 'tosql') {
                    if (!empty($val)) {
                        $val = date_to_sql($val);
                    } else {
                        $val = '0000-00-00';
                    }
                } else if (isset($parameter[1]) && strtolower($parameter[1]) == 'fromsql') {
                    $val = sql_to_date($val);
                }
            }
            $this->columns_val[$varName] = $val;
        } elseif ($method == 'get') {
            $val = $this->columns_val[$varName];
            if ($this->columns_conf[$varName] == 'date') {
                if (isset($parameter[0]) && strtolower($parameter[0]) == 'tosql') {
                    $val = date_to_sql($val);
                } else if (isset($parameter[0]) && strtolower($parameter[0]) == 'fromsql') {
                    if (!empty($val)) {
                        $val = sql_to_date($val);
                    } else {
                        $val = '00/00/0000';
                    }
                }
            }
            return $val;
        }
        //}

        return $this;
    }

    public function __set($name, $value) {
        $this->columns_val[$name] = $value;
    }

    public function __get($name) {
        return $this->columns_val[$name];
    }

    /**
     * Verifica se uma variavel do mapa foi setada ou n�o esta em branco
     * @param string $name
     * @return boolean
     */
    public function isVarSet($name){
        return (isset($this->columns_val[$name]) && !empty ($this->columns_val[$name]));
    }

}
