<?php
include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if(isset($_POST['get_data_lop_hoc'])) {
    $id_nien_khoa = (int)$_POST['id_nien_khoa'];
    $query = mysqli_query($dbc, "SELECT * FROM lophoc_chitiet WHERE nien_khoa_id = {$id_nien_khoa}");
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        while ($row = mysqli_fetch_array($query)){
            $result[] = array(
                'id' => $row['id'],
                'mo_ta' => $row['mo_ta']
            );
        }
    }
    echo json_encode($result);
}


if(isset($_GET['load_list_be'])) {
    $str = "SELECT * FROM be INNER JOIN lophoc_be ON be.id = lophoc_be.be_id INNER JOIN lophoc_chitiet ON lophoc_be.lop_hoc_chi_tiet_id = lophoc_chitiet.id ORDER BY be.id DESC";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0)
    {
        $index = 1;
        while ($row = mysqli_fetch_array($query)){
            $result[] = array (
                'index'    =>$index++,
                'ten'      => $row['ten'],
                'ngaysinh' => date_format(date_create($row['ngaysinh']),'d/m/Y'),
                'gioitinh' => $row['gioitinh'] ? "Nam" : "Nữ",
                'cannang'  => $row['cannang'],
                'sdtcha'   => $row['sdtcha'],
                'sdtme'    => $row['sdtme'],
                'diachi'   => $row['diachi'],
                'chieucao' => $row['chieucao'],
                'mo_ta'    => $row['mo_ta'],
            );
        }
    }
    echo json_encode($result);
}