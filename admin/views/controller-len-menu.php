<?php
include "xuly-data.php";

class LenMenu extends xuly {
    protected $arr_week = ['week_1', 'week_2', 'week_3', 'week_4'];
    protected $arr_day = [1, 2, 3, 4, 5, 6, 7];
    function __contructor()
    {
        parent::__contructor();
    }

    function get_menu() {

    }

    public function insert_menu($data_insert)
    {
        return $this->insert_multiple('menu', $data_insert);
    }

    public function update_menu($data_update)
    {
        return $this->where('id = 1')->update_multiple('menu', $data_update);
    }

    public function check_menu_thang($date)
    {
        $date = date($date);
        $month = date("m", strtotime($date)); // lấy tháng

        $year = date("Y", strtotime($date)); // lây năm
        $date_start = $year . '-' . $month . '-01'; // nối chuỗi

        $data_menu = $this->from('menu')
            ->select('*')
            ->where("DATE(ngay_tao) >= DATE('". $date_start ."')")
            ->where("DATE(ngay_tao) <= LAST_DAY('". $date_start ."')")
            ->order_by('ngay_tao DESC')
            ->get();
        return $data_menu;
    }

    public function get_data_menu_theo_thang($date)
    {
        $date = date($date);
        $month = date("m", strtotime($date)); // lấy tháng

        $year = date("Y", strtotime($date)); // lây năm
        $date_start = $year . '-' . $month . '-01'; // nối chuỗi

        $data_menu = $this->from('menu')
            ->select('*')
            ->where("DATE(ngay_tao) >= DATE('". $date_start ."')")
            ->where("DATE(ngay_tao) <= LAST_DAY('". $date_start ."')")
            ->order_by('ngay_tao DESC')
            ->get();
        $result = [];
        foreach ($data_menu as $item) {
            if($item->tuan_trong_thang == 'week_1'){
                if($item->buoi == 'sang'){
                    $result['week_1']['t2'][] = $item->t2;
                    $result['week_1']['t3'][] = $item->t3;
                    $result['week_1']['t4'][] = $item->t4;
                    $result['week_1']['t5'][] = $item->t5;
                    $result['week_1']['t6'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_1']['t2'][] = $item->t2;
                    $result['week_1']['t3'][] = $item->t3;
                    $result['week_1']['t4'][] = $item->t4;
                    $result['week_1']['t5'][] = $item->t5;
                    $result['week_1']['t6'][] = $item->t6;

                }
                else if($item->buoi == 'toi'){
                    $result['week_1']['t2'][] = $item->t2;
                    $result['week_1']['t3'][] = $item->t3;
                    $result['week_1']['t4'][] = $item->t4;
                    $result['week_1']['t5'][] = $item->t5;
                    $result['week_1']['t6'][] = $item->t6;
                }
            }
            else if ($item->tuan_trong_thang == 'week_2')
            {
                if($item->buoi == 'sang'){
                    $result['week_2']['t2'][] = $item->t2;
                    $result['week_2']['t3'][] = $item->t3;
                    $result['week_2']['t4'][] = $item->t4;
                    $result['week_2']['t5'][] = $item->t5;
                    $result['week_2']['t6'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_2']['t2'][] = $item->t2;
                    $result['week_2']['t3'][] = $item->t3;
                    $result['week_2']['t4'][] = $item->t4;
                    $result['week_2']['t5'][] = $item->t5;
                    $result['week_2']['t6'][] = $item->t6;
                }
                else if($item->buoi == 'toi') {
                    $result['week_2']['t2'][] = $item->t2;
                    $result['week_2']['t3'][] = $item->t3;
                    $result['week_2']['t4'][] = $item->t4;
                    $result['week_2']['t5'][] = $item->t5;
                    $result['week_2']['t6'][] = $item->t6;
                }
            }
            else if ($item->tuan_trong_thang == 'week_3')
            {
                if($item->buoi == 'sang'){
                    $result['week_3']['t2'][] = $item->t2;
                    $result['week_3']['t3'][] = $item->t3;
                    $result['week_3']['t4'][] = $item->t4;
                    $result['week_3']['t5'][] = $item->t5;
                    $result['week_3']['t6'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_3']['t2'][] = $item->t2;
                    $result['week_3']['t3'][] = $item->t3;
                    $result['week_3']['t4'][] = $item->t4;
                    $result['week_3']['t5'][] = $item->t5;
                    $result['week_3']['t6'][] = $item->t6;
                }
                else if($item->buoi == 'toi'){
                    $result['week_3']['t2'][] = $item->t2;
                    $result['week_3']['t3'][] = $item->t3;
                    $result['week_3']['t4'][] = $item->t4;
                    $result['week_3']['t5'][] = $item->t5;
                    $result['week_3']['t6'][] = $item->t6;
                }
            }
            else if ($item->tuan_trong_thang == 'week_4')
            {
                if($item->buoi == 'sang'){
                    $result['week_4']['t2'][] = $item->t2;
                    $result['week_4']['t3'][] = $item->t3;
                    $result['week_4']['t4'][] = $item->t4;
                    $result['week_4']['t5'][] = $item->t5;
                    $result['week_4']['t6'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_4']['t2'][] = $item->t2;
                    $result['week_4']['t3'][] = $item->t3;
                    $result['week_4']['t4'][] = $item->t4;
                    $result['week_4']['t5'][] = $item->t5;
                    $result['week_4']['t6'][] = $item->t6;
                }
                else if($item->buoi == 'toi'){
                    $result['week_4']['t2'][] = $item->t2;
                    $result['week_4']['t3'][] = $item->t3;
                    $result['week_4']['t4'][] = $item->t4;
                    $result['week_4']['t5'][] = $item->t5;
                    $result['week_4']['t6'][] = $item->t6;
                }
            }
        }
        $data = [];
        foreach ($result['week_1'] as $item) {
            $data['week_1'][] = $item[0];
            $data['week_1'][] = $item[1];
            $data['week_1'][] = $item[2];
        }

        foreach ($result['week_2'] as $item) {
            $data['week_2'][] = $item[0];
            $data['week_2'][] = $item[1];
            $data['week_2'][] = $item[2];
        }

        foreach ($result['week_3'] as $item) {
            $data['week_3'][] = $item[0];
            $data['week_3'][] = $item[1];
            $data['week_3'][] = $item[2];
        }

        foreach ($result['week_4'] as $item) {
            $data['week_4'][] = $item[0];
            $data['week_4'][] = $item[1];
            $data['week_4'][] = $item[2];
        }

        return $data;
    }

    public function get_danh_sach_menu_theo_nam($date)
    {
        $date = date($date);
        $year = date("Y", strtotime($date)); // lây năm
        $start_of_year = $year . "-01-01";
        $end_of_year = $year . "-12-31";
        $data_menu = $this->from('menu')
            ->select('id, buoi, t2, t3, t4, t5, t6, t7, chua_nhat, (DATE_FORMAT(ngay_tao,\'%d-%m-%Y\')) as ngay_tao, tuan_trong_thang')
            ->where("DATE(ngay_tao) >= DATE('" . $start_of_year . "')")
            ->where("DATE(ngay_tao) <= LAST_DAY('" . $end_of_year . "')")
            ->where('tuan_trong_thang = "week_1"')
            ->where('buoi = "sang"')
            ->order_by('ngay_tao DESC')
            ->get();
        return $data_menu;
    }

    public function check_menu_da_len($date)
    {

    }
}