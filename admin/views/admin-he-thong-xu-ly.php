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