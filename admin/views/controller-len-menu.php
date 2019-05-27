<?php
include "xuly-data.php";

class LenMenu extends xuly {
    function __contructor()
    {
        parent::__contructor();
    }

    function get_menu() {

    }

    public function insert_menu($data_insert)
    {

        $this->insert_multiple('menu', $data_insert);
    }
}