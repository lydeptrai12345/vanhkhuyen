<?php
include "controller-len-menu.php";

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

            $item_menu = array(
                'buoi' => 'sang',
                't2' => $thu_2[0],
                't3' => $thu_3[0],
                't4' => $thu_4[0],
                't5' => $thu_5[0],
                't6' => $thu_6[0],
                'ngay_tao' => date('Y-m-d'),
                'tuan_trong_thang' => $key
            );
            $arr[] = $item_menu;
        }
        echo json_encode($arr);
        $menu = new LenMenu();
    }
}


function create_menu ($item, $week, $dbc) {

    $query = "INSERT INTO menu ( buoi, t2, t3, t4, t5, t6, t7, chua_nhat, ngay_tao, tuan_trong_thang )
              VALUES ( 
              $item->buoi, 
              $item->thu2, 
              $item->thu3, 
              $item->thu4, 
              $item->thu5, 
              $item->thu6, 
              $item->thu7, 
              $item->thu7, 
              NOW( ), 
              $week 
              )";
    mysqli_query($dbc, $query);
    return 1;
}