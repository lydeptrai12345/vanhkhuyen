<?php


include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_congviec'])) {
    $str = "SELECT * FROM congviec ORDER BY ten_cong_viec ASC";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'congviec_id' => $row['congviec_id'],
                'ten_cong_viec' => $row['ten_cong_viec'],
                'phucap' => $row['phucap'],
            );
        }
    }
    echo json_encode($result);
}