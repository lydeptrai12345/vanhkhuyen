<?php
include "controller-nguyen-lieu.php";

// Lay danh nguyen lieu
if (isset($_GET['danh_sach_nguyen_lieu'])) {
    $nguyen_lieu = new NguyenLieu();
    echo json_encode($nguyen_lieu->get_danh_sach_nguyen_lieu());
}

// Insert nguyen lieu
if (isset($_POST['add_nguyen_lieu'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    if($data){
        $nguyen_lieu = new NguyenLieu();
        $result = $nguyen_lieu->insert_nguyen_lieu($data);
        echo $result;
    }
    else echo -1;
}