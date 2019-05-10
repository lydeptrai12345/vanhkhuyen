<?php


include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_tintuc'])) {
    $str = "SELECT * FROM tintuc";
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
                'loai_tin_id' => $row['loai_tin_id'],
                'nguoi_dang' => $row['nguoi_dang'],
            );
        }
    }
    echo json_encode($result);
}