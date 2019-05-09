<?php


include "../../inc/myconnect.php";
include "../../inc/myfunction.php";


if (isset($_GET['load_list_bangcap'])) {
    $str = "SELECT * FROM bangcap ORDER BY heso ASC";
    $query = mysqli_query($dbc, $str);
    $result = array();

    if (mysqli_num_rows($query) > 0) {
        $index = 1;
        while ($row = mysqli_fetch_array($query)) {
            $result[] = array(
                'bang_cap_id' => $row['bang_cap_id'],
                'ten_bang_cap' => $row['ten_bang_cap'],
                'heso' => $row['heso'],
            );
        }
    }
    echo json_encode($result);
}