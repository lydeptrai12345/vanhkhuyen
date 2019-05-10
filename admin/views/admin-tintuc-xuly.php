<?php


include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_tintuc'])) {
    $str = "SELECT tintuc.id, tintuc.tieude,tintuc.tomtat,tintuc.noidung,tintuc.hinh, loaitin.ten, nguoidung.ten_nguoi_dung  FROM `tintuc` 
            INNER JOIN loaitin ON tintuc.loai_tin_id=loaitin.id 
            INNER JOIN nguoidung ON tintuc.nguoi_dang=nguoidung.id";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'id' => $row['id'],
                'tieude' => $row['tieude'],
                'tomtat' => $row['tomtat'],
                'noidung' => $row['noidung'],
                'hinh' => $row['hinh'],
                'ten' => $row['ten'],
                'ten_nguoi_dung' => $row['ten_nguoi_dung'],
            );
        }
    }
    echo json_encode($result);
}