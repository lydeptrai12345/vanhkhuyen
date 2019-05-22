<?php

interface xulyData {
    function getTable($tableName, $select, $where);
    function insertTable($tableName, $arrayInsertTable);
    function updateTable($tableName, $arrayDataUpdateTable, $where);
    function deleteTable($tableName, $where);
}


class xuly implements xulyData {
    protected $dbc;
    function __construct() {
        $this->dbc = mysqli_connect('localhost','root','','qlmamnon')
        or die(mysqli_error());
        mysqli_set_charset($this->dbc,"utf8");
    }

    /**
     * @param $tableName
     * @param $select
     * @param $where
     * @return array
     */
    public function getTable($tableName, $select, $where)
    {
        if (empty($tableName)) return [];

        $query = "SELECT ";

        (is_array($select) && count($select)) ? $query .= implode(",", $select) : $query .= " *";

        $query .= " FROM " . $tableName;

        (empty($where)) ? $query .= "" : $query .= " " . $where;
//        return $query;
        $query = mysqli_query($this->dbc, $query);
        $result = array();

        if (is_array($select) && count($select) <= 0) {
            while ($row = mysqli_fetch_object($query)) {
                $result[] = $row;
            }
        } else {
            while ($row = mysqli_fetch_row($query)) {
                $result[] = array_combine($select, $row);
            }
        }

        return $result;
    }

    /**
     * @param $tableName
     * @param $arrayInsertTable ex array(array("color" => "aaaa", "id" => "bbbb"))
     * @return int
     */
    public function insertTable($tableName, $arrayInsertTable)
    {
        if (empty($tableName)) return -1;
        $query = "INSERT INTO " . $tableName . " ";
        $arrNameColumn = "";
        $arrQuery = [];
        if (is_array($arrayInsertTable)) {
            foreach ($arrayInsertTable as $item) {
                $item = (array)$item;

                $arrNameColumn = array_keys($item);
                $arrValues = array_values($item);

                $strUpdate = null;
                for ($i = 0; $i < count($arrValues); $i++) {
                    if ($i < count($arrValues) - 1)
                        $strUpdate .= "('" . $arrValues[$i] . "', ";
                    else
                        $strUpdate .= "'" . $arrValues[$i] . "')";
                }
                $arrQuery[] = $strUpdate;
            }

            $query .= "(" . implode(",", $arrNameColumn) . ") VALUES ";
            $query .= implode(",", $arrQuery);
        }
        return $query;
        mysqli_query($this->dbc, $query);

        if (mysqli_affected_rows($this->dbc) > 0) return 1;

        return -1;
    }

    /**
     * @param $tableName name of table
     * @param $arrayDataUpdateTable ex array("color" => "aaaa", "id" => "bbbb")
     * @param $where
     * @return int
     */
    public function updateTable($tableName, $arrayDataUpdateTable, $where)
    {
        if (empty($tableName)) return -1;

        $query = "UPDATE {$tableName} SET ";

        if ($arrayDataUpdateTable) {
            $arrayDataUpdateTable = (array)$arrayDataUpdateTable;
            $arrKeys = array_keys($arrayDataUpdateTable);
            $arrValues = array_values($arrayDataUpdateTable);

            $strUpdate = null;
            for ($i = 0; $i < count($arrKeys); $i++) {
                if ($i < count($arrKeys) - 1)
                    $strUpdate .= $arrKeys[$i] . " = '" . $arrValues[$i] . "', ";
                else
                    $strUpdate .= $arrKeys[$i] . " = '" . $arrValues[$i] . "'";
            }

            $query .= $strUpdate;
        }

        if (!empty($where)) $query .= " " . $where;

        mysqli_query($this->dbc, $query);

        if (mysqli_affected_rows($this->dbc) > 0) return 1;

        return -1;
    }

    /**
     * @param $tableName
     * @param $where
     * @return int
     */
    public function deleteTable($tableName, $where)
    {
        if (empty($tableName)) return -1;

        $query = "DELETE $tableName WHERE ";
        if (!empty($where)) $query .= $where;
        mysqli_query($this->dbc, $query);

        if (mysqli_affected_rows($this->dbc) > 0) return 1;

        return -1;
    }
}

?>
