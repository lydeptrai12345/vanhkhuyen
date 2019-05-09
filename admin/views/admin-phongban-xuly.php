<?php


include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_phongban'])) {
    $str = "SELECT * FROM phongban ORDER BY ten_phong_ban ASC";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'phong_ban_id' => $row['phong_ban_id'],
                'ten_phong_ban' => $row['ten_phong_ban'],
            );
        }
    }
    echo json_encode($result);
}