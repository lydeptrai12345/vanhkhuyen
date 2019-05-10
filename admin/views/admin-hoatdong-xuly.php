<?php

include "../../inc/myconnect.php";
include "../../inc/myfunction.php";

if(isset($_POST['get_data_nguoidung'])) {
    $id_nhan_vien = (int)$_POST['$id_nhan_vien'];
    $query = mysqli_query($dbc, "SELECT * FROM nguoidung WHERE nhan_vien_id = {$id_nhan_vien}");
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        while ($row = mysqli_fetch_array($query)){
            $result[] = array(
                'id' => $row['id'],
                'ten_nguoi_dung' => $row['ten_nguoi_dung']
            );
        }
    }
    echo json_encode($result);
}

if (isset($_GET['load_list_hoatdong'])) {
    $str = "SELECT hoatdong.id as 'id',hoatdong.tieu_de,hoatdong.mo_ta,nguoidung.nhan_vien_id
            FROM hoatdong INNER JOIN nguoidung ON  ";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'id' => $row['id'],
                'tieu_de' => $row['tieu_de'],
                'mo_ta' => $row['mo_ta'],
                'nguoi_dang' => $row['nguoi_dang'],
            );
        }
    }
    echo json_encode($result);
}