<?php
include "xuly-data.php";

class NhomNguoiDung extends xuly {
    function __contructor()
    {
        parent::__contructor();
    }

    public function add_nhom_nguoi_dung($data_insert)
    {
        $data_insert = (object)$data_insert;

        if (empty($data_insert->ten_nhom)) return 'ten_nhom';

        if ($data_insert->ten_nhom)
            $data_insert = array(
                'ten_nhom' => $data_insert->ten_nhom,
                'created_at' => date("Y-m-d")
            );
        return $this->insert('nhom_nguoi_dung', $data_insert);
    }
}