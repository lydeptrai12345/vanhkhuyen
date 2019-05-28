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
    protected $_insert = "";
    protected $_update = "";
    protected $_delete = "";
    protected $_order_by = "";

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

    public function order_by($order_by)
    {
        $this->_order_by .= $order_by . " ";
        return $this;
    }

    public function get()
    {
        $query_select = "SELECT " . (empty($this->_select) ? "*" : $this->_select);

        $query_from   = $this->_from;

        $query_join   = !empty($this->_join) ? " INNER JOIN " . implode(" INNER JOIN ", $this->_join) : "";

        $query_where  = !empty($this->_where) ? " WHERE " . implode(" AND ", $this->_where) : "";

        $query_oder_by = !empty($this->_order_by) ? " ORDER BY " . $this->_order_by : "";

        $query = $query_select . $query_from . $query_join . $query_where . $query_oder_by;

        $query = mysqli_query($this->dbc, $query);
        $result = array();

        if (mysqli_num_rows($query) > 0){
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
        }
        else return [];

        mysqli_close($this->dbc);

        return $result;
    }

    private function get_name_column_select($select)
    {
        $arr_select = explode(", ", $select);
        $arr_result = [];
        foreach ($arr_select as $item) {
            $arr_name = explode(" ", $item);
            $arr_result[] = end($arr_name);
        }
        return $arr_result;
    }

    public function insert($name_table, $data_insert = [])
    {
        if (!is_array($data_insert) || count($data_insert) <= 0) return -1;
        $arr_column = array_keys($data_insert);
        $arr_value = array_values($data_insert);
        $query_insert = "INSERT INTO " . $name_table . " (" . implode(",", $arr_column) . ") VALUE";
        $array_value = [];
        foreach ($arr_value as $item) {
            $array_value[] = "'" . $item . "'";
        }
        $query_insert .= " (" . implode(",", $array_value) . ")";
//        return $query_insert;
        mysqli_query($this->dbc, $query_insert);

        mysqli_affected_rows($this->dbc) > 0 ? $result = 1 : $result = -1;

        mysqli_close($this->dbc);
        return $result;
    }

    public function update($name_table, $data_update) {
        if (!is_array($data_update) || count($data_update) <= 0) return -1;

        $query_update = "UPDATE " . $name_table . " SET ";

        $str_update = [];
        foreach ($data_update as $key => $item) {
            $str_update[] = $key . "=" . "'" . $item . "'";
        }

        $query_where  = !empty($this->_where) ? " WHERE " . implode(" AND ", $this->_where) : "";

        $query_update .= implode(",", $str_update) . $query_where;

        mysqli_query($this->dbc, $query_update);

        mysqli_affected_rows($this->dbc) >= 0 ? $result = 1 : $result = -1;

        mysqli_close($this->dbc);
        return $result;
    }

    public function delete($name_table)
    {
        $query = "DELETE FROM " .$name_table;
        $query_where = !empty($this->_where) ? " WHERE " . implode(" AND ", $this->_where) : "";
        $query_delete = $query . $query_where;

        mysqli_query($this->dbc, $query_delete);

        mysqli_affected_rows($this->dbc) > 0 ? $result = 1 : $result = -1;

        mysqli_close($this->dbc);
        return $result;
    }

    public function insert_multiple($name_table, $array_data)
    {
        $arr_column = array_keys($array_data[0]);
        $array_value = [];
        $arr_str = [];

        for ($i = 0; $i < count($array_data); $i++) {
            $arr_value = array_values($array_data[$i]);
            foreach ($arr_value as $item) {
                $array_value[] = "'" . $item . "'";
            }
            $arr_str[] = "(" . implode(",", $array_value) . ")";
            $array_value = [];
        }
        $query_insert = "INSERT INTO " . $name_table . " (" . implode(",", $arr_column) . ") VALUE " .implode(",", $arr_str);

        mysqli_query($this->dbc, $query_insert);

        mysqli_affected_rows($this->dbc) > 0 ? $result = 1 : $result = -1;

        mysqli_close($this->dbc);
        return $result;
    }

}

?>
