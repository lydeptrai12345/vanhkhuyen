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


// Get nguyen lieu
if(isset($_GET['get_nguyen_lieu'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if($id > 0){
        $nguyen_lieu = new NguyenLieu();
        $result = $nguyen_lieu->get_nguyen_lieu($id);
        foreach ($result as $item){
            $result = $item;
        }
        echo json_encode($result);
    }
    else echo -1;
}

// Update nguyen lieu
if (isset($_POST['edit_nguyen_lieu'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    if($data){
        $nguyen_lieu = new NguyenLieu();
        $result = $nguyen_lieu->update_nguyen_lieu($data);
        echo $result;
    }
    else echo -1;
}

// Delete nguyen lieu
if (isset($_POST['delete_nguyen_lieu'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    if($id){
        $nguyen_lieu = new NguyenLieu();
        echo $nguyen_lieu->delete_nguyen_lieu($id);
    }
    else echo -1;
}