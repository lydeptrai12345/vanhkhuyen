<?php

interface xulyData {
    function getTable($select, $where);
    function insertTable($arrayInsertTable);
    function updateTable($arrayDataUpdateTable, $where);
    function deleteTable($where);
}


class xuly {
    protected $dbc;
    protected $_table = "";
    protected $_select = "";
    protected $_where = [];
    protected $_from = " FROM ";
    protected $_join = [];

    function __construct() {
        $this->dbc = mysqli_connect('localhost','root','','qlmamnon') or die(mysqli_error());
        mysqli_set_charset($this->dbc,"utf8");
    }

    public function select($select = "*")
    {
        $this->_select .= $select;
        return $this;

    }

    public function from($from)
    {
        $this->_from .= $from;
        return $this;
    }

    public function where($where)
    {
        $this->_where[] = $where;
        return $this;
    }

    public function join($table_join, $foreign_key, $condition, $foreign)
    {
        $this->_join[] = $table_join . " ON " . $foreign_key . " " . $condition . " " .$foreign;
        return $this;
    }

    public function get()
    {
        $query_select = "SELECT " . (empty($this->_select) ? "*" : $this->_select);

        $query_from   = $this->_from;

        $query_join   = !empty($this->_join) ? " INNER JOIN " . implode(" INNER JOIN ", $this->_join) : "";

        $query_where  = !empty($this->_where) ? " WHERE " . implode(" AND ", $this->_where) : "";

        $query = $query_select . $query_from . $query_join . $query_where;

        $query = mysqli_query($this->dbc, $query);
        $result = array();

        if ($this->_select == "*") {
            while ($row = mysqli_fetch_object($query)) {
                $result[] = $row;
            }
        } else {
            $arr_select = $this->get_name_column_select($this->_select);
            while ($row = mysqli_fetch_row($query)) {
                $result[] = array_combine($arr_select, $row);
            }
        }

        return $result;
    }

    private function get_name_column_select($select)
    {
        $arr_select = explode(",", $select);
        $arr_result = [];
        foreach ($arr_select as $item) {
            $arr_name = explode(" ", $item);
            $arr_result[] = end($arr_name);
        }
        return $arr_result;
    }
}

?>
