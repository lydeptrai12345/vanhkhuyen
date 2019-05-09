<?php

include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_nhanvien'])) {
    $str = "SELECT * FROM nhanvien 
            INNER JOIN congviec ON nhanvien.cong_viec_id = congviec.congviec_id 
            INNER JOIN phongban ON nhanvien.phong_ban_id = phongban.phong_ban_id
            ORDER BY REVERSE(SPLIT_STRING(REVERSE(TRIM(ho_ten)),' ', 1))";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'id' => $row['id'],
                'ho_ten' => $row['ho_ten'],
                'gioi_tinh' => ($row['gioi_tinh'] == 1) ? "Nam" : "Nữ",
                'dien_thoai' => $row['dien_thoai'],
                'email' => $row['email'],
                'ngay_sinh' => date_format(date_create($row['ngay_sinh']), 'd/m/Y'),
                'noi_sinh' => $row['noi_sinh'],
                'cmnd' => $row['cmnd'],
                'dia_chi' => $row['dia_chi'],
                'bang_cap_id' => $row['bang_cap_id'],
                'ten_phong_ban' => $row['ten_phong_ban'],
                'ngay_vao_lam' => $row['ngay_vao_lam'],
                'muc_luong_cb' => $row['muc_luong_cb'],
                'he_so' => $row['he_so'],
                'ten_cong_viec' => $row['ten_cong_viec'],
                'trangthai' => $row['trangthai'],
            );
        }
    }
    echo json_encode($result);
}