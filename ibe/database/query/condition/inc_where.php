<?php

/**
 * Classe para criacao de condicoes WHERE de querys SQL
 * @author Renan Abreu
 * @version 20102011
 * @package ibe
 * @subpackage database.query.condition
 */
class Ibe_Database_Query_Condition_Where {

    private $where = array();

    /**
     * Where and
     * @param type $field
     * @param type $value
     * @param type $sinal
     * @return Ibe_Database_Query
     */
    public function andWhere($field, $value, $sinal = '=', $type = 'string') {
        $this->addWhere('AND', $field, $value, $sinal, $type);
        return $this;
    }

    /**
     * Where or
     * @param type $field
     * @param type $value
     * @param type $sinal
     * @return Ibe_Database_Query
     */
    public function orWhere($field, $value, $sinal = '=', $type = 'string') {
        $this->addWhere('OR', $field, $value, $sinal, $type);
        return $this;
    }

    /**
     * Where Not
     * @param type $field
     * @param type $value
     * @param type $sinal
     * @return Ibe_Database_Query
     */
    public function notWhere($field, $value, $sinal = '=', $type = 'string') {
        $this->addWhere('NOT', $field, $value, $sinal, $type);
        return $this;
    }

    /**
     * Adiciona um WHERE de um campo
     * @param type $field
     * @param type $value
     * @param type $sinal
     * @return Ibe_Database_Query
     */
    public function where($field, $value, $sinal = '=', $type = 'string') {
        $this->addWhere('', $field, $value, $sinal, $type);
        return $this;
    }

    /**
     * Adiciona where
     * @param string $operator
     * @param string $field
     * @param string $value
     * @param string $sinal
     * @param string $type
     * @return Ibe_Database_Query_Condition_Where
     * @todo escapar o valor conforme o sinal da operacao
     */
    private function addWhere($operator, $field, $value, $sinal, $type) {

        $operator = strtoupper($operator);
        $sinal = trim(strtoupper($sinal));
        //if($value !== NULL || $value === '0' || $value === 0){
        if (isset($operator) && isset($field)) {
            if (sizeof($this->where) == 0) {
                $operator = '';
            }

            $condition = NULL;
            switch ($sinal) {
                case '=':
                case '>':
                case '<':
                case '<>':
                case '>=':
                case '<=':
                case 'LIKE':
                case 'NOT LIKE':
                case 'IN':
                case 'NOT IN':
                    $condition = $operator . ' ' . $field . ' ' . $sinal . ' ' . $this->scape($value, $type);
                    break;
                case '%LIKE':
                case 'NOT %LIKE':
                    $sinal = str_replace('%', '', $sinal);
                    $condition = $operator . ' ' . $field . ' ' . $sinal . ' ' . $this->scape($value, $type, '%');
                    break;
                case 'LIKE%':
                case 'NOT LIKE%':
                    $sinal = str_replace('%', '', $sinal);
                    $condition = $operator . ' ' . $field . ' ' . $sinal . ' ' . $this->scape($value, $type, '', '%');
                    break;
                case '%LIKE%':
                case 'NOT %LIKE%':
                    $sinal = str_replace('%', '', $sinal);
                    $condition = $operator . ' ' . $field . ' ' . $sinal . ' ' . $this->scape($value, $type, '%', '%');
                    break;
                case 'IS NULL':
                case 'IS NOT NULL':
                    $condition = $operator . ' ' . $field . ' ' . $sinal;
                    break;
                default:
                    break;
            }
            $this->where[] = $condition;
        }
        //  }
        return $this;
    }

    private function scape($value, $type, $append = '', $prepend = '') {

        if ($type != 'int' && $type != 'float' && $type != 'boolean' && $type != 'column') {
            $value = "'{$append}{$value}{$prepend}'";
        }

        return $value;
    }

    /**
     * Retorna where
     * @return string
     */
    public function getWhere() {
        $where = null;

        if (sizeof($this->where)) {
            $where = implode(' ', $this->where);
        }

        return $where;
    }

}

