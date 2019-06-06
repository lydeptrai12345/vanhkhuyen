<?php
include "xuly-data.php";

class HeThong extends xuly {

    function __contructor()
    {
        parent::__contructor();
    }

    public function get_danh_sach_tai_khoan()
    {
        $result = $this->from('nguoidung')
            ->select("nguoidung.id as id, nhom_nguoi_dung_id, ten_nguoi_dung, ten_nhom, nhan_vien_id, nguoidung.trang_thai as trang_thai")
            ->join("nhom_nguoi_dung", "nhom_nguoi_dung.id", "=", "nguoidung.nhom_nguoi_dung_id")
            ->get();
        return $result;
    }

    public function get_danh_sach_nhom_nguoi_dung()
    {
        $result = $this->from('nhom_nguoi_dung')
            ->select("id, ten_nhom, ghi_chu, (SELECT COUNT(id) FROM nguoidung WHERE trang_thai = 1 AND nhom_nguoi_dung_id = nhom_nguoi_dung.id) as sum_nguoi_dung")
            ->get();
        return $result;
    }

    public function get_danh_sach_chuc_nang_cha()
    {
        $result = $this->from('nhom_chuc_nang')
            ->select("*")
            ->where('nhom_cha = 0')
            ->where('hien_thi = 1')
            ->get();
        return $result;
    }

    public function get_danh_sach_chuc_nang_con_theo_cha($nhom_cha_id)
    {
        $result = $this->from('nhom_chuc_nang')
            ->select("*")
            ->where('nhom_cha = '. $nhom_cha_id)
            ->where('hien_thi = 1')
            ->get();
        return $result;
    }


    public function insert_thiet_bi($data_insert)
    {
        if(empty($data_insert)) return null;

        $data_insert = (object)$data_insert;

        $ten_thiet_bi = $data_insert->ten_thiet_bi;
        if(empty($data_insert->ten_thiet_bi)) return 'ten_thiet_bi';

        $so_luong = (float)$data_insert->so_luong;
        if($so_luong < 0) return 'so_luong';

        if(empty($data_insert->gia_tien)) return 'gia_tien';
        $gia_tien = str_replace(",", "", $data_insert->gia_tien);

        $dvt = $data_insert->dvt;
        if(empty($dvt)) return 'dvt';

        $ngay_san_xuat = $data_insert->ngay_san_xuat;
        if(empty($ngay_san_xuat)) return 'ngay_san_xuat';

        $ngay_het_han = $data_insert->ngay_het_han;
        if(empty($ngay_het_han)) return 'ngay_het_han';

//        $thanh_ly = $data_insert->thanh_ly;
//        if(empty($thanh_ly)) return 'thanh_ly';

        $nien_khoa_id = $data_insert->nien_khoa_id;
        if(empty($nien_khoa_id)) return 'nien_khoa';

        $nhan_vien_id = (int)$data_insert->nhan_vien_id;
        if($nhan_vien_id < 0) return 'nhan_vien_id';

        $data_insert = array(
            'ten_thiet_bi' => $ten_thiet_bi,
            'so_luong' => $so_luong,
            'ngay_san_xuat' => date_create($ngay_san_xuat)->format('Y-m-d'),
            'ngay_het_han' => date_create($ngay_het_han)->format('Y-m-d'),
            'bao_hanh' => $so_luong,
            'thanh_ly' => 0,
            'nien_khoa_id' => $nien_khoa_id,
            'gia_tien' => str_replace(".", "", $gia_tien),
            'dvt' => $dvt,
            'ngay_nhap' => date("Y-m-d"),
            'nhan_vien_id' => $nhan_vien_id,
            'ghi_chu' => $nhan_vien_id,
        );

        return $this->insert('thiet_bi', $data_insert);
    }

    public function update_thiet_bi($data_update)
    {
        if(empty($data_update)) return null;

        $data_update = (object)$data_update;

        $id = (isset($data_update->id) && $data_update->id > 0) ? $data_update->id :0;
        if($id <= 0) return 'id';

//        if(empty($this->get_thiet_bi($id))) return 'data_null';

        $ten_thiet_bi = $data_update->ten_thiet_bi;
        if(empty($data_update->ten_thiet_bi)) return 'ten_thiet_bi';

        $so_luong = (float)$data_update->so_luong;
        if($so_luong < 0) return 'so_luong';

        if(empty($data_update->gia_tien)) return 'gia_tien';
        $gia_tien = str_replace(",", "", $data_update->gia_tien);

        $dvt = $data_update->dvt;
        if(empty($dvt)) return 'dvt';

        $ngay_san_xuat = $data_update->ngay_san_xuat;
        if(empty($ngay_san_xuat)) return 'ngay_san_xuat';

        $ngay_het_han = $data_update->ngay_het_han;
        if(empty($ngay_het_han)) return 'ngay_het_han';

        $thanh_ly = $data_update->thanh_ly;
        if(empty($thanh_ly)) return 'thanh_ly';

        $nien_khoa_id = $data_update->nien_khoa_id;
        if(empty($nien_khoa_id)) return 'nien_khoa';

        $nhan_vien_id = (int)$data_update->nhan_vien_id;
        if($nhan_vien_id < 0) return 'nhan_vien_id';

        $data_update = array(
            'ten_thiet_bi' => $ten_thiet_bi,
            'so_luong' => $so_luong,
            'ngay_san_xuat' => date_create($ngay_san_xuat)->format('Y-m-d'),
            'ngay_het_han' => date_create($ngay_het_han)->format('Y-m-d'),
            'bao_hanh' => $so_luong,
            'thanh_ly' => $thanh_ly,
            'nien_khoa_id' => $nien_khoa_id,
            'gia_tien' => str_replace(".", "", $gia_tien),
            'dvt' => $dvt,
            'ngay_nhap' => date("Y-m-d"),
            'nhan_vien_id' => $nhan_vien_id,
            'ghi_chu' => $nhan_vien_id,
        );
        return $this->where('id = ' . $id)->update('thiet_bi', $data_update);
    }

    public function get_thiet_bi($thiet_bi_id)
    {
        $thiet_bi_id = (int)$thiet_bi_id;

        if($thiet_bi_id <=0 ) return -1;

        $result = $this->select('*')->from('thiet_bi')->where('id = ' . $thiet_bi_id)->get();
        return $result;

    }

    public function delete_thiet_bi($id)
    {
        if($id <= 0) return null;
        return $this->where('id = ' . $id)->delete('thiet_bi');
    }
}