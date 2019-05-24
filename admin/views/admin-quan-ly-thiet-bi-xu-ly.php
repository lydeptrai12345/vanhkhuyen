<?php
include "controller-quan-ly-thiet-bi.php";

// Lay danh nguyen lieu
if (isset($_GET['danh_sach_thiet_bi'])) {
    $date = $_GET['date'];
    $thiet_bi = new QuanLyThietBi();
    echo json_encode($thiet_bi->get_danh_sach_thiet_bi($date));
}

// Insert nguyen lieu
if (isset($_POST['add_thiet_bi'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    if($data){
        $thiet_bi = new QuanLyThietBi();
        $result = $thiet_bi->insert_thiet_bi($data);
        echo $result;
    }
    else echo -1;
}


// Get nguyen lieu
if(isset($_GET['get_thiet_bi'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if($id > 0){
        $thiet_bi = new QuanLyThietBi();
        $result = $thiet_bi->get_thiet_bi($id);
        foreach ($result as $item){
            $result = $item;
        }
        echo json_encode($result);
    }
    else echo -1;
}

// Update nguyen lieu
if (isset($_POST['edit_thiet_bi'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    if($data){
        $thiet_bi = new QuanLyThietBi();
        $result = $thiet_bi->update_thiet_bi($data);
        echo $result;
    }
    else echo -1;
}

// Delete nguyen lieu
if (isset($_POST['delete_thiet_bi'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    if($id){
        $thiet_bi = new QuanLyThietBi();
        echo $thiet_bi->delete_thiet_bi($id);
    }
    else echo -1;
}