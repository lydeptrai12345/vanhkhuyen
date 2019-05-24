<?php
include "xuly-data.php";

class NguyenLieu extends xuly
{
    protected $_tableName = "nienkhoa";

    function __contructor()
    {
        parent::__contructor();
    }

    public function get_danh_sach_nguyen_lieu()
    {
        $result = $this->from('nguyen_lieu')->select('ten_nguyen_lieu, so_luong, gia_tien, dvt, ngay_nhap, nhan_vien_id, (so_luong*gia_tien) as thanh_tien')->get();
        return $result;
    }

    public function insert_nguyen_lieu($data_insert)
    {
        if(empty($data_insert)) return null;

        $data_insert = (object)$data_insert;

        $ten_nguyen_lieu = $data_insert->ten_nguyen_lieu;
        if(empty($data_insert->ten_nguyen_lieu)) return 'ten_nguyen_lieu';

        $so_luong = (int)$data_insert->so_luong;
        if($so_luong < 0) return 'so_luong';

        if(empty($data_insert->gia_tien)) return 'gia_tien';
        $gia_tien = str_replace(",", "", $data_insert->gia_tien);

        $dvt = $data_insert->dvt;
        if(empty($dvt)) return 'dvt';

        $nhan_vien_id = (int)$data_insert->nhan_vien_id;
        if($nhan_vien_id < 0) return 'nhan_vien_id';

        $data_insert = array(
            'ten_nguyen_lieu' => $ten_nguyen_lieu,
            'so_luong' => $so_luong,
            'gia_tien' => $gia_tien,
            'dvt' => $dvt,
            'ngay_nhap' => date("Y-m-d"),
            'nhan_vien_id' => 1,
        );
        return $this->insert('nguyen_lieu', $data_insert);
    }
}