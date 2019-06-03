<?php
include "controller-len-menu.php";

if(isset($_GET['get_menu_theo_thang'])) {
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $menu = new LenMenu();
    echo json_encode($menu->get_data_menu_theo_thang($date));
}

if(isset($_GET['get_all_menu'])) {
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $menu = new LenMenu();
    echo json_encode($menu->get_danh_sach_menu_theo_nam($date));
}

if(isset($_POST['add_menu'])) {
    $data = ($_POST['data']);

    if(!$data) {
        echo null;return -1;
    }
    else{
        $arr = [];
        foreach ($data as $key => $item){
            $thu_2 = json_decode($item['thu_2']);
            $thu_3 = json_decode($item['thu_3']);
            $thu_4 = json_decode($item['thu_4']);
            $thu_5 = json_decode($item['thu_5']);
            $thu_6 = json_decode($item['thu_6']);

            $item_menu_sang = array(
                'buoi' => 'sang',
                't2' => $thu_2[0],
                't3' => $thu_3[0],
                't4' => $thu_4[0],
                't5' => $thu_5[0],
                't6' => $thu_6[0],
                'ngay_tao' => date('Y-m-d'),
                'tuan_trong_thang' => $key
            );

            $item_menu_trua = array(
                'buoi' => 'trua',
                't2' => $thu_2[1],
                't3' => $thu_3[1],
                't4' => $thu_4[1],
                't5' => $thu_5[1],
                't6' => $thu_6[1],
                'ngay_tao' => date('Y-m-d'),
                'tuan_trong_thang' => $key
            );

            $item_menu_toi = array(
                'buoi' => 'toi',
                't2' => $thu_2[2],
                't3' => $thu_3[2],
                't4' => $thu_4[2],
                't5' => $thu_5[2],
                't6' => $thu_6[2],
                'ngay_tao' => date('Y-m-d'),
                'tuan_trong_thang' => $key
            );
            $arr[] = $item_menu_sang;
            $arr[] = $item_menu_trua;
            $arr[] = $item_menu_toi;
        }
//        echo json_encode($arr);
        $menu = new LenMenu();
        echo json_encode($menu->insert_menu($arr));
    }
}
