<?php
include "controller-he-thong.php";

if(isset($_GET['danh_sach_tai_khoan'])) {
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $menu = new HeThong();
    echo json_encode($menu->get_danh_sach_tai_khoan());
}

if(isset($_GET['danh_sach_nhom_nguoi_dung'])) {
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $menu = new HeThong();
    echo json_encode($menu->get_danh_sach_nhom_nguoi_dung());
}