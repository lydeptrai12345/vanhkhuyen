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
        $result = $this->from('nguyen_lieu')->select('id, ten_nguyen_lieu, so_luong, gia_tien, dvt, ngay_nhap, nhan_vien_id, (so_luong*gia_tien) as thanh_tien')->get();
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
            'nhan_vien_id' => $nhan_vien_id,
        );
        return $this->insert('nguyen_lieu', $data_insert);
    }

    public function update_nguyen_lieu($data_update)
    {
        if(empty($data_update)) return null;

        $data_update = (object)$data_update;

        $id = (isset($data_update->id) && $data_update->id > 0) ? $data_update->id :0;
        if($id <= 0) return 'id';

        if(empty($this->get_nguyen_lieu($id))) return 'data_null';

        $ten_nguyen_lieu = $data_update->ten_nguyen_lieu;
        if(empty($data_update->ten_nguyen_lieu)) return 'ten_nguyen_lieu';

        $so_luong = (int)$data_update->so_luong;
        if($so_luong < 0) return 'so_luong';

        if(empty($data_update->gia_tien)) return 'gia_tien';
        $gia_tien = str_replace(",", "", $data_update->gia_tien);

        $dvt = $data_update->dvt;
        if(empty($dvt)) return 'dvt';

        $nhan_vien_id = (int)$data_update->nhan_vien_id;
        if($nhan_vien_id < 0) return 'nhan_vien_id';

        $data_update = array(
            'ten_nguyen_lieu' => $ten_nguyen_lieu,
            'so_luong' => $so_luong,
            'gia_tien' => $gia_tien,
            'dvt' => $dvt,
            'ngay_nhap' => date("Y-m-d"),
            'nhan_vien_id' => $nhan_vien_id,
        );
        return $this->where('id = ' . 1)->update('nguyen_lieu', $data_update);
    }

    public function get_nguyen_lieu($nguyen_lieu_id)
    {
        $nguyen_lieu_id = (int)$nguyen_lieu_id;

        if($nguyen_lieu_id <=0 ) return -1;

        $result = $this->select('*')->from('nguyen_lieu')->where('id = ' . $nguyen_lieu_id)->get();
        return $result;

    }
}