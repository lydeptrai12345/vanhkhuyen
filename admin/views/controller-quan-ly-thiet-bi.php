<?php
include "xuly-data.php";

class QuanLyThietBi extends xuly {
    
    function __contructor()
    {
        parent::__contructor();
    }

    public function get_danh_sach_thiet_bi($date)
    {
        $date = date($date);
        $year = date("Y", strtotime($date));
        $date_start_of_year = $year . '-01-01';
        $date_end_of_year = $year . '-12-31';

        $result = $this->from('thiet_bi')
            ->select("id, ten_thiet_bi, (DATE_FORMAT(ngay_san_xuat,'%d-%m-%Y')) as ngay_san_xuat, (DATE_FORMAT(ngay_het_han,'%d-%m-%Y')) as ngay_het_han, bao_hanh, (DATE_FORMAT(ngay_nhap,'%d-%m-%Y')) as ngay_nhap, nhan_vien_id, so_luong, gia_tien, thanh_ly, ghi_chu, nien_khoa_id, nhan_vien_id, (so_luong*gia_tien) as thanh_tien")
            ->where("DATE(ngay_nhap) >= DATE('". $date_start_of_year ."')")
            ->where("DATE(ngay_nhap) <= DATE('". $date_end_of_year ."')")
            ->order_by('ngay_nhap DESC')
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

        $ngay_san_san_xuat = $data_insert->ngay_san_san_xuat;
        if(empty($ngay_san_san_xuat)) return 'ngay_san_san_xuat';

        $ngay_het_han = $data_insert->ngay_het_han;
        if(empty($ngay_het_han)) return 'ngay_het_han';

        $ngay_het_han = $data_insert->thanh_ly;
        if(empty($ngay_het_han)) return 'thanh_ly';

        $nhan_vien_id = (int)$data_insert->nhan_vien_id;
        if($nhan_vien_id < 0) return 'nhan_vien_id';

        $data_insert = array(
            'ten_thiet_bi' => $ten_thiet_bi,
            'so_luong' => $so_luong,
            'ngay_san_san_xuat' => $ngay_san_san_xuat,
            'ngay_het_han' => $ngay_het_han,
            'bao_hanh' => $so_luong,
            'thanh_ly' => $so_luong,
            'nien_khoa_id' => $so_luong,
            'gia_tien' => $gia_tien,
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

        $nhan_vien_id = (int)$data_update->nhan_vien_id;
        if($nhan_vien_id < 0) return 'nhan_vien_id';

        $data_update = array(
            'ten_thiet_bi' => $ten_thiet_bi,
            'so_luong' => $so_luong,
            'gia_tien' => $gia_tien,
            'dvt' => $dvt,
            'ngay_nhap' => date("Y-m-d"),
            'nhan_vien_id' => $nhan_vien_id,
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