<?php
include "controller-he-thong.php";

if(isset($_GET['danh_sach_tai_khoan'])) {
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $menu = new HeThong();
    echo json_encode($menu->get_danh_sach_tai_khoan());
}

if(isset($_GET['danh_sach_nhom_nguoi_dung'])) {
    $menu = new HeThong();
    echo json_encode($menu->get_danh_sach_nhom_nguoi_dung());
}

if(isset($_GET['danh_sach_nhom_chuc_nang'])) {
    $menu = new HeThong();
    echo json_encode($menu->get_danh_sach_chuc_nang_cha());
}

if(isset($_GET['danh_sach_nhom_chuc_nang_con_theo_cha'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $menu = new HeThong();
    echo json_encode($menu->get_danh_sach_chuc_nang_con_theo_cha($id));
}

if(isset($_GET['danh_sach_all_nhan_vien'])) {
    $he_thong = new HeThong();
    echo json_encode($he_thong->get_tat_ca_danh_sach_nhan_vien());
}

if(isset($_GET['danh_sach_nhan_vien_chua_co_tai_khoan'])) {
    $he_thong = new HeThong();
    echo json_encode($he_thong->get_danh_sach_nhan_vien_chua_co_tai_khoan());
}

if(isset($_GET['get_nguoi_dung_id'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
//    echo $id;return $id;
    $he_thong = new HeThong();
    echo json_encode($he_thong->get_tai_khoan_nguoi_dung($id));
}

if(isset($_POST['kich_hoat_nguoi_dung'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : 0;

    $he_thong = new HeThong();
    echo json_encode($he_thong->kich_nguoi_dung($id, $type));
}

// Insert nguoi dung
if (isset($_POST['add_nguoi_dung'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    if($data){
        $da = (object)$data;
        $ht = new HeThong();
        $check = $ht->check_ten_tai_khoan_nguoi_dung($da->ten_nguoi_dung);
        if(empty($check)){
            $he_thong = new HeThong();
            $result = $he_thong->insert_nguoi_dung($data);
            echo $result;
        }
        else{
            echo 'trung_ten_nguoi_dung';
        }
    }
    else echo -1;
}

if (isset($_POST['edit_nguoi_dung'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    if($data){
        $he_thong = new HeThong();
        $result = $he_thong->update_nguoi_dung($data);
        echo $result;
    }
    else echo -1;
}