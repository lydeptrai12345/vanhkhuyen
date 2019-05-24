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
            ->select("id, ten_thiet_bi, (DATE_FORMAT(ngay_san_san_xuat,'%d-%m-%Y')) as ngay_san_san_xuat, (DATE_FORMAT(ngay_het_han,'%d-%m-%Y')) as ngay_het_han, bao_hanh, (DATE_FORMAT(ngay_nhap,'%d-%m-%Y')) as ngay_nhap, nhan_vien_id, so_luong, gia_tien, thanh_ly, ghi_chu, nien_khoa_id, nhan_vien_id, (so_luong*gia_tien) as thanh_tien")
            ->where("DATE(ngay_nhap) >= DATE('". $date_start_of_year ."')")
            ->where("DATE(ngay_nhap) <= DATE('". $date_end_of_year ."')")
            ->order_by('ngay_nhap DESC')
            ->get();
        return $result;
    }
}