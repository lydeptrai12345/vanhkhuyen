<?php


include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_loaitin'])) {
    $str = "SELECT * FROM loaitin";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'id' => $row['id'],
                'ten' => $row['ten'],
                'the_loai_cha' => $row['the_loai_cha'],
            );
        }
    }
    echo json_encode($result);
}