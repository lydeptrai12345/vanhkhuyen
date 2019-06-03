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
                    $result['week_1'][] = $item->t2;
                    $result['week_1'][] = $item->t3;
                    $result['week_1'][] = $item->t4;
                    $result['week_1'][] = $item->t5;
                    $result['week_1'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_1'][] = $item->t2;
                    $result['week_1'][] = $item->t3;
                    $result['week_1'][] = $item->t4;
                    $result['week_1'][] = $item->t5;
                    $result['week_1'][] = $item->t6;

                }
                else if($item->buoi == 'toi'){
                    $result['week_1'][] = $item->t2;
                    $result['week_1'][] = $item->t3;
                    $result['week_1'][] = $item->t4;
                    $result['week_1'][] = $item->t5;
                    $result['week_1'][] = $item->t6;
                }
            }
            else if ($item->tuan_trong_thang == 'week_2')
            {
                if($item->buoi == 'sang'){
                    $result['week_2'][] = $item->t2;
                    $result['week_2'][] = $item->t3;
                    $result['week_2'][] = $item->t4;
                    $result['week_2'][] = $item->t5;
                    $result['week_2'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_2'][] = $item->t2;
                    $result['week_2'][] = $item->t3;
                    $result['week_2'][] = $item->t4;
                    $result['week_2'][] = $item->t5;
                    $result['week_2'][] = $item->t6;
                }
                else if($item->buoi == 'toi') {
                    $result['week_2'][] = $item->t2;
                    $result['week_2'][] = $item->t3;
                    $result['week_2'][] = $item->t4;
                    $result['week_2'][] = $item->t5;
                    $result['week_2'][] = $item->t6;
                }
            }
            else if ($item->tuan_trong_thang == 'week_3')
            {
                if($item->buoi == 'sang'){
                    $result['week_3'][] = $item->t2;
                    $result['week_3'][] = $item->t3;
                    $result['week_3'][] = $item->t4;
                    $result['week_3'][] = $item->t5;
                    $result['week_3'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_3'][] = $item->t2;
                    $result['week_3'][] = $item->t3;
                    $result['week_3'][] = $item->t4;
                    $result['week_3'][] = $item->t5;
                    $result['week_3'][] = $item->t6;
                }
                else if($item->buoi == 'toi'){
                    $result['week_3'][] = $item->t2;
                    $result['week_3'][] = $item->t3;
                    $result['week_3'][] = $item->t4;
                    $result['week_3'][] = $item->t5;
                    $result['week_3'][] = $item->t6;
                }
            }
            else if ($item->tuan_trong_thang == 'week_4')
            {
                if($item->buoi == 'sang'){
                    $result['week_4'][] = $item->t2;
                    $result['week_4'][] = $item->t3;
                    $result['week_4'][] = $item->t4;
                    $result['week_4'][] = $item->t5;
                    $result['week_4'][] = $item->t6;
                }
                else if($item->buoi == 'trua'){
                    $result['week_4'][] = $item->t2;
                    $result['week_4'][] = $item->t3;
                    $result['week_4'][] = $item->t4;
                    $result['week_4'][] = $item->t5;
                    $result['week_4'][] = $item->t6;
                }
                else if($item->buoi == 'toi'){
                    $result['week_4'][] = $item->t2;
                    $result['week_4'][] = $item->t3;
                    $result['week_4'][] = $item->t4;
                    $result['week_4'][] = $item->t5;
                    $result['week_4'][] = $item->t6;
                }
            }
        }
        return $result;
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
}