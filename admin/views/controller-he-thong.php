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
        if($nhom_cha_id == 0){
            $result_cn = $this->from('nhom_chuc_nang')
                ->select("*")
                ->where('hien_thi = 1')
                ->get();
            $arr = [];
            $arr_nhom_con = [];
            foreach ($result_cn as $value) {
                if($value->nhom_cha == 0) {
                    $value->nhom_con = [];
                    $arr[] = $value;
                }
                else {
                    $arr_nhom_con[] = $value;
                }
            }

            foreach ($arr_nhom_con as $item) {
                $idx = array_search($item->nhom_cha, array_column($arr, 'id'));
                if($idx >= 0){
                    $arr[$idx]->nhom_con[] = $item;
                }
            }

            $result = $arr;
        }
        else{
            $result = $this->from('nhom_chuc_nang')
                ->select("*")
                ->where('nhom_cha = '. $nhom_cha_id)
                ->where('hien_thi = 1')
                ->get();
        }

        return $result;
    }

    public function get_tat_ca_danh_sach_nhan_vien()
    {
        $result = $this->from('nhanvien')
            ->select('*')
            ->get();
        return $result;
    }

    public function get_danh_sach_nhan_vien_chua_co_tai_khoan()
    {
        $result = $this->from('nhanvien')
            ->select('*')
            ->where('id NOT IN (SELECT nhan_vien_id FROM nguoidung)')
            ->get();
        return $result;
    }

    public function get_phan_quyen_chuc_nang_theo_nhom_nguoi_dung($nhom_nguoi_dung_id)
    {
        $result = $this->from('nhom_phan_quyen')
            ->select('*')
            ->where('id_nhom_nguoi_dung = ' . $nhom_nguoi_dung_id)
            ->get();
        return $result;
    }


    public function insert_nguoi_dung($data_insert)
    {
        if(empty($data_insert)) return null;

        $data_insert = (object)$data_insert;

        $ten_nguoi_dung = $data_insert->ten_nguoi_dung;
        if(empty($data_insert->ten_nguoi_dung)) return 'ten_nguoi_dung';

        $mat_khau = $data_insert->mat_khau;
        if(empty($mat_khau)) return 'mat_khau';

        if(empty($data_insert->nhan_vien_id)) return 'nhan_vien_id';

        $nhom_nguoi_dung_id = (int)$data_insert->nhom_nguoi_dung_id;
        if($nhom_nguoi_dung_id <= 0) return 'nhom_nguoi_dung_id';

        $nhan_vien_id = (int)$data_insert->nhan_vien_id;
        if($nhan_vien_id <= 0) return 'nhan_vien_id';

        $data_insert = array(
            'ten_nguoi_dung' => $ten_nguoi_dung,
            'mat_khau' => md5($mat_khau),
            'nhom_nguoi_dung_id' => $nhom_nguoi_dung_id,
            'nhan_vien_id' => $nhan_vien_id,
            'trang_thai' => 1,
        );

        return $this->insert('nguoidung', $data_insert);
    }

    public function update_nguoi_dung($data_update)
    {
        if (empty($data_update)) return null;

        $data_update = (object)$data_update;

        $id = (isset($data_update->id) && $data_update->id > 0) ? $data_update->id : 0;
        if ($id <= 0) return 'id';

//        $mat_khau = $data_update->mat_khau;
//        if (empty($mat_khau)) return 'mat_khau';

        if (empty($data_update->nhan_vien_id)) return 'nhan_vien_id';

        $nhom_nguoi_dung_id = (int)$data_update->nhom_nguoi_dung_id;
        if ($nhom_nguoi_dung_id <= 0) return 'nhom_nguoi_dung_id';

        $nhan_vien_id = (int)$data_update->nhan_vien_id;
        if ($nhan_vien_id <= 0) return 'nhan_vien_id';

        $data_update = array(
//            'mat_khau' => $mat_khau,
            'nhom_nguoi_dung_id' => $nhom_nguoi_dung_id,
//            'nhan_vien_id' => $nhan_vien_id,
            'trang_thai' => 1,
        );

        return $this->where('id = ' . $id)->update('nguoidung', $data_update);
    }

    public function get_tai_khoan_nguoi_dung($id)
    {
        if((int)$id <= 0) return [];
        $result = $this->select('*')->from('nguoidung')->where('id = '. "'" . $id ."'")->get();
        return $result;

    }

    public function check_ten_tai_khoan_nguoi_dung($ten_tai_khoan)
    {
        if(empty($ten_tai_khoan)) return -1;
        $result = $this->select('*')->from('nguoidung')->where('ten_nguoi_dung = '. "'" . $ten_tai_khoan ."'")->get();
        return $result;

    }

    public function kich_nguoi_dung($id, $type=0)
    {
        if((int)$id <= 0) return -1;
        $data = array('trang_thai' => $type);
        return $this->where('id = ' . $id)->update('nguoidung', $data);
    }

    public function delete_nguoi_dung($id)
    {
        if($id <= 0) return null;
        return $this->where('id = ' . $id)->delete('nguoidung');
    }

    public function add_phan_quyen_nhom_nguoi_dung($data)
    {
        if(empty($data)) return -1;
        return $this->insert_multiple('nhom_phan_quyen', $data);
    }

    public function delete_phan_quyen_nhom_nguoi_dung($id)
    {
        return $this->where('id_nhom_nguoi_dung = ' . $id)->delete('nhom_phan_quyen');
    }

    public function kiem_tra_quyen_menu_trai() {
        $result_cn = $this->from('nhom_chuc_nang')
            ->select("*")
            ->where('hien_thi = 1')
            ->get();
        $arr = [];
        $arr_nhom_con = [];
        foreach ($result_cn as $value) {
            if($value->nhom_cha == 0) {
                $value->nhom_con = [];
                $arr[] = $value;
            }
            else {
                $arr_nhom_con[] = $value;
            }
        }

        foreach ($arr_nhom_con as $item) {
            $idx = array_search($item->nhom_cha, array_column($arr, 'id'));
            if($idx >= 0){
                $arr[$idx]->nhom_con[] = $item;
            }
        }

        return $arr;
    }
}